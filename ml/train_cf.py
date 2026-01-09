import os
import pandas as pd
import numpy as np
from datetime import datetime
from sqlalchemy import create_engine, text
from sklearn.metrics.pairwise import cosine_similarity
from dotenv import load_dotenv

load_dotenv()  # đọc .env Laravel ở root project (nếu bạn chạy từ root)

TOP_N = int(os.getenv("ML_TOP_N", "8"))
MODEL_VERSION = os.getenv("ML_MODEL_VERSION", "cf_v1")

DB_HOST = os.getenv("DB_HOST", "127.0.0.1")
DB_PORT = os.getenv("DB_PORT", "3306")
DB_NAME = os.getenv("DB_DATABASE", "elearning_platform")
DB_USER = os.getenv("DB_USERNAME", "root")
DB_PASS = os.getenv("DB_PASSWORD", "")

# Trọng số hành vi (bạn có thể điều chỉnh)
WEIGHTS = {
    "enroll": 5.0,
    "view_course": 2.0,
    "view_lesson": 1.0,
    "complete_lesson": 3.0,
}

def main():
    engine = create_engine(
        f"mysql+pymysql://{DB_USER}:{DB_PASS}@{DB_HOST}:{DB_PORT}/{DB_NAME}?charset=utf8mb4"
    )

    # 1) Lấy danh sách course published
    courses = pd.read_sql("""
        SELECT id
        FROM courses
        WHERE status = 'published'
    """, engine)

    if courses.empty:
        print("No published courses. Exit.")
        return

    published_course_ids = set(courses["id"].astype(int).tolist())

    # 2) Lấy events
    events = pd.read_sql("""
        SELECT user_id, course_id, event_type
        FROM user_events
        WHERE course_id IS NOT NULL
    """, engine)

    if events.empty:
        print("No user events. Exit.")
        return

    # chỉ giữ course published
    events["course_id"] = events["course_id"].astype(int)
    events = events[events["course_id"].isin(published_course_ids)]
    if events.empty:
        print("No events for published courses. Exit.")
        return

    # map weights
    events["w"] = events["event_type"].map(WEIGHTS).fillna(1.0)

    # 3) Build user-course matrix (implicit feedback)
    # gộp nhiều event cùng user-course => sum weight
    agg = events.groupby(["user_id", "course_id"], as_index=False)["w"].sum()

    users = agg["user_id"].unique()
    course_list = sorted(list(published_course_ids))

    user_to_idx = {u: i for i, u in enumerate(users)}
    course_to_idx = {c: j for j, c in enumerate(course_list)}

    mat = np.zeros((len(users), len(course_list)), dtype=np.float32)

    for _, row in agg.iterrows():
        ui = user_to_idx[row["user_id"]]
        cj = course_to_idx[row["course_id"]]
        mat[ui, cj] = row["w"]

    # Nếu chỉ có 1 user hoặc quá ít dữ liệu thì không tính similarity được tốt
    if mat.shape[0] < 1 or mat.shape[1] < 2:
        print("Not enough data. Exit.")
        return

    # 4) Similarity user-user
    sim = cosine_similarity(mat)

    results = []
    generated_at = datetime.now().strftime("%Y-%m-%d %H:%M:%S")

    for u in users:
        ui = user_to_idx[u]
        user_vec = mat[ui]

        # các course user đã tương tác
        interacted = set(np.where(user_vec > 0)[0].tolist())

        # dự đoán = tổng (similarity * vector của user khác)
        scores = sim[ui].dot(mat)

        # loại course đã tương tác
        if interacted:
            scores[list(interacted)] = -1

        top_idx = np.argsort(-scores)[:TOP_N]

        rank = 1
        for cj in top_idx:
            score = float(scores[cj])
            if score <= 0:
                continue
            course_id = course_list[cj]
            results.append((int(u), int(course_id), score, rank, MODEL_VERSION, generated_at))
            rank += 1

    if not results:
        print("No recommendations produced (need more events).")
        return

    # 5) Save to DB
    with engine.begin() as conn:
        conn.execute(text("TRUNCATE TABLE recommend_results"))
        conn.execute(
            text("""
                INSERT INTO recommend_results (user_id, course_id, score, rank_no, model_version, generated_at)
                VALUES (:user_id, :course_id, :score, :rank_no, :model_version, :generated_at)
            """),
            [
                {
                    "user_id": r[0],
                    "course_id": r[1],
                    "score": r[2],
                    "rank_no": r[3],
                    "model_version": r[4],
                    "generated_at": r[5],
                }
                for r in results
            ]
        )

    print(f"Saved {len(results)} rows into recommend_results at {generated_at}")

if __name__ == "__main__":
    main()

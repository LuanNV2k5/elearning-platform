import mysql.connector
from config import DB_CONFIG

def save_to_db(results):
    conn = mysql.connector.connect(**DB_CONFIG)
    cursor = conn.cursor()

    cursor.execute("DELETE FROM recommend_results")

    for user_id, course_id, score in results:
        cursor.execute("""
            INSERT INTO recommend_results (user_id, course_id, score)
            VALUES (%s, %s, %s)
        """, (user_id, course_id, score))

    conn.commit()
    conn.close()

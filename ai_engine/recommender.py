from sklearn.metrics.pairwise import cosine_similarity

def generate_recommendations(courses, enrolls, tfidf_matrix, top_k=5):
    similarity_matrix = cosine_similarity(tfidf_matrix)

    course_id_map = dict(zip(courses['id'], range(len(courses))))
    results = []

    for user_id in enrolls['user_id'].unique():
        user_courses = enrolls[enrolls['user_id'] == user_id]['course_id']

        scores = {}
        for cid in user_courses:
            idx = course_id_map[cid]
            for i, score in enumerate(similarity_matrix[idx]):
                course_id = courses.iloc[i]['id']
                scores[course_id] = scores.get(course_id, 0) + score

        for cid in user_courses:
            scores.pop(cid, None)

        top_courses = sorted(scores.items(), key=lambda x: x[1], reverse=True)[:top_k]
        for course_id, score in top_courses:
            results.append((user_id, course_id, score))

    return results

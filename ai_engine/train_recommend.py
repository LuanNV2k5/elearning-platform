from db_loader import load_data
from feature_extractor import extract_features
from recommender import generate_recommendations
from save_results import save_to_db

def main():
    courses, enrolls = load_data()
    tfidf_matrix = extract_features(courses)
    results = generate_recommendations(courses, enrolls, tfidf_matrix)
    save_to_db(results)

    print("✅ Recommendation system training completed")

if __name__ == "__main__":
    main()

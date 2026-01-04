from sklearn.feature_extraction.text import TfidfVectorizer

def extract_features(courses):
    tfidf = TfidfVectorizer(stop_words='english')
    tfidf_matrix = tfidf.fit_transform(courses['description'])
    return tfidf_matrix

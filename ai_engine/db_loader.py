import mysql.connector
import pandas as pd
from config import DB_CONFIG

def load_data():
    conn = mysql.connector.connect(**DB_CONFIG)

    courses = pd.read_sql(
        "SELECT id, title, description FROM courses",
        conn
    )

    enrolls = pd.read_sql(
        "SELECT user_id, course_id FROM enrollments",
        conn
    )

    conn.close()
    return courses, enrolls

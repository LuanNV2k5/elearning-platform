import os
from dotenv import load_dotenv

load_dotenv('../.env')

DB_CONFIG = {
    "host": os.getenv("DB_HOST"),
    "user": os.getenv("DB_USERNAME"),
    "password": os.getenv("DB_PASSWORD"),
    "database": os.getenv("DB_DATABASE")
}

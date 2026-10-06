import os
from dotenv import load_dotenv

load_dotenv()

class Config:
    # Qdrant
    QDRANT_URL = os.getenv('QDRANT_URL', 'http://localhost:6333')
    QDRANT_API_KEY = os.getenv('QDRANT_API_KEY', None)
    COLLECTION_NAME = 'sellmate_knowledge'
    
    # 🔥 FREE Local Embeddings
    EMBEDDING_MODEL = 'all-MiniLM-L6-v2'
    VECTOR_SIZE = 384
    
    # RAG Settings
    MAX_TOKENS = 500
    TEMPERATURE = 0.7
    CHUNK_SIZE = 500
    CHUNK_OVERLAP = 50
    SEARCH_LIMIT = 5

config = Config()
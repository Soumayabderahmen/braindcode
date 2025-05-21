# ==== core/intent_detector.py ====
import re, logging
from langchain.schema.document import Document
from langchain_community.document_loaders import TextLoader
from langchain.text_splitter import RecursiveCharacterTextSplitter
from langchain_ollama import OllamaEmbeddings
from langchain_chroma import Chroma

intentions_path = "intentions/intentions.txt"
embedding_model = OllamaEmbeddings(model="nomic-embed-text")
text_splitter = RecursiveCharacterTextSplitter(chunk_size=300, chunk_overlap=50)

def create_intentions_db():
    loader = TextLoader(intentions_path)
    raw_docs = loader.load()
    documents, current_intent = [], None
    for doc in raw_docs:
        for line in doc.page_content.splitlines():
            if line.startswith("#INTENTION:"):
                current_intent = line.split(":", 1)[1].strip()
            elif line and current_intent:
                documents.append(Document(page_content=line, metadata={"intent_name": current_intent}))
    texts = text_splitter.split_documents(documents)
    return Chroma.from_documents(texts, embedding_model, persist_directory="./chroma-intentions")

try:
    intentions_db = create_intentions_db()
except Exception as e:
    logging.error(f"[Startup Error] Intentions: {e}")
    intentions_db = None

def detect_intention_semantic(message: str):
    try:
        cleaned = re.sub(r"[^\w\s,\.''!?-]", '', message)
        results = intentions_db.similarity_search_with_score(cleaned, k=1)
        if not results:
            return "fallback", None
        doc, score = results[0]
        if score > 0.7:
            return "fallback", None
        return doc.metadata.get("intent_name", "unknown"), doc.page_content.strip()
    except Exception as e:
        logging.error(f"[Intentions ERROR] {e}")
        return "fallback", None

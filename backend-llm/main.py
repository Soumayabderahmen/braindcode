from fastapi import FastAPI, Request
from fastapi.responses import JSONResponse
from fastapi.middleware.cors import CORSMiddleware
from langdetect import detect
from langchain.text_splitter import RecursiveCharacterTextSplitter
from langchain.schema.document import Document
from langchain_community.llms import Ollama
from langchain_chroma import Chroma
from langchain_community.document_loaders import TextLoader
from langchain_ollama import OllamaEmbeddings
from langchain_ollama import OllamaLLM as Ollama
from fastapi.responses import StreamingResponse
from fastapi import UploadFile, File
from langchain_community.document_loaders import PyPDFLoader
from fastapi import Body
from symspellpy import SymSpell, Verbosity
from textblob import TextBlob
from fuzzywuzzy import process
import os
import tempfile
import os
import httpx
import logging
import time
import re
import json
import asyncio
app = FastAPI()

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

def load_existing_user_vectors():
    base_path = "./user_vectors"
    if not os.path.exists(base_path):
        os.makedirs(base_path)

    for session_id in os.listdir(base_path):
        dir_path = os.path.join(base_path, session_id)
        if os.path.isdir(dir_path):
            try:
                user_pdf_vectors[session_id] = Chroma(
                    embedding_function=embedding_model,
                    persist_directory=dir_path
                )
                logging.info(f"[Persistence] Vecteur chargé pour session : {session_id}")
            except Exception as e:
                logging.warning(f"[Persistence] Échec de chargement vecteur session {session_id}: {e}")

logging.basicConfig(level=logging.INFO)
# Initialisation de l'embedding + LLM
embedding_model = OllamaEmbeddings(model="nomic-embed-text")
llm = Ollama(model="llama3")




# Création du vecteurstore à partir du fichier intentions.txt
text_splitter = RecursiveCharacterTextSplitter(chunk_size=300, chunk_overlap=50)
intentions_path = "intentions/intentions.txt"
# Stockage temporaire en RAM par utilisateur
#user_pdf_vectors = {}
#  Charger les vecteurs utilisateurs sauvegardés
#load_existing_user_vectors()


# @app.post("/upload-user-pdf")
# async def upload_user_pdf(request: Request, file: UploadFile = File(...)):
#     session_id = request.headers.get("X-Session-ID", "anonymous")
#     logging.info(f"[Upload PDF] session_id = {session_id}")  # pour assurer que session_id est bien pris en compte 
#     if not file.filename.endswith(".pdf"):
#         return JSONResponse(status_code=400, content={"error": "Fichier non PDF."})

#     with tempfile.NamedTemporaryFile(delete=False, suffix=".pdf") as tmp:
#         tmp.write(await file.read())
#         tmp_path = tmp.name

#     loader = PyPDFLoader(tmp_path)
#     documents = loader.load()

#     if not documents:
#         return JSONResponse(status_code=400, content={"error": "PDF vide ou illisible."})

#     splitter = RecursiveCharacterTextSplitter(chunk_size=400, chunk_overlap=50)
#     texts = splitter.split_documents(documents)

#     if not texts:
#         return JSONResponse(status_code=400, content={"error": "Aucun texte détecté."})

#     persist_dir = f"./user_vectors/{session_id}"
#     vectordb = Chroma.from_documents(texts, embedding_model, persist_directory=persist_dir)
#     vectordb.persist()  # Sauvegarde sur disque
#     user_pdf_vectors[session_id] = vectordb

#     return {"message": "Fichier PDF analysé avec succès."}


def create_intentions_db():
    loader = TextLoader(intentions_path)
    raw_docs = loader.load()
    
    documents = []
    current_intent = None

    for doc in raw_docs:
        lines = doc.page_content.splitlines()
        for line in lines:
            line = line.strip()
            if line.startswith("#INTENTION:"):
                current_intent = line.split(":", 1)[1].strip()
            elif line and current_intent is not None:
                documents.append(Document(
                    page_content=line,
                    metadata={"intent_name": str(current_intent)}  # ✅ évite None
                ))

    texts = text_splitter.split_documents(documents)

# 🔍 Vérification du contenu avant insertion
    if not texts:
        logging.error("[Intentions DB] Aucun document à indexer. Vérifiez intentions.txt.")
        raise ValueError("Aucun texte à vectoriser.")

# ✅ Filtrer les documents vides
    texts = [doc for doc in texts if doc.page_content.strip()]

# 🔍 Rejet si toujours vide
    if not texts:
        raise ValueError("Tous les documents sont vides après nettoyage.")

# ✅ Création du vecteurstore
    return Chroma.from_documents(texts, embedding_model, persist_directory="./chroma-intentions")


   





def detect_language(text):
    try:
        # Supprimer les emojis
        cleaned = re.sub(r'[^\w\s,.\'’!?-]', '', text)
        return detect(cleaned)
    except:
        return "unknown"


# Initialiser SymSpell (à faire une seule fois)
# Initialisation de SymSpell
sym_spell = SymSpell(max_dictionary_edit_distance=2, prefix_length=7)
sym_spell.load_dictionary("data/french_dictionary.txt", term_index=0, count_index=1)

# Liste des mots du dico (pour fuzzywuzzy)
with open("data/french_dictionary.txt", encoding="utf-8") as f:
    DICTIONARY_WORDS = [line.split()[0] for line in f.readlines()]

def corriger_fautes(message: str) -> str:
    # 1. Correction mot à mot avec SymSpell
    mots = message.strip().split()
    message_symspell = []

    for mot in mots:
        suggestions = sym_spell.lookup(mot, Verbosity.CLOSEST, max_edit_distance=2)
        if suggestions:
            message_symspell.append(suggestions[0].term)
        else:
            message_symspell.append(mot)

    phrase_corrigee = " ".join(message_symspell)

    # 2. Correction grammaticale avec TextBlob
    try:
        phrase_blob = str(TextBlob(phrase_corrigee).correct())
    except Exception:
        phrase_blob = phrase_corrigee

    # 3. Dernier recours : fuzzywuzzy pour les mots douteux
    mots_finals = []
    for mot in phrase_blob.split():
        meilleur_match, score = process.extractOne(mot, DICTIONARY_WORDS)
        if score > 90:
            mots_finals.append(meilleur_match)
        else:
            mots_finals.append(mot)

    return " ".join(mots_finals)

def generate_language_sensitive_prompt(message: str) -> str:
    try:
        lang = detect(message)
    except Exception:
        lang = "unknown"

    if lang == "fr":
        return (
            "Tu es un assistant IA sympathique. Si quelqu’un écrit 'bonjour' ou 'salut', "
            "réponds simplement par une salutation naturelle comme 'Bonjour ! 😊', 'Salut !', ou autre. "
            f"Voici le message utilisateur : {message}"
        )
    elif lang == "en":
        return (
            "You are a friendly AI assistant. If the user says 'hello' or 'hi', "
            "just respond with a natural greeting like 'Hey there!', 'Hello! 😊', or something similar. "
            f"Here is the user message: {message}"
        )
    else:
        return f"You are a helpful assistant. Respond simply and naturally to the user message: {message}"

def detect_intention_semantic(message: str):
    try:
        results = intentions_db.similarity_search_with_score(message, k=1)
        if not results:
            logging.info("[Intentions] Aucun résultat trouvé.")
            return None, None
        
        best_match: tuple[Document, float] = results[0]
        doc, score = best_match

        logging.info(f"[Intentions] Similarité: {score:.4f}")
        if score > 0.3:
            return None, None  # Trop éloigné

        # Récupère le nom de l’intention depuis les metadata
        intent_name = doc.metadata.get("intent_name", "unknown")
        return intent_name, doc.page_content.strip()

    except Exception as e:
        logging.error(f"[Intentions ERROR] {e}")
        return None, None

@app.get("/intentions/list")
async def list_intentions():
    try:
        results = intentions_db.similarity_search("liste", k=20)
        return [doc.metadata.get("intent_name", "unknown") for doc in results]
    except Exception as e:
        return JSONResponse(status_code=500, content={"error": str(e)})


@app.post("/chat")
async def chat(request: Request):
    data = await request.json()
    intent_override = data.get("intent_override")  # ✅ ajout
    message = data.get("message", "").strip()
    history = data.get("history", [])  # 👈 Historique du frontend

    if len(message) > 1000:
        return JSONResponse(
            status_code=400,
            content={"error": "Message trop long (1000 caractères max autorisés)."}
        )

    message = corriger_fautes(message)
    lang = detect_language(message)
    if intent_override:
        intent_name = intent_override

        # 🔥 Forcer la langue française si c'est une intention connue
        if intent_name in {
            "liste_etapes_programme",
            "objectif_du_programme",
            "contact_mentor",
            "prise_de_rdv",
            "besoin_aide_financement",
            "livrable",
            "retard"
        }:
            lang = "fr"
        else:
            lang = detect_language(message)

        prompt_from_intent = build_personalized_prompt(intent_name, message, lang, "")
    else:
        intent_name, prompt_from_intent = detect_intention_semantic(message)





    # Nettoyage du message utilisateur
    cleaned_message = re.sub(r'\s+', ' ', message).strip()[:500]
    cleaned_message = re.sub(r"[^a-zA-ZÀ-ÿ0-9\s]", "", cleaned_message).lower()
    logging.info(f"[Message utilisateur] {cleaned_message}")

    # 🔁 Traitement de l'historique en texte structuré
    formatted_history = []
    for item in history:
        if isinstance(item, dict) and "sender" in item and "text" in item:
            sender = "User" if item["sender"] == "user" else "Bot"
            text = item["text"].strip()
            formatted_history.append(f"{sender}: {text}")

    if len(formatted_history) > 10:
        prompt_history = "\n".join(formatted_history[-10:])
    else:
        prompt_history = "\n".join(formatted_history)

    # Récupération du session_id
    session_id = request.headers.get("X-Session-ID", "anonymous")
    logging.info(f"[Chat] session_id = {session_id}")

    prompt_to_send = None

    # # ✅ Priorité 1 : PDF utilisateur
    # if session_id in user_pdf_vectors:
    #     try:
    #         pdf_search = user_pdf_vectors[session_id].similarity_search_with_score(cleaned_message, k=2)
    #         chunks = [doc.page_content for doc, score in pdf_search if score < 0.5]
    #         if chunks:
    #             joined_chunks = "\n".join(chunks)
    #             prompt_to_send = (
    #                 f"Voici un extrait du fichier envoyé :\n{joined_chunks}\n\n"
    #                 f"Question utilisateur : {cleaned_message}\n\n"
    #                 f"Réponds précisément, en 500 caractères max."
    #             )
    #     except Exception as e:
    #         logging.warning(f"[User PDF Search Error] {e}")

    # ✅ Priorité 2 : Intentions
    if not prompt_to_send and intent_name and prompt_from_intent:
        prompt_to_send = prompt_from_intent

    # ✅ Priorité 3 : Fallback LLM avec historique
    if not prompt_to_send:
        # Réduire la taille de l'historique à  échanges max (6 messages total)
        reduced_history = formatted_history[-6:]  # 3 user + 3 bot max
        prompt_history = "\n".join(reduced_history)

        # Si encore trop long (au cas où), tronque par caractères
        if len(prompt_history) > 1000:
            prompt_history = prompt_history[-1000:]

        #Génère le prompt personnalisé
        prompt_base = build_personalized_prompt(intent_name, cleaned_message, lang, prompt_history)

        #Assemble le prompt final
        prompt_to_send = f"{prompt_base}"  # ✅ ici



    

    # ✅ Prompt final
    final_prompt = prompt_to_send

    logging.info(f"[Prompt final envoyé à Ollama] {final_prompt}")

    return StreamingResponse(stream_generator(final_prompt), media_type="text/plain")






def build_personalized_prompt(intent_name: str, user_message: str, lang: str, context_dialogue: str) -> str:
    if lang == "fr":
        base = "Tu es BrainBot, l’assistant IA du programme d’incubation BraindCode Startup Studio."
    else:
        base = "You are BrainBot, the AI assistant of the BrainCode startup program."

    # Ajout de l'historique de conversation au prompt
    if context_dialogue:
        base += f"\n\nVoici l’historique de la conversation récente :\n{context_dialogue}"

    if intent_name == "salutation":
        return (
            f"{base}\n\n"
            f"Message utilisateur : {user_message}\n\n"
            "Réponds par une salutation amicale et chaleureuse."
        )

    elif intent_name == "contact_mentor":
        return (
            f"{base}\n\n"
            f"Message utilisateur : {user_message}\n\n"
            "Pour contacter votre coach ou mentor, merci d'utiliser votre espace personnel sur la plateforme BrainCode.\n"
            "👉 Cliquez ici : https://braincode.tn/dashboard/coach\n"
            "Depuis cette section, vous pouvez envoyer une demande facilement.\n"
            "Bonne chance dans votre parcours entrepreneurial 🚀"
        )

    elif intent_name == "liste_etapes_programme":
        return (
            f"{base}\n\n"
            f"L’utilisateur souhaite connaître les 12 étapes du programme BrainCode.\n"
            f"Réponds avec une liste numérotée très claire. Chaque étape doit contenir uniquement un titre explicite.\n\n"
            f"Format attendu :\n"
            f"1. Titre de l'étape 1\n"
            f"2. Titre de l'étape 2\n"
            f"...\n"
            f"12. Titre de l'étape 12\n\n"
            f"Exclusivement la liste. Pas d’introduction, pas de conclusion, pas de texte inutile.\n\n"
            f"Message utilisateur : {user_message}"
    )


    elif intent_name == "objectif_du_programme":
        return (
            f"{base}\n\n"
            f"Message utilisateur : {user_message}\n\n"
            "Explique l’objectif général du programme d’incubation BrainCode de manière concise et motivante."
        )

    elif intent_name == "livrable":
        return (
            f"{base}\n\n"
            f"Message utilisateur : {user_message}\n\n"
            "Indique que chaque étape a un livrable spécifique visible depuis l’espace personnel de l’utilisateur. "
            "Propose de consulter la plateforme pour plus de détails."
        )

    elif intent_name == "retard":
        return (
            f"{base}\n\n"
            f"Message utilisateur : {user_message}\n\n"
            "Rassure l’utilisateur sur son retard. Explique qu’il peut contacter son coach ou le support pour demander un délai supplémentaire."
        )

    elif intent_name == "prise_de_rdv":
        return (
            f"{base}\n\n"
            f"Message utilisateur : {user_message}\n\n"
            "Explique comment planifier un rendez-vous ou un appel depuis l’espace personnel. Propose aussi une suggestion d’action simple."
        )

    elif intent_name == "besoin_aide_financement":
        return (
            f"{base}\n\n"
            f"Message utilisateur : {user_message}\n\n"
            "Présente brièvement les options de financement disponibles dans le programme, et encourage l’utilisateur à consulter la section ‘Investisseurs’."
        )

    else:
        # Fallback si l’intention n’est pas personnalisée
        return (
            f"{base}\n\n"
            f"Message utilisateur : {user_message}\n\n"
            "Réponds de façon bienveillante et utile, avec un ton amical."
        )

async def stream_generator(final_prompt: str):
    MAX_RETRIES = 2
    TIMEOUT = 30.0

    ERROR_MESSAGES = {
        404: "Service non trouvé. Vérifie que Ollama est bien lancé sur http://127.0.0.1:11434",
        500: "Erreur interne du modèle. Merci de réessayer plus tard.",
        503: "Le service IA est temporairement indisponible.",
    }

    for attempt in range(MAX_RETRIES):
        try:
            async with httpx.AsyncClient(timeout=TIMEOUT) as client:
                response = await client.post(
                    "http://127.0.0.1:11434/api/generate",
                    json={
                        "model": "llama3",
                        "prompt": final_prompt,
                        "stream": True,
                        "options": {
                            "num_predict": 400  # ou plus selon besoin
                        }
                    }
                )

                if response.status_code != 200:
                    msg = ERROR_MESSAGES.get(response.status_code, f"Erreur inconnue (HTTP {response.status_code})")
                    logging.error(f"[Ollama HTTP Error] {msg}")
                    yield msg
                    return

                async for line in response.aiter_lines():
                    if line.strip():
                        try:
                            data = json.loads(line)
                            part = data.get("response", "")
                            if part:
                                yield part
                                await asyncio.sleep(0.01)
                        except Exception as e:
                            logging.error(f"[Stream Parsing Error] {e}")
                            yield "⚠️ Erreur de lecture dans le flux."
                            return
                return

        except httpx.ReadTimeout:
            logging.error("[Timeout] Temps d'attente dépassé pour la réponse du modèle.")
            yield "⏱️ Temps d'attente dépassé. Réessaie plus tard."
            return

        except Exception as e:
            logging.error(f"[Tentative {attempt + 1}] ❌ Erreur Ollama : {type(e).__name__} - {e}")

    yield "🚫 Toutes les tentatives ont échoué. Réessaie plus tard."





@app.post("/intentions/train")
async def retrain_intentions(payload: dict = Body(...)):
    try:
        training_text = payload.get("training_text", "").strip()

        # 1. Si vide, on retourne une erreur
        if not training_text:
            return JSONResponse(status_code=400, content={"error": "Aucun texte fourni."})

        # 2. Ajouter au fichier intentions.txt
        with open("intentions/intentions.txt", "a", encoding="utf-8") as f:
            f.write(f"\n{training_text}\n")

        # 3. Recalculer les embeddings
        global intentions_db
        try:
            intentions_db = create_intentions_db()
        except Exception as e:
            logging.error(f"[Startup Error] Impossible de créer intentions_db : {e}")
            intentions_db = None

        logging.info("[Intentions] Intentions mises à jour avec succès.")
        return {"status": "success", "message": "Base des intentions mise à jour."}

    except Exception as e:
        logging.error(f"[Intentions Reload ERROR] {e}")
        return JSONResponse(status_code=500, content={"error": str(e)})

try:
    intentions_db = create_intentions_db()
except Exception as e:
    logging.error(f"[Startup Error] Impossible de créer intentions_db : {e}")
    intentions_db = None

# @app.get("/ping")
# async def ping():
#     return {"status": "ok"}

@app.get("/ping")
async def ping():
    try:
        async with httpx.AsyncClient(timeout=2) as client:
            response = await client.get("http://127.0.0.1:11434/")
            if response.status_code == 200:
                return {"status": "ok", "ollama": "online"}
    except:
        pass
    return {"status": "ok", "ollama": "offline"}

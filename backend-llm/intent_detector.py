import json
from sentence_transformers import SentenceTransformer, util

# Chargement du modèle d'embedding (léger et rapide)
model = SentenceTransformer("all-MiniLM-L6-v2")

# Chargement des intentions
with open("intents.json", "r", encoding="utf-8") as f:
    intents_data = json.load(f)

# Préparation : indexation des phrases
intent_examples = []
intent_labels = []

for intent in intents_data["intents"]:
    for example in intent["examples"]:
        intent_examples.append(example)
        intent_labels.append(intent["name"])

# Calcul des embeddings des exemples
example_embeddings = model.encode(intent_examples, convert_to_tensor=True)


def detect_intent(message: str, threshold: float = 0.6) -> str:
    """
    Détecte l'intention d'un message utilisateur en comparant avec les exemples.

    :param message: Message utilisateur
    :param threshold: Seuil de similarité minimum pour accepter une correspondance
    :return: nom de l'intention ou "unknown"
    """
    input_embedding = model.encode(message, convert_to_tensor=True)
    similarities = util.cos_sim(input_embedding, example_embeddings)[0]
    best_score = similarities.max().item()
    best_index = similarities.argmax().item()

    if best_score >= threshold:
        return intent_labels[best_index]
    else:
        return "unknown"

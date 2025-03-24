from transformers import pipeline

# On utilise un modèle de génération simple (tu peux le changer après)
chatbot = pipeline("text-generation", model="distilgpt2")

def get_response(message):
    response = chatbot(message, max_length=50, num_return_sequences=1)
    return response[0]["generated_text"]

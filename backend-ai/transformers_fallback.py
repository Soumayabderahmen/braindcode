from transformers import pipeline

# Charge une seule fois
qa_pipeline = pipeline("text-generation", model="gpt2")

def get_transformers_response(message):
    try:
        result = qa_pipeline(message, max_length=50, do_sample=True, temperature=0.8)
        return result[0]["generated_text"]
    except Exception as e:
        return "Je suis désolé, je n'ai pas compris."

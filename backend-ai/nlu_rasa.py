import requests

def get_rasa_response(sender_id, message):
    try:
        url = "http://localhost:5006/webhooks/rest/webhook"
        payload = {
            "sender": sender_id,
            "message": message
        }
        response = requests.post(url, json=payload, timeout=10)

        if response.status_code == 200 and response.json():
            return response.json()[0].get("text")
    except Exception as e:
        print("Erreur Rasa :", e)

    return None

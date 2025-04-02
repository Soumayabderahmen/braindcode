# This files contains your custom actions which can be used to run
# custom Python code.
#
# See this guide on how to implement these action:
# https://rasa.com/docs/rasa/custom-actions


# This is a simple example for a custom action which utters "Hello World!"

# from typing import Any, Text, Dict, List
#
# from rasa_sdk import Action, Tracker
# from rasa_sdk.executor import CollectingDispatcher
#
#
# class ActionHelloWorld(Action):
#
#     def name(self) -> Text:
#         return "action_hello_world"
#
#     def run(self, dispatcher: CollectingDispatcher,
#             tracker: Tracker,
#             domain: Dict[Text, Any]) -> List[Dict[Text, Any]]:
#
#         dispatcher.utter_message(text="Hello World!")
#
#         return []
from rasa_sdk import Action, Tracker
from rasa_sdk.executor import CollectingDispatcher
from langdetect import detect
def get_language(text):
    try:
        return detect(text)
    except:
        return "en"
class ActionGoodbyeMultilang(Action):
    def name(self):
        return "action_goodbye_multilang"

    def run(self, dispatcher, tracker, domain):
        lang = get_language(tracker.latest_message.get("text"))
        if lang == "fr":
            dispatcher.utter_message(text="Au revoir 👋")
        else:
            dispatcher.utter_message(text="Goodbye 👋")
        return []
    
class ActionStepInfo(Action):
    def name(self):
        return "action_step_info"

    def run(self, dispatcher, tracker, domain):
        lang = get_language(tracker.latest_message.get("text"))
        sender_id = tracker.sender_id

        if sender_id.startswith("user_"):
            if lang == "fr":
                dispatcher.utter_message(text="Tu es connecté. Donne-moi le numéro d’étape, et je t’affiche ton avancement.")
            else:
                dispatcher.utter_message(text="You're logged in. Tell me the step number, and I'll show you your progress.")
        else:
            if lang == "fr":
                dispatcher.utter_message(text="Notre programme comprend 12 étapes, de la détection du problème jusqu’au pitch final.")
            else:
                dispatcher.utter_message(text="Our program includes 12 steps, from problem discovery to investor pitch.")
        return []
class ActionUtterGreet(Action):
    def name(self):
        return "action_utter_greet"

    def run(self, dispatcher, tracker, domain):
        lang = get_language(tracker.latest_message.get("text"))
        if lang == "fr":
            dispatcher.utter_message(text="Bonjour ! Comment puis-je vous aider ?")
        else:
            dispatcher.utter_message(text="Hi there! How can I help you?")
        return []

class ActionUtterHappy(Action):
    def name(self):
        return "action_utter_happy"

    def run(self, dispatcher, tracker, domain):
        lang = get_language(tracker.latest_message.get("text"))
        if lang == "fr":
            dispatcher.utter_message(text="Super, continue comme ça !")
        else:
            dispatcher.utter_message(text="Great, carry on!")
        return []

class ActionUtterCheerUp(Action):
    def name(self):
        return "action_utter_cheer_up"

    def run(self, dispatcher, tracker, domain):
        lang = get_language(tracker.latest_message.get("text"))
        if lang == "fr":
            dispatcher.utter_message(text="Voici de quoi te remonter le moral : https://i.imgur.com/nGF1K8f.jpg")
        else:
            dispatcher.utter_message(text="Here is something to cheer you up: https://i.imgur.com/nGF1K8f.jpg")
        return []

class ActionUtterDidThatHelp(Action):
    def name(self):
        return "action_utter_did_that_help"

    def run(self, dispatcher, tracker, domain):
        lang = get_language(tracker.latest_message.get("text"))
        if lang == "fr":
            dispatcher.utter_message(text="Est-ce que ça t’a aidé ?")
        else:
            dispatcher.utter_message(text="Did that help you?")
        return []

class ActionUtterIamabot(Action):
    def name(self):
        return "action_utter_iamabot"

    def run(self, dispatcher, tracker, domain):
        lang = get_language(tracker.latest_message.get("text"))
        if lang == "fr":
            dispatcher.utter_message(text="Je suis un bot, propulsé par Rasa.")
        else:
            dispatcher.utter_message(text="I am a bot, powered by Rasa.")
        return []
class ActionUtterHelp(Action):
    def name(self):
        return "action_utter_help"

    def run(self, dispatcher, tracker, domain):
        lang = get_language(tracker.latest_message.get("text"))
        if lang == "fr":
            dispatcher.utter_message(text="Bien sûr, je suis là pour vous aider ! Posez-moi votre question.")
        else:
            dispatcher.utter_message(text="Of course, I'm here to help! Feel free to ask me anything.")
        return []
class ActionDefaultFallback(Action):
    def name(self):
        return "action_default_fallback"

    def run(self, dispatcher, tracker, domain):
        lang = get_language(tracker.latest_message.get("text"))
        if lang == "fr":
            dispatcher.utter_message(text="Désolé, je n'ai pas compris. Peux-tu reformuler ?")
        else:
            dispatcher.utter_message(text="Sorry, I didn't understand. Could you rephrase that?")
        return []

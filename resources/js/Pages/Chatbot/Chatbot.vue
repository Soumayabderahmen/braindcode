<script setup>
import { ref, computed, onMounted, nextTick } from "vue";
//import { usePage } from "@inertiajs/vue3";
import axios from "axios";
import ChatInput from "./ChatInput.vue";
import { marked } from "marked";

const welcomeShown = ref(false);
const messages = ref([]);
const isOpen = ref(false);
const messageContainer = ref(null);
const botStatus = ref("unknown");

const markdownToHtml = (text) => {
  return marked.parse(text);
};

const botSettings = ref({
  bot_name: 'ChatBot',           // valeur par défaut
  welcome_message: "Bienvenue ! 🎉 Je suis là pour vous aider. Que puis-je faire pour vous aujourd’hui ?",
  primary_color: "#2563eb"
});
const getSessionId = () => {
  let id = localStorage.getItem("chatbot_session_id");
  if (!id) {
    id = crypto.randomUUID();
    localStorage.setItem("chatbot_session_id", id);
  }
  return id;
};

// const user = computed(() => usePage().props.auth.user);
// const isAuthenticated = computed(() => !!user.value);
const props = defineProps({
  user: Object,
});
const isAuthenticated = computed(() => !!props.user);

const isLoading = ref(false);
const chatStarted = ref(false);
const activeReactionIndex = ref(null);
const resetChat = async () => {
  messages.value = [];
  chatStarted.value = true;

  // Charger l'historique si l'utilisateur n'est PAS connecté
  if (!isAuthenticated.value) {
    await loadHistory();

    // S'il n'y a aucun message => afficher le message de bienvenue
    if (messages.value.length === 0 && !welcomeShown.value) {
      messages.value.push({
  sender: "bot",
  text: botSettings.value.welcome_message,
  animatedText: botSettings.value.welcome_message,
  reactable: false,
  isWelcome: true // ✅ ici OK
});

  welcomeShown.value = true;
}



    scrollToBottom();
  }
};


const formatDate = (dateStr) => {
  const date = new Date(dateStr);
  return date.toLocaleString();
};

const scrollToBottom = async () => {
  await nextTick();
  setTimeout(() => {
    const el = messageContainer.value;
    if (el) el.scrollTop = el.scrollHeight;
  }, 50);
};

const suggestions = [
  { 
    label: "📋 Les étapes du programme", 
    intent: "liste_etapes_programme",
    description: "Découvrez les étapes clés du parcours" 
  },
  { 
    label: "🎯 Objectif du programme", 
    intent: "objectif_du_programme",
    description: "Comprendre les buts à atteindre" 
  },
  { 
    label: "📅 Prendre rendez-vous", 
    intent: "prise_de_rdv",
    description: "Planifier une session avec un expert" 
  },
  { 
    label: "📨 Contacter un coach", 
    intent: "contact_mentor",
    description: "Obtenir de l'aide personnalisée" 
  },
  { 
    label: "💰 Besoin d'un financement", 
    intent: "besoin_aide_financement",
    description: "Explorer les options de financement disponibles" 
  },
];

const handleSuggestion = async (suggestion) => {
  chatStarted.value = true;
  messages.value.push({ text: suggestion.label, sender: "user" });
  scrollToBottom();

  isLoading.value = true;

  const botIndex = messages.value.length;
  messages.value.push({
    sender: "bot",
    text: "",
    animatedText: "",
    reactable: false,
    thinking: true
  });
  scrollToBottom();

  try {
    const response = await fetch("http://127.0.0.1:5005/chat", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-Session-ID": getSessionId(),
      },
      body: JSON.stringify({
        message: suggestion.label,
        intent_override: suggestion.intent,
        history: [],
      }),
    });

    const reader = response.body?.getReader();
    const decoder = new TextDecoder();
    let fullBotMessage = "";

    if (reader) {
      while (true) {
        const { done, value } = await reader.read();
        if (done) break;

        const chunk = decoder.decode(value, { stream: true });
        if (chunk) {
          messages.value[botIndex].animatedText += chunk;
          fullBotMessage += chunk;
          await nextTick();
          scrollToBottom();
        }
      }

      messages.value[botIndex].thinking = false;
      messages.value[botIndex].reactable = true;

      // (Facultatif) enregistrer l'historique si connecté
      if (isAuthenticated.value) {
        await axios.post("/api/chatbot/history/save", {
          userMessage: suggestion.label,
          botMessage: fullBotMessage.trim(),
          intent: suggestion.intent,
          startTime: Date.now()
        }, {
          headers: {
            "X-Session-ID": getSessionId()
          }
        });
      }
    }
  } catch (err) {
    messages.value[botIndex] = {
      sender: "bot",
      text: "Erreur réseau ou bot hors ligne.",
      animatedText: "Erreur réseau ou bot hors ligne.",
      reactable: false,
      thinking: false
    };
  } finally {
    isLoading.value = false;
    scrollToBottom();
  }
};


const sendMessage = async (message) => {
  if (!message.trim()) return;

  messages.value.push({ text: message, sender: "user" });
  scrollToBottom();
  isLoading.value = true;

  const startTime = Date.now();

  try {
    const history = messages.value
      .filter(m => m.sender === "user" || m.sender === "bot")
      .slice(-10)
      .map(m => ({ sender: m.sender, text: m.text }));

    const response = await fetch("http://127.0.0.1:5005/chat", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-Session-ID": getSessionId(),
      },
      body: JSON.stringify({ message, history }),
    });

    if (!response.body) throw new Error("Pas de réponse du serveur.");

    const botIndex = messages.value.length;
    messages.value.push({
      sender: "bot",
      text: "",
      animatedText: "",
      reactable: false,
      thinking: true
    });
    scrollToBottom();

    const reader = response.body.getReader();
    const decoder = new TextDecoder();
    let fullBotMessage = "";

    while (true) {
      const { done, value } = await reader.read();
      if (done) break;
      const chunk = decoder.decode(value, { stream: true });

      if (chunk) {
        messages.value[botIndex].animatedText += chunk;
        fullBotMessage += chunk;
        await nextTick();
        scrollToBottom();
      }
    }

    messages.value[botIndex].thinking = false;
    messages.value[botIndex].reactable = true;

    if (isAuthenticated.value) {
      await axios.post("/api/chatbot/history/save", {
        userMessage: message,
        botMessage: fullBotMessage.trim(),
        intent: null,
        startTime: startTime,
      }, {
        headers: {
          "X-Session-ID": getSessionId()
        }
      });
    }

  } catch (error) {
    messages.value.push({
      sender: "bot",
      text: "Erreur réseau ou bot hors ligne.",
      animatedText: "Erreur réseau ou bot hors ligne.",
      reactable: false,
    });
  } finally {
    isLoading.value = false;
    scrollToBottom();
  }
};





const checkBotStatus = async () => {
  try {
    const response = await axios.get("http://127.0.0.1:5005/ping");
    botStatus.value = response.status === 200 ? "online" : "offline";
  } catch (error) {
    botStatus.value = "offline";
  }
};

const loadHistory = async () => {
  try {
    const response = await axios.get("/api/chatbot/history", {
      headers: {
        "X-Session-ID": getSessionId() // 🔥 toujours envoyer le header, même si connecté
      }
    });

    messages.value = response.data.history.map((m) => ({
      text: m.message,
      sender: m.sender,
      date: m.created_at,
      reaction: m.reaction || null,
      reactable: m.sender === 'bot' && m.message !== botSettings.value.welcome_message,
    }));

    scrollToBottom();
  } catch (error) {
    console.error("Erreur chargement historique", error);
  }
};


const toggleChatbot = async () => {
  isOpen.value = !isOpen.value;

  if (isOpen.value) {
    if (isAuthenticated.value) {
      await loadHistory();
    } else {
      // Invité : pas besoin de loadHistory, on attend un resetChat()
    }

    if (messages.value.length === 0 && !welcomeShown.value) {
  messages.value.push({
    text: botSettings.value.welcome_message,
    sender: "bot",
    reactable: false,
    isWelcome: true  //  obligatoire pour afficher les suggestions !
  });
  welcomeShown.value = true;
}


    scrollToBottom();
  }
};



const toggleReaction = (index) => {
  activeReactionIndex.value = activeReactionIndex.value === index ? null : index;
};

const setReaction = (index, emoji) => {
  if (!messages.value[index].reactable) return;

  if (messages.value[index].reaction === emoji) {
    messages.value[index].reaction = null;
  } else {
    messages.value[index].reaction = emoji;
  }
  activeReactionIndex.value = null;

  if (isAuthenticated.value) {
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    axios.post("/chatbot/reaction", {
      message: messages.value[index].text,
      reaction: messages.value[index].reaction,
    }, {
      headers: {
        'X-CSRF-TOKEN': token,
        'X-Requested-With': 'XMLHttpRequest'
      }
    });
  }
};

onMounted(async () => {
  try {
    const res = await axios.get("/api/public/chatbot/settings");
    if (res.data) {
      botSettings.value = {
        bot_name: res.data.bot_name || "ChatBot",
        welcome_message: res.data.welcome_message || "Bienvenue !",
        primary_color: res.data.primary_color || "#2563eb"
      };
    }
  } catch (e) {
    console.warn("Erreur récupération paramètres bot :", e);
  }
  await checkBotStatus();
});



</script>

<template>
  <div>
    <!-- BACKDROP flouté -->
    <transition name="fade">
      <div
        v-if="isOpen"
        class="chatbot-backdrop"
        @click="toggleChatbot"
      ></div>
    </transition>

    <!-- BOUTON FLOTANT -->
    <div
      class="chat-icon-wrapper"
      :class="{ 'chat-open': isOpen, 'pulse': !isOpen }"
      @click="toggleChatbot"
    >
      <img src="/images/chat-icon.png" alt="chatbot icon" class="chat-icon" />
      <div class="chat-tooltip">Besoin d'aide ?</div>
    </div>
    <transition name="chatbot-popup">
      <div v-if="isOpen" class="chatbot-container">
      <div class="chatbot-header">

        <!-- <div class="chatbot-header" :style="{ background: `linear-gradient(to right, ${botSettings.primary_color}, ${adjustColor(botSettings.primary_color, -20)})` }"> -->
          <div class="chatbot-info">
            <div class="chatbot-avatar-container">
              <img src="/images/bot-avatar.png" alt="bot avatar" class="chatbot-avatar" />
            </div>
            <div class="chatbot-name-status">
              <h4 class="chat-title">{{ botSettings.bot_name }}</h4>
              <span class="active-status">{{ botStatus === 'online' ? '🟢 Active' : '🔴 Offline' }}</span>
            </div>
          </div>
          
          <button class="close-btn" @click="toggleChatbot">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M18 6L6 18M6 6L18 18" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </button>
        </div>

        <div class="chatbot-messages" ref="messageContainer">
          <div
            v-for="(msg, index) in messages"
            :key="index"
            class="message-block"
            :class="[
              msg.sender === 'user' ? 'user' : 'bot',
              msg.sender === 'bot' && msg.text.match(/^\d+\./m) ? 'list-message' : ''
            ]"
          >
            <div v-if="msg.sender === 'bot'" class="bot-message-wrapper">
              <div class="bot-message-header">
                <div class="bot-icon">
                  <img src="/images/bot-avatar.png" alt="bot icon" class="bot-avatar-small" />
                </div>
              <!-- Bulle normale -->
<div
  v-if="!(msg.thinking && index === messages.length - 1)"
  class="chat-bubble bot"
  :style="{ borderColor: `${botSettings.primary_color}20` }"
>
  <span v-html="markdownToHtml(msg.animatedText || msg.text)"></span>
</div>

<!-- Bulle thinking uniquement pendant le stream -->
<div
  v-else
  class="chat-bubble bot thinking"
>
  <span class="dot" :style="{ backgroundColor: botSettings.primary_color }"></span>
  <span class="dot" :style="{ backgroundColor: botSettings.primary_color }"></span>
  <span class="dot" :style="{ backgroundColor: botSettings.primary_color }"></span>
</div>




              </div>
              
              <!-- Suggestions en dehors de la bulle mais toujours dans le wrapper -->
             <div v-if="msg.isWelcome" class="suggestions-container">
    
    <!-- Version grille des suggestions - style cartes modernes -->
    <div class="suggestions-grid">
      <button
        v-for="(s, i) in suggestions"
        :key="i"
        class="suggestion-card"
        :style="{ '--accent-color': botSettings.primary_color }"
        @click="handleSuggestion(s)"
      >
        <div class="suggestion-top">
          <div class="suggestion-icon" :style="{ background: `${botSettings.primary_color}15`, color: botSettings.primary_color }">
            {{ s.label.split(' ')[0] }}
          </div>
          <div class="suggestion-indicator" :style="{ background: botSettings.primary_color }"></div>
        </div>
        <div class="suggestion-content">
          <h4 class="suggestion-title">{{ s.label.split(' ').slice(1).join(' ') }}</h4>
          <p class="suggestion-description">{{ s.description }}</p>
        </div>
        <div class="suggestion-footer">
          <div class="suggestion-arrow" :style="{ background: botSettings.primary_color }">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M5 12h14M12 5l7 7-7 7" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>
        </div>
      </button>
    </div>
    
   
  </div>

              <!-- Réactions visibles pour tous les messages bots reactables -->
              <div class="reaction-fixed" v-if="msg.reactable">
                <button class="reaction-trigger" @click="toggleReaction(index)" 
                       :style="{ borderColor: botSettings.primary_color }">
                  {{ msg.reaction || '😊' }}
                </button>
                <div v-if="activeReactionIndex === index" class="emoji-picker">
                  <span @click="setReaction(index, '👍')">👍</span>
                  <span @click="setReaction(index, '👎')">👎</span>
                  
                </div>
              </div>
            </div>

            <div v-else class="chat-bubble user" :style="{ backgroundColor: `${botSettings.primary_color}15` }">
              <span>{{ msg.text }}</span>
            </div>

            <small class="msg-date" v-if="msg.date">🕒 {{ formatDate(msg.date) }}</small>
          </div>



        </div>

        <!-- Input visible si utilisateur connecté OU si le non-connecté a cliqué sur "commencer" -->
        <transition name="fade">
          <ChatInput
            v-if="isAuthenticated || chatStarted"
            @send-message="sendMessage"
            :primary-color="botSettings.primary_color"
          />
        </transition>
        
        <!-- Pour les non-connectés, bouton affiché uniquement si chat non encore démarré -->
        <div v-if="!isAuthenticated && !chatStarted" class="chat-reset-container">
          <button class="chat-reset-btn" @click="resetChat" >
                    <!--<button class="chat-reset-btn" @click="resetChat" :style="{ backgroundColor: botSettings.primary_color }">-->

            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="chat-icon-svg">
              <path d="M5 12h14M12 5l7 7-7 7"></path>
            </svg>
            Commencer le <span class="highlight">chat</span>
          </button>
        </div>
      </div>
    </transition>
  </div>
</template>

<style scoped>
/* Reset et styles de base */
* {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}
/* Styles améliorés pour les suggestions */
.suggestions-container {
  margin-top: 24px;
  margin-left: 42px;
  width: calc(100% - 50px);
  animation: fadeInUp 0.5s ease;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.suggestions-heading {
  font-size: 16px;
  font-weight: 600;
  margin-bottom: 16px;
  padding-left: 4px;
  position: relative;
}

.suggestions-heading::after {
  content: '';
  position: absolute;
  bottom: -8px;
  left: 0;
  width: 40px;
  height: 3px;
  border-radius: 2px;
  background: currentColor;
  opacity: 0.6;
}

.suggestions-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 14px;
  margin-bottom: 16px;
}

.suggestion-card {
  background: white;
  border: 1px solid rgba(0, 102, 255, 0.15);
  border-radius: 16px;
  padding: 18px;
  box-shadow: rgba(0, 102, 255, 0.03) 0px 10px 30px;
  transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
  cursor: pointer;
  position: relative;
  overflow: hidden;
  display: flex;
  flex-direction: column;
  gap: 12px;
  text-align: left;
  height: 100%;
}

.suggestion-top {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.suggestion-card:hover {
  transform: translateY(-3px);
  box-shadow: rgba(0, 102, 255, 0.1) 0px 15px 25px;
  border-color: rgba(0, 102, 255, 0.3);
}

.suggestion-indicator {
  width: 24px;
  height: 4px;
  border-radius: 2px;
  opacity: 0.5;
  transition: all 0.3s ease;
}

.suggestion-card:hover .suggestion-indicator {
  width: 32px;
  opacity: 0.8;
}

.suggestion-card:active {
  transform: translateY(0px);
  box-shadow: rgba(0, 102, 255, 0.05) 0px 5px 15px;
}

.suggestion-icon {
  flex-shrink: 0;
  width: 42px;
  height: 42px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 20px;
  transition: all 0.3s ease;
  box-shadow: 0 3px 10px rgba(0, 102, 255, 0.08);
}

.suggestion-card:hover .suggestion-icon {
  transform: scale(1.05);
  box-shadow: 0 4px 12px rgba(0, 102, 255, 0.15);
}

.suggestion-footer {
  display: flex;
  justify-content: flex-end;
  margin-top: auto;
}

.suggestion-content {
  display: flex;
  flex-direction: column;
  flex: 1;
}

.suggestion-title {
  font-weight: 600;
  font-size: 15px;
  color: #333;
  margin: 0 0 4px 0;
  line-height: 1.3;
}

.suggestion-description {
  font-size: 12px;
  color: #666;
  margin: 0;
  line-height: 1.4;
}

.suggestion-arrow {
  flex-shrink: 0;
  width: 24px;
  height: 24px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
  opacity: 0.9;
  margin-left: auto;
}

.suggestion-card:hover .suggestion-arrow {
  transform: translateX(3px);
  opacity: 1;
}

/* Style pour l'option "Poser une autre question" */
.other-question {
  text-align: center;
  margin-top: 8px;
  padding-top: 12px;
  border-top: 1px dashed rgba(0, 102, 255, 0.15);
}

.chat-prompt-btn {
  background: transparent;
  border: 1px dashed;
  border-radius: 12px;
  padding: 10px 16px;
  font-size: 14px;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  transition: all 0.2s ease;
  cursor: pointer;
  font-weight: 500;
}

.chat-prompt-btn:hover {
  background: rgba(0, 102, 255, 0.03);
  transform: translateY(-2px);
}

.chat-prompt-btn svg {
  transition: transform 0.3s ease;
}

.chat-prompt-btn:hover svg {
  transform: rotate(90deg);
}

/* Responsive design pour mobiles */
@media (max-width: 480px) {
  .suggestions-grid {
    grid-template-columns: 1fr;
  }
  
  .suggestions-container {
    margin-left: 32px;
    width: calc(100% - 38px);
  }
  
  .suggestion-title {
    font-size: 14px;
  }
  
  .suggestion-description {
    font-size: 11px;
  }
  
  .suggestion-icon {
    width: 36px;
    height: 36px;
    font-size: 18px;
  }
}
/* Chat container moderne */
.chatbot-container {
  position: fixed;
  bottom: 80px;
  right: 20px;
  width: 380px;            
  height: 520px;           
  background: white;
  border-radius: 20px;     
  box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.15);
  overflow: hidden;
  z-index: 999;
  display: flex;
  flex-direction: column;
  transition: all 0.3s ease;
}

/* Header modernisé */
.chatbot-header {
  background: linear-gradient(to right, #00c6ff, #0072ff);
  color: white;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 16px;
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.chatbot-info {
  display: flex;
  align-items: center;
}

.chatbot-avatar-container {
  width: 42px;
  height: 42px;
  border-radius: 50%;
  background: white;
  padding: 2px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-right: 12px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.chatbot-avatar {
  width: 38px;
  height: 38px;
  border-radius: 50%;
  object-fit: cover;
}

.chatbot-name-status {
  line-height: 1.3;
}

.chat-title {
  margin: 0;
  font-size: 17px;
  font-weight: 600;
  letter-spacing: 0.2px;
}

.active-status {
  font-size: 13px;
  color: rgba(255, 255, 255, 0.9);
  display: flex;
  align-items: center;
  gap: 4px;
}

.close-btn {
  background: rgba(255, 255, 255, 0.2);
  border: none;
  color: white;
  width: 32px;
  height: 32px;
  border-radius: 50%;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s ease;
}

.close-btn:hover {
  background: rgba(255, 255, 255, 0.3);
  transform: rotate(90deg);
}

/* Messages container */
.chatbot-messages {
  flex: 1; 
  overflow-y: auto;
  padding: 20px;
  background: #f9fafc;
  display: flex;
  flex-direction: column;
  gap: 16px;
  scroll-behavior: smooth;
}

/* Scroll styling */
.chatbot-messages::-webkit-scrollbar {
  width: 6px;
}

.chatbot-messages::-webkit-scrollbar-track {
  background: transparent;
}

.chatbot-messages::-webkit-scrollbar-thumb {
  background-color: rgba(0, 0, 0, 0.1);
  border-radius: 10px;
}

/* Message blocks */
.message-block {
  display: flex;
  flex-direction: column;
  max-width: 85%;
  animation: fadeInMessage 0.3s ease;
}

@keyframes fadeInMessage {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.message-block.user {
  align-self: flex-end;
  align-items: flex-end;
}

.message-block.bot {
  align-self: flex-start;
  align-items: flex-start;
}

/* Bot message wrapper */
.bot-message-wrapper {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  gap: 12px;
  position: relative;
  width: 100%;
}

.bot-message-header {
  display: flex;
  align-items: flex-start;
  width: 100%;
}

.bot-icon {
  width: 32px;
  height: 32px;
  margin-right: 10px;
  flex-shrink: 0;
  border-radius: 50%;
  overflow: hidden;
  background: white;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
}

.bot-avatar-small {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

/* Chat bubbles */
.chat-bubble {
  padding: 14px 18px;
  border-radius: 18px;
  font-size: 14px;
  line-height: 1.5;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
  word-wrap: break-word;
  white-space: pre-line;
  transition: all 0.2s ease;
}

.chat-bubble.user {
  background-color: rgba(0, 102, 255, 0.08);
  color: #333;
  align-self: flex-end;
  border-bottom-right-radius: 4px;
  margin-left: auto;
  font-weight: 500;
}

.chat-bubble.bot {
  background-color: white;
  color: #333;
  border-bottom-left-radius: 4px;
  border: 1px solid rgba(0, 102, 255, 0.1);
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
  margin-right: auto;
}

/* Suggestions styling */
.suggestions-container {
  margin-top: 14px;
  margin-left: 42px;
  width: calc(100% - 42px);
}

.suggestions-modern {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 12px;
  margin-bottom: 8px;
}


.suggestion-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 4px;
  height: 100%;
  background: var(--accent-color, #0066FF);
  opacity: 0.4;
  transition: all 0.3s ease;
}



.suggestion-card:hover::before {
  width: 6px;
  opacity: 0.7;
}






/* Thinking animation */
.chat-bubble.thinking {
  display: flex;
  gap: 6px;
  align-items: center;
  padding: 12px 18px;
  width: auto;
  max-width: 100px;
  background-color: white;
  border-radius: 18px;
  border: 1px solid rgba(0, 102, 255, 0.1);
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
  margin-right: auto;
}



.thinking .dot {
  height: 8px;
  width: 8px;
  opacity: 0.8;
  border-radius: 50%;
  background: #0066FF;
  animation: dotPulse 1.5s ease-in-out infinite;
}

.thinking .dot:nth-child(2) {
  animation-delay: 0.2s;
}

.thinking .dot:nth-child(3) {
  animation-delay: 0.4s;
}

@keyframes dotPulse {
  0%, 100% {
    transform: translateY(0);
  }
  50% {
    transform: translateY(-6px);
    opacity: 0.5;
  }
}

/* Animations */
.chatbot-popup-enter-active {
  animation: chatbotScaleIn 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.chatbot-popup-leave-active {
  animation: chatbotScaleOut 0.3s ease-in;
}

@keyframes chatbotScaleIn {
  0% {
    transform: scale(0.8);
    opacity: 0;
  }
  100% {
    transform: scale(1);
    opacity: 1;
  }
}

@keyframes chatbotScaleOut {
  0% {
    transform: scale(1);
    opacity: 1;
  }
  100% {
    transform: scale(0.85);
    opacity: 0;
  }
}

/* Reset button */
.chat-reset-container {
  text-align: center;
  padding: 20px;
  background: white;
  border-top: 1px solid #f0f0f0;
}

.chat-reset-btn {
  background: #0066FF;
  border: none;
  color: white;
  padding: 12px 24px;
  font-weight: 600;
  border-radius: 12px;
  cursor: pointer;
  transition: 0.3s;
  box-shadow: 0px 4px 12px rgba(0, 102, 255, 0.2);
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  margin: 0 auto;
}

.chat-reset-btn:hover {
  transform: translateY(-3px);
  box-shadow: 0px 6px 16px rgba(0, 102, 255, 0.3);
}

.chat-icon-svg {
  flex-shrink: 0;
}

.highlight {
  font-weight: 700;
  position: relative;
  z-index: 1;
}

/* Icon wrapper */
.chat-icon-wrapper {
  position: fixed;
  bottom: 20px;
  right: 20px;
  cursor: pointer;
  z-index: 1000;
  transition: all 0.3s ease;
}

.chat-icon {
  width: 64px;
  height: 64px;
  border-radius: 50%;
  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
  transition: all 0.3s ease;
}

.chat-tooltip {
  position: absolute;
  right: 76px;
  top: 50%;
  transform: translateY(-50%);
  background-color: #333;
  color: white;
  padding: 8px 16px;
  border-radius: 10px;
  font-size: 14px;
  font-weight: 500;
  white-space: nowrap;
  opacity: 0;
  transition: all 0.3s ease;
  pointer-events: none;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.chat-tooltip:after {
  content: '';
  position: absolute;
  right: -6px;
  top: 50%;
  transform: translateY(-50%);
  border-width: 6px 0 6px 6px;
  border-style: solid;
  border-color: transparent transparent transparent #333;
}

.chat-icon-wrapper:hover .chat-tooltip {
  opacity: 1;
  right: 80px;
}

.chat-icon-wrapper.pulse {
  animation: pulse 2s infinite;
}

@keyframes pulse {
  0% {
    transform: scale(1);
    box-shadow: 0 0 0 0 rgba(0, 102, 255, 0.7);
  }
  70% {
    transform: scale(1.05);
    box-shadow: 0 0 0 12px rgba(0, 102, 255, 0);
  }
  100% {
    transform: scale(1);
    box-shadow: 0 0 0 0 rgba(0, 102, 255, 0);
  }
}

.chat-icon-wrapper.chat-open {
  bottom: 530px;
  right: 30px;
  transform: scale(0.8);
  transition: all 0.4s ease;
}

.chat-icon-wrapper.chat-open .chat-icon {
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.chatbot-backdrop {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.25);
  backdrop-filter: blur(3px);
  z-index: 998;
  transition: all 0.3s ease;
}

/* Fade transition */
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}

/* Reaction system */
.reaction-fixed {
  position: absolute;
  bottom: -12px;
  right: 10px;
}

.reaction-trigger {
  background: rgba(255, 255, 255, 0.95);
  border: 1px solid #e0e0e0;
  font-size: 16px;
  cursor: pointer;
  transition: all 0.2s;
  border-radius: 50%;
  width: 28px;
  height: 28px;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
}

.reaction-trigger:hover {
  transform: scale(1.2);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.12);
}

.emoji-picker {
  position: absolute;
  bottom: 140%;
  right: 0;
  display: flex;
  gap: 10px;
  background: white;
  padding: 10px 14px;
  border-radius: 30px;
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
  z-index: 100;
  animation: fadeInEmoji 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

.emoji-picker span {
  font-size: 20px;
  cursor: pointer;
  transition: transform 0.2s;
}

.emoji-picker span:hover {
  transform: scale(1.3);
}

@keyframes fadeInEmoji {
  from {
    transform: scale(0.9) translateY(10px);
    opacity: 0;
  }
  to {
    transform: scale(1) translateY(0);
    opacity: 1;
  }
}

.msg-date {
  font-size: 11px;
  color: #999;
  margin-top: 6px;
  font-style: italic;
}
</style>

<script>
export default {
  methods: {
    adjustColor(hex, percent) {
      // Convertir hex en RGB
      let r = parseInt(hex.substring(1, 3), 16);
      let g = parseInt(hex.substring(3, 5), 16);
      let b = parseInt(hex.substring(5, 7), 16);

      // Ajuster la luminosité
      r = Math.max(0, Math.min(255, r + percent));
      g = Math.max(0, Math.min(255, g + percent));
      b = Math.max(0, Math.min(255, b + percent));

      // Convertir RGB en hex
      return "#" + ((1 << 24) + (r << 16) + (g << 8) + b).toString(16).slice(1);
    }
  }
}
</script>
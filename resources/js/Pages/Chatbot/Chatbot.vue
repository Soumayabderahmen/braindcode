<script setup>
import { ref, computed, onMounted, nextTick } from "vue";
import { usePage } from "@inertiajs/vue3";
import axios from "axios";
import ChatInput from "./ChatInput.vue";
const welcomeShown = ref(false);
const messages = ref([]);
const isOpen = ref(false);
const messageContainer = ref(null);
const botStatus = ref("unknown");
const user = computed(() => usePage().props.auth.user);
const isAuthenticated = computed(() => !!user.value);
const isLoading = ref(false);
const chatStarted = ref(false); // contrôle l'affichage de l'input pour les non-connectés

//chatbot.vue


const resetChat = () => {
  messages.value = [];
  chatStarted.value = true; // après clic, on affiche l’input
  scrollToBottom();
};

const formatDate = (dateStr) => {
  const date = new Date(dateStr);
  return date.toLocaleString();
};

const scrollToBottom = async () => {
  await nextTick();
  const el = messageContainer.value;
  if (el) el.scrollTop = el.scrollHeight;
};

const sendMessage = async (message) => {
  if (!message.trim()) return;
  messages.value.push({ text: message, sender: "user" });
  scrollToBottom();
  isLoading.value = true;

  try {
    // 1️⃣ Appel direct à FastAPI
    const response = await fetch("http://127.0.0.1:5005/chat", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ message }),
    });

    const result = await response.json();
    const reply = result.reply || "❌ Pas de réponse du bot.";

    // 2️⃣ Affiche la réponse
    // const chunks = reply.match(/.{1,500}/g) || [];
    // chunks.forEach((chunk) => {
    //   messages.value.push({ text: chunk, sender: "bot" });
    // });
    messages.value.push({ text: reply, sender: "bot" });

    // 3️⃣ Si connecté → enregistrer l’historique dans Laravel
    if (isAuthenticated.value) {
      await axios.post("/api/chatbot/history/save", {
        userMessage: message,
        botMessage: reply,
      });
    }

  } catch (error) {
    messages.value.push({ text: "Erreur réseau ou bot hors ligne.", sender: "bot" });
  } finally {
    isLoading.value = false;
    scrollToBottom();
  }
};
// const copyToClipboard = (text) => {
//   navigator.clipboard.writeText(text).then(() => {
//     alert("✅ Réponse copiée !");
//   });
// };
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
    const response = await axios.get("/api/chatbot/history");
    messages.value = response.data.history.map((m) => ({
      text: m.message,
      sender: m.sender,
      date: m.created_at,
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
    }

    if (!welcomeShown.value) {
      // ✅ Afficher les messages d’accueil
      messages.value.push({
        text: "Bienvenue ! 🎉 Je suis là pour vous aider. Que puis-je faire pour vous aujourd’hui ?",
        sender: "bot"
      });

     

      welcomeShown.value = true;
      scrollToBottom();
    }
  }
};

onMounted(async () => {
  if (isAuthenticated.value) {
    await loadHistory();
  }
  await checkBotStatus();
});
</script>

<template>
  <div>
     <!-- ✅ BACKDROP flouté -->
     <transition name="fade">
      <div
        v-if="isOpen"
        class="chatbot-backdrop"
        @click="toggleChatbot"
      ></div>
    </transition>

    <!-- ✅ BOUTON FLOTANT -->
    <div
  class="chat-icon-wrapper"
  :class="{ 'chat-open': isOpen, 'pulse': !isOpen }"
  @click="toggleChatbot"
>
  <img src="/images/chat-icon.png" alt="chatbot icon" class="chat-icon" />
  <div class="chat-tooltip">Besoin d’aide ?</div>
</div>
    <transition name="chatbot-popup">
      <div v-if="isOpen" class="chatbot-container">
      <div class="chatbot-header">
        <div class="chatbot-info">
          <img src="/images/bot-avatar.png" alt="bot avatar" class="chatbot-avatar" />
          <div class="chatbot-name-status">
            <h4 class="chat-title">ChatBot</h4>
            <span class="active-status">{{ botStatus === 'online' ? '🟢 Active' : '🔴 Offline' }}</span>
          </div>
        </div>
        <button class="close-btn" @click="toggleChatbot">✖</button>
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
  <div class="chat-bubble">
    <!-- <span v-if="msg.sender === 'bot'" class="copy-btn" @click="copyToClipboard(msg.text)">📋</span> -->

    <span>{{ msg.text }}</span>
  </div>
  <small class="msg-date" v-if="msg.date">🕒 {{ formatDate(msg.date) }}</small>
</div>

  <!-- 💬 Loading (thinking) indicator -->
  <div v-if="isLoading" class="chat-bubble bot thinking">
    <span class="dot"></span>
    <span class="dot"></span>
    <span class="dot"></span>
  </div>
</div>

      <!-- Input visible si utilisateur connecté OU si le non-connecté a cliqué sur "commencer" -->
      <transition name="fade">
  <ChatInput
    v-if="isAuthenticated || chatStarted"
    @send-message="sendMessage"
  />
</transition>
<!-- Pour les non-connectés, bouton affiché uniquement si chat non encore démarré -->
<div v-if="!isAuthenticated && !chatStarted" class="chat-reset-container">
  <button class="chat-reset-btn" @click="resetChat">
    👉 Commencer le <span class="highlight">chat</span> à nouveau
  </button>
</div>
</div>
</transition>
  </div>
</template>

<style scoped>
.chatbot-backdrop {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.25); 
  backdrop-filter: blur(3px);      
  z-index: 998;                    
}

.message-block.bot.list-message .chat-bubble {
  background: linear-gradient(to right, #00c6ff, #0072ff);
  border: 1px solid #ffd17b;
  color: #333;
}
.chatbot-messages {
  flex: 1; 
  overflow-y: auto;
  padding: 12px;
  background: #f9f9f9;
  display: flex;
  flex-direction: column;
  gap: 10px;
}
.message-block {
  display: flex;
  flex-direction: column;
  max-width: 75%;
}

.message-block.user {
  align-self: flex-end;
  align-items: flex-end;
}

.message-block.bot {
  align-self: flex-start;
  align-items: flex-start;
}
/* 💬 Bulle de base */
.chat-bubble {
  /* max-width: 80%; */
  padding: 10px 15px;
  border-radius: 16px;
  font-size: 14px;
  line-height: 1.4;
  box-shadow: 0 1px 4px rgba(0, 0, 0, 0.06); /* effet doux */
  word-wrap: break-word;
  white-space: pre-line;
}

/* 👤 Message utilisateur */
.chat-bubble.user {
  
  background-color: #e5e7eb;
  color: #333;
  align-self: flex-end;
  border-bottom-right-radius: 0;
  margin-left: auto;
}

/* 🤖 Message du bot */
.chat-bubble.bot {
  background: linear-gradient(to right, #00c6ff, #00c6ff);
  color: rgb(0, 0, 0);
  align-self: flex-start;
  border-bottom-left-radius: 0;
  margin-right: auto;
}


.message-block.user .chat-bubble {
  background-color: #e5e7eb;
  color: #333;
  border-bottom-right-radius: 0;
}

.message-block.bot .chat-bubble {
  /* background: linear-gradient(to right, #00c6ff, #2b9dbd); */
  background: linear-gradient(to right, #00c6ff, #0072ff);
  color: rgb(6, 6, 6);
  border-bottom-left-radius: 0;
}
/* .copy-btn {
  float: right;
  margin-left: 8px;
  cursor: pointer;
  font-size: 14px;
  color: white;
  background: rgba(0, 0, 0, 0.15);
  border-radius: 4px;
  padding: 2px 5px;
}
.message-block.bot .chat-bubble {
  position: relative;
  padding-top: 25px;
} */


/* .chat-bubble {
  max-width: 75%;
  padding: 10px 14px;
  border-radius: 16px;
  font-size: 14px;
  line-height: 1.4;
  display: inline-block;
  margin-bottom: 6px;
} */

/* .chat-bubble.user {
  background-color: #4e46e3;
  color: white;
  align-self: flex-end;
  border-bottom-right-radius: 0;
  margin-left: auto;
} */
/* .chat-bubble.bot {
  background-color: #f1f1f1;
  color: #333;
  align-self: flex-start;
  border-bottom-left-radius: 0;
  margin-right: auto;
} */


/* ✅ Animation d’ouverture style scale + fade */
.chatbot-popup-enter-active {
  animation: chatbotScaleIn 0.35s ease-out;
}
.chatbot-popup-leave-active {
  animation: chatbotScaleOut 0.25s ease-in;
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


/* Thinking dots animation */
.thinking {
  display: flex;
  gap: 4px;
  align-items: center;
  margin-left: 10px;
  margin-top: 4px;
}

.dot {
  height: 7px;
  width: 7px;
  opacity: 0.7;
  border-radius: 50%;
  background: #1f1f1f;
  animation: dotPulse 1.5s ease-in-out infinite;
}

@keyframes dotPulse {
  0%, 44% {
    transform: translateY(0);
  }
  28% {
    transform: translateY(-4px);
    opacity: 0.4;
  }
  72% {
    transform: translateY(4px);
    opacity: 0.4;
  }
  100% {
    transform: translateY(0);
    opacity: 0.7;
  }
}
.chat-icon-wrapper:hover {
  transform: scale(1.1);
  transition: transform 0.3s ease;
}
@keyframes pulse {
  0% {
    transform: scale(1);
    box-shadow: 0 0 0 0 rgba(0, 123, 255, 0.7);
  }
  70% {
    transform: scale(1.1);
    box-shadow: 0 0 0 10px rgba(0, 123, 255, 0);
  }
  100% {
    transform: scale(1);
    box-shadow: 0 0 0 0 rgba(0, 123, 255, 0);
  }
}
.chat-icon-wrapper {
  position: fixed;
  bottom: 20px;
  right: 20px;
  cursor: pointer;
  z-index: 1000;
}

.chat-icon {
  width: 60px;
  height: 60px;
  border-radius: 50%;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
}

.chat-tooltip {
  position: absolute;
  right: 70px;
  top: 50%;
  transform: translateY(-50%);
  background-color: #007bff;
  color: white;
  padding: 6px 10px;
  border-radius: 20px;
  font-size: 13px;
  white-space: nowrap;
  opacity: 0;
  transition: opacity 0.3s ease;
  pointer-events: none;
}

.chat-icon-wrapper:hover .chat-tooltip {
  opacity: 1;
}

.chat-icon-wrapper.pulse {
  animation: pulse 1.5s infinite;
}

.chat-icon-wrapper.chat-open {
  bottom: 370px; /* pour qu’elle monte au-dessus de la fenêtre du chatbot */
  transition: bottom 0.3s ease;
}
.chat-reset-container {
  text-align: center;
  padding: 10px;
}

.chat-reset-btn {
  background: linear-gradient(to right, #0099ff, #00cc88);
  border: none;
  color: white;
  padding: 10px 15px;
  font-weight: bold;
  border-radius: 25px;
  cursor: pointer;
  transition: 0.3s;
  box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.2);
}

.chat-reset-btn:hover {
  transform: scale(1.03);
}

.chat-reset-btn .highlight {
  background: yellow;
  color: black;
  padding: 2px 4px;
  border-radius: 4px;
}



.chatbot-container {
  position: fixed;
  bottom: 80px;
  right: 20px;
  width: 360px;            
  height: 500px;           
  background: white;
  border-radius: 16px;     
  box-shadow: 0px 6px 24px rgba(0, 0, 0, 0.12);
  overflow: hidden;
  z-index: 999;
  display: flex;
  flex-direction: column;
}

.chatbot-header {
  background: linear-gradient(to right, #00c6ff, #0072ff);
  color: white;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 10px;
}
.chatbot-info {
  display: flex;
  align-items: center;
}
.chatbot-avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  margin-right: 10px;
}
.chatbot-name-status {
  line-height: 1.2;
}
.chat-title {
  margin: 0;
  font-size: 16px;
}
.active-status {
  font-size: 12px;
  color: white;
}
.close-btn {
  background: transparent;
  border: none;
  color: white;
  font-size: 18px;
  cursor: pointer;
}
.chatbot-welcome {
  padding: 15px;
  text-align: center;
  font-size: 14px;
  color: #444;
}
/* .chatbot-messages {
  height: 250px;
  overflow-y: auto;
  padding: 10px;
  background: #f9f9f9;
} */
.chatbot-spinner {
  text-align: center;
  color: #007bff;
  margin: 10px;
  font-style: italic;
}
.message-wrapper {
  margin-bottom: 10px;
}
.msg-date {
  font-size: 11px;
  color: #999;
  margin-top: 4px;
  display: flex;
  align-items: center;
  gap: 4px;
}
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.4s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
.fade-enter-to, .fade-leave-from {
  opacity: 1;
}
</style>

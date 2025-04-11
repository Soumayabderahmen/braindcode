<script setup>
import { ref, computed, onMounted, nextTick } from "vue";
import { usePage } from "@inertiajs/vue3";
import axios from "axios";
import ChatBubble from "./ChatBubble.vue";
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
    const trimmedMessage = message.slice(0, 500);
    const response = await axios.post("/api/chatbot", { message });
    const chunks = response.data.reply.match(/.{1,500}/g) || [];
    chunks.forEach((chunk) => {
      messages.value.push({ text: chunk, sender: "bot" });
    });
  } catch (error) {
    messages.value.push({
      text: "⏳ Je réfléchis... cela peut prendre un peu de temps...",
      sender: "bot",
    });
  } finally {
    isLoading.value = false;
  }
  scrollToBottom();
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
    <div
  class="chat-icon-wrapper"
  :class="{ 'chat-open': isOpen, 'pulse': !isOpen }"
  @click="toggleChatbot"
>
  <img src="/images/chat-icon.png" alt="chatbot icon" class="chat-icon" />
  <div class="chat-tooltip">Besoin d’aide ?</div>
</div>
    <transition name="fade">
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
        <div v-for="(msg, index) in messages" :key="index" class="message-wrapper">
          <ChatBubble :message="msg" />
          <small class="msg-date" v-if="msg.date">🕒 {{ formatDate(msg.date) }}</small>
        </div>
      </div>

      <div v-if="isLoading" class="chatbot-spinner">
        ⏳ Chargement de la réponse...
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
.chatbot-container {
  position: fixed;
  bottom: 90px;
  right: 20px;
  width: 350px;
  background: white;
  border-radius: 10px;
  box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
  overflow: hidden;
  z-index: 999;
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
.chatbot-messages {
  height: 250px;
  overflow-y: auto;
  padding: 10px;
  background: #f9f9f9;
}
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
  color: #888;
  margin-left: 12px;
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

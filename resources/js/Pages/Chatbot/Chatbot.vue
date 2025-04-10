<script setup>
import { ref, computed, onMounted, nextTick } from "vue";
import { usePage } from "@inertiajs/vue3";
import axios from "axios";
import ChatBubble from "./ChatBubble.vue";
import ChatInput from "./ChatInput.vue";
import ChatbotIcon from "./ChatbotIcon.vue";

const messages = ref([]);
const isOpen = ref(false);
const messageContainer = ref(null);
const botStatus = ref("unknown"); // "online", "offline"
const user = computed(() => usePage().props.auth.user);
const isAuthenticated = computed(() => !!user.value);
const isLoading = ref(false)

// Scroll toujours en bas après chaque nouveau message
const scrollToBottom = async () => {
    await nextTick();
    const el = messageContainer.value;
    if (el) el.scrollTop = el.scrollHeight;
};

// Envoi de message
const sendMessage = async (message) => {
  if (!message.trim()) return;

  messages.value.push({ text: message, sender: "user" });
  scrollToBottom();
  isLoading.value = true;

  try {
    const trimmedMessage = message.slice(0, 500); // max 500 caractères
    const response = await axios.post("/api/chatbot", { message });
    console.log("Réponse du bot (source):", response.data.source);
    console.log("Longueur réponse :", response.data.reply.length);

    const chunks = response.data.reply.match(/.{1,500}/g) || [];
    chunks.forEach(chunk => {
      messages.value.push({ text: chunk, sender: "bot" });
    });

  } catch (error) {
    messages.value.push({
      text: "⏳ Je réfléchis... cela peut prendre un peu de temps...",
      sender: "bot"
    });
  } finally {
    isLoading.value = false;
  }

  scrollToBottom();
};


const checkBotStatus = async () => {
    try {
        const response = await axios.get("http://127.0.0.1:5005/ping"); // Ou ton endpoint FastAPI
        if (response.status === 200) {
            botStatus.value = "online";
        } else {
            botStatus.value = "offline";
        }
    } catch (error) {
        botStatus.value = "offline";
    }
};
// Chargement de l'historique
const loadHistory = async () => {
    try {
        const response = await axios.get("/api/chatbot/history");
        messages.value = response.data.history.map(m => ({
            text: m.message,
            sender: m.sender,
            date: m.created_at,
        }));
        scrollToBottom();
    } catch (error) {
        console.error("Erreur chargement historique", error);
    }
};

// Toggle chatbot
const toggleChatbot = async () => {
    isOpen.value = !isOpen.value;
    if (isOpen.value && isAuthenticated.value) {
        await loadHistory();
    }
};

// Chargement à l'arrivée
onMounted(async () => {
    if (isAuthenticated.value) {
        await loadHistory();
    }
    await checkBotStatus();
});

</script>

<template>
    <div>
      <ChatbotIcon @click="toggleChatbot" v-if="!isOpen" />
      <div v-if="isOpen" class="chatbot-container">
        <div class="chatbot-header ,bot-status">
          <span>🤖 Chatbot
            <span :class="{ online: botStatus === 'online', offline: botStatus === 'offline' }">
           {{ botStatus === 'online' ? '🟢' : '🔴' }}
         </span>
          </span>
         
          <button @click="toggleChatbot">✖</button>
        </div>
  
        <!-- 👇 Ajoute ce bloc ici -->
       
  
        <div class="chatbot-messages" ref="messageContainer">
          <ChatBubble v-for="(msg, index) in messages" :key="index" :message="msg" />
        </div>
        <div v-if="isLoading" class="chatbot-spinner">
            ⏳ Chargement de la réponse...
        </div>
  
        <ChatInput @send-message="sendMessage" />
      </div>
    </div>
  </template>
  

<style scoped>
.chatbot-spinner {
  text-align: center;
  color: #007bff;
  margin: 10px;
  font-style: italic;
}

.chatbot-container {
    position: fixed;
    bottom: 20px;
    right: 20px;
    width: 350px;
    background: white;
    border-radius: 10px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    z-index: 999;
}
.chatbot-header {
    background: #007bff;
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px;
}
.chatbot-messages {
    height: 250px;
    overflow-y: auto;
    padding: 10px;
}
.bot-status {
  font-size: 14px;
  margin: 5px 10px;
}
.online {
  color: green;
}
.offline {
  color: red;
}

</style>

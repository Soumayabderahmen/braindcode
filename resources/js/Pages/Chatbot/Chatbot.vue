<script setup>
import { ref, computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import axios from "axios";
import ChatBubble from "./ChatBubble.vue";
import ChatInput from "./ChatInput.vue";
import ChatbotIcon from "./ChatbotIcon.vue";

const messages = ref([]);
const isOpen = ref(false);
const page = usePage();
const user = computed(() => page.props.auth.user);
const isAuthenticated = computed(() => !!user.value);

// ✅ Envoi du message au backend Laravel
const sendMessage = async (message) => {
    if (!message.trim()) return;

    messages.value.push({ text: message, sender: "user" });

    try {
        const response = await axios.post("/api/chatbot", { message });

        messages.value.push({ text: response.data.reply, sender: "bot" });
    } catch (error) {
        messages.value.push({ text: "Erreur de connexion avec le chatbot.", sender: "bot" });
    }
};

// ✅ Toggle d'affichage du chatbot
const toggleChatbot = () => {
    isOpen.value = !isOpen.value;
};
</script>

<template>
    <div>
        <ChatbotIcon @click="toggleChatbot" v-if="!isOpen" />
        <div v-if="isOpen" class="chatbot-container">
            <div class="chatbot-header">
                <span>🤖 Chatbot</span>
                <button @click="toggleChatbot">✖</button>
            </div>
            <div class="chatbot-messages">
                <ChatBubble v-for="(msg, index) in messages" :key="index" :message="msg" />
            </div>
            <ChatInput @send-message="sendMessage" />
        </div>
    </div>
</template>

<style scoped>
.chatbot-container {
    position: fixed;
    bottom: 20px;
    right: 20px;
    width: 350px;
    background: white;
    border-radius: 10px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    overflow: hidden;
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
</style>

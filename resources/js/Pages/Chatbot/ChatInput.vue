<script setup>
import { ref } from "vue";

const newMessage = ref("");
const emit = defineEmits(["send-message"]);

const send = () => {
    if (newMessage.value.trim()) {
        emit("send-message", newMessage.value);
        newMessage.value = "";
    }
};
</script>

<template>
    <div class="chatbot-input">
        <input v-model="newMessage" type="text" placeholder="Écrivez un message..." @keyup.enter="send">
        <button @click="send">Envoyer</button>
    </div>
</template>

<style scoped>
.chatbot-input {
    display: flex;
    padding: 10px;
    border-top: 1px solid #ddd;
}
.chatbot-input input {
    flex: 1;
    padding: 8px;
    border: none;
    border-radius: 5px;
}
.chatbot-input button {
    background: #007bff;
    color: white;
    border: none;
    padding: 8px;
    border-radius: 5px;
    margin-left: 5px;
}
</style>

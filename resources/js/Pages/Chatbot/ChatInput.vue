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
  <div class="chat-input-wrapper">
    <textarea
      v-model="newMessage"
      placeholder="Écrivez un message..."
      @keyup.enter.exact.prevent="send"
      rows="1"
      class="chat-textarea"
    />

  

    <!-- Icône pièce jointe -->
    <button class="icon-btn" title="Fichier">
      📎
    </button>

    <!-- Bouton envoi -->
    <button class="send-btn" @click="send">
      <svg viewBox="0 0 24 24" width="20" height="20" fill="white">
        <path d="M2 21l21-9L2 3v7l15 2-15 2v7z" />
      </svg>
    </button>
  </div>
</template>

<style scoped>
.chat-input-wrapper {
  display: flex;
  align-items: center;
  border: 2px solid #6186ff; /* Violet */
  border-radius: 50px;
  padding: 8px 12px;
  background-color: #fff;
  margin: 8px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

.chat-textarea {
  flex: 1;
  resize: none;
  border: none;
  outline: none;
  padding: 10px 14px;
  font-size: 14px;
  line-height: 1.4;
  border-radius: 30px;
  max-height: 100px;
  overflow-y: auto;
  background-color: transparent;
}

.icon-btn {
  background: none;
  border: none;
  font-size: 18px;
  cursor: pointer;
  margin: 0 5px;
  padding: 5px;
  color: #666;
  transition: 0.2s;
}
.icon-btn:hover {
  color: #000;
}

.send-btn {
  background-color: #4635df;
  border: none;
  border-radius: 50%;
  width: 38px;
  height: 38px;
  margin-left: 5px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: background-color 0.3s ease;
}
.send-btn:hover {
  background-color: #4a38dc;
}
</style>

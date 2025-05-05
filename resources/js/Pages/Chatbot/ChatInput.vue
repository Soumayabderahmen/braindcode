<script setup>
import { ref } from "vue";

const newMessage = ref("");
const emit = defineEmits(["send-message"]);
const fileInput = ref(null); // Référence vers l'input file

const send = () => {
  if (newMessage.value.trim()) {
    emit("send-message", newMessage.value);
    newMessage.value = "";
  }
};

// 📎 Lorsqu’un fichier est sélectionné
const handleFileUpload = async (event) => {
  const file = event.target.files[0];
  if (!file || !file.name.endsWith(".pdf")) {
    alert("Merci de sélectionner un fichier PDF.");
    return;
  }

  const formData = new FormData();
  formData.append("file", file);

  try {
    const response = await fetch("http://127.0.0.1:5005/upload-user-pdf", {
      method: "POST",
      body: formData,
    });

    const result = await response.json();

    if (response.ok) {
      // ✅ EMETTRE l'événement vers le parent avec le nom du fichier
      emit("pdf-uploaded", file.name);
      alert("📄 PDF analysé avec succès ! Posez votre question maintenant.");
    } else {
      alert(result.error || "Erreur lors du traitement du PDF.");
    }
  } catch (error) {
    alert("❌ Erreur réseau lors de l’envoi du PDF.");
  }
};

// Clic sur l’icône déclenche le sélecteur de fichier
const triggerFileSelect = () => {
  fileInput.value?.click();
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

    <!-- 📎 Icône fichier -->
    <button class="icon-btn" title="Envoyer un PDF" @click="triggerFileSelect">📎</button>
    <input
      type="file"
      ref="fileInput"
      class="d-none"
      accept="application/pdf"
      @change="handleFileUpload"
    />

    <!-- 📨 Bouton envoi -->
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
  border: 2px solid #6186ff;
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

.d-none {
  display: none;
}
</style>

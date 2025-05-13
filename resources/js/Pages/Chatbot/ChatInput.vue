<script setup>
import { ref } from "vue";
const props = defineProps({
  primaryColor: {
    type: String,
    default: "#0066FF"
  }
});

const message = ref("");
const emit = defineEmits(["send-message", "pdf-uploaded"]);

const sendMessage = () => {
  if (message.value.trim()) {
    emit("send-message", message.value);
    message.value = "";
  }
};

const handleKeydown = (e) => {
  if (e.key === "Enter" && !e.shiftKey) {
    e.preventDefault();
    sendMessage();
  }
};

const handleFileChange = async (event) => {
  const file = event.target.files[0];
  if (!file) return;

  if (file.type === 'application/pdf') {
    emit("pdf-uploaded", file);
  } else {
    alert("Seuls les fichiers PDF sont acceptés");
  }
  
  // Réinitialiser l'input file
  event.target.value = null;
};

const openFileDialog = () => {
  document.getElementById('file-upload').click();
};
const newMessage = ref("");
const fileInput = ref(null);

// 🔐 Ajout de getSessionId
const getSessionId = () => {
  let id = localStorage.getItem("chatbot_session_id");
  if (!id) {
    id = crypto.randomUUID();
    localStorage.setItem("chatbot_session_id", id);
  }
  return id;
};

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
      headers: {
        "X-Session-ID": getSessionId(), // ✅ Ajouté ici
      },
      body: formData,
    });

    const result = await response.json();

    if (response.ok) {
      emit("pdf-uploaded", file.name);
      alert("📄 PDF analysé avec succès ! Posez votre question maintenant.");
    } else {
      alert(result.error || "Erreur lors du traitement du PDF.");
    }
  } catch (error) {
    alert("❌ Erreur réseau lors de l’envoi du PDF.");
  }
};

// const triggerFileSelect = () => {
//   fileInput.value?.click();
// };
</script>


<template>
  <div class="chat-input-container">
    <div class="chat-input-wrapper">
      <textarea
        v-model="message"
        placeholder="Écrivez votre message..."
        @keydown="handleKeydown"
        class="chat-textarea"
      ></textarea>
      
      <div class="chat-actions">
        <button @click="openFileDialog" class="action-btn upload-btn" :style="{ color: primaryColor }">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M17 8l-5-5-5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M12 3v12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </button>
        <input
          type="file"
          id="file-upload"
          @change="handleFileChange"
          accept="application/pdf"
          class="hidden-file-input"
        />
        
        <button
          @click="sendMessage"
          class="send-btn"
          :disabled="!message.trim()"
          :style="{ backgroundColor: primaryColor, opacity: message.trim() ? '1' : '0.6' }"
        >
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M22 2L11 13" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M22 2l-7 20-4-9-9-4 20-7z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </button>
      </div>
    </div>
  </div>
</template>

<style scoped>
.chat-input-container {
  padding: 16px;
  background: white;
  border-top: 1px solid #f0f0f0;
}

.chat-input-wrapper {
  display: flex;
  align-items: flex-end;
  position: relative;
  background: #f5f7fa;
  border-radius: 18px;
  padding: 12px 12px 12px 16px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
  transition: all 0.2s ease;
}

.chat-input-wrapper:focus-within {
  background: white;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
  border: 1px solid rgba(0, 102, 255, 0.2);
  padding: 11px 11px 11px 15px; /* Adjust for the border */
}

.chat-textarea {
  flex: 1;
  border: none;
  background: transparent;
  padding: 0;
  min-height: 40px;
  max-height: 120px;
  resize: none;
  font-family: inherit;
  font-size: 15px;
  color: #333;
  outline: none;
  line-height: 1.5;
}

.chat-textarea::placeholder {
  color: #999;
}

.chat-actions {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-left: 8px;
}

.action-btn {
  background: none;
  border: none;
  color: #667085;
  padding: 8px;
  border-radius: 50%;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s ease;
}

.action-btn:hover {
  background: rgba(0, 0, 0, 0.05);
  transform: translateY(-2px);
}

.upload-btn {
  opacity: 0.8;
}

.upload-btn:hover {
  opacity: 1;
}

.hidden-file-input {
  display: none;
}

.send-btn {
  background: #0066FF;
  border: none;
  color: white;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s ease;
  box-shadow: 0 2px 6px rgba(0, 102, 255, 0.2);
}

.send-btn:hover:not(:disabled) {
  transform: scale(1.05) translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 102, 255, 0.3);
}

.send-btn:disabled {
  cursor: not-allowed;
  background: #ddd;
}
</style>
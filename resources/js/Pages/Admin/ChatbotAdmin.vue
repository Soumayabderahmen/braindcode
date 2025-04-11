<script setup>
  import { ref, onMounted, computed } from 'vue';
  import axios from 'axios';
  import Main from '@/Layouts/Main.vue';

  const allMessages = ref([]);
  const selectedUser = ref(null);
  const messageContainer = ref(null);

const scrollToBottom = () => {
  nextTick(() => {
    const el = messageContainer.value;
    if (el) el.scrollTop = el.scrollHeight;
  });
};
const selectUser = (user) => {
  selectedUser.value = user;
  scrollToBottom();
};
  const fetchMessages = async () => {
    const response = await axios.get('/admin/chatbot/messages');
    allMessages.value = response.data.messages;
  };

  onMounted(fetchMessages);

  const uniqueUsers = computed(() => {
    const users = allMessages.value.map((msg) => msg.user_name);
    return [...new Set(users)];
  });

  onMounted(async () => {
  await fetchMessages();
  scrollToBottom();
});

  const selectedUserMessages = computed(() => {
    return allMessages.value.filter(
      (msg) => msg.user_name === selectedUser.value
    );
  });

  // const selectUser = (user) => {
  //   selectedUser.value = user;
  // };

  const formatDate = (datetime) => {
    return new Date(datetime).toLocaleString();
  };
</script>

<template>
  <Main>
    <div class="admin-chat-container">
      <h2 class="admin-chat-title">📲 Conversations avec le chatbot</h2>

      <div class="chat-layout">
        <!-- Liste des utilisateurs à gauche -->
        <div class="user-list">
          <div
            v-for="(user, index) in uniqueUsers"
            :key="index"
            :class="['user-item', { active: selectedUser === user }]"
            @click="selectUser(user)"
          >
            {{ user }}
          </div>
        </div>

        <!-- Vue conversationnelle -->
        <div class="chat-window">
          <div v-if="selectedUserMessages.length === 0" class="no-messages">
            📢 Aucun message sélectionné.
          </div>
          <div v-else class="messages" ref="messageContainer">

            <div
              v-for="(msg, index) in selectedUserMessages"
              :key="index"
              :class="['message-bubble', msg.sender === 'user' ? 'user' : 'bot']"
            >
              <p class="msg-text">{{ msg.message }}</p>
              <span class="msg-meta">{{ formatDate(msg.created_at) }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </Main>
</template>

<style scoped>
.messages {
  display: flex;
  flex-direction: column;
  gap: 10px;
  max-height: 430px;
  overflow-y: auto;
  padding-right: 8px;
}

.admin-chat-container {
  padding: 30px;
  max-width: 1100px;
  margin: auto;
  font-family: 'Poppins', sans-serif;
}

.admin-chat-title {
  font-size: 24px;
  margin-bottom: 20px;
  font-weight: 600;
}

.chat-layout {
  display: flex;
  border: 1px solid #ccc;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
  height: 500px;
}

.user-list {
  width: 250px;
  border-right: 1px solid #ddd;
  background: #f7f7f7;
  overflow-y: auto;
}

.user-item {
  padding: 15px;
  cursor: pointer;
  border-bottom: 1px solid #eee;
}

.user-item.active {
  background-color: #e1f0ff;
  font-weight: 600;
  color: #007bff;
}

.chat-window {
  flex: 1;
  padding: 20px;
  overflow-y: auto;
  background: #fff;
  display: flex;
  flex-direction: column;
  justify-content: flex-end;
}

.no-messages {
  color: #888;
  text-align: center;
  margin-top: 40px;
  font-style: italic;
}

.messages {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.message-bubble {
  max-width: 70%;
  padding: 12px 16px;
  border-radius: 15px;
  position: relative;
  box-shadow: 0 1px 4px rgba(0, 0, 0, 0.08);
  line-height: 1.4;
  font-size: 14px;
}

.message-bubble.user {
  background: #007bff;
  color: white;
  align-self: flex-end;
  border-bottom-right-radius: 0;
}

.message-bubble.bot {
  background: #f0f0f0;
  color: #333;
  align-self: flex-start;
  border-bottom-left-radius: 0;
}

.msg-meta {
  display: block;
  font-size: 11px;
  color: #888;
  margin-top: 4px;
}
</style>

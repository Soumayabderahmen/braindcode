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

  const groupedUsersByRole = computed(() => {
  const groups = {};

  allMessages.value.forEach((msg) => {
    if (!msg.user_id) return;

    const role = msg.user_role || 'invité';

    // ✅ Ne pas inclure les admins
    if (role.toLowerCase() === 'admin') return;

    if (!groups[role]) groups[role] = {};

    if (!groups[role][msg.user_id]) {
      groups[role][msg.user_id] = {
        id: msg.user_id,
        name: msg.user_name,
        role: role
      };
    }
  });

  return groups;
});



  onMounted(async () => {
  await fetchMessages();
  scrollToBottom();
});

const selectedUserMessages = computed(() => {
  let messages = allMessages.value.filter(
    (msg) => msg.user_id === selectedUser.value?.id
  );

  if (showWithoutIntentOnly.value) {
    messages = messages.filter(
      (msg) => msg.sender === 'bot' && !msg.intent
    );
  }

  return messages;
});


  // const selectUser = (user) => {
  //   selectedUser.value = user;
  // };

  const formatDate = (datetime) => {
    return new Date(datetime).toLocaleString();
  };

  const showWithoutIntentOnly = ref(false);

</script>

<template>
  <Main>
    <div class="admin-chat-container">
      <h2 class="admin-chat-title">📲 Conversations avec le chatbot</h2>

      <div class="chat-layout">
  <!-- ✅ Liste des utilisateurs à gauche -->
  <div class="user-list">
    <div
      v-for="(usersByRole, role) in groupedUsersByRole"
      :key="role"
      class="user-group"
    >
      <div class="role-title">{{ role }}</div>
      <div
        v-for="user in Object.values(usersByRole)"
        :key="user.id"
        :class="['user-item', { active: selectedUser?.id === user.id }]"
        @click="selectUser(user)"
      >
        {{ user.name }}
      </div>
    </div>
  </div>


  <!-- ✅ Messages à droite -->
  <div class="chat-window">
    <div class="filter-bar">
    <label>
      <input type="checkbox" v-model="showWithoutIntentOnly" />
      Afficher uniquement les messages sans intention reconnue
    </label>
  </div>
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
        <span class="msg-meta">
    🕒 {{ formatDate(msg.created_at) }}
    <span v-if="msg.sender === 'bot' && msg.intent" class="intent-badge">
    🎯 {{ msg.intent }}
  </span>
  </span>
      </div>
    </div>
  </div>
</div>
</div>



       
   
  </Main>
</template>

<style scoped>
.intent-badge {
  display: inline-block;
  margin-top: 6px;
  font-size: 11px;
  color: #007bff;
  background: #e1f0ff;
  padding: 2px 6px;
  border-radius: 6px;
  font-weight: 500;
}

.filter-bar {
  margin-bottom: 12px;
  font-size: 13px;
  color: #333;
}


.messages {
  display: flex;
  flex-direction: column;
  gap: 10px;
  max-height: 430px;
  overflow-y: auto;
  padding-right: 8px;
}
.role-title {
  font-weight: bold;
  font-size: 14px;
  padding: 10px 15px 5px;
  background: #eaeaea;
  color: #333;
  text-transform: capitalize;
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



.user-list {
  width: 250px;
  background: #f7f7f7;
  border-right: 1px solid #ddd;
  overflow-y: auto;
}

.chat-window {
  flex: 1;
  background: #fff;
  display: flex;
  flex-direction: column;
  padding: 20px;
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



.no-messages {
  color: #888;
  text-align: center;
  margin-top: 40px;
  font-style: italic;
}

/* .messages {
  display: flex;
  flex-direction: column;
  gap: 10px;
} */

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
.chat-layout {
  display: flex;
  height: 500px;
  border: 1px solid #ccc;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

</style>

<script setup>
import { ref, onMounted, computed, nextTick } from 'vue';
import axios from 'axios';
import Main from '@/Layouts/Main.vue';

// États
const allMessages = ref([]);
const selectedUser = ref(null);
const messageContainer = ref(null);
const showWithoutIntentOnly = ref(false);
const isLoading = ref(true);
const searchTerm = ref('');
const totalUsers = ref(0);
const totalMessages = ref(0);

// Fetch des données
const fetchMessages = async () => {
  isLoading.value = true;
  try {
    const response = await axios.get('/admin/chatbot/messages');
    allMessages.value = response.data.messages;
    
    // Statistiques
    const uniqueUsers = new Set();
    allMessages.value.forEach(msg => {
      if (msg.user_id) uniqueUsers.add(msg.user_id);
    });
    
    totalUsers.value = uniqueUsers.size;
    totalMessages.value = allMessages.value.length;
  } catch (error) {
    console.error('Erreur lors du chargement des messages:', error);
  } finally {
    isLoading.value = false;
  }
};

// Fonction pour assurer le scroll vers le bas
const scrollToBottom = () => {
  nextTick(() => {
    const el = messageContainer.value;
    if (el) {
      el.scrollTop = el.scrollHeight;
    }
  });
};

// Sélection d'un utilisateur
const selectUser = (user) => {
  selectedUser.value = user;
  scrollToBottom();
};

// Formattage de la date et heure
const formatDate = (datetime) => {
  const date = new Date(datetime);
  
  const today = new Date();
  const yesterday = new Date(today);
  yesterday.setDate(yesterday.getDate() - 1);
  
  // Si c'est aujourd'hui
  if (date.toDateString() === today.toDateString()) {
    return `Aujourd'hui à ${date.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' })}`;
  }
  
  // Si c'est hier
  if (date.toDateString() === yesterday.toDateString()) {
    return `Hier à ${date.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' })}`;
  }
  
  // Sinon format complet
  return date.toLocaleDateString('fr-FR', { 
    day: '2-digit', 
    month: '2-digit', 
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
};

// Formatage du temps écoulé
const getTimeAgo = (datetime) => {
  const date = new Date(datetime);
  const now = new Date();
  const diffMs = now - date;
  const diffSec = Math.floor(diffMs / 1000);
  const diffMin = Math.floor(diffSec / 60);
  const diffHour = Math.floor(diffMin / 60);
  const diffDay = Math.floor(diffHour / 24);

  if (diffSec < 60) return 'À l\'instant';
  if (diffMin < 60) return `Il y a ${diffMin} min`;
  if (diffHour < 24) return `Il y a ${diffHour}h`;
  if (diffDay < 7) return `Il y a ${diffDay}j`;

  return date.toLocaleDateString('fr-FR', { day: '2-digit', month: '2-digit' });
};

// Récupération des initiales pour l'avatar
const getInitials = (name) => {
  if (!name) return '?';
  const words = name.split(' ');
  if (words.length === 1) return name.charAt(0).toUpperCase();
  return (words[0].charAt(0) + words[1].charAt(0)).toUpperCase();
};

// Génération d'une couleur cohérente pour l'avatar
const getAvatarColor = (userId) => {
  if (!userId) return '#aaaaaa';
  
  const colors = [
    '#4f46e5', // Indigo
    '#8b5cf6', // Violet
    '#06b6d4', // Cyan
    '#10b981', // Emerald
    '#f59e0b', // Amber
    '#ef4444', // Red
    '#f97316', // Orange
    '#8b5cf6', // Purple
    '#ec4899'  // Pink
  ];
  
  // Utiliser l'ID utilisateur pour sélectionner une couleur cohérente
  const colorIndex = parseInt(userId.toString().split('').reduce((sum, char) => 
    sum + char.charCodeAt(0), 0)) % colors.length;
  
  return colors[colorIndex];
};

// Récupération du dernier message d'un utilisateur
const getLastMessage = (userId) => {
  const userMessages = allMessages.value.filter(msg => msg.user_id === userId);
  if (userMessages.length === 0) return { message: '', date: '' };
  
  const lastMsg = userMessages[userMessages.length - 1];
  return {
    message: lastMsg.message,
    date: lastMsg.created_at,
    isBot: lastMsg.sender === 'bot'
  };
};

// Utilisateurs regroupés par rôle
const groupedUsersByRole = computed(() => {
  const groups = {};

  allMessages.value.forEach((msg) => {
    if (!msg.user_id) return;

    const role = msg.user_role || 'invité';

    // Ne pas inclure les admins
    if (role.toLowerCase() === 'admin') return;

    if (!groups[role]) groups[role] = {};

    if (!groups[role][msg.user_id]) {
      groups[role][msg.user_id] = {
        id: msg.user_id,
        name: msg.user_name,
        role: role,
        lastMessage: getLastMessage(msg.user_id)
      };
    }
  });

  return groups;
});

// Utilisateurs filtrés par recherche
const filteredUsersByRole = computed(() => {
  if (!searchTerm.value) return groupedUsersByRole.value;
  
  const filtered = {};
  
  Object.entries(groupedUsersByRole.value).forEach(([role, users]) => {
    const filteredUsers = {};
    
    Object.entries(users).forEach(([userId, userData]) => {
      if (userData.name.toLowerCase().includes(searchTerm.value.toLowerCase())) {
        filteredUsers[userId] = userData;
      }
    });
    
    if (Object.keys(filteredUsers).length > 0) {
      filtered[role] = filteredUsers;
    }
  });
  
  return filtered;
});

// Messages de l'utilisateur sélectionné
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

// Regroupement des messages par date
const messagesByDate = computed(() => {
  const groups = {};
  
  selectedUserMessages.value.forEach(msg => {
    const date = new Date(msg.created_at).toLocaleDateString('fr-FR');
    if (!groups[date]) groups[date] = [];
    groups[date].push(msg);
  });
  
  return groups;
});

// Initialisation
onMounted(async () => {
  await fetchMessages();
  scrollToBottom();
});
</script>

<template>
  
    <div class="admin-messenger">
      <!-- En-tête -->
      <div class="messenger-header">
        <h1>
          <span class="header-icon">💬</span>
          Conversations Admin
        </h1>
        <div class="stats-container">
          <div class="stat-item">
            <div class="stat-icon">👥</div>
            <div class="stat-info">
              <span class="stat-value">{{ totalUsers }}</span>
              <span class="stat-label">Utilisateurs</span>
            </div>
          </div>
          <div class="stat-item">
            <div class="stat-icon">✉️</div>
            <div class="stat-info">
              <span class="stat-value">{{ totalMessages }}</span>
              <span class="stat-label">Messages</span>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Contenu principal -->
      <div class="messenger-content">
        <!-- Panneau de gauche: liste des utilisateurs -->
        <div class="messenger-sidebar">
          <div class="search-container">
            <input 
              type="text" 
              v-model="searchTerm" 
              placeholder="Rechercher un utilisateur..." 
              class="search-input"
            />
            <span class="search-icon">🔍</span>
          </div>
          
          <div class="user-lists-container">
            <div v-if="isLoading" class="loading-indicator">
              <div class="loading-spinner"></div>
              <span>Chargement des conversations...</span>
            </div>
            
            <template v-else>
              <div v-if="Object.keys(filteredUsersByRole).length === 0" class="no-results">
                <div class="no-results-icon">🔍</div>
                <p>Aucun utilisateur trouvé</p>
              </div>
              
              <div v-else v-for="(usersByRole, role) in filteredUsersByRole" :key="role" class="user-group">
                <div class="role-header">
                  <span class="role-name">{{ role }}</span>
                  <span class="role-counter">{{ Object.keys(usersByRole).length }}</span>
                </div>
                
                <div 
                  v-for="user in Object.values(usersByRole)" 
                  :key="user.id" 
                  :class="['user-card', { active: selectedUser?.id === user.id }]"
                  @click="selectUser(user)"
                >
                  <div class="user-avatar" :style="{ backgroundColor: getAvatarColor(user.id) }">
                    {{ getInitials(user.name) }}
                  </div>
                  
                  <div class="user-info">
                    <div class="user-name">{{ user.name }}</div>
                    <div class="user-preview">
                      <span v-if="user.lastMessage.isBot" class="bot-indicator">🤖</span>
                      {{ user.lastMessage.message.length > 30 ? user.lastMessage.message.substring(0, 30) + '...' : user.lastMessage.message }}
                    </div>
                  </div>
                  
                  <div class="user-meta">
                    <div class="message-time">{{ getTimeAgo(user.lastMessage.date) }}</div>
                  </div>
                </div>
              </div>
            </template>
          </div>
        </div>
        
        <!-- Panneau central: messages -->
        <div class="messenger-chat">
          <div v-if="!selectedUser" class="chat-placeholder">
            <div class="placeholder-icon">👈</div>
            <h3>Sélectionnez une conversation</h3>
            <p>Choisissez un utilisateur dans la liste pour voir les messages échangés</p>
          </div>
          
          <template v-else>
            <div class="chat-header">
              <div class="chat-user-info">
                <div class="chat-avatar" :style="{ backgroundColor: getAvatarColor(selectedUser.id) }">
                  {{ getInitials(selectedUser.name) }}
                </div>
                <div>
                  <h3 class="chat-user-name">{{ selectedUser.name }}</h3>
                  <span class="chat-user-role">{{ selectedUser.role }}</span>
                </div>
              </div>
              
              <div class="chat-actions">
                <label class="filter-toggle">
                  <input type="checkbox" v-model="showWithoutIntentOnly">
                  <span class="toggle-label">Messages sans intention</span>
                </label>
              </div>
            </div>
            
            <div class="chat-messages" ref="messageContainer">
              <template v-if="selectedUserMessages.length === 0">
                <div class="empty-chat">
                  <div class="empty-icon">🔍</div>
                  <p>Aucun message disponible</p>
                </div>
              </template>
              
              <template v-else>
                <div v-for="(messages, date) in messagesByDate" :key="date" class="message-group">
                  <div class="date-separator">
                    <span class="date-text">{{ date }}</span>
                  </div>
                  
                  <div v-for="(msg, idx) in messages" :key="idx" 
     :class="['message', msg.sender === 'user' ? 'user-message' : 'bot-message']">
  
  <div class="avatar-bubble" v-if="msg.sender === 'bot'">
    <div class="avatar-icon bot-avatar">🤖</div>
  </div>

  <div class="message-container">
    <div class="message-bubble">
      <div class="message-content">{{ msg.message }}</div>
      <div class="message-time">{{ formatDate(msg.created_at).split(' à ')[1] }}</div>
    </div>
    <div v-if="msg.sender === 'bot'" class="message-tags">
      <span v-if="msg.intent" class="intent-tag">
        <span class="tag-icon">🎯</span>
        {{ msg.intent }}
      </span>
      <span v-else class="no-intent-tag">
        <span class="tag-icon">❓</span>
        Sans intention
      </span>
    </div>
  </div>

  <div class="avatar-bubble" v-if="msg.sender === 'user'">
    <div class="avatar-icon" :style="{ backgroundColor: getAvatarColor(selectedUser.id) }">
      {{ getInitials(selectedUser.name) }}
    </div>
  </div>
</div>

                </div>
              </template>
            </div>
          </template>
        </div>
      </div>
    </div>
 
</template>

<style scoped>
/* Variables */
:root {
  --primary-color: #0084ff;
  --secondary-color: #f3f3f5;
  --text-primary: #050505;
  --text-secondary: #65676b;
  --border-color: #e4e6eb;
  --hover-bg: #f2f2f2;
  --message-user-bg: #0084ff;
  --message-user-color: #ffffff;
  --message-bot-bg: #f0f0f0;
  --message-bot-color: #050505;
  --intent-tag-bg: #e7f3ff;
  --intent-tag-color: #0078d4;
  --no-intent-tag-bg: #fff4e5;
  --no-intent-tag-color: #ff8c00;
}

/* Styles globaux */
.admin-messenger {
  font-family: 'Segoe UI', Helvetica, Arial, sans-serif;
  height: calc(100vh - 100px);
  min-height: 600px;
  display: flex;
  flex-direction: column;
  background-color: white;
  border-radius: 12px;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
  overflow: hidden;
  margin: 2%;
}

/* En-tête */
.messenger-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 16px 24px;
  border-bottom: 1px solid var(--border-color);
  background-color: white;
}

.messenger-header h1 {
  display: flex;
  align-items: center;
  font-size: 20px;
  font-weight: 600;
  margin: 0;
  color: var(--text-primary);
}

.header-icon {
  margin-right: 12px;
  font-size: 22px;
}

.stats-container {
  display: flex;
  gap: 20px;
}

.stat-item {
  display: flex;
  align-items: center;
  gap: 12px;
}

.stat-icon {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 36px;
  height: 36px;
  border-radius: 50%;
  background-color: #f0f2f5;
  font-size: 16px;
}

.stat-info {
  display: flex;
  flex-direction: column;
}

.stat-value {
  font-weight: 600;
  color: var(--text-primary);
  font-size: 16px;
  line-height: 1.2;
}

.stat-label {
  color: var(--text-secondary);
  font-size: 12px;
}

/* Contenu principal */
.messenger-content {
  display: flex;
  flex: 1;
  overflow: hidden;
}

/* Sidebar */
.messenger-sidebar {
  width: 320px;
  border-right: 1px solid var(--border-color);
  display: flex;
  flex-direction: column;
  background-color: white;
}

.search-container {
  padding: 12px 16px;
  position: relative;
  border-bottom: 1px solid var(--border-color);
}

.search-input {
  width: 100%;
  padding: 8px 12px 8px 36px;
  border-radius: 20px;
  border: none;
  background-color: #f0f2f5;
  font-size: 14px;
  outline: none;
  transition: background-color 0.2s;
}

.search-input:focus {
  background-color: #e4e6eb;
}

.search-icon {
  position: absolute;
  left: 28px;
  top: 50%;
  transform: translateY(-50%);
  font-size: 14px;
  color: var(--text-secondary);
}

.user-lists-container {
  flex: 1;
  overflow-y: auto;
  padding: 8px 0;
}

.loading-indicator {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: 200px;
  color: var(--text-secondary);
  gap: 16px;
}

.loading-spinner {
  width: 30px;
  height: 30px;
  border: 3px solid #f3f3f5;
  border-top: 3px solid var(--primary-color);
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.no-results {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 40px 20px;
  color: var(--text-secondary);
  text-align: center;
}

.no-results-icon {
  font-size: 32px;
  margin-bottom: 16px;
  opacity: 0.7;
}

.role-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 8px 16px;
  margin-top: 8px;
  font-size: 13px;
  font-weight: 600;
  color: var(--text-secondary);
  text-transform: uppercase;
}

.role-counter {
  background-color: #e4e6eb;
  border-radius: 10px;
  padding: 2px 8px;
  font-size: 12px;
}

.user-card {
  display: flex;
  align-items: center;
  padding: 12px 16px;
  cursor: pointer;
  transition: background-color 0.2s;
  border-radius: 8px;
  margin: 0 8px;
  gap: 12px;
}

.user-card:hover {
  background-color: var(--hover-bg);
}

.user-card.active {
  background-color: rgba(0, 132, 255, 0.1);
}

.user-avatar {
  width: 48px;
  height: 48px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 600;
  color: white;
  font-size: 18px;
  flex-shrink: 0;
}

.user-info {
  flex: 1;
  min-width: 0;
}

.user-name {
  font-weight: 600;
  font-size: 15px;
  color: var(--text-primary);
  margin-bottom: 4px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.user-preview {
  font-size: 13px;
  color: var(--text-secondary);
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.bot-indicator {
  margin-right: 4px;
  font-size: 12px;
}

.user-meta {
  display: flex;
  flex-direction: column;
  align-items: flex-end;
}



/* Chat central */
.messenger-chat {
  flex: 1;
  display: flex;
  flex-direction: column;
  background-color: #ffffff;
  position: relative;
  overflow: hidden;
}

.chat-placeholder {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: 100%;
  color: var(--text-secondary);
  text-align: center;
  padding: 20px;
}

.placeholder-icon {
  font-size: 48px;
  margin-bottom: 16px;
  opacity: 0.7;
}

.chat-placeholder h3 {
  margin: 0 0 8px;
  font-weight: 600;
  color: var(--text-primary);
}

.chat-placeholder p {
  max-width: 300px;
  margin: 0;
  font-size: 14px;
}

.chat-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 12px 16px;
  border-bottom: 1px solid var(--border-color);
}

.chat-user-info {
  display: flex;
  align-items: center;
  gap: 12px;
}

.chat-avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 600;
  color: white;
  font-size: 16px;
}

.chat-user-name {
  margin: 0;
  font-size: 16px;
  font-weight: 600;
  color: var(--text-primary);
}

.chat-user-role {
  font-size: 13px;
  color: var(--text-secondary);
  text-transform: capitalize;
}

.chat-actions {
  display: flex;
  align-items: center;
}

.filter-toggle {
  display: flex;
  align-items: center;
  font-size: 13px;
  color: var(--text-secondary);
  cursor: pointer;
}

.filter-toggle input {
  margin-right: 8px;
}

.chat-messages {
  flex: 1;
  overflow-y: auto;
  padding: 16px;
  background-color: rgb(242, 249, 255);
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.empty-chat {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: 100%;
  color: var(--text-secondary);
  text-align: center;
}

.empty-icon {
  font-size: 32px;
  margin-bottom: 12px;
  opacity: 0.7;
}

.message-group {
  display: flex;
  flex-direction: column;
  gap: 8px;

}

.date-separator {
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 12px 0;
}

.date-text {
  font-size: 12px;
  color: var(--text-secondary);
  background-color: white;
  padding: 4px 12px;
  border-radius: 12px;
  box-shadow: 0 1px 2px rgba(0,0,0,0.1);
}

.message {
  display: flex;
  margin-bottom: 8px;
}

.message-container {
  max-width: 80%;
  display: flex;
  flex-direction: column;
  
}

.user-message {
  justify-content: flex-end;
}

.bot-message .message-container {
  align-items: flex-start;
}

.user-message .message-container {
  align-items: flex-end;
}

.message-bubble {
  border-radius: 18px;
  padding: 8px 12px;
  position: relative;
  word-break: break-word;
  box-shadow: 0 1px 4px rgba(0, 0, 0, 0.08);
 
}

.user-message .message-bubble {
  background: #007bff;
  color: white;
  align-self: flex-end;
  border-bottom-right-radius: 4px;
}

.bot-message .message-bubble {
  background: #f0f0f0;
  color: #333;
  align-self: flex-start;
  border-bottom-left-radius: 4px;
}


.message-content {
  font-size: 14px;
  line-height: 1.4;
  margin-bottom: 4px;
}

.message-time {
  font-size: 10px;
  opacity: 0.8;
  text-align: right;
  color: var(--text-secondary);
  white-space: nowrap;
}

.message-tags {
  display: flex;
  margin-top: 4px;
  gap: 8px;
}

.intent-tag, .no-intent-tag {
  font-size: 11px;
  padding: 2px 8px;
  border-radius: 12px;
  display: inline-flex;
  align-items: center;
  gap: 4px;
  font-weight: 500;
}

.intent-tag {
  background-color: var(--intent-tag-bg);
  color: var(--intent-tag-color);
}

.no-intent-tag {
  background-color: var(--no-intent-tag-bg);
  color: var(--no-intent-tag-color);
}

.tag-icon {
  font-size: 10px;
}

/* Media queries */
@media (max-width: 992px) {
  .messenger-sidebar {
    width: 280px;
  }
}

@media (max-width: 768px) {
  .admin-messenger {
    height: calc(100vh - 60px);
  }
  
  .messenger-content {
    flex-direction: column;
   
  }
  
  .messenger-sidebar {
    width: 100%;
    height: 300px;
    border-right: none;
    border-bottom: 1px solid var(--border-color);
  }
  
  .stats-container {
    display: none;
  }
}
.avatar-bubble {
  display: flex;
  align-items: flex-end;
  margin: 0 8px;
}

.avatar-icon {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  font-size: 18px;
  font-weight: bold;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: #e4e6eb;
  color: white;
  flex-shrink: 0;
}

.bot-avatar {
  background-color: #64748b; /* gris foncé pour le bot */
}

</style>
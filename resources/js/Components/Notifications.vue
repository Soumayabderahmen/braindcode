<script setup>
import { ref, onMounted, onBeforeUnmount, computed } from "vue";
import axios from 'axios';

const props = defineProps({
  user: {
    type: Object,
    required: true
  }
});

const notifications = ref([]);
const unreadCount = ref(0);
const showNotifications = ref(false);
const showAll = ref(false);
const NOTIFICATIONS_PER_PAGE = 5;

const displayedNotifications = computed(() => {
  const sorted = [...notifications.value].sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
  return showAll.value ? sorted : sorted.slice(0, NOTIFICATIONS_PER_PAGE);
});

onMounted(() => {
  if (props.user) {
    loadNotifications();

    window.Echo.private(`App.Models.User.${props.user.id}`)
      .notification(notification => {
        const type = notification.type.split('\\').pop();
        if (type === 'ReservationRequestNotification') {
          const data = {
            id: notification.id,
            created_at: new Date(),
            read_at: null,
            type,
            data: {
              reservation_id: notification.reservation_id,
              startup_name: notification.data.startup_name,
              message: notification.message,
              statut:notification.data.statut,
            }
          };
          notifications.value = [data, ...notifications.value];
          unreadCount.value++;
          showToast(data);
        }
      });
  }

  document.addEventListener('click', handleClickOutside);
});

onBeforeUnmount(() => {
  document.removeEventListener('click', handleClickOutside);
});

const handleClickOutside = (e) => {
  const dropdown = document.querySelector('.notification-dropdown');
  const button = document.querySelector('.notification-button');
  if (dropdown && !dropdown.contains(e.target) && !button.contains(e.target)) {
    showNotifications.value = false;
  }
};
const respondToRequest = async (notification, newStatus) => {
  try {
    await axios.post(`/reservations/${notification.data.reservation_id}/response`, {
      statut: newStatus
    });
    
    notification.data.statut = newStatus;

    // Mettre à jour read_at avec la date actuelle manuellement
    notification.read_at = new Date();

    // Appeler l'API pour marquer comme lu
    await markAsRead(notification);

    // Retirer la notification de la liste
    notifications.value = notifications.value.filter(n => n.id !== notification.id);
  } catch (e) {
    console.error("Erreur lors de la réponse à la demande :", e);
  }
};



const loadNotifications = async () => {
  try {
    const res = await axios.get('/notifications');
    notifications.value = res.data
      .filter(n => n.type.includes('ReservationRequestNotification') && n.read_at === null);
    
    unreadCount.value = notifications.value.length;
  } catch (e) {
    console.error("Erreur lors du chargement des notifications:", e);
  }
};


const markAsRead = async (notification) => {
  try {
    await axios.patch(`/notifications/${notification.id}`);
    notification.read_at = new Date();
    unreadCount.value = Math.max(0, unreadCount.value - 1);
  } catch (e) {
    console.error("Erreur lors du marquage comme lu:", e);
  }
};

const showToast = (notification) => {
  const toast = document.createElement('div');
  toast.className = 'notification-toast info';
  toast.innerHTML = `
    <div class="toast-content">
      <p>${notification.data.message}</p>
    </div>
  `;
  document.body.appendChild(toast);
  setTimeout(() => {
    toast.style.animation = 'slideOut 0.3s ease-out forwards';
    setTimeout(() => toast.remove(), 300);
  }, 4700);
};
</script>

<template>
  <div class="relative">
    <button @click="showNotifications = !showNotifications"
            class="relative p-2 text-gray-600 hover:text-[#4A90E2] notification-button">
            <span class="text-2xl">🔔</span>      <span v-if="unreadCount > 0"
            class="absolute top-0 right-0 bg-red-500 text-white rounded-full w-5 h-5 text-xs flex items-center justify-center">
        {{ unreadCount }}
      </span>
    </button>

    <div v-if="showNotifications"
         class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 notification-dropdown">
      <div v-if="notifications.length === 0" class="p-4 text-center text-gray-500">Aucune notification</div>

      <div v-else class="notifications-container" :class="{ 'has-more': notifications.length > 5 }">
        <div v-for="notification in displayedNotifications"
             :key="notification.id"
             @click="markAsRead(notification)"
             class="notification-item p-4 hover:bg-gray-50 cursor-pointer border-b last:border-b-0"
             :class="{ 'bg-blue-50': !notification.read_at }">
          <p class="font-medium text-gray-600">{{ notification.data.startup_name }}</p>
          <p class="text-sm text-gray-600">{{ notification.data.message }}</p>
          <p class="text-xs mt-1 text-gray-600" :class="{
    'text-yellow-500': notification.data.statut === 'en attente',
    'text-green-600': notification.data.statut === 'acceptée',
    'text-red-500': notification.data.statut === 'refusée'
  }">
    Statut : {{ notification.data.statut }}
  </p>
  <div v-if="notification.data.statut === 'en attente'" class="flex gap-2 mt-2">
    <button @click="respondToRequest(notification, 'acceptée')"
            class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600 text-sm">
      Accepter
    </button>
    <button @click="respondToRequest(notification, 'refusée')"
            class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 text-sm">
      Refuser
    </button>
  </div>
        </div>

        <div v-if="notifications.length > NOTIFICATIONS_PER_PAGE && !showAll"
             class="show-more p-2 text-center border-t">
          <button @click="showAll = true" class="text-[#4A90E2] hover:text-[#357ABD] font-medium">
            Voir plus ({{ notifications.length - NOTIFICATIONS_PER_PAGE }})
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Styles des toasts et notifications conservés tels quels... */
.notification-toast {
  position: fixed;
  top: 20px;
  right: 20px;
  background: white;
  border-radius: 8px;
  padding: 1rem;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  z-index: 1000;
  animation: slideIn 0.3s ease-out;
  border-left: 4px solid #4A90E2;
  min-width: 300px;
  max-width: 400px;
}

@keyframes slideIn {
  from {
    transform: translateX(100%);
    opacity: 0;
  }
  to {
    transform: translateX(0);
    opacity: 1;
  }
}

@keyframes slideOut {
  from {
    transform: translateX(0);
    opacity: 1;
  }
  to {
    transform: translateX(100%);
    opacity: 0;
  }
}

.notification-dropdown {
  max-height: 480px;
  display: flex;
  flex-direction: column;
}

.notifications-container {
  overflow-y: auto;
  max-height: 400px;
}

.notification-item {
  transition: background-color 0.2s ease;
}

.notification-item:hover {
  background-color: #f8fafc;
}

.show-more {
  background: white;
  position: sticky;
  bottom: 0;
  border-top: 1px solid #e2e8f0;
  padding: 8px;
  text-align: center;
}
</style>

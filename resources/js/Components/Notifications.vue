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
  <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-1">
    <button @click="showNotifications = !showNotifications"
            class="nav-link dropdown-toggle hide-arrow btn-top notification-button"
             data-bs-auto-close="outside"
            aria-expanded="false" type="button">
      <!-- Icône cloche -->
      <i class="ti ti-bell ti-md text-dark"></i>
      <!-- Badge -->
      <span v-if="unreadCount > 0"
            class="badge bg-danger rounded-pill badge-notifications">
        {{ unreadCount }}
      </span>
    </button>

    <!-- Menu déroulant -->
    <ul v-if="showNotifications"
        class="dropdown-menu dropdown-menu-end notificationBox py-0 notification-dropdown"
        style="min-width: 320px;">
      <li class="dropdown-menu-header border-bottom">
        <div class="dropdown-header d-flex align-items-center py-3">
          <h5 class="text-body mb-0 me-auto text-primary title-notif">Notifications</h5>
        </div>
      </li>

      <li v-if="notifications.length === 0" class="p-4 text-center text-gray-500">
        Aucune notification
      </li>

      <li v-else class="dropdown-notifications-list scrollable-container">
  <ul class="list-group list-group-flush notifications-scroll">
    <li v-for="notification in displayedNotifications"
        :key="notification.id"
        class="list-group-item list-group-item-action dropdown-notifications-item"
        @click="markAsRead(notification)">
            <div class="d-flex">
              <div class="flex-shrink-0 me-3">
                <div class="avatar">
                  <div class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                         viewBox="0 0 24 24" fill="none" stroke="#7E57C2"
                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"></path>
                      <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                    </svg>
                  </div>
                </div>
              </div>
              <div class="flex-grow-1">
                <h6 class="mb-1 text-sm font-semibold text-dark">
                  {{ notification.data.startup_name }}
                </h6>
                <p class="mb-0 text-sm text-muted">{{ notification.data.message }}</p>
                <p class="mb-0 text-xs mt-1"
                   :class="{
                     'text-yellow-500': notification.data.statut === 'en attente',
                     'text-green-600': notification.data.statut === 'acceptée',
                     'text-red-500': notification.data.statut === 'refusée'
                   }">
                  Statut : {{ notification.data.statut }}
                </p>

                <!-- Actions si en attente -->
                <div v-if="notification.data.statut === 'en attente'"
                     class="d-flex gap-2 mt-2">
                  <button @click.stop="respondToRequest(notification, 'acceptée')"
                          class="btn btn-sm btn-success">
                    Accepter
                  </button>
                  <button @click.stop="respondToRequest(notification, 'refusée')"
                          class="btn btn-sm btn-danger">
                    Refuser
                  </button>
                </div>
              </div>
            </div>
          </li>
        </ul>
      </li>

      <!-- Voir plus -->
      <li v-if="notifications.length > NOTIFICATIONS_PER_PAGE && !showAll"
          class="dropdown-menu-footer border-top d-flex justify-content-center pt-3 pb-3">
        <button @click="showAll = true"
                class="dropdown-item btn-show text-primary">
          Voir plus ({{ notifications.length - NOTIFICATIONS_PER_PAGE }})
        </button>
      </li>
    </ul>
  </li>
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
.notifications-scroll {
  max-height: 300px;
  overflow-y: auto;
  padding-right: 4px; /* pour espace scrollbar */
}

.notifications-scroll::-webkit-scrollbar {
  width: 6px;
}

.notifications-scroll::-webkit-scrollbar-thumb {
  background-color: rgba(0, 0, 0, 0.2);
  border-radius: 3px;
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
.notification-dropdown {
  z-index: 1050 !important; 
  position: absolute !important;
  top: 100% !important;
  right: 0;
  background-color: white;
  border: 1px solid #e2e8f0;
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
  border-radius: 0.5rem;
  width: 350px;

}

</style>

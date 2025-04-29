<template>
  <aside 
    :class="['aside layout-menu menu-vertical bg-menu-theme', { 'collapsed': !isMenuOpen }]" 
    id="layout-menu"
  >
    <div class="app-brand demo" style="margin: 0 0.875rem 0 1rem;
    padding-right: 0.5rem;
   padding-left: 0.5rem; 
      margin-bottom: 40px !important;"
>
      <router-link to="/" class="app-brand-link">
        <span class="app-brand-logo demo">
          <img src="/assets/img/dash/logo.png" alt="Logo" style="width: 80% !important;"/>
        </span>
      </router-link>
    </div>

    <div class="menu-toggle-wrapper">
      <button class="menu-toggle-btn" @click="toggleSidebar">
        <i v-if="isMenuOpen" class="bx bx-chevron-left"></i>
        <i v-else class="bx bx-chevron-right"></i>
      </button>
    </div>
   
    <ul class="menu-inner py-1">
      <li class="menu-item">
        <a href="/dashboard" class="menu-link">
          <i class="menu-icon">
            <Icon icon="material-symbols:dashboard-outline-rounded" width="24" height="24" />
          </i>
          <div v-if="isMenuOpen">Tableau de bord</div>
        </a>
      </li>

      <li class="menu-item">
        <router-link to="/formation" class="menu-link">
          <i class="menu-icon">
            <Icon icon="covid:symptoms-virus-headache-2" width="24" height="24" />
          </i>
          <div v-if="isMenuOpen">Formation</div>
        </router-link>
      </li>

      <li class="menu-item">
        <router-link to="/agent-ia" class="menu-link">
          <i class="menu-icon">
            <Icon icon="mage:robot" width="24" height="24" />
          </i>
          <div v-if="isMenuOpen">Agent Ai</div>
        </router-link>
      </li>

      <li class="menu-item">
        <a href ="/calendar" class="menu-link">
          <i class="menu-icon">
            <Icon icon="solar:calendar-line-duotone" width="24" height="24" />
          </i>
          <div v-if="isMenuOpen">Calendrier</div>
        </a>
      </li>

      <li class="menu-item">
        <router-link to="/forum" class="menu-link">
          <i class="menu-icon">
            <Icon icon="charm:messages" width="24" height="24" />
          </i>
          <div v-if="isMenuOpen">Forum</div>
        </router-link>
      </li>

      <li class="menu-item">
        <router-link to="/ressources" class="menu-link">
          <i class="menu-icon">
            <Icon icon="fe:document" width="24" height="24" />
          </i>
          <div v-if="isMenuOpen">Ressources</div>
        </router-link>
      </li>

      <li class="menu-item">
        <router-link to="/messagerie" class="menu-link">
          <i class="menu-icon">
            <Icon icon="hugeicons:message-multiple-01" width="24" height="24" />
          </i>
          <div v-if="isMenuOpen">Messagerie</div>
        </router-link>
      </li>

      <li class="menu-item">
        <router-link to="/agent-ia-generaliste" class="menu-link">
          <i class="menu-icon">
            <Icon icon="fluent:bot-sparkle-48-regular" width="24" height="24" />
          </i>
          <div v-if="isMenuOpen">Agent IA généraliste</div>
        </router-link>
      </li>
    </ul>
  </aside>
</template>

<script setup>
import { Icon } from '@iconify/vue';
import { ref, onMounted, watch } from 'vue';

// État pour suivre si le menu est ouvert ou fermé
const isMenuOpen = ref(true);

// Fonction qui bascule l'état du menu
const toggleSidebar = () => {
  isMenuOpen.value = !isMenuOpen.value;
  // Sauvegarder l'état dans le localStorage pour le conserver entre les sessions
  localStorage.setItem('sidebarOpen', isMenuOpen.value);
};

// Récupérer l'état sauvegardé au chargement du composant
onMounted(() => {
  const savedState = localStorage.getItem('sidebarOpen');
  if (savedState !== null) {
    isMenuOpen.value = savedState === 'true';
  }
});

// Écouter les changements de taille d'écran pour fermer automatiquement sur mobile
onMounted(() => {
  const handleResize = () => {
    if (window.innerWidth < 992 && isMenuOpen.value) {
      isMenuOpen.value = false;
    }
  };
  
  window.addEventListener('resize', handleResize);
  // Vérification initiale
  handleResize();
  
  // Nettoyage de l'écouteur d'événements
  return () => {
    window.removeEventListener('resize', handleResize);
  };
});
</script>

<style scoped>
#layout-menu {
  width: 260px;
  background-color: #ffffff;
  border-right: 1px solid #e0e0e0;
  padding: 20px 0;
  height: 100vh;
  transition: width 0.3s ease;
  position: relative;
}

#layout-menu.collapsed {
  width: 70px;
  /* overflow-x: hidden; */
}

.app-brand {
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 20px;
  transition: all 0.3s ease;
}

.collapsed .app-brand {
  padding: 10px;
}

.collapsed .app-brand img {
  width: 40px;
  height: auto;
}

.menu-toggle-wrapper {
  position: absolute;
  right: -15px;
  top: 70px;
  z-index: 10;
}

.menu-toggle-btn {
  width: 30px;
  height: 30px;
  border-radius: 50%;
  background-color: #005183;
  color: white;
  border: none;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}

.menu-inner {
  list-style: none;
  padding: 0;
  margin: 0;
}

.menu-item {
  margin-bottom: 10px;
}

.menu-link {
  display: flex;
  align-items: center;
  padding: 10px 20px;
  color: #94d6ff;
  text-decoration: none;
  transition: background-color 0.3s;
  white-space: nowrap;
}

.collapsed .menu-link {
  padding: 10px;
  justify-content: center;
}

.menu-link:hover {
  background-color: #f5f5f5;
  border-radius: 8px;
  color: #005183 !important;
}

.menu-icon {
  display: flex;
  align-items: center;
  justify-content: center;
  margin-right: 12px;
  transition: margin 0.3s ease;
}

.collapsed .menu-icon {
  margin-right: 0;
}

.menu-icon :deep(svg) {
  width: 24px;
  height: 24px;
}

/* Ajouter un tooltip pour la version réduite */
.collapsed .menu-item {
  position: relative;
}

.collapsed .menu-item:hover::after {
  content: attr(data-title);
  position: absolute;
  left: 70px;
  top: 50%;
  transform: translateY(-50%);
  background: #005183;
  color: white;
  padding: 5px 10px;
  border-radius: 4px;
  font-size: 14px;
  white-space: nowrap;
  z-index: 10;
}
</style>
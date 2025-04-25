<script setup>
import { useTheme } from "@/theme"
import { computed, ref, onMounted, onBeforeUnmount } from "vue"
import { route } from 'ziggy-js'
import { usePage, Link } from "@inertiajs/vue3"
import { pageTitles } from "@/constants/menuItems"

const { isDark } = useTheme()
const page = usePage()
const showUserMenu = ref(false)

const toggleUserMenu = () => {
  showUserMenu.value = !showUserMenu.value
}

const closeUserMenu = (e) => {
  if (!e.target.closest(".user-menu-wrapper")) {
    showUserMenu.value = false
  }
}

onMounted(() => {
  document.addEventListener("click", closeUserMenu)
})

onBeforeUnmount(() => {
  document.removeEventListener("click", closeUserMenu)
})

const pageTitle = computed(() => {
  const current = route().current()
  return pageTitles[current] || "Page"
})

const goBack = () => history.back()
</script>

<template>
  <nav class="navbar">
    <!-- Gauche : retour + titre -->
    <div class="left-side">
      <button class="back-btn" @click="goBack">
        <i class="fa-solid fa-angle-left" />
      </button>
      <Transition name="fade-scale">
        <h1 class="page-title" :key="pageTitle">{{ pageTitle }}</h1>
      </Transition>
    </div>

    <!-- Droite : icônes avec animation et support dark -->
    <div class="right-side">
      <div class="user-menu-wrapper">
        <button class="icon-button animate" @click="toggleUserMenu" title="Profil">
          <img :src="isDark ? '/images/user-light.png' : '/images/user-light.png'" alt="User" />
        </button>

        <Transition name="fade-scale">
          <div v-if="showUserMenu" class="user-dropdown">
            <Link :href="route('profile.edit')" class="menu-item">
              <i class="fas fa-user-circle"></i> Profil
            </Link>
            <Link :href="route('logout')" method="post" as="button" class="menu-item logout-link">
              <i class="fas fa-sign-out-alt"></i> Déconnexion
            </Link>
          </div>
        </Transition>
      </div>
    </div>
  </nav>
</template>

<style scoped>
.user-menu-wrapper {
  position: relative;
}

.user-dropdown {
  position: absolute;
  right: 0;
  top: 54px;
  background: white;
  border-radius: 16px;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
  padding: 10px 30px;
  min-width: 160px;
  z-index: 1000;
}

.menu-item {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 10px 18px;
  color: rgb(94, 88, 88);
  font-size: 14px;
  cursor: pointer;
  text-decoration: none;
  transition: background 0.2s;
}

.menu-item:hover {
  background-color: #f1f5f9;
 
}

.fade-scale-enter-active,
.fade-scale-leave-active {
  transition: all 0.3s ease;
}
.fade-scale-enter-from,
.fade-scale-leave-to {
  opacity: 0;
  transform: scale(0.95);
}

.navbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: var(--navbar-bg, #f0f8ff);
  padding: 14px 20px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
  border-bottom: 1px solid #dbeafe;
}

.left-side {
  display: flex;
  align-items: center;
  gap: 10px;
}
.back-btn {
  background: transparent;
  border: none;
  font-size: 18px;
  color: #0c4a6e;
  cursor: pointer;
}
.page-title {
  font-size: 20px;
  font-weight: bold;
  color: #0c4a6e;
  transition: all 0.3s ease-in-out;
}

.right-side {
  display: flex;
  align-items: center;
  gap: 16px;
}

.icon-button {
  width: 42px;
  height: 42px;
  border-radius: 12px;
  background: white;
  border: none;
  padding: 8px;
  cursor: pointer;
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.08);
  display: flex;
  align-items: center;
  justify-content: center;
  transition: transform 0.15s ease, box-shadow 0.15s ease;
}
.icon-button img {
  width: 22px;
  height: 22px;
}

.animate:hover {
  transform: scale(1.1);
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.12);
}
</style>

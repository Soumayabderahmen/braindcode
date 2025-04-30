<template>
  <aside :class="['aside layout-menu menu-vertical bg-menu-theme', { 'collapsed': isClosed }]" id="layout-menu">
    <!-- Logo -->
    <div class="logo_item">
      <img src="/images/logo.jpg" alt="Logo" />
      <div v-if="!isClosed" class="logo-text">
        <span class="brand-name">Braincode</span>
        <span class="brand-subtitle">Startup Studio</span>
      </div>
    </div>

    <link
      href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
      rel="stylesheet"
    />
    <!-- Toggle button -->
    <div class="menu-toggle-wrapper">
      <button class="menu-toggle-btn" @click="toggleSidebar">
        <i v-if="!isClosed" class="bx bx-chevron-left"></i>
        <i v-else class="bx bx-chevron-right"></i>
      </button>
    </div>

    <!-- Menu -->
    <ul class="menu-inner py-1">
      <li class="menu-item" v-for="item in menuItems" :key="item.name">
        <Link :href="route(item.route)" class="menu-link">
          <i :class="['menu-icon', item.icon]"></i>
          <div v-if="!isClosed">{{ item.name }}</div>
        </Link>
      </li>
    </ul>


  </aside>
</template>

<script setup>
import { ref, computed } from "vue";
import { usePage, Link } from "@inertiajs/vue3";
import { adminMenuItems, userMenuItems } from "@/constants/menuItems";
import { route } from 'ziggy-js';

defineProps(['isClosed']);
const emit = defineEmits(['toggle']);

const page = usePage();
const user = computed(() => page.props.auth.user);
const isAdmin = computed(() => user.value && user.value.role === "admin");

const menuItems = computed(() => (isAdmin.value ? adminMenuItems : userMenuItems));

const toggleSidebar = () => {
  emit('toggle');
};
</script>

<style scoped>
@import url("https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap");

/* === Structure générale === */
#layout-menu {
  width: 260px;
  background-color: #ffffff;
  border-right: 1px solid #e0e0e0;
  height: 100vh;
  padding-top: 20px;
  position: fixed;
  top: 0;
  left: 0;
  transition: width 0.3s ease;
  overflow-y: auto;
}

#layout-menu.collapsed {
  width: 80px;
}

/* === Logo === */
.logo_item {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 0 20px;
}

.logo_item img {
  width: 40px;
  height: 40px;
  border-radius: 50%;
}

.logo-text {
  display: flex;
  flex-direction: column;
  line-height: 1.1;
}

.brand-name {
  font-size: 18px;
  font-weight: 700;
  color: var(--blue-color);
}

.brand-subtitle {
  font-size: 12px;
  color: var(--black-color);
}

/* === Toggle Button === */
.menu-toggle-wrapper {
  position: absolute;
  top: 20px;
  right: -15px;
}

.menu-toggle-btn {
  width: 30px;
  height: 30px;
  background-color: #005183;
  color: white;
  border-radius: 50%;
  border: none;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
}

/* === Menu Items === */
.menu-inner {
  list-style: none;
  padding: 0;
  margin-top: 50px;
}

.menu-item {
  margin-bottom: 10px;
}

.menu-link {
  display: flex;
  align-items: center;
  padding: 10px 20px;
  color: #6cbdf0;
  text-decoration: none;
  transition: background-color 0.3s;
}

.menu-link:hover {
  background-color: #f5f5f5;
  border-radius: 8px;
  color: #005183;
}

.menu-icon {
  margin-right: 12px;
  font-size: 22px;
}

/* Quand collapse */
#layout-menu.collapsed .menu-link {
  justify-content: center;
}

#layout-menu.collapsed .menu-link div {
  display: none;
}

/* === Bottom Button Expand/Collapse === */
.bottom_content {
  position: absolute;
  bottom: 20px;
  width: 100%;
  text-align: center;
}

.bottom {
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
}

.bottom i {
  font-size: 24px;
  color: #005183;
}
</style>

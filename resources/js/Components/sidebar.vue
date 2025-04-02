

<script setup>
import { ref, computed } from "vue";
import { usePage } from "@inertiajs/vue3";

const isSidebarOpen = ref(true);
const page = usePage();

// Vérifier si l'utilisateur est un admin
const isAdmin = computed(() => page.props.auth.user.role === "admin");
const isStartup =computed(()=>page.props.auth.user.role==='startup');
const isInvestisseur=computed(()=>page.props.auth.user.role==='investisseur');
const isCoach=computed(()=>page.props.auth.user.role==='coach');
const toggleSidebar = () => {
  isSidebarOpen.value = !isSidebarOpen.value;
};
</script>

<template>
    <div id="sidebar" class="sidebar">
      <div class="sidebar-header">
        <span v-if="isAdmin">Admin Dashboard</span>
  <span v-else-if="isInvestisseur">Investisseur Dashboard</span>
  <span v-else-if="isCoach">Coach Dashboard</span>
  <span v-else-if="isStartup">Startup Dashboard</span>
          <button @click="toggleSidebar" class="toggle-btn">☰</button>
      </div>
      <ul class="sidebar-menu">
        <template v-if="isAdmin">
          <li>
            <a href="/admin/dashboard" class="menu-item">🏠 Dashboard</a>
          </li>
          <li>
            <a href="/admin/coaches" class="menu-item">👨‍🏫 Coachs</a>
          </li>
          <li>
            <a href="/admin/investisseurs" class="menu-item">💰 Investisseurs</a>
          </li>
          <li>
            <a href="/admin/startups" class="menu-item">🚀 Startups</a>
          </li>
        </template>
        <template v-if="isStartup">
          <li>
            <a href="/dashboard" class="menu-item">🏠 Dashboard</a>
          </li>
          <li>
            <a href="/profile" class="menu-item">👤 Profil</a>
          </li>
          <li>
            <a href="/ListCoachs" class="menu-item">👨‍🏫 Coachs</a>
          </li>
          <li>
            <a href="/ListInvestisseurs" class="menu-item">💰 Investisseurs</a>
          </li>
          <li>
            <a href="/ListStartups" class="menu-item">🚀 Startups</a>
          </li>
          <li>
            <a href="/calendar" class="menu-item">🗓️ Calandrier</a>
          </li>
        </template>
        <template v-if="isCoach">
          <li>
            <a href="/dashboard" class="menu-item">🏠 Dashboard</a>
          </li>
          <li>
            <a href="/profile" class="menu-item">👤 Profil</a>
          </li>
          <li>
            <a href="/ListStartups" class="menu-item">🚀 Startups</a>
          </li>
          <li>
            <a href="/ListInvestisseurs" class="menu-item">💰 Investisseurs</a>
          </li>
          <li>
            <a href="/coach/availability" class="menu-item">⏳ Disponibilité</a>
          </li>
          <li>
            <a href="/calendar" class="menu-item">🗓️ Calandrier</a>
          </li>
        </template>
        <template v-if="isInvestisseur">
          <li>
            <a href="/dashboard" class="menu-item">🏠 Dashboard</a>
          </li>
          <li>
            <a href="/profile" class="menu-item">👤 Profil</a>
          </li>
          <li>
            <a href="/ListStartups" class="menu-item">🚀 Startups</a>
          </li>
          
         
        </template>
       
      </ul>
    </div>
  </template>
  
 
  <style scoped>
  .sidebar {
    width: 250px;
    position: fixed;
    top: 0;
    left: 0;
    height: 100%;
    background-color: #2c3e50;
    color: #fff;
    transition: width 0.3s;
  }
  
  .sidebar-header {
    padding: 15px;
    text-align: center;
    background-color: #34495e;
  }
  
  .sidebar-menu {
    list-style-type: none;
    padding: 0;
    margin: 0;
  }
  
  .sidebar-menu li {
    padding: 10px 20px;
  }
  
  .menu-item {
    color: #ecf0f1;
    text-decoration: none;
    display: block;
    cursor: pointer;
  }
  
  .menu-item:hover {
    background-color: #16a085;
  }
  </style>
  
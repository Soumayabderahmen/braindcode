<template>
    <aside id="layout-menu" class="aside layout-menu menu-vertical menu bg-menu-theme">
      <div class="app-brand demo">
        <router-link to="/" class="app-brand-link">
          <span class="app-brand-logo demo">
            <img src="@/assets/img/dash/logo.png" alt="Logo" />
          </span>
        </router-link>
  
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
          <i class="menu-toggle-icon ti ti-text-wrap-disabled d-none d-xl-block ti-sm align-middle"></i>
          <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
        </a>
      </div>
  
      <ul class="menu-inner py-1">
        <!-- Dashboards -->
        <li class="menu-item">
          <router-link to="/dashboard" class="menu-link">
            <i class="menu-icon">
              <span class="iconify" data-icon="material-symbols:dashboard-outline-rounded" data-inline="false"></span>
            </i>
            <div>Tableau de bord</div>
          </router-link>
        </li>
  
        <!-- Formation -->
        <li class="menu-item">
          <router-link to="/formation" class="menu-link">
            <i class="menu-icon">
              <span class="iconify" data-icon="covid:symptoms-virus-headache-2" data-inline="false"></span>
            </i>
            <div>Formation</div>
          </router-link>
        </li>
  
        <!-- Agent IA -->
        <li class="menu-item">
          <router-link to="/agent-ia" class="menu-link">
            <i class="menu-icon">
              <span class="iconify" data-icon="mage:robot" data-inline="false"></span>
            </i>
            <div>Agent Ai</div>
          </router-link>
        </li>
  
        <!-- Calendrier -->
        <li class="menu-item">
          <router-link to="/calendrier" class="menu-link">
            <i class="menu-icon">
              <span class="iconify" data-icon="solar:calendar-line-duotone" data-inline="false"></span>
            </i>
            <div>Calendrier</div>
          </router-link>
        </li>
  
        <!-- Forum -->
        <li class="menu-item">
          <router-link to="/forum" class="menu-link">
            <i class="menu-icon">
              <span class="iconify" data-icon="charm:messages" data-inline="false"></span>
            </i>
            <div>Forum</div>
          </router-link>
        </li>
  
        <!-- Ressources -->
        <li class="menu-item">
          <router-link to="/ressources" class="menu-link">
            <i class="menu-icon">
              <span class="iconify" data-icon="fe:document" data-inline="false"></span>
            </i>
            <div>Ressources</div>
          </router-link>
        </li>
  
        <!-- Messagerie -->
        <li class="menu-item">
          <router-link to="/messagerie" class="menu-link">
            <i class="menu-icon">
              <span class="iconify" data-icon="hugeicons:message-multiple-01" data-inline="false"></span>
            </i>
            <div>Messagerie</div>
          </router-link>
        </li>
  
        <!-- Agent IA généraliste -->
        <li class="menu-item">
          <router-link to="/agent-ia-generaliste" class="menu-link">
            <i class="menu-icon">
              <span class="iconify" data-icon="fluent:bot-sparkle-48-regular" data-inline="false"></span>
            </i>
            <div>Agent IA généraliste</div>
          </router-link>
        </li>
      </ul>
    </aside>
  </template>
  
  <script setup>
  // Pas besoin de script pour ce composant pour l'instant
  </script>
  
  <style scoped>
  /* Ton CSS spécifique ici */
  </style>
  
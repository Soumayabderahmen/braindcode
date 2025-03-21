<template>
    <nav class="navbar">
      <!-- Bouton pour ouvrir le menu latéral -->
     
  
      <!-- Titre du tableau de bord -->
      <div class="nav-title">Tableau de bord</div>
      <div class="search_bar">
        <input type="text" placeholder="Search" />
      </div>

      <!-- Section droite de la navbar -->
      <div class="flex items-center space-x-6">
        <!-- Authentification -->
        <div v-if="user" class="relative">
          <div 
            @click="show = !show" 
            class="flex items-center gap-2 px-3 py-1 rounded-lg hover:bg-gray-700 cursor-pointer"
            :class="{ 'bg-gray-700': show }"
          >
            <p>{{ user.name }}</p>
            <i class="fa-solid fa-angle-down"></i>
          </div>
  
          <!-- Menu déroulant de l'utilisateur -->
          <div v-show="show" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2">
            <DropdownLink :href="route('profile.edit')">Profil</DropdownLink>
            <DropdownLink :href="route('logout')" method="post" as="button">Déconnexion</DropdownLink>
          </div>
        </div>
  
        <!-- Invité -->
        <div v-else class="space-x-6">
          <NavLink routeName="login" componentName="Auth/Login">Se connecter</NavLink>
        </div>
  
        <!-- Bouton de changement de thème -->
        <button class="dark-mode-button" @click="toggleDarkMode">
          <i :class="isDark ? 'bx bx-moon' : 'bx bx-sun'"></i>
        </button>
      </div>
    </nav>
  </template>
  
  <script>
  import { ref, computed } from "vue";
  import { useTheme } from "@/theme";
  import { usePage } from "@inertiajs/vue3";
  import DropdownLink from "../Components/DropdownLink.vue"; 
  import NavLink from "../Components/NavLink.vue"; 
  
  export default {
    components: { DropdownLink, NavLink },
    setup() {
      const { isDark, toggleDarkMode } = useTheme();
      const page = usePage();
      const user = computed(() => page.props.auth.user);
      const show = ref(false);
  
      return { isDark, toggleDarkMode, user, show };
    },
  };
  </script>
  
  <style scoped>
  .navbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 15px 20px;
    background: var(--navbar-bg);
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  }

  .nav-title {
    font-size: 18px;
    font-weight: bold;
    color: var(--text-color);
  }
  .relative {
    position: relative;
  }
  .absolute {
    position: absolute;
    background: white;
    border-radius: 8px;
    padding: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
  }

  .search_bar {
  height: 47px;
  max-width: 430px;
  width: 100%;
}
.search_bar input {
  height: 100%;
  width: 100%;
  border-radius: 25px;
  font-size: 18px;
  outline: none;
  background-color: var(--white-color);
  color: var(--grey-color);
  padding: 0 20px;
}

  </style>
  
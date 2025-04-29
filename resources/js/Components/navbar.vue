<script setup>
import { switchTheme } from "../theme";
import NavLink from "../Components/NavLink.vue";
import Dropdown from "../Components/Dropdown.vue";
import DropdownLink from "../Components/DropdownLink.vue";
import { usePage } from "@inertiajs/vue3";
import { computed, ref } from "vue";
import Notifications from "./Notifications.vue";

// Définir les props pour recevoir la fonction toggleSidebar du composant parent
const props = defineProps({
    toggleSidebar: {
        type: Function,
        default: () => { }
    }
});

const page = usePage();
const user = computed(() => page.props.auth.user);
const show = ref(false);

// Définir pageTitle avec une valeur par défaut ou récupérée dynamiquement
const pageTitle = computed(() => page.props.title || "Tableau de board");

// Fonction pour basculer le sidebar
const handleToggleSidebar = () => {
    props.toggleSidebar();
};
const isDropdownOpen = ref(false);
const toggleDropdown = () => {
    isDropdownOpen.value = !isDropdownOpen.value;
};

</script>

<template>
    <div class="rectangle">
        <div class="navbar">
            <img src="/assets/img/logo/vector.svg" style="
    width: 8px;
" />
            <b class="dashboard">{{ pageTitle }}</b>

            <div class="rectangle-container flex ml-auto items-center">
                <div class="rectanglediv">
                    <img src="/assets/img/dash/robot.png" class="rectangleImage" />
                </div>
                <div class="rectanglediv">
                    <img src="/assets/img/logo/Group.svg" class="groupeIcon" />
                </div>
                <div class="rectanglediv" >
                    <div v-if="user" class="relative">
                        <div 
                            @click="show = !show" 
                            class="flex items-center gap-2 px-3 py-1 rounded-lg cursor-pointer"
                        >
                            <img src="/assets/img/dash/entreprise.png" alt="" />
                        </div>

                        <!-- Menu déroulant de l'utilisateur -->
                        <div v-show="show" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2">
                            <DropdownLink :href="route('profile.edit')" class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="18" viewBox="0 0 14 18" fill="none">
                                    <path d="M10.0004 4.19937C10.0004 4.99513 9.68429 5.75829 9.1216 6.32098C8.55892 6.88366 7.79576 7.19977 7 7.19977C6.20424 7.19977 5.44108 6.88366 4.8784 6.32098C4.31571 5.75829 3.9996 4.99513 3.9996 4.19937C3.9996 3.40362 4.31571 2.64046 4.8784 2.07777C5.44108 1.51509 6.20424 1.19897 7 1.19897C7.79576 1.19897 8.55892 1.51509 9.1216 2.07777C9.68429 2.64046 10.0004 3.40362 10.0004 4.19937ZM1 15.4953C1.02571 13.921 1.66916 12.4198 2.79158 11.3156C3.914 10.2113 5.42546 9.59247 7 9.59247C8.57454 9.59247 10.086 10.2113 11.2084 11.3156C12.3308 12.4198 12.9743 13.921 13 15.4953C11.1179 16.3592 9.07088 16.8047 7 16.8011C4.85891 16.8011 2.82664 16.3338 1 15.4953Z" stroke="#474747" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                Profil
                            </DropdownLink>
                            <DropdownLink :href="route('logout')" method="post" as="button" class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="20" viewBox="0 0 17 20" fill="none">
                                    <path d="M9.996 19H3C1.895 19 1 17.849 1 16.429V3.57C1 2.151 1.895 1 3 1H10M12.5 13.5L16 10L12.5 6.5M6 9.996H16" stroke="#474747" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                Déconnexion
                            </DropdownLink>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Styles pour la navbar comme sur l'image */
.navbar {
    height: 60px;
    padding: 0 1.5rem;
    position: sticky;
    top: 0;
    z-index: 999;
    box-shadow: none;
}

.back-button {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 8px;
    color: #333;
    background-color: #f5f5f5;
    text-decoration: none;
}

.page-title {
    font-size: 18px;
    font-weight: 600;
    color: #0c4a6e;
}

.navbar-nav .nav-link {
    padding: 8px;
    color: #555;
    display: flex;
    align-items: center;
    justify-content: center;
}

.notification-badge {
    position: absolute;
    top: 3px;
    right: 3px;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background-color: #ff3e3e;
}

/* Icônes */
.bi {
    font-size: 20px;
}

/* Boutons dans la navbar */
.navbar .nav-item {
  margin-left: 8px;
}

.navbar{
  display: flex; 
  align-items: center; 
  gap: 10px; 
  margin-top: 21px;
  margin-left: 63px;
  height: 97px;
}
.groupeIcon{
 
position: relative;
max-width: 100%;
overflow: hidden;
max-height: 100%;
height: 55%;
}
.rectanglediv{
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 10px 8px 20px rgba(0, 81, 131, 0.25);
  border-radius: 7px;
  background-color: #fff;
  height: 45px;
  width: 51px;
  box-sizing:border-box;
}
.rectangleImage{
 
position: relative;
max-width: 100%;
overflow: hidden;
max-height: 100%;
object-fit: cover;
height: 80%;

}
.rectangle{
  border-radius: 27px 0px 0px 27px;
background-color: #f1f9ff;
position: fixed;
height: 1539px;
margin-top: -22px;
width: 100%;
margin-left: -40px;
}
.rectangle-container {
  display: flex;
  gap: 20px;
  margin-left: 657px;
}
.dashboard{
    font-family: 'SF Pro Display', sans-serif;
    font-size: 29px;
    font-style: normal;
    font-weight: 700;
    line-height: 46.8%;
    margin-bottom: 0px;
    margin-left: 35px;
    color: #005183 !important;
}
</style>
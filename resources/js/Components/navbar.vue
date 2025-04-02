<script setup>
import { switchTheme } from "../theme";
import NavLink from "../Components/NavLink.vue";
import Dropdown from "../Components/Dropdown.vue"; // Vérifie que ce composant existe bien
import DropdownLink from "../Components/DropdownLink.vue"; // Vérifie que ce composant existe bien
import { usePage } from "@inertiajs/vue3";
import { computed, ref } from "vue";

const page = usePage();
const user = computed(() => page.props.auth.user);
const show = ref(false);
</script>

<template>
    <header class="bg-slate-800 text-white">
        <nav class="p-6 mx-auto max-w-screen-lg flex items-center justify-between">
            <NavLink :href="route('dashboard')">Accueil</NavLink>

            <div class="flex items-center space-x-6">
                <!-- Authentification -->
                <div v-if="user" class="relative">
                    <div 
                        @click="show = !show" 
                        class="flex items-center gap-2 px-3 py-1 rounded-lg hover:bg-slate-700 cursor-pointer" 
                        :class="{ 'bg-slate-700': show }"
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
                <button @click="switchTheme" class="hover:bg-slate-700 w-6 h-6 grid place-items-center rounded-full hover:outline outline-1 outline-white">
                    <i class="fa-solid fa-circle-half-stroke"></i>
                </button>
            </div>
        </nav>
    </header>
</template>

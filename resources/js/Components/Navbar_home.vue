<script setup>
import { ref } from 'vue';
import { usePage, Link } from '@inertiajs/vue3';
import { onClickOutside } from '@vueuse/core';

const user = usePage().props.auth.user;
const showingNavigationDropdown = ref(false);
const dropdownRef = ref(null);

onClickOutside(dropdownRef, () => {
    showingNavigationDropdown.value = false;
});
</script>

<template>
    <nav class="bg-white border-b border-gray-100 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                
                <!-- Logo et liens -->
                <div class="flex items-center space-x-6">
                    <!-- Logo -->
                    <a href="#" class="text-xl font-semibold">
                        Braindcode Startup Studio
                    </a>

                    <!-- Liens de navigation -->
                    <Link :href="route('dashboard')" class="text-gray-700 hover:text-gray-900">
                        Accueil
                    </Link>
                    <Link :href="route('contactus')" class="text-gray-700 hover:text-gray-900">
                        Contact Us
                    </Link>
                </div>

                <!-- Menu utilisateur / connexion -->
                <div class="flex items-center">
                    <template v-if="user">
                        <!-- Dropdown utilisateur -->
                        <div class="relative ms-3">
                            <button 
                                @click="showingNavigationDropdown = !showingNavigationDropdown" 
                                class="inline-flex items-center text-gray-500 focus:outline-none"
                            >
                                <span>{{ user.name }}</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="-me-0.5 ms-2 h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>

                            <!-- Menu déroulant -->
                            <transition enter-active-class="transition ease-out duration-200"
                                enter-from-class="opacity-0 scale-95"
                                enter-to-class="opacity-100 scale-100"
                                leave-active-class="transition ease-in duration-150"
                                leave-from-class="opacity-100 scale-100"
                                leave-to-class="opacity-0 scale-95">
                                <div ref="dropdownRef" v-if="showingNavigationDropdown" class="absolute right-0 mt-2 w-48 bg-white border rounded-md shadow-lg">
                                    <Link :href="route('profile.edit')" class="block px-4 py-2 text-sm text-gray-700">
                                        Profile
                                    </Link>
                                    <Link 
                                        :href="route('logout')" 
                                        method="post" 
                                        as="button" 
                                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                    >
                                        Logout
                                    </Link>
                                </div>
                            </transition>
                        </div>
                    </template>
                    <template v-else>
                        <Link :href="route('login')" class="text-gray-700 hover:text-gray-900">
                            Se connecter
                        </Link>
                    </template>
                </div>
            </div>
        </div>
    </nav>
</template>

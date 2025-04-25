<script setup>
import { ref } from 'vue';
import { usePage, Link } from '@inertiajs/vue3';
import { onClickOutside } from '@vueuse/core';
const mobileMenuOpen = ref(false);
const user = usePage().props.auth?.user ?? null;
const showingNavigationDropdown = ref(false);
const dropdownRef = ref(null);

onClickOutside(dropdownRef, () => {
    showingNavigationDropdown.value = false;
});
</script>

<template>
    <nav class="bg-white shadow-md border-b border-gray-100">
      <div class="w-full px-4 sm:px-6 lg:px-8 ">
        <div class="flex justify-between items-center h-16" style="margin-right: 3%;">
          
          <!-- Burger -->
          <div class="md:hidden">
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-[#1D4ED8] hover:text-blue-800 focus:outline-none">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
              </svg>
            </button>
          </div>
  
          <!-- Logo -->
          <div class="flex items-center space-x-6 ml-6">
            <Link href="/">
              <img src="/images/logo-braindcode.png" alt="Braindcode Logo" class="h-10" />
            </Link>
          </div>
  
          <!-- Liens de navigation -->
          <div class="hidden md:flex items-center space-x-8 font-medium text-[#0093EE] font-bold" style="margin-left: 27%;">            
            <Link :href="route('dashboard')" class="hover:text-blue-800 transition">À Propos</Link>
            <Link :href="route('dashboard')" class="hover:text-blue-800 transition">Coach</Link>
            <Link :href="route('dashboard')" class="hover:text-blue-800 transition">Forum</Link>
            <Link :href="route('dashboard')" class="hover:text-blue-800 transition">Startup incubé</Link>
            <Link :href="route('dashboard')" class="hover:text-blue-800 transition">Investisseur</Link>
          </div>
  
          <!-- Auth ou profil -->
          <div class="flex items-center">
            <template v-if="user">
              <div class="relative" ref="dropdownRef">
                <button @click="showingNavigationDropdown = !showingNavigationDropdown" class="flex items-center text-gray-700 hover:text-gray-900">
                  <span class="me-2">{{ user.name }}</span>
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                  </svg>
                </button>
                <transition enter-active-class="transition ease-out duration-200"
                            enter-from-class="opacity-0 scale-95"
                            enter-to-class="opacity-100 scale-100"
                            leave-active-class="transition ease-in duration-150"
                            leave-from-class="opacity-100 scale-100"
                            leave-to-class="opacity-0 scale-95">
                  <div v-if="showingNavigationDropdown" class="absolute right-0 mt-2 w-48 bg-white border rounded-md shadow-lg">
                    <Link :href="route('logout')" method="post" as="button" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</Link>
                  </div>
                </transition>
              </div>
            </template>
            <template v-else>
              <Link :href="route('login')" class="hidden md:flex items-center gap-2 font-medium text-[#0093EE] hover:text-blue-800">
                <img src="/images/user-icon.png" alt="User Icon" class="w-5 h-5" />
                Se connecter
              </Link>
            </template>
          </div>
  
        </div>
      </div>
    </nav>
  
    <!-- ✅ Menu mobile ici -->
    <div v-if="mobileMenuOpen" class="md:hidden px-4 pb-4 space-y-2">
      <Link :href="route('dashboard')" class="block text-[#0093EE] font-medium hover:text-blue-800">A propos</Link>
      <Link :href="route('dashboard')" class="block text-[#0093EE] font-medium hover:text-blue-800">Coach</Link>
      <Link :href="route('dashboard')" class="block text-[#0093EE] font-medium hover:text-blue-800">Forum</Link>
      <Link :href="route('dashboard')" class="block text-[#0093EE] font-medium hover:text-blue-800">Startup incubé</Link>
      <Link :href="route('dashboard')" class="block text-[#0093EE] font-medium hover:text-blue-800">Investisseur</Link>
  
      <div class="border-t pt-2">
        <template v-if="user">
          <Link :href="route('logout')" method="post" as="button" class="block text-[#0093EE] hover:text-blue-800">Logout</Link>
        </template>
        <template v-else>
          <Link :href="route('login')" class="flex items-center gap-2 text-[#0093EE] hover:text-blue-800">
            <img src="/images/user-icon.png" class="w-5 h-5" />
            Se connecter
          </Link>
        </template>
      </div>
    </div>
  </template>
  

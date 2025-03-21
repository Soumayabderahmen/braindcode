<script setup>
import Navbar_home from '@/Components/Navbar_home.vue';
import Chatbot from '@/Pages/Chatbot/Chatbot.vue';

</script>

<template>
    <div class="min-h-screen bg-gray-100">
        <Navbar_home /> 
        <Chatbot />
        <!-- Contenu principal -->
        <main>
            <div class="py-6">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            Bienvenue dans votre tableau de bord !
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import StatsTab from '../Chatbot/StatsTab.vue'
import SettingsTab from '../Chatbot/SettingsTab.vue'
import ResponsesTab from '../Chatbot/ResponsesTab.vue'
import axios from 'axios'

const activeTab = ref('stats')
const settings = ref({})

// Load settings on mounted
onMounted(async () => {
  const response = await axios.get('/admin/chatbot/settings');
  settings.value = response.data;
});

// Save settings
const saveSettings = async () => {
  await axios.post('/admin/chatbot/settings', settings.value);
  alert('Paramètres sauvegardés');
};

</script>

<template>
  <div class="p-6">
   

    <!-- Onglets -->
    <div class="flex space-x-4 mb-4 border-b border-gray-200">
      <button
        @click="activeTab = 'stats'"
        :class="['px-4 py-2 font-medium', activeTab === 'stats' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-500']"
      >
        📊 Statistiques
      </button>
      <button
        @click="activeTab = 'settings'"
        :class="['px-4 py-2 font-medium', activeTab === 'settings' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-500']"
      >
        ⚙️ Paramètres
      </button>
      <button
        @click="activeTab = 'responses'"
        :class="['px-4 py-2 font-medium', activeTab === 'responses' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-500']"
      >
        💬 Réponses prédéfinies
      </button>
    </div>

    <!-- Contenu de l'onglet -->
    <div>
      <StatsTab v-if="activeTab === 'stats'" />
      <SettingsTab v-else-if="activeTab === 'settings'" />
      <ResponsesTab v-else-if="activeTab === 'responses'" />
    </div>
  </div>
</template>

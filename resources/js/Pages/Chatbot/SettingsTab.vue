<template>
 
    
    <!-- Navigation Tabs -->
   
    
    <!-- Main Content -->
    <div  class="flex gap-6">
      <!-- Left Column: General Settings -->
      <div class="bg-white rounded-lg shadow p-6 w-1/2">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Paramètres généraux</h2>
        
       
        
        <!-- Bot Name -->
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-1">Bot Name</label>
          <input 
            v-model="settings.bot_name" 
            type="text" 
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
            placeholder="Support Bot"
          />
        </div>
        
        <!-- Welcome Message -->
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-1">Message de bienvenue</label>
          <textarea 
            v-model="settings.welcome_message" 
            rows="3"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
            placeholder="Hello! How can I help you today?"
          ></textarea>
        </div>
        
        
  
       
        
        <!-- Primary Color -->
      <!-- 🎨 Couleur principale -->
<div class="mb-4">
  <label class="block text-sm font-medium text-gray-700 mb-1">Couleur principale</label>
  <input
    v-model="settings.primary_color"
    type="color"
    class="h-10 w-20 rounded-md border border-gray-300 shadow-sm"
  />
</div>

        
        <!-- Timeout Response -->
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-1">Timeout Response: Message d'inactivité</label>
          <textarea 
            v-model="settings.timeout_response" 
            rows="3"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
            placeholder="I'm sorry for the delay. If this is urgent, please contact us directly at support@company.com."
          ></textarea>
        </div>
        
        <!-- Save Button -->
        <button 
          @click="saveSettings" 
          class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
        >
        Enregistrer les modifications
        </button>
      </div>
      
      <!-- Right Column: AI Training -->
      <div class="bg-white rounded-lg shadow p-6 w-1/2">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Entraînement IA</h2>
        <p class="text-gray-600 mb-4">
          Provide information about your company and products to train the AI chatbot.
        </p>
        
        <textarea 
          v-model="trainingText" 
          rows="10"
          class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
          placeholder="# liste_etapes_programme
Give me the 12 steps
Give me the 12 steps of the incubation
What are the 12 steps?
What are the steps of the incubation program?
I want the 12 steps please
Could you list the 12 stages of incubation?
Tell me the full list of the startup program steps"
        ></textarea>
        
        <!-- Train Button -->
        <button 
          @click="trainModel" 
          class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
        >
          Train AI Model
        </button>
      </div>
    </div>
    
  
 
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'

const props = defineProps({
  settings: Object
})

const emit = defineEmits(['update'])
const settings = ref({ ...props.settings })
const trainingText = ref('') // Non stocké en BDD

const saveSettings = async () => {
  try {
    const payload = {
      bot_name: settings.value.bot_name,
      welcome_message: settings.value.welcome_message,
      timeout_response: settings.value.timeout_response,
      primary_color: settings.value.primary_color,
    }

    await axios.post('/admin/chatbot/settings', payload)
    emit('update', settings.value)
    alert('✅ Paramètres sauvegardés avec succès.')
  } catch (err) {
    console.error('❌ Erreur lors de la sauvegarde', err)
    alert("Une erreur est survenue.")
  }
}

const trainModel = async () => {
  try {
    await axios.post('http://127.0.0.1:5005/intentions/train', {
      training_text: trainingText.value  // ✅ important !
    })
    alert("📚 Modèle IA entraîné avec succès.")
  } catch (e) {
    console.error("Erreur entraînement IA", e)
    alert("❌ Erreur lors de l'entraînement IA.")
  }
}

</script>

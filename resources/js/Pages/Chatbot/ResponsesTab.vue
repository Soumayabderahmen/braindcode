<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const responses = ref([])
const newResponse = ref('')
const editingId = ref(null)
const loading = ref(true)

onMounted(async () => {
  await fetchResponses()
})

const fetchResponses = async () => {
  try {
    const res = await axios.get('/admin/chatbot/responses')
    responses.value = res.data.responses
  } catch (e) {
    console.error('Erreur chargement des réponses prédéfinies', e)
  } finally {
    loading.value = false
  }
}

const saveResponse = async () => {
  if (!newResponse.value.trim()) return

  try {
    if (editingId.value) {
      await axios.put(`/admin/chatbot/responses/${editingId.value}`, {
        text: newResponse.value
      })
    } else {
      await axios.post('/admin/chatbot/responses', {
        text: newResponse.value
      })
    }
    await fetchResponses()
    newResponse.value = ''
    editingId.value = null
  } catch (e) {
    console.error('Erreur sauvegarde', e)
  }
}

const editResponse = (r) => {
  newResponse.value = r.text
  editingId.value = r.id
}

const deleteResponse = async (id) => {
  if (!confirm("Supprimer cette réponse ?")) return
  await axios.delete(`/admin/chatbot/responses/${id}`)
  await fetchResponses()
}
</script>

<template>
  <div>
    <div class="mb-4">
      <label class="font-medium">Nouvelle réponse</label>
      <textarea
        v-model="newResponse"
        rows="2"
        class="w-full p-2 border rounded mt-1"
        placeholder="Ex: Pour contacter un coach, cliquez sur le bouton support..."
      ></textarea>
      <button @click="saveResponse" class="mt-2 px-4 py-2 bg-blue-600 text-white rounded">
        {{ editingId ? 'Mettre à jour' : 'Ajouter' }}
      </button>
    </div>

    <div v-if="loading">Chargement...</div>
    <div v-else-if="responses.length === 0" class="text-gray-500">Aucune réponse enregistrée.</div>
    
    <ul class="space-y-3">
      <li
        v-for="r in responses"
        :key="r.id"
        class="border p-3 rounded flex justify-between items-center"
      >
        <span class="text-gray-800">{{ r.text }}</span>
        <div class="flex gap-2">
          <button @click="editResponse(r)" class="text-blue-600 hover:underline">Modifier</button>
          <button @click="deleteResponse(r.id)" class="text-red-600 hover:underline">Supprimer</button>
        </div>
      </li>
    </ul>
  </div>
</template>

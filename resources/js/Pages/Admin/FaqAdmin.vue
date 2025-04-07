<script setup>
import { ref, watch, computed } from 'vue'
import { router, useForm, usePage } from '@inertiajs/vue3'
import Main from '@/Layouts/Main.vue'

const flash = computed(() => usePage().props.flash || {})
const props = defineProps({
  faqs: Object
})

// Formulaire partagé pour création & édition
const form = useForm({
  id: null,            // ← Ajouté pour update
  question: '',
  answer: '',
  is_active: true
})

const editingFaq = ref(false) // ← Boolean flag plus simple

// ✏️ Commencer l'édition
const startEdit = (faq) => {
  editingFaq.value = true
  form.id = faq.id
  form.question = faq.question
  form.answer = faq.answer
}

// ❌ Annuler l'édition
const cancelEdit = () => {
  editingFaq.value = false
  form.reset()
  form.clearErrors()
}

// ✅ Soumettre un ajout
const submitFaq = () => {
  form.post('/admin/faqs', {
    preserveScroll: true,
    onSuccess: () => {
      form.reset()
    }
  })
}

// 🔁 Mise à jour d’une FAQ existante
const updateFaq = () => {
  if (!form.id) return
  form.put(`/admin/faqs/${form.id}`, {
    preserveScroll: true,
    onSuccess: () => {
      cancelEdit()
    }
  })
}

// 🗑️ Suppression
const deleteFaq = (id) => {
  if (confirm('Confirmer la suppression de cette FAQ ?')) {
    router.delete(`/admin/faqs/${id}`)
  }
}
</script>

<template>
  <Main>
    <div class="p-6">
      <!-- Alerte succès -->
      <div v-if="flash.success" class="bg-green-100 text-green-800 p-3 rounded mb-4">
  {{ flash.success }}
</div>
      <h1 class="text-2xl font-bold mb-4">Gestion des FAQs</h1>

      <!-- Formulaire Ajout / Modification -->
      <form @submit.prevent="editingFaq ? updateFaq() : submitFaq()" class="mb-6 bg-white p-4 rounded shadow">
        <div class="mb-4">
          <label class="block font-semibold mb-1">Question</label>
          <input v-model="form.question" class="w-full border p-2 rounded" type="text" />
          <div v-if="form.errors.question" class="text-red-600 text-sm">{{ form.errors.question }}</div>
        </div>

        <div class="mb-4">
          <label class="block font-semibold mb-1">Réponse</label>
          <textarea v-model="form.answer" class="w-full border p-2 rounded"></textarea>
          <div v-if="form.errors.answer" class="text-red-600 text-sm">{{ form.errors.answer }}</div>
        </div>
        <div class="mb-4">
  <label class="block font-semibold mb-1">
    <input type="checkbox" v-model="form.is_active" />
    Activer cette FAQ
  </label>
</div>
        <div class="flex gap-2">
          <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            {{ editingFaq ? 'Mettre à jour' : 'Ajouter' }}
          </button>
          <button v-if="editingFaq" type="button" @click="cancelEdit" class="text-gray-600 hover:underline">Annuler</button>
        </div>
      </form>

      <!-- Liste des FAQs -->
      <table class="w-full table-auto bg-white shadow rounded">
        <thead class="bg-gray-100 text-left">
          <tr>
            <th class="p-4">Question</th>
            <th class="p-4">Réponse</th>
            <th class="p-4">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="faq in faqs.data" :key="faq.id" class="border-t">
            <td class="p-4">{{ faq.question }}</td>
            <td class="p-4">{{ faq.answer }}</td>
            <td class="p-4 flex gap-2">
              <button @click="startEdit(faq)" class="text-blue-600 hover:underline">Modifier</button>
              <button @click="deleteFaq(faq.id)" class="text-red-600 hover:underline">Supprimer</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </Main>
</template>

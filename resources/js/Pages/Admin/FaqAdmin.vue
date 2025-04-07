<script setup>
import { ref } from 'vue'
import { useForm, router } from '@inertiajs/vue3'
import Main from '@/Layouts/Main.vue'
import { onMounted } from 'vue'

const props = defineProps({
  faqs: Array
})

const showModal = ref(false)
const editingFaq = ref(null)

const form = useForm({
  question: '',
  answer: '',
  is_active: true
})

const openModal = (faq = null) => {
  showModal.value = true
  if (faq) {
    editingFaq.value = faq
    form.question = faq.question
    form.answer = faq.answer
    form.is_active = faq.is_active
  } else {
    editingFaq.value = null
    form.reset()
    form.is_active = true
  }
}

const closeModal = () => {
  showModal.value = false
  form.reset()
  editingFaq.value = null
}

const submit = () => {
  if (editingFaq.value) {
    form.put(`/admin/faqs/${editingFaq.value.id}`, {
      onSuccess: closeModal
    })
  } else {
    form.post('/admin/faqs', {
      onSuccess: closeModal
    })
  }
}

const deleteFaq = (id) => {
  if (confirm('Supprimer cette FAQ ?')) {
    router.delete(`/admin/faqs/${id}`)
  }
}
</script>

<template>
  <Main>
    <div class="p-6">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Gestion des FAQs</h1>
        <button @click="openModal()" class="bg-blue-600 text-white px-4 py-2 rounded">Ajouter une FAQ</button>
      </div>

      <!-- Table moderne -->
      <table class="min-w-full table-auto border border-gray-200 rounded shadow">
        <thead class="bg-gray-100">
          <tr>
            <th class="p-4 text-left">Question</th>
            <th class="p-4 text-left">Réponse</th>
            <th class="p-4 text-left">Active</th>
            <th class="p-4 text-left">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="faq in faqs" :key="faq.id" class="border-t">
            <td class="p-4">{{ faq.question }}</td>
            <td class="p-4">{{ faq.answer }}</td>
            <td class="p-4">{{ faq.is_active ? 'Oui' : 'Non' }}</td>
            <td class="p-4">
              <button @click="openModal(faq)" class="text-blue-600 hover:underline">Modifier</button>
              <button @click="deleteFaq(faq.id)" class="text-red-600 hover:underline ml-4">Supprimer</button>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Modal -->
    <!-- Modal -->
    <div
  v-if="showModal"
  class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 transition-opacity">
  <div class="bg-white rounded-xl shadow-xl w-full max-w-3xl p-8 relative animate-fade-in">
    <button @click="closeModal" class="absolute top-4 right-4 text-gray-400 hover:text-black text-xl font-bold">
      &times;
    </button>

    <h2 class="text-2xl font-semibold mb-6 text-center">
      {{ editingFaq ? 'Modifier une FAQ' : 'Créer une nouvelle FAQ' }}
    </h2>

    <div class="grid grid-cols-1 gap-4">
      <div>
        <label class="block font-medium mb-1">Question</label>
        <input
          type="text"
          v-model="form.question"
          placeholder="Entrez la question"
          class="w-full border border-gray-300 rounded-md px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
        />
        <div v-if="form.errors.question" class="text-sm text-red-500 mt-1">
          {{ form.errors.question }}
        </div>
      </div>

      <div>
        <label class="block font-medium mb-1">Réponse</label>
        <textarea
          v-model="form.answer"
          placeholder="Entrez la réponse"
          class="w-full border border-gray-300 rounded-md px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
        ></textarea>
        <div v-if="form.errors.answer" class="text-sm text-red-500 mt-1">
          {{ form.errors.answer }}
        </div>
      </div>

      <div class="flex items-center space-x-2 mt-2">
        <input id="is_active" type="checkbox" v-model="form.is_active" class="h-4 w-4 text-blue-600 rounded focus:ring-blue-500" />
        <label for="is_active" class="text-sm font-medium">Activer cette FAQ</label>
      </div>
    </div>

    <div class="mt-6 flex justify-end space-x-3">
      <button
        @click="closeModal"
        class="px-4 py-2 border rounded-md text-gray-700 hover:bg-gray-100 transition"
      >
        Annuler
      </button>
      <button
        @click="submit"
        class="px-5 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition"
      >
        {{ editingFaq ? 'Mettre à jour' : 'Ajouter' }}
      </button>
    </div>
  </div>
</div>

    </div>
  </Main>
</template>

<style scoped>
table th, table td {
  font-size: 14px;
}
@keyframes fade-in {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
.animate-fade-in {
  animation: fade-in 0.3s ease-out;
}

</style>

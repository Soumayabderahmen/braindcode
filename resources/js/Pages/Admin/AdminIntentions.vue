<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import Main from '@/Layouts/Main.vue'

const intentions = ref([]);
const form = ref({ id: null, name: '', example_message: '', prompt_template: '' });

const fetchIntentions = async () => {
    const res = await axios.get('/admin/intentions-list');
    intentions.value = res.data.intentions;
};

const resetForm = () => {
  form.value = { id: null, name: '', example_message: '', prompt_template: '' };
};

const submitIntention = async () => {
  if (form.value.id) {
    await axios.put(`/admin/intentions/${form.value.id}`, form.value);
  } else {
      await axios.post('/admin/intentions', form.value);

  }
  await fetchIntentions();
  resetForm();
};

const editIntention = (intent) => {
  form.value = { ...intent };
};

const deleteIntention = async (id) => {
  if (confirm('Voulez-vous supprimer cette intention ?')) {
    await axios.delete(`/admin/intentions/${id}`);
   await fetchIntentions();
  }
};

onMounted(fetchIntentions);
</script>


<template>
    <Main>
    <div class="intentions-container">
      <h2 class="title">🎯 Gestion des intentions du chatbot</h2>
  
      <div class="intentions-card">
        <!-- Formulaire -->
        <form @submit.prevent="submitIntention">
          <div class="form-group">
            <label>Nom</label>
            <input v-model="form.name" type="text" placeholder="ex: probleme_etape" required />
          </div>
  
          <div class="form-group">
            <label>Exemple</label>
            <input v-model="form.example_message" type="text" placeholder="ex: j'ai un souci à l'étape 4" required />
          </div>
  
          <div class="form-group">
            <label>Prompt Template</label>
            <textarea v-model="form.prompt_template" rows="3" placeholder="ex: L'utilisateur a un souci à l'étape {{etape}}..." required></textarea>
          </div>
  
          <button class="submit-btn" type="submit">
            {{ form.id ? 'Mettre à jour' : 'Ajouter' }} l'intention
          </button>
        </form>
      </div>
  
      <!-- Liste des intentions -->
      <div class="intentions-list">
        <div v-for="intent in intentions" :key="intent.id" class="intent-card">
          <div class="intent-info">
            <h3>{{ intent.name }}</h3>
            <p><strong>Exemple:</strong> {{ intent.example_message }}</p>
            <p><strong>Template:</strong> {{ intent.prompt_template }}</p>
          </div>
  
          <div class="actions">
            <button class="edit-btn" @click="editIntention(intent)">
              ✏ Modifier
            </button>
            <button class="delete-btn" @click="deleteIntention(intent.id)">
              ❌ Supprimer
            </button>
          </div>
        </div>
      </div>
    </div>
</Main>
  </template>
  
 
  <style scoped>
  .intentions-container {
    max-width: 900px;
    margin: 40px auto;
    font-family: 'Poppins', sans-serif;
  }
  .title {
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 20px;
  }
  .intentions-card {
    background: #f9f9f9;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    margin-bottom: 30px;
  }
  .form-group {
    margin-bottom: 15px;
  }
  label {
    font-weight: 500;
    display: block;
    margin-bottom: 5px;
  }
  input, textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
  }
  .submit-btn {
    background: #007bff;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 600;
    transition: 0.3s;
  }
  .submit-btn:hover {
    background: #0056d2;
  }
  .intentions-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
  }
  .intent-card {
    border: 1px solid #eee;
    padding: 15px;
    border-radius: 8px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #fff;
  }
  .intent-info h3 {
    margin: 0 0 6px;
    font-size: 18px;
  }
  .intent-info p {
    margin: 4px 0;
    font-size: 13px;
  }
  .actions button {
    margin-left: 10px;
    border: none;
    padding: 6px 10px;
    font-size: 13px;
    border-radius: 5px;
    cursor: pointer;
  }
  .edit-btn {
    background: #ffc107;
    color: white;
  }
  .delete-btn {
    background: #dc3545;
    color: white;
  }
  </style>
  
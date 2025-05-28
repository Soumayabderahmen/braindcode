<template>
    <div class="row">
        <div class="col-12 mb-5">
            <div class="card card-1 cardDetailsAgent">
                <div class="card-body">
                    <img :src="'/storage/' + agent.image"
        alt="Agent IA" class="image">
                    <div>
                        <br>
                        <div class="title d-flex justify-content-between align-items-start">
                            
                            <h5>{{ agent.name }}</h5>
                            <div class="d-flex align-items-center">

                            <button @click="openEditModal" class="btn btn-primary">Modifier</button>

    <!-- Modal -->
    <div v-if="showModal" class="modal-overlay">
      <div class="modal-content">
        <button @click="showModal = false" class="close-btn">&times;</button>

        <form @submit.prevent="submitForm">
          <!-- Nom -->
          <div class="form-group">
            <label>Nom de l'agent</label>
            <input v-model="form.name" type="text" class="form-control" />
          </div>

          <!-- Domaine -->
          <div class="form-group">
            <label>Domaine</label>
            <input v-model="form.domain" type="text" class="form-control" />
          </div>

          <!-- Type -->
          <div class="form-group">
            <label>Type</label>
            <select v-model="form.type" class="form-control">
              <option value="public">Public</option>
              <option value="private">Privé</option>
            </select>
          </div>

          <!-- Description -->
          <div class="form-group">
            <label>Description</label>
            <textarea v-model="form.description" class="form-control"></textarea>
          </div>

          <!-- Image -->
          <div class="form-group">
            <label>Image de couverture</label>
            <img v-if="agent.image" :src="'/storage/' + agent.image" class="w-24 h-24 object-cover mb-2" />
            <input type="file" @change="uploadFile" class="form-control" />
          </div>

          <!-- Sections et Tâches -->
          <!-- Sections et Tâches -->
<div
  v-for="(section, index) in form.sections"
  :key="index"
  class="form-group border p-3 mb-3 rounded bg-light"
>
  <div class="d-flex justify-between align-items-center mb-2">
    <label class="mb-0">Sous-titre</label>
  </div>
  <input v-model="section.title" class="form-control mb-3" />

  <div
    v-for="(task, tIndex) in section.tasks"
    :key="tIndex"
    class="d-flex align-items-center mb-2"
  >
    <input v-model="section.tasks[tIndex].content" class="form-control me-2" />
    
  </div>

 
</div>

<!-- Ajouter Section -->



         

          <div class="text-right mt-4">
            <button type="submit" class="btn btn-success">Mettre à jour</button>
          </div>
        </form>
      </div>
    </div>
  </div>
                        </div>
                        <br>
                        <h6>{{ agent.domain }}</h6>
                        <br>
                        <p>{{ agent.description }}</p>

                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 mb-5">
            <div class="card card-1 cardTimeline">
                <div class="card-header">
                    <h5>Responsabilité</h5>
                </div>
                <div class="card-body">
                    <ul class="timeline mb-0">
                        <li v-for="(responsibility, index) in responsibilities" :key="index"
                            class="timeline-item timeline-item-transparent"style="
    margin-left: 26px;
">
                            <span class="timeline-point timeline-point-primary"></span>
                            <div class="timeline-event p-3"style="
    margin-left: -24px;
">
                                <div class="timeline-heet on the flightader mb-3">
                                    <h6 class="mb-0">{{ responsibility.title }}</h6>
                                </div>
                                <div class="content">
                                    <ul>
                                        <li v-for="(task, taskIndex) in responsibility.tasks" :key="taskIndex">
                                            <img src="/assets/img/dash/fleche.png" alt="">
                                            {{ task }}
                                        </li>
                                    </ul>

                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!--chat-->

    </div>

</template>
<script setup>
import 'bootstrap'
import '@popperjs/core'
import { ref , computed} from 'vue'
import axios from 'axios'

const props = defineProps({
  agent: Object
})
const agent = ref(props.agent) // pour mettre à jour dynamiquement

const showModal = ref(false)

const form = ref({})
const addSection = () => {
  form.value.sections.push({ title: '', tasks: [] });
};

const removeSection = (index) => {
  form.value.sections.splice(index, 1);
};



const removeTaskFromSection = (sectionIndex, taskIndex) => {
  form.value.sections[sectionIndex].tasks.splice(taskIndex, 1);
};

// Fonction pour ouvrir la modale et remplir le formulaire avec les données existantes
const openEditModal = () => {
  form.value = {
    name: props.agent.name,
    domain: props.agent.domain,
    description: props.agent.description,
    type: props.agent.type,
    image: null,
    sections: props.agent.sections?.map(s => ({
      id: s.id,
      title: s.title,
      tasks: s.tasks?.map(t => ({ id: t.id, content: t.content })) || []
    })) || []
  }
  showModal.value = true
}

// Fonction pour gérer l’upload du fichier image
const uploadFile = (event) => {
  const file = event.target.files[0]
  if (file) {
    form.value.image = file
  }
}



// Ajouter une tâche (responsabilité) à une section
const addTaskToSection = (index) => {
  form.value.sections[index].tasks.push('')
}

// Envoyer les données au backend Laravel via Axios
const submitForm = async () => {
  try {
    const formData = new FormData()
    formData.append('name', form.value.name)
    formData.append('domain', form.value.domain)
    formData.append('description', form.value.description)
    formData.append('type', form.value.type)

    if (form.value.image) {
      formData.append('image', form.value.image)
    }

    // ✅ Envoyer toute la structure sections/tasks en JSON
    formData.append('sections', JSON.stringify(form.value.sections))

    await axios.post(`/admin/updateAgent/${props.agent.id}`, formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    })

    await fetchAgent()
    showModal.value = false
    alert('✅ Agent mis à jour avec succès.')
  } catch (error) {
    console.error('❌ Erreur lors de la mise à jour :', error)
    alert('Une erreur est survenue.')
  }
}


// Liste des responsabilités à afficher
const responsibilities = ref([]) // 🔁 dynamique et modifiable

// initialise dès le début
responsibilities.value = agent.value.sections?.map(section => ({
  title: section.title,
  tasks: section.tasks?.map(task => task.content)
})) || []

const fetchAgent = async () => {
  try {
    const res = await axios.get(`/admin/api/agent/${props.agent.id}`)
    agent.value = res.data

    // ✅ Recalculer les responsabilités ici
    responsibilities.value = agent.value.sections?.map(section => ({
      title: section.title,
      tasks: section.tasks?.map(task => task.content)
    })) || []

    console.log("✅ agent rechargé :", agent.value)
  } catch (err) {
    console.error("❌ Erreur fetch agent :", err)
  }
}

</script>

<style scoped>

.modal-overlay {
  position: fixed;
  inset: 0;
  background-color: rgba(0, 0, 0, 0.4);
  backdrop-filter: blur(3px);
  overflow-y: auto;
  z-index: 9999;
  display: flex;
  justify-content: center;
  padding: 80px 20px; /* margin top + bottom pour centre visuel */
}

.modal-content {
  background-color: #f1faff;
  border-radius: 25px;
  padding: 40px;
  width: 100%;
  max-width: 700px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
  animation: fade-in 0.3s ease-in-out;
  position: relative;
  margin: auto;
}
.modal-title {
  font-size: 22px;
  font-weight: bold;
  text-align: center;
  color: #003366;
  margin-bottom: 25px;
}

.form-group {
  margin-bottom: 18px;
}

.form-group label {
  font-weight: 600;
  color: #003366;
  display: block;
  margin-bottom: 6px;
}

input,
textarea {
  width: 100%;
  padding: 12px 16px;
  border-radius: 12px;
  border: 1px solid #ccc;
  font-size: 14px;
  outline: none;
}

textarea {
  min-height: 90px;
  resize: none;
}

.image-upload {
  border: 2px dashed #3399ff;
  border-radius: 12px;
  text-align: center;
  padding: 20px;
  color: #3399ff;
  cursor: pointer;
  font-size: 14px;
}

.btn-blue {
  background-color: #007bff;
  color: white;
  padding: 10px 14px;
  border-radius: 10px;
  border: none;
}

.btn-submit {
  background-color: #0066cc;
  color: white;
  padding: 10px 30px;
  border-radius: 12px;
  font-weight: 600;
  border: none;
}

.close-btn {
  position: absolute;
  top: 14px;
  right: 18px;
  font-size: 24px;
  font-weight: bold;
  color: #999;
  background: none;
  border: none;
  cursor: pointer;
}

@keyframes fade-in {
  from {
    opacity: 0;
    transform: scale(0.96);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}



</style>
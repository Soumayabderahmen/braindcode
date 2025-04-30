<script setup>
import { ref, watch } from "vue";
import axios from "axios";
import { route } from "ziggy-js";

const props = defineProps({
  availabilities: Array,
  coachId: {
    type: [Number, String],
    required: true,
  },
});

const isEditing = ref(false);

// 🔹 Formulaire d'ajout
const form = ref({
  coach_id: props.coachId,
  date: "",
  start_time: "",
  end_time: "",
  statut: "available",
  day_of_week: "",
  honoraire: "",
  nb_place: "",
});

// 🔹 Formulaire d'édition
const editForm = ref({
  id: null,
  date: "",
  start_time: "",
  end_time: "",
  day_of_week: "",
  honoraire: "",
  nb_place: "",

});

// 🔹 Mise à jour coach_id dès qu’il est disponible
watch(
  () => props.coachId,
  (newCoachId) => {
    if (newCoachId) {
      form.value.coach_id = newCoachId;
      console.log("Coach ID mis à jour dans form :", newCoachId);
    }
  },
  { immediate: true }
);

// 🔹 Obtenir le jour de la semaine depuis une date
const getDayOfWeek = (date) => {
  const days = ["Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi"];
  return days[new Date(date).getDay()];
};

// 🔹 Réinitialiser le formulaire d’ajout sans toucher à coach_id
const resetForm = () => {
  form.value.date = "";
  form.value.start_time = "";
  form.value.end_time = "";
  form.value.statut = "available";
  form.value.day_of_week = "";
  form.value.honoraire = "";
  form.value.nb_place = "";
};

// 🔹 Réinitialiser le formulaire de modification
const resetEditForm = () => {
  editForm.value = {
    id: null,
    date: "",
    start_time: "",
    end_time: "",
    day_of_week: "",
    honoraire: "",
    nb_place: "",
  };
  isEditing.value = false;
};

// 🔹 Ajout d’une disponibilité
const submitAvailability = async () => {
  form.value.day_of_week = getDayOfWeek(form.value.date);

  if (!form.value.coach_id) {
    alert("Coach ID manquant !");
    console.error("Coach ID absent dans form :", form.value);
    return;
  }

  try {
    await axios.post('/coach/availability', form.value);
    alert("Disponibilité ajoutée !");
    resetForm();
    location.reload(); // optionnel : utiliser un événement ou props
  } catch (error) {
    if (error.response && error.response.data && error.response.data.errors) {
      console.error("Erreur de validation :", error.response.data.errors);
      alert("Erreur de validation : " + JSON.stringify(error.response.data.errors));
    } else {
      console.error("Erreur inconnue :", error);
      alert("Une erreur est survenue.");
    }
  }
};

// 🔹 Mise à jour du statut
const updateStatus = async (id, newStatus) => {
  if (!newStatus) {
    alert("Veuillez sélectionner un statut.");
    return;
  }
  try {

      const response = await axios.put(route("coach.availability.updateStatus",id), { statut: newStatus });
    alert("Statut mis à jour !");
  } catch (error) {
    console.error("Erreur mise à jour statut :", error);
    alert("Erreur lors de la mise à jour.");
  }
};

// 🔹 Suppression d’une disponibilité
const deleteAvailability = async (id) => {
  if (confirm("Voulez-vous supprimer cette disponibilité ?")) {
    try {
      await axios.delete(route("coach.availability.destroy", id));
      alert("Disponibilité supprimée.");
      location.reload(); // ou filtre localement
    } catch (error) {
      console.error("Erreur suppression :", error);
      alert("Erreur lors de la suppression.");
    }
  }
};

// 🔹 Pré-remplissage du formulaire d’édition
const editAvailability = (availability) => {
  isEditing.value = true;
  editForm.value = {
    id: availability.id,
    date: availability.date,
    start_time: availability.start_time,
    end_time: availability.end_time,
    day_of_week: getDayOfWeek(availability.date),
    honoraire: availability.honoraire,
    nb_place: availability.nb_place,
  };
};

const updateTimes = async () => {
  editForm.value.day_of_week = getDayOfWeek(editForm.value.date);

  const formatTime = (time) => time?.substring(0, 5) || null;

  const payload = {
    date: editForm.value.date,
    start_time: formatTime(editForm.value.start_time),
    end_time: formatTime(editForm.value.end_time),
    day_of_week: editForm.value.day_of_week,
    honoraire: editForm.value.honoraire,
    nb_place: editForm.value.nb_place,
  };

  try {
    const response = await axios.put(route('coach.availability.updateTimes', editForm.value.id), payload);    if (response.status === 200) {
      alert("Disponibilité modifiée !");
      resetEditForm();
      location.reload();
    }
  } catch (error) {
    console.error("Erreur modification :", error.response?.data || error);
    alert("Erreur lors de la modification.");
  }
};
</script>

<template>
          <div class="card rounded"
>
            <div class="card-body" >
    <div class="d-flex justify-content-center align-items-center " >
      <div class="card-body">
        <h2 class="card-title text-center mb-4"></h2>

        <!-- Formulaire d'ajout -->
        <form v-if="!isEditing" @submit.prevent="submitAvailability">
          <div class="row">
            <div class="col-mb-12 mb-3">
              <label class="form-label">Date</label>
              <input type="date" v-model="form.date" class="form-control" required />
            </div>

            <div class="col-md-12 mb-3">
              <label class="form-label">Heure de début</label>
              <input type="time" v-model="form.start_time" class="form-control" required />
            </div>
            <div class="col-md-12 mb-3">
              <label class="form-label">Heure de fin</label>
              <input type="time" v-model="form.end_time" class="form-control" required />
            </div>

            
            <div class="mb-3">
              <label class="form-label">Nombre de Place</label>
              <input type="number" step="0.01" v-model="form.nb_place" class="form-control" placeholder=" 20 personnes" />
            </div>
            <div class="mb-3">
              <label class="form-label">Honoraire (€)</label>
              <input type="number" step="0.01" v-model="form.honoraire" class="form-control" placeholder=" 50.00 €" />
            </div>
            <div class="col-md-12 mb-3" >
              <label class="form-label">Statut</label>
              <select v-model="form.statut" class="form-control" >
                <option value="available">Disponible</option>
                <option value="unavailable">Indisponible</option>
              </select>
            </div>

          </div>
          <div class="text-center">
            <button type="submit" class="btn btn-primary">Ajouter</button>
          </div>
        </form>

        <!-- Formulaire de modification -->
        <form v-else @submit.prevent="updateTimes">
          <div class="row">
            <div class="mb-12">
              <label class="form-label"><strong>Date</strong></label>
              <input type="date" v-model="editForm.date" class="form-control" />
            </div>

            <div class="col-md-12 mb-3">
              <label class="form-label">Heure de début</label>
              <input type="time" v-model="editForm.start_time" class="form-control"
                @input="editForm.start_time = $event.target.value">
            </div>
            <div class="col-md-12 mb-3">
              <label class="form-label">Heure de fin</label>
              <input type="time" v-model="editForm.end_time" class="form-control"
                @input="editForm.end_time = $event.target.value">
            </div>
            <div class="col-md-12 mb-3">
              <label class="form-label">Nombre de Place</label>
              <input type="number" step="0.01" v-model="editForm.nb_place" class="form-control"
                placeholder="20 personnes" />
            </div>
            <div class="col-md-12 mb-3">
              <label class="form-label">Honoraire (€)</label>
              <input type="number" step="0.01" v-model="editForm.honoraire" class="form-control"
                placeholder="Ex: 60.00" />
            </div>

          </div>

          <div class="text-center">
            <button type="submit" class="btn btn-warning">Modifier</button>
            <button type="button" class="btn btn-secondary ms-2" @click="isEditing = false">Annuler</button>
          </div>
        </form>
      </div>
    </div>
</div>
</div>
    <br />
   
    <br />
    <div class="card rounded"
>
            <div class="card-body" >
    <div class="d-flex justify-content-center align-items-center " >
      <div class="card-body">
       

        <table class="table-auto w-full border-collapse  " style="background-color: azure; margin-left: 44px;">
          <thead>
            <tr class="bg-gray-100" style="background-color: darkseagreen;">
              <th class="border p-2"><center>Date</center></th>
              <th class="border p-2"><center>Début</center></th>
              <th class="border p-2"><center>Fin</center></th>
              <th class="border p-2"><center>Jour de la semaine</center></th>
              <th class="border p-2"><center>Nombre de Place</center></th>

              <th class="border p-2"><center>Honoraire (€)</center></th>

              <th class="border p-2"><center>Statut</center></th>
              <th class="border p-3"><center>Actions</center></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="availability in props.availabilities" :key="availability.id" class="border border-gray-300">
              <td class="border border-gray-300 px-4 py-2">{{ availability.date }}</td>
              <td class="border border-gray-300 px-4 py-2">{{ availability.start_time }}</td>
              <td class="border border-gray-300 px-4 py-2">{{ availability.end_time }}</td>
              <td class="border border-gray-300 px-4 py-2">{{ availability.day_of_week }}</td>
              <td class="border border-gray-300 px-4 py-2">{{ availability.nb_place ?? '—' }}</td>

              <td class="border border-gray-300 px-4 py-2">{{ availability.honoraire ?? '—' }}</td>

              <!-- Affichage du jour -->

              <td class="border border-gray-300 px-6 py-4">
                <!-- Menu déroulant pour changer le statut -->
                <select v-model="availability.statut" @change="updateStatus(availability.id, availability.statut)"
                  class="form-select" required style="color: white;"
                  :class="availability.statut === 'available' ? 'badge bg-success' : 'badge bg-danger'">
                  <option :class="availability.statut === 'available' ? 'badge bg-success' : 'badge bg-danger'"
                    value="available">Disponible</option>
                  <option :class="availability.statut === 'unavailable' ? 'badge bg-success' : 'badge bg-danger'"
                    value="unavailable">Indisponible</option>
                </select>

              </td>


              <td>
                <button class="btn btn-warning btn-lm me-1" @click="editAvailability(availability)">
                  Modifier
                </button>
                <button class="btn btn-danger btn-sm" @click="deleteAvailability(availability.id)">
                  Supprimer
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
</div>
</div>
</template>
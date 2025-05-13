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
  titre: "",
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
  titre: "",

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
  form.value.titre = "";
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
    titre: "",
  };
  isEditing.value = false;
};

// 🔹 Ajout d’une disponibilité
const showSuccessAlert = ref(false);

const submitAvailability = async () => {
  form.value.day_of_week = getDayOfWeek(form.value.date);

  if (!form.value.coach_id) {
    alert("Coach ID manquant !");
    console.error("Coach ID absent dans form :", form.value);
    return;
  }

  try {
    await axios.post('/coach/availability', form.value);
    showSuccessAlert.value = true;
    showModal.value = true;
    resetForm();

    setTimeout(() => {
      showSuccessAlert.value = false;
              location.reload();

      // recharger seulement après avoir montré l’alerte
    }, 3000);
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
const showstatutAlert = ref(false);

// 🔹 Mise à jour du statut
const updateStatus = async (id, newStatus) => {
  if (!newStatus) {
    alert("Veuillez sélectionner un statut.");
    return;
  }
  try {

    const response = await axios.put(route("coach.availability.updateStatus", id), { statut: newStatus });
    showstatutAlert.value = true;
    setTimeout(() => {
      showstatutAlert.value = false;

    }, 3000);
  } catch (error) {
    console.error("Erreur mise à jour statut :", error);
    alert("Erreur lors de la mise à jour.");
  }
};
const showDangerAlert = ref(false);

// 🔹 Suppression d’une disponibilité
const deleteAvailability = async (id) => {
  if (confirm("Voulez-vous supprimer cette disponibilité ?")) {
    try {
      await axios.delete(route("coach.availability.destroy", id));
      showDangerAlert.value = true;
      setTimeout(() => {
        showDangerAlert.value = false;
        location.reload();
      }, 3000);

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
    titre: availability.titre,
  };
};
const showWarningAlert = ref(false);

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
    titre: editForm.value.titre,
  };

  try {
    const response = await axios.put(route('coach.availability.updateTimes', editForm.value.id), payload); if (response.status === 200) {
      showWarningAlert.value = true;
      resetEditForm();

      setTimeout(() => {
        showWarningAlert.value = false;
        location.reload(); // recharger la page après 3 secondes
        resetEditForm();
      }, 3000);
    }
  } catch (error) {
    console.error("Erreur modification :", error.response?.data || error);
    alert("Erreur lors de la modification.");
  }
};
</script>

<template>
  <div v-if="showDangerAlert" class="alert alert-danger text-center" role="alert"
    style="position: fixed; top: 20px; left: 50%; transform: translateX(-50%); z-index: 9999; width: 50%;">
    Disponibilité a étè supprimer avec success !
  </div>
  <div v-if="showSuccessAlert" class="alert alert-success text-center" role="alert"
    style="position: fixed; top: 20px; left: 50%; transform: translateX(-50%); z-index: 9999; width: 50%;">
    ✅ Disponibilité ajoutée avec succès !
  </div>
  <div v-if="showWarningAlert" class="alert alert-warning text-center" role="alert"
    style="position: fixed; top: 20px; left: 50%; transform: translateX(-50%); z-index: 9999; width: 50%;">
    ✅ Disponibilité mis à jour avec succès !
  </div>
  <div v-if="showstatutAlert" class="alert alert-warning text-center" role="alert"
    style="position: fixed; top: 20px; left: 50%; transform: translateX(-50%); z-index: 9999; width: 50%;">
    ✅ Statut mis à jour avec succès !
  </div>
  <div class="mt-3  formulaire">
    <div class="col-12">
      <div class="card card-1">
        <div class="card-body px-lg-4 px-md-3 px-2">
          <div class="bs-stepper wizard-numbered shadow-none mt-2">

            <!-- Formulaire d'ajout -->
            <form v-if="!isEditing" @submit.prevent="submitAvailability">
              <div class="row">
                <div class="mb-3">
                  <label class="form-label">Titre de Formation</label>
                  <input type="text" step="0.01" v-model="form.titre" class="form-control"
                    placeholder=" Coaching Stratégique" />
                </div>
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
                  <input type="number" step="0.01" v-model="form.nb_place" class="form-control"
                    placeholder=" 20 personnes" />
                </div>
                
                <div class="mb-3">
                  <label class="form-label">Honoraire (€)</label>
                  <input type="number" step="0.01" v-model="form.honoraire" class="form-control"
                    placeholder=" 50.00 €" />
                </div>
                <div class="col-md-12 mb-3">
                  <label class="form-label">Statut</label>
                  <select v-model="form.statut" class="form-control">
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
  </div>
  <br />

  <br />

  <div class="card-header d-lg-flex d-md-flex d-sm-flex d-block">


  </div>
  <div class="card-body">
    <div class="flex flex-col gap-4 mt-4">
      <div class="flex items-center px-6 py-3 font-semibold text-sm" style=" border-radius: 12px;">

        <div class="w-1/6 text-center" style="color: #005183;"><b>Titre de Formation</b></div>
        <div class="w-1/5 text-center" style="color: #005183;"><b>Date</b></div>
        <div class="w-1/6 text-center" style="color: #005183;"><b>Début</b></div>
        <div class="w-1/6 text-center" style="color: #005183;"><b>Jour de la semaine</b></div>
        <div class="w-1/6 text-center" style="color: #005183;"><b>Nombre de Place</b></div>
        <div class="w-1/6 text-center" style="color: #005183;"><b>Honoraire (€)</b></div>

        <div class="w-1/5 text-center" style="color: #005183;"><b>Statut</b></div>
        <div class="w-1/6 text-center" style="color: #005183;"><b>Actions</b></div>

      </div>
          <div v-for="availability in props.availabilities" :key="availability.id"
            class="flex items-center justify-between bg-white rounded-xl  px-6 py-6 hover:shadow-md transition" style="
    height: 70px;
    background: var(--Color, #FFF);
    box-shadow: 10px 8px 20px 0px rgba(0, 81, 131, 0.25);
    border-radius: 15px;
">
 <div class="w-28 text-center text-sm " style="
    color: #0093EE;
">
              {{ availability.titre ?? '—' }}
            </div>
            <div class="w-28 text-center text-sm " style="
    color: #0093EE;
">
              {{ availability.date }}
            </div>
           

            <div class="w-28 text-center text-sm " style="
    color: #0093EE;
">
              {{ availability.start_time }}
            </div>
            
            <div class="w-28 text-center text-sm " style="
    color: #0093EE;
">
              {{ availability.day_of_week }}
            </div>
            <div class="w-28 text-center text-sm " style="
    color: #0093EE;
">
              {{ availability.nb_place ?? '—' }}
            </div>
           
            <div class="w-28 text-center text-sm " style="
    color: #0093EE;
">
              {{ availability.honoraire ?? '—' }}
            </div>
            <div class="w-28 text-center" style="
    width: 141px;
">
              <select v-model="availability.statut" @change="updateStatus(availability.id, availability.statut)"
                class="form-select" required style="color: white;"
                :class="availability.statut === 'available' ? 'badge bg-success' : 'badge bg-danger'">
                <option :class="availability.statut === 'available' ? 'badge bg-success' : 'badge bg-danger'"
                  value="available">Disponible</option>
                <option :class="availability.statut === 'unavailable' ? 'badge bg-success' : 'badge bg-danger'"
                  value="unavailable">Indisponible</option>
              </select>
</div>
<div class="w-28 text-center">
  <div class="flex justify-center items-center gap-2">
    <button class="btn btn-secondary btn-sm" @click="editAvailability(availability)">
      ✍️
    </button>
    <button class="btn btn-secondary btn-sm" @click="deleteAvailability(availability.id)">
      ❌
    </button>
  </div>
</div>

            


          </div>
         
      </div>
    </div>


</template>
<style>
.modal-content {
  -webkit-border-radius: 0;
  -webkit-background-clip: padding-box;
  -moz-border-radius: 0;
  -moz-background-clip: padding;
  border-radius: 6px;
  background-clip: padding-box;
  -webkit-box-shadow: 0 0 40px rgba(0, 0, 0, .5);
  -moz-box-shadow: 0 0 40px rgba(0, 0, 0, .5);
  box-shadow: 0 0 40px rgba(0, 0, 0, .5);
  color: #000;
  background-color: #fff;
  border: rgba(0, 0, 0, 0);
}

.modal-message .modal-dialog {
  width: 300px;
}

.modal-message .modal-body,
.modal-message .modal-footer,
.modal-message .modal-header,
.modal-message .modal-title {
  background: 0 0;
  border: none;
  margin: 0;
  padding: 0 20px;
  text-align: center !important;
}

.modal-message .modal-title {
  font-size: 17px;
  color: #737373;
  margin-bottom: 3px;
}

.modal-message .modal-body {
  color: #737373;
}

.modal-message .modal-header {
  color: #fff;
  margin-bottom: 10px;
  padding: 15px 0 8px;
}

.modal-message .modal-header .fa,
.modal-message .modal-header .glyphicon,
.modal-message .modal-header .typcn,
.modal-message .modal-header .wi {
  font-size: 30px;
}

.modal-message .modal-footer {
  margin: 25px 0 20px;
  padding-bottom: 10px;
}

.modal-backdrop.in {
  zoom: 1;
  filter: alpha(opacity=75);
  -webkit-opacity: .75;
  -moz-opacity: .75;
  opacity: .75;
}

.modal-backdrop {
  background-color: #fff;
}

.modal-message.modal-success .modal-header {
  color: #53a93f;
  border-bottom: 3px solid #a0d468;
}
</style>
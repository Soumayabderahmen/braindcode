<script setup>
import { ref } from "vue";
import { useForm, router } from "@inertiajs/vue3";
import Main from "../../Layouts/main.vue";
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';

const props = defineProps({
  availabilities: Array,
  coachId: Number, // ID du coach
});
const getDayOfWeek = (date) => {
  const days = ["Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi"];
  const d = new Date(date);
  return days[d.getDay()];
};
// Formulaire d'ajout d'une disponibilité
const form = useForm({
  coach_id: props.coachId,
  date: "",
  start_time: "",
  end_time: "",
  statut: "available",
  day_of_week: "",
  honoraire: "",
});

// Formulaire de modification
const editForm = useForm({
  id: null,
  date: "",
  start_time: "",
  end_time: "",
  day_of_week: "",
  honoraire: "",
});

// Indicateur d'édition
const isEditing = ref(false);

// Ajouter une disponibilité
const submitAvailability = () => {
  form.day_of_week = getDayOfWeek(form.date);
  form.post(route("coach.availability.store"), {
    onSuccess: () => {
      form.reset(); // Réinitialisation après succès
    }
  });
};

const updateStatus = (id, newStatus) => {
  if (!newStatus) {
    alert("Veuillez sélectionner un statut.");
    return; // Arrêter l'exécution si le statut est manquant
  }

  const statusForm = useForm({
    statut: newStatus,
  });

  statusForm.put(route("coach.availability.updateStatus", id), {
    onSuccess: () => {
      alert("Statut mis à jour avec succès !");
    },
    onError: (errors) => {
      console.error("Erreur de mise à jour :", errors);
      alert("Une erreur s'est produite lors de la mise à jour du statut.");
    },
  });
};

// Supprimer une disponibilité
const deleteAvailability = (id) => {
  if (confirm("Voulez-vous supprimer cette disponibilité ?")) {
    router.delete(route("coach.availability.destroy", id), {
      onSuccess: () => {
        console.log("Disponibilité supprimée avec succès");
      },
    });
  }
};


// Préparer le formulaire de modification
const editAvailability = (availability) => {
  isEditing.value = true;
  editForm.id = availability.id;
  editForm.date = availability.date;
  editForm.start_time = availability.start_time;
  editForm.end_time = availability.end_time;
  editForm.day_of_week = getDayOfWeek(availability.date);
  editForm.honoraire = availability.honoraire;

};

// Mettre à jour la disponibilité (uniquement date et heures)
const updateTimes = () => {
  // Fonction pour formater correctement l'heure
  const formatTimeForBackend = (time) => {
    if (!time) return null;
    // Si l'heure est au format HH:MM
    if (time.length === 5) return time;
    // Si c'est un input time HTML (HH:MM)
    if (time.length === 8) return time.substring(0, 5);
    return null;
  };
  editForm.day_of_week = getDayOfWeek(editForm.date);
  const payload = {
    date: editForm.date || null,
    start_time: formatTimeForBackend(editForm.start_time),
    end_time: formatTimeForBackend(editForm.end_time),
    day_of_week: editForm.day_of_week,
    honoraire: editForm.honoraire,
  };

  editForm.put(route("coach.availability.updateTimes", editForm.id), {
    data: payload,
    onSuccess: () => {
      alert("Disponibilité mise à jour avec succès !");
      isEditing.value = false;
      editForm.reset();
    }
  });
};
</script>

<template>
  <Main>
    <div class="d-flex justify-content-center align-items-center " style="margin-left:206px">
      <div class="card-body">
        <h2 class="card-title text-center mb-4">Gérer mes disponibilités</h2>

        <!-- Formulaire d'ajout -->
        <form v-if="!isEditing" @submit.prevent="submitAvailability">
          <div class="row">
            <div class="mb-12">
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
              <label class="form-label">Statut</label>
              <select v-model="form.statut" class="form-select">
                <option value="available">Disponible</option>
                <option value="unavailable">Indisponible</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Honoraire (€)</label>
              <input type="number" step="0.01" v-model="form.honoraire" class="form-control" placeholder="Ex: 50.00" />
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
              <label class="form-label">Date</label>
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

    <br />
    <hr style="margin-left: 184px;" />
    <div class="d-flex justify-content-center align-items-center " style="margin-left:206px">
      <div class="card-body">
        <h2 class="card-title text-center mb-4">Mes disponibilités</h2>

        <table class="table-auto w-full border-collapse border border-gray-200" style="margin-right: 260px;">
          <thead>
            <tr class="bg-gray-100">
              <th class="border p-2">Date</th>
              <th class="border p-2">Début</th>
              <th class="border p-2">Fin</th>
              <th class="border p-2">Jour de la semaine</th>
              <th class="border p-2">Honoraire (€)</th>

              <th class="border p-2">Statut</th>
              <th class="border p-2">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="availability in props.availabilities" :key="availability.id" class="border border-gray-300">
              <td class="border border-gray-300 px-4 py-2">{{ availability.date }}</td>
              <td class="border border-gray-300 px-4 py-2">{{ availability.start_time }}</td>
              <td class="border border-gray-300 px-4 py-2">{{ availability.end_time }}</td>
              <td class="border border-gray-300 px-4 py-2">{{ availability.day_of_week }}</td>
              <td class="border border-gray-300 px-4 py-2">{{ availability.honoraire ?? '—' }}</td>

              <!-- Affichage du jour -->

              <td class="border border-gray-300 px-6 py-4">
                <!-- Menu déroulant pour changer le statut -->
                <select v-model="availability.statut" @change="updateStatus(availability.id, availability.statut)"
                  class="form-select" required
                  :class="availability.statut === 'available' ? 'badge bg-success' : 'badge bg-danger'">
                  <option :class="availability.statut === 'available' ? 'badge bg-success' : 'badge bg-danger'"
                    value="available">Disponible</option>
                  <option :class="availability.statut === 'unavailable' ? 'badge bg-success' : 'badge bg-danger'"
                    value="unavailable">Indisponible</option>
                </select>

              </td>


              <td>
                <button class="btn btn-warning btn-sm me-2" @click="editAvailability(availability)">
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
  </Main>
</template>
<script setup>
import { ref, defineProps } from "vue";
import Main from "../../Layouts/main.vue";
import FullCalendar from "@fullcalendar/vue3";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import interactionPlugin from "@fullcalendar/interaction";
import { router } from "@inertiajs/vue3";

const props = defineProps({
  coach: Object,
  availabilities: Array,
  coachs: Object,

  tabs: {
    type: Array,
    default: () => [
      { name: "ABOUT", url: "" },
      { name: "PHOTOS", url: "" },
      { name: "🗓️ CALENDER", url: "" },
    ],
  },
});
const calendarOptions = ref({
  plugins: [dayGridPlugin, timeGridPlugin],
  initialView: "dayGridMonth",
  headerToolbar: {
    left: "prev,next today",
    center: "title",
    right: "dayGridMonth,timeGridWeek,timeGridDay",
  },
  events: props.availabilities
    .filter(avail => avail !== null)
    .map((avail) => ({
      id: avail.id,
      title: avail.statut === "available" ? "Disponible" : "Indisponible",
      start: `${avail.date}T${avail.start_time}`,
      end: `${avail.date}T${avail.end_time}`,
      color: avail.statut === "available" ? "#28a745" : "#dc3545",
      extendedProps: {
        statut: avail.statut,
      },
    })),

  editable: false,
  selectable: false,
  eventStartEditable: false,
  eventDurationEditable: false,
  displayEventTime: true,
});
console.log(props.availabilities)
const activeTab = ref("ABOUT");
</script>

<template>
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div id="content" class="content content-full-width">
            <div class="profile">
              <!-- Section de couverture -->
              <div class="profile-header">
                <div class="profile-header-cover">
                  <img
                    :src="coach.coach?.cover_image ? `/storage/${coach.coach.cover_image}` : 'https://bootdey.com/img/Content/avatar/avatar3.png'"
                    alt="Cover Image" class="cover-image" />
                </div>

                <div class="profile-header-content">
                  <div class="profile-header-img">
                    <img
                      :src="coach.coach?.profile_image ? `/storage/${coach.coach.profile_image}` : 'https://bootdey.com/img/Content/avatar/avatar3.png'"
                      alt="Coach Avatar" class="avatar-image" />
                  </div>
                  <div class="profile-header-info">
                    <h4 class="m-t-12 m-b-10">{{ coach.name }}</h4>
                    <h4 class="m-b-10">Spécialité : {{ coach.specialty }}</h4>
                  </div>
                </div>

                <!-- Onglets -->
                <ul class="profile-header-tab nav nav-tabs">
                  <li class="nav-item" v-for="tab in tabs" :key="tab.name">
                    <a href="#" class="nav-link" :class="{ 'active show': activeTab === tab.name }"
                      @click.prevent="activeTab = tab.name">
                      {{ tab.name }}
                    </a>
                  </li>
                </ul>
              </div>

              <!-- Contenu conditionnel -->
              <div class="p-4">
  <!-- Onglet Calendrier -->
  <div v-if="activeTab === '🗓️ CALENDER'" class="text-center text-xl font-semibold">
    🎉 Bienvenue sur Mon Calendrier 🎉

    <!-- Vérifie s'il n'y a pas d'événements -->
    <div v-if="calendarOptions.events.length === 0" class="mt-4 text-red-500">
      ❌ Aucune disponibilité pour le moment.
    </div>

    <!-- Affichage du calendrier si des événements existent -->
    <div v-else class="card" style="margin-right: -63px;">
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <div id="right">
              <div class="card-body">
                <FullCalendar :options="calendarOptions" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Onglet À propos -->
  <div v-else-if="activeTab === 'ABOUT'" class="text-center text-xl font-semibold">
    <section class="section about-section gray-bg" id="about" style="margin-right: -19px; margin-bottom: 265px;">
      <div class="row align-items-center flex-row-reverse" style="margin-left: 643px;">
        <div class="col-lg-6" style="margin-left: -180px;">
          <div class="about-text go-to">
            <h3 class="dark-color">📌 À propos de Moi 📌</h3><br/>
            <h6 class="theme-color lead">Je suis un coach dans le domaine d'{{ coach.specialty }}</h6>
            <!-- Affiche la description du coach ou un texte par défaut -->
            <p v-if="coach.description">{{ coach.description }}</p>
            <p v-else class="mt-2 text-gray-600">Ce coach n'a pas encore ajouté de description. 😊</p>
          </div>
        </div>
        <div class="col-lg-6" >
          <div class="about-avatar" style="margin-left: -605px;margin-top: -176px;">
            <img
                    :src="coach.coach?.cover_image ? `/storage/${coach.coach.cover_image}` : 'https://bootdey.com/img/Content/avatar/avatar7.png'"
                    title="" alt="" />
          </div>
        </div>
      </div>
    </section>
  </div>

  <!-- Contenu des autres onglets -->
  <div v-else>
    <p class="text-gray-600">Contenu de l'onglet {{ activeTab }}</p>
  </div>


              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
</template>


<style scoped>
.profile-header {
  position: relative;
  overflow: hidden;
  margin-bottom: 20px;
}

.profile-header-cover {
  width: 100%;
  height: 200px;
  /* Ajuste la hauteur de la couverture */
  position: relative;
  overflow: hidden;
}

.cover-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  object-position: center;
}

.profile-header-content {
  color: #fff;
  padding: 25px;
  position: relative;
  z-index: 2;
}

.profile-header-img {
  float: left;
  width: 120px;
  height: 120px;
  overflow: hidden;
  position: relative;
  z-index: 10;
  margin: 0 0 -20px;
  padding: 3px;
  border-radius: 4px;
  background: #626466;
  border: -8px solid #fff;
}

.avatar-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 50%;
}

.profile-header-info h4 {
  font-weight: 500;
  color: black;
}

.profile-header-img+.profile-header-info {
  margin-left: 140px;
  color: black;

}

.profile-header .profile-header-tab {
  background: dimgrey;
  list-style-type: none;
  margin: -10px 0 0;
  padding: 0 0 0 140px;
  white-space: nowrap;
  border-radius: 0;
}

.profile-header .profile-header-tab>li {
  display: inline-block;
  margin: 0;
}

.profile-header .profile-header-tab>li>a {
  display: block;
  color: #929ba1;
  line-height: 20px;
  padding: 10px 20px;
  text-decoration: none;
  font-weight: 700;
  font-size: 12px;
  border: none;
}

.profile-header .profile-header-tab>li.active>a,
.profile-header .profile-header-tab>li>a.active {
  color: #242a30;
}

.calendar-container {
  max-width: 900px;
  margin: auto;
  padding: 20px;
  background: white;
  border-radius: 10px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

:deep(.fc-toolbar) {
  background: #34495F;
  color: white;
  border-radius: 8px;
  padding: 10px;
}

:deep(.fc-button) {
  background: #2980b9 !important;
  border: none !important;
  color: white !important;
}

:deep(.fc-daygrid-day) {
  border: 1px solid #ddd;
}

:deep(.fc-daygrid-day-number) {
  color: #2c3e50;
  font-weight: bold;
}
.about-text h3 {
  font-size: 45px;
  font-weight: 700;
  margin: 0 0 6px;
}
@media (max-width: 767px) {
  .about-text h3 {
    font-size: 35px;
  }
}
.about-text h6 {
  font-weight: 600;
  margin-bottom: 15px;
}
@media (max-width: 767px) {
  .about-text h6 {
    font-size: 18px;
  }
}
.about-text p {
  font-size: 18px;
  max-width: 450px;
}
.about-text p mark {
  font-weight: 600;
  color: #20247b;
}

.about-list {
  padding-top: 10px;
}
.about-list .media {
  padding: 5px 0;
}
.about-list label {
  color: #20247b;
  font-weight: 600;
  width: 88px;
  margin: 0;
  position: relative;
}
.about-list label:after {
  content: "";
  position: absolute;
  top: 0;
  bottom: 0;
  right: 11px;
  width: 1px;
  height: 12px;
  background: #20247b;
  -moz-transform: rotate(15deg);
  -o-transform: rotate(15deg);
  -ms-transform: rotate(15deg);
  -webkit-transform: rotate(15deg);
  transform: rotate(15deg);
  margin: auto;
  opacity: 0.5;
}
.about-list p {
  margin: 0;
  font-size: 15px;
}

@media (max-width: 991px) {
  .about-avatar {
    margin-top: 30px;
  }
}

.about-section .counter {
  padding: 22px 20px;
  background: #ffffff;
  border-radius: 10px;
  box-shadow: 0 0 30px rgba(31, 45, 61, 0.125);
}
.about-section .counter .count-data {
  margin-top: 10px;
  margin-bottom: 10px;
}
.about-section .counter .count {
  font-weight: 700;
  color: #20247b;
  margin: 0 0 5px;
}
.about-section .counter p {
  font-weight: 600;
  margin: 0;
}
mark {
    background-image: linear-gradient(rgba(252, 83, 86, 0.6), rgba(252, 83, 86, 0.6));
    background-size: 100% 3px;
    background-repeat: no-repeat;
    background-position: 0 bottom;
    background-color: transparent;
    padding: 0;
    color: currentColor;
}
.theme-color {
    color: #fc5356;
}
.dark-color {
    color: #20247b;
}
body{
    color: #6F8BA4;
    margin-top:20px;
}
.section {
    padding: 100px 0;
    position: relative;
}
.gray-bg {
    background-color: #f5f5f5;
}
img {
    max-width: 100%;
}
img {
    vertical-align: middle;
    border-style: none;
}
</style>

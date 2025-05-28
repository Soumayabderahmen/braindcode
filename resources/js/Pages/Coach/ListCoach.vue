<script setup>
import { ref ,defineProps} from "vue";

const props = defineProps({
  coachs: Array,
});
const getCoachId = (id) => {
  // Tu peux ici effectuer une action avec l'ID, comme la redirection vers une autre page
  console.log("Coach ID:", id);
  // Exemple de redirection vers la page de profil du coach
  window.location.href = `/profile/Coach/${id}`;
};

</script>
<template>
    <div class="px-6 pb-4 flex justify-between items-center">
    <div
      class="col-lg-8 col-md-8 col-sm-8 col-12 d-lg-flex d-md-flex d-sm-flex d-block justify-content-start align-items-center">
      <div class="input-group me-8 mb-4 " style="width: 247px;">
        <span class="input-group-text">
          <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 19 19" fill="none">
            <path
              d="M17.8749 18L12.4244 12.3333M1.52344 7.61111C1.52344 8.47929 1.68792 9.33898 2.00748 10.1411C2.32705 10.9432 2.79544 11.672 3.38592 12.2859C3.9764 12.8998 4.6774 13.3867 5.4489 13.719C6.22039 14.0512 7.04728 14.2222 7.88234 14.2222C8.71741 14.2222 9.54429 14.0512 10.3158 13.719C11.0873 13.3867 11.7883 12.8998 12.3788 12.2859C12.9692 11.672 13.4376 10.9432 13.7572 10.1411C14.0768 9.33898 14.2412 8.47929 14.2412 7.61111C14.2412 6.74293 14.0768 5.88325 13.7572 5.08115C13.4376 4.27905 12.9692 3.55025 12.3788 2.93635C11.7883 2.32245 11.0873 1.83548 10.3158 1.50324C9.54429 1.171 8.71741 1 7.88234 1C7.04728 1 6.22039 1.171 5.4489 1.50324C4.6774 1.83548 3.9764 2.32245 3.38592 2.93635C2.79544 3.55025 2.32705 4.27905 2.00748 5.08115C1.68792 5.88325 1.52344 6.74293 1.52344 7.61111Z"
              stroke="#C6C6C6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </span>
        <input v-model="search" type="text" class="form-control  " placeholder="Rechercher">
      </div>
      <div class="d-lg-flex d-md-flex d-sm-flex d-block">
        <select class="form-select mb-4 me-8" style="
    border-radius: 15px;
    background: var(--Color, #FFF);
    box-shadow: 10px 8px 20px 0px rgba(0, 81, 131, 0.25);
    padding: 13px 35px;
    width: 180px;
">

          <option value="">

            🕒
            Domaines</option>
          <!-- <option v-for="r in [...new Set(props.reservations.map(res => res.meeting_time))]" :key="r" :value="r">
            {{ r }}
          </option> -->
        </select>

        <select  class="form-select mb-4" style="
    border-radius: 15px;
    background: var(--Color, #FFF);
    box-shadow: 10px 8px 20px 0px rgba(0, 81, 131, 0.25);
    padding: 13px 35px;
    width: 213px;
">
          <option value="">⏳ Compétances</option>
          <!-- <option v-for="r in [...new Set(props.reservations.map(res => res.duration))]" :key="r" :value="r">
            {{ r }} minutes
          </option> -->
        </select>
      </div>
    </div>

    <!-- <span class="text-sm text-gray-500 ml-4">{{ filteredReservations.length }} résultat(s)</span> -->
  </div>
      <div class="col-12 px-lg-4 px-md-3 px-2" style="
    border-radius: 15px;
    background: var(--Color, #FFF);
    box-shadow: 10px 8px 20px 0px rgba(0, 81, 131, 0.25);
    padding: 13px 35px;
">
          <div class="bs-stepper wizard-numbered shadow-none mt-2" >
    <!-- 🧾 Carte englobante principale -->

      <div class="d-flex flex-column gap-4">
        <div
          v-for="coach in coachs"
          :key="coach.id"
class="p-4 bg-white rounded shadow-sm d-flex flex-column flex-md-row gap-4 align-items-start coach-card"
        style="
    border-radius: 15px;
    background: var(--Color, #FFF);
    box-shadow: 10px 8px 20px 0px rgba(0, 81, 131, 0.25);
    padding: 13px 35px;
" >
          <!-- Image -->
          <img
            :src="coach.coach?.profile_image ? `/storage/${coach.coach.profile_image}` : '/image/default-user.png'"
            alt="Photo Coach"
            class="rounded-circle object-fit-cover"
            style="width: 70px; height: 70px;"
          />

          <!-- Contenu -->
          <div class="flex-grow-1">
            <h6 class="fw-bold text-primary mb-2" style="font-size: 15px;">
              {{ coach.name }} - {{ coach.specialty }}
            </h6>

            <p class="mb-2" style="font-size: 14px;">
              {{ coach.coach?.description || 'Coach expérimenté dans son domaine, accompagnant les startups vers le succès.' }}
            </p>

            <p class="mb-2" style="font-size: 14px;">
              <strong class="text-dark">Email :</strong> {{ coach.email }}<br />
              <strong class="text-dark">Téléphone :</strong> {{ coach.phone_number }}
            </p>

            <button class="btn btn-primary btn-sm px-4" @click="getCoachId(coach.id)" style="
    margin-left: 912px;
">
              Voir profil
            </button>
          </div>
        </div>
      </div>

  </div>
  </div>
</template>



  

  
  
<style>
.coach-card {
  box-shadow: 0px 4px 20px rgba(0, 147, 238, 0.1);
  transition: transform 0.2s ease;
}

.coach-card:hover {
  transform: translateY(-2px);
}

.body{
    margin-top:20px;
    background:#f3f3f3;
}
.row {
    display: flex;
  flex-wrap: wrap; /* Permet aux cartes de s'ajuster si l'écran est petit */
  justify-content: center;
  gap: 10px;
}
.container {
    margin-top: 20px;
  }
  .user-card {
    width: 100%;
    max-width: 300px;
  }
.card.user-card {
    border-top: none;
  box-shadow: 0 0 1px 2px rgba(0, 0, 0, 0.05);
  transition: all 150ms linear;
}

.card {
    border-radius: 5px;
  box-shadow: 0 1px 2.94px 0.06px rgba(4, 26, 55, 0.16);
  border: none;
  transition: all 0.3s ease-in-out;
}

.card .card-header {
    background-color: transparent;
    border-bottom: none;
    padding: 20px;
}

.card .card-header h5 {
    margin-bottom: 0;
    color: #222;
    font-size: 14px;
    font-weight: 600;
    display: inline-block;
    margin-right: 10px;
    line-height: 1.4;
}

.card .card-header+.card-block, .card .card-header+.card-block-big {
    padding-top: 0;
}

.user-card .card-block {
    text-align: center;
}

.card .card-block {
    padding: 25px;
}

.user-card .card-block .user-image {
    position: relative;
    margin: 0 auto;
    display: inline-block;
    padding: 5px;
    width: 110px;
    height: 110px;
}

.user-card .card-block .user-image img {
    z-index: 20;
    position: absolute;
    top: 5px;
    left: 5px;
        width: 100px;
    height: 100px;
}

.img-radius {
    border-radius: 50%;
}

.f-w-600 {
    font-weight: 600;
}

.m-b-10 {
    margin-bottom: 10px;
}

.m-t-25 {
    margin-top: 25px;
}

.m-t-15 {
    margin-top: 15px;
}

.card .card-block p {
    line-height: 1.4;
}

.text-muted {
    color: #919aa3!important;
}

.user-card .card-block .activity-leval li.active {
    background-color: #2ed8b6;
}

.user-card .card-block .activity-leval li {
    display: inline-block;
    width: 15%;
    height: 4px;
    margin: 0 3px;
    background-color: #ccc;
}

.user-card .card-block .counter-block {
    color: #fff;
}

.bg-c-blue {
    background: linear-gradient(45deg,#4099ff,#73b4ff);
}

.bg-c-green {
    background: linear-gradient(45deg,#2ed8b6,#59e0c5);
}

.bg-c-yellow {
    background: linear-gradient(45deg,#FFB64D,#ffcb80);
}

.bg-c-pink {
    background: linear-gradient(45deg,#FF5370,#ff869a);
}

.m-t-10 {
    margin-top: 10px;
}

.p-20 {
    padding: 20px;
}

.user-card .card-block .user-social-link i {
    font-size: 30px;
}

.text-facebook {
    color: #3B5997;
}

.text-twitter {
    color: #42C0FB;
}

.text-dribbble {
    color: #EC4A89;
}

.user-card .card-block .user-image:before {
    bottom: 0;
    border-bottom-left-radius: 50px;
    border-bottom-right-radius: 50px;
}

.user-card .card-block .user-image:after, .user-card .card-block .user-image:before {
    content: "";
    width: 100%;
    height: 48%;
    border: 2px solid #4099ff;
    position: absolute;
    left: 0;
    z-index: 10;
}

.user-card .card-block .user-image:after {
    top: 0;
    border-top-left-radius: 50px;
    border-top-right-radius: 50px;
}

.user-card .card-block .user-image:after, .user-card .card-block .user-image:before {
    content: "";
    width: 100%;
    height: 48%;
    border: 2px solid #4099ff;
    position: absolute;
    left: 0;
    z-index: 10;
}
</style>
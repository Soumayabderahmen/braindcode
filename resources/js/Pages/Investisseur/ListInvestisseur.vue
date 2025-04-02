<script setup>
import { ref ,defineProps} from "vue";
import Main from "../../Layouts/main.vue";

const props = defineProps({
  investisseurs: Array,
});
const getinvestisseurId = (id) => {
  console.log("investisseur ID:", id);
  window.location.href = `/profile/Investisseur/${id}`;
};
</script>
<template>
<Main  :showSidebar="true">

    <div class="container">
      <div class="row d-flex flex-wrap justify-content-center">
        <div class="col-md-3 col-sm-6 mb-4"   v-for="investisseur in investisseurs" :key="investisseur.id"        >
            <div class="card user-card flex-grow-1">
            <div class="card-header">
              <h5>Profile</h5>
            </div>
            <div class="card-block">
              <div class="user-image">
                <img :src="`/storage/${investisseur.investisseur?.profile_image}`" alt="Logo Startup" v-if="investisseur.investisseur.profile_image" class="rounded-full">

              </div>
              <h6 class="f-w-600 m-t-25 m-b-10" @click="getinvestisseurId(investisseur.id)">
                {{ investisseur.name }}
              </h6> 
              <p class="text-muted"> {{investisseur.statut}} | {{investisseur.visibility}} |Tel {{investisseur.phone_number}}</p>
              <hr />
              <p class="m-t-15 text-muted">{{investisseur.investisseur?.description}}.</p>

            </div>
            <hr/>
            <div class="row justify-content-center user-social-link">
                <div class="col-auto"><a href="{{ investisseur.investisseur?.website_link }}">🌐</a></div>
                <div class="col-auto"><a href="#!"><svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="20" height="20" viewBox="0 0 48 48">
<linearGradient id="Ld6sqrtcxMyckEl6xeDdMa_uLWV5A9vXIPu_gr1" x1="9.993" x2="40.615" y1="9.993" y2="40.615" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#2aa4f4"></stop><stop offset="1" stop-color="#007ad9"></stop></linearGradient><path fill="url(#Ld6sqrtcxMyckEl6xeDdMa_uLWV5A9vXIPu_gr1)" d="M24,4C12.954,4,4,12.954,4,24s8.954,20,20,20s20-8.954,20-20S35.046,4,24,4z"></path><path fill="#fff" d="M26.707,29.301h5.176l0.813-5.258h-5.989v-2.874c0-2.184,0.714-4.121,2.757-4.121h3.283V12.46 c-0.577-0.078-1.797-0.248-4.102-0.248c-4.814,0-7.636,2.542-7.636,8.334v3.498H16.06v5.258h4.948v14.452 C21.988,43.9,22.981,44,24,44c0.921,0,1.82-0.084,2.707-0.204V29.301z"></path>
</svg></a></div>

                    
            </div>
          </div>
        </div>
  
       
      </div>
    </div>
    </Main>
  </template>
  

  
  
<style>
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
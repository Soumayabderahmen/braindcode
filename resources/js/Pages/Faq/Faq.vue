<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import Navbar_home from '@/Components/Navbar_home.vue'
import Footer from '@/Components/Footer.vue'


const selected = ref(null)
const faqs = ref([])
const tutorials = ref([
  { title: "Créer une formation", url: "/videos/creer-formation.mp4" },
  { title: "Contacter un mentor", url: "/videos/contacter-mentor.mp4" },
  { title: "Utiliser le tableau de bord", url: "/videos/utiliser-dashboard.mp4" },
])

// toggle FAQ
const toggleFaq = (index) => {
  selected.value = selected.value === index ? null : index
}

// Charger depuis backend
onMounted(async () => {
  const response = await axios.get('/faqs/list') 
  faqs.value = response.data
})
</script>

<template>
  <Navbar_home />

  <div class="faq-page">
  <div class="faq-header">
    <h1 class="main-title">FAQ – BraindCode Startup Studio</h1>
    <h2 class="subtitle">Questions fréquemment posées</h2>
  </div>
  <div class="faq-section">
    <div class="faq-image">
      <img src="/images/faq-illustration.png" alt="FAQ" />
    </div>
    <div class="faq-list">
      <!-- Boucle sur les questions ici -->
      <div v-for="(faq, index) in faqs" :key="index" class="faq-item" @click="toggleFaq(index)">
        <div class="faq-question">
          <span>{{ faq.question }}</span>
          <span class="symbol">{{ selected === index ? '–' : '+' }}</span>
        </div>
        <div v-if="selected === index" class="faq-answer">
          {{ faq.answer }}
        </div>
      </div>
    </div>
  </div>




    <hr class="divider" />

    <div class="tutorials">
      <h3>Tutoriels :</h3>
      <div class="tutorials-list">
        <div v-for="video in tutorials" :key="video.title" class="tutorial">
          <video controls width="200">
            <source :src="video.url" type="video/mp4" />
          </video>
          <p>{{ video.title }}</p>
        </div>
      </div>
    </div>
  </div>
  <Footer />
</template>

<style scoped>

.faq-page {
  margin: auto;
  padding: 3rem 1rem;
  font-family: 'Inter', sans-serif;
  background: var(--Gris-White, #FFF);
}

.faq-header {
  margin-top: 2%;
  text-align: left;
  margin-bottom: 2rem;
  margin-left: 44%; 
  margin-right: 1%;
  font-family: 'Poppins', sans-serif;
  /* Ajuste cette valeur */
  
}

.main-title {
  font-size: 2.5rem;
  font-weight: 800;
 
  margin-bottom: 0.2rem;
color: var(--Blue-blue-700, #253d4d);

font-family: 'Poppins', sans-serif;
font-size: 49px;
font-style: bold;
line-height: 100%; 
}

/* .brand {
  color: #1976d2;
} */

.subtitle {
 
  color: var(--Blue-blue-500, #1c82c2);
/* H4 / Regular */
font-family: 'Poppins', sans-serif;
margin-top: 2%;
font-size: 25px;
font-style: normal;
font-weight: 400;
line-height: 30px;
}

.faq-section {
  display: flex;
  align-items: flex-start;
  margin-left: 10%; /* Aligne avec le titre */
}

.faq-image img {
  width: 95%;
  margin-top: -125px;  
 
}

.faq-list {
  min-width: 57%;
}

.faq-item {
  background: #eef4fa;
  /* f4faff */
  border-radius: 12px;
  margin-bottom: 1.2rem;
  box-shadow: 0 2px 8px rgba(30,136,229,0.06);
  transition: box-shadow 0.2s;
  cursor: pointer;
  border: 1px solid #e3eaf5;
}

.faq-item:hover {
  box-shadow: 0 4px 18px rgba(30,136,229,0.12);
  background-color: #e9f4fb;

}

.faq-question {
  padding: 1.2rem 1.5rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-weight: 700;
  color: #263a4e;
  
  font-family: 'Poppins', sans-serif;
   line-height: 19.2px
}

.symbol {
  font-size: 1.5rem;
  color: #1976d2;
}

.faq-answer {
  color: var(--Black-black-300, #737373);
  font-size: 1rem;
  line-height: 1.6;
  font-family: 'Poppins', sans-serif;
  border-left: 4px solid #0086D9;
  padding-left: 1rem;
  background: #fff;
  padding: 1%;
  font-style: normal;
  font-weight: 400;

}



.divider {
  margin: 3rem 0;
  border-color: #f5efef;
}

.tutorials h3 {
  margin-bottom: 1.5rem;
  margin-left: 6%;
  color: #0086D9;
  font-family: 'Poppins', sans-serif;
  font-size: 20px;
  font-weight: 700;
  border-bottom: 2px solid #0086D9;
  display: inline-block;
  padding-bottom: 4px;
}


.tutorials-list {
  display: flex;
  gap: 9rem;
  flex-wrap: wrap;
  justify-content: center;
}

.tutorial {
  box-shadow: 10px 8px 20px 0px rgba(0, 81, 131, 0.25);
  text-align: center;
  border-radius: 5%;
  background-color: #e1e9ee;

}

@media (max-width: 900px) {
  .faq-section {
    flex-direction: column;
    align-items: center;
    margin-left: 0; 
  }
  .faq-header {
    text-align: center;
    margin-left: 0; 
  }
}
</style>

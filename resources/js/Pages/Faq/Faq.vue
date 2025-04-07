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
    <h1 class="main-title">
      <span class="highlight">FAQ</span>
      <span class="brand"> - BrainCode Startup Studio</span>
    </h1>

    <h2 class="subtitle">Questions fréquemment posées</h2>

    <div class="faq-section">
      <div class="faq-image">
        <img src="/images/faq-illustration.png" alt="FAQ" />
      </div>
      <div class="faq-list">
        <div v-for="(faq, index) in faqs" :key="index" class="faq-item" @click="toggleFaq(index)">
          <div class="faq-question">
            <span>{{ faq.question }}</span>
            <span class="symbol">{{ selected === index ? '✖' : '+' }}</span>
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
  max-width: 1200px;
  margin: auto;
  padding: 3rem 1rem;
  font-family: 'Inter', sans-serif;
}

.main-title {
  text-align: center;
  font-size: 36px;
  margin-bottom: 1rem;
}

.highlight {
  background-color: #ffeb3b;
  padding: 0 10px;
  font-weight: bold;
}

.brand {
  color: #1e88e5;
  font-weight: bold;
}

.subtitle {
  text-align: center;
  font-size: 20px;
  margin-bottom: 2rem;
}

.faq-section {
  display: flex;
  gap: 2rem;
  flex-wrap: wrap;
  align-items: flex-start;
  margin-bottom: 3rem;
}

.faq-image img {
  width: 100%;
  max-width: 400px;
}

.faq-list {
  flex: 1;
}

.faq-item {
  background: #fff;
  border-bottom: 1px solid #ddd;
  padding: 1rem 0;
  cursor: pointer;
  transition: all 0.2s;
}

.faq-item:hover {
  background-color: #f9f9f9;
}

.faq-question {
  display: flex;
  justify-content: space-between;
  font-weight: bold;
  color: #333;
}

.faq-answer {
  margin-top: 0.5rem;
  color: #555;
  font-size: 15px;
  line-height: 1.5;
}

.symbol {
  font-weight: bold;
  font-size: 20px;
  color: #1e88e5;
}

.divider {
  margin: 3rem 0;
  border-color: #ccc;
}

.tutorials h3 {
  font-size: 20px;
  margin-bottom: 1rem;
}

.tutorials-list {
  display: flex;
  gap: 2rem;
  flex-wrap: wrap;
  justify-content: center;
}

.tutorial {
  text-align: center;
}
</style>

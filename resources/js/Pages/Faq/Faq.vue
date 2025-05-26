<script setup>
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'

const selected = ref(null)
const faqs = ref([])
const tutorials = ref([])

const searchQuery = ref('')
const isLoading = ref(true)
const playingIndex = ref(null)

const playVideo = (index) => {
  const video = document.querySelectorAll('video')[index]
  if (video) {
    playingIndex.value = index
    video.play()
  }
}

// Filtrer les FAQs basées sur la recherche
const filteredFaqs = computed(() => {
  if (!searchQuery.value) return faqs.value
  const query = searchQuery.value.toLowerCase()
  return faqs.value.filter(
    faq => faq.question.toLowerCase().includes(query) || 
           faq.answer.toLowerCase().includes(query)
  )
})

// toggle FAQ
const toggleFaq = (index) => {
  selected.value = selected.value === index ? null : index
}

// Charger depuis backend
onMounted(async () => {
  try {
    const [faqResponse, tutorialsResponse] = await Promise.all([
      axios.get('/faqs/list'),
      axios.get('/tutorials/public')
    ])
    
    faqs.value = faqResponse.data
    tutorials.value = tutorialsResponse.data.map(tuto => ({
      title: tuto.title,
      url: tuto.video_url
    }))
    
    isLoading.value = false
  } catch (error) {
    console.error('Erreur lors du chargement', error)
    isLoading.value = false
  }
})

</script>

<template>
  <div class="faq-page">
    <div class="faq-header">
      <h1 class="main-title">FAQ <span class="brand">BraindCode</span> Startup Studio</h1>
      <h2 class="subtitle">Questions fréquemment posées</h2>
      
     <div class="search-container">
  <input 
    v-model="searchQuery" 
    type="text" 
    placeholder="Rechercher une question..." 
    class="search-input"
    aria-label="Rechercher une question dans la FAQ"
  />
  <span class="search-icon">
    <svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
      <circle cx="11" cy="11" r="8"></circle>
      <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
    </svg>
  </span>
</div>

    </div>

    <div class="faq-section">
      <div class="faq-image">
        <img src="/images/faq-illustration.png" alt="FAQ" class="floating-image" />
      </div>
      
      <div class="faq-list">
        <div v-if="isLoading" class="loading-container">
          <div class="loading-spinner"></div>
          <p>Chargement des questions...</p>
        </div>
        
        <transition-group name="list" tag="div">
          <div 
            v-for="(faq, index) in filteredFaqs" 
            :key="index" 
            class="faq-item" 
            @click="toggleFaq(index)"
          >
            <div class="faq-question">
              <span>{{ faq.question }}</span>
              <span class="symbol" :class="{ 'rotated': selected === index }">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <polyline points="6 9 12 15 18 9"></polyline>
                </svg>
              </span>
            </div>
            <transition name="slide">
              <div v-if="selected === index" class="faq-answer">
                {{ faq.answer }}
              </div>
            </transition>
          </div>
        </transition-group>

        <div v-if="filteredFaqs.length === 0 && !isLoading" class="no-results">
          <p>Aucune question ne correspond à votre recherche</p>
        </div>
      </div>
    </div>

    <hr class="divider" />

    <div class="tutorials-section">
      <h3 class="tutorials-title">Tutoriels Vidéo</h3>
      <div class="tutorials-list">
       <div 
  v-for="(video, idx) in tutorials" 
  :key="video.title" 
  class="tutorial-card"
  :style="{ animationDelay: `${idx * 0.2}s` }"
>
  <div class="video-container" @click="playVideo(idx)">
    <video controls :class="{ 'pointer-events-none': playingIndex !== idx }">
      <source :src="video.url" type="video/mp4" />
    </video>
    <div 
      class="play-overlay" 
      v-if="playingIndex !== idx"
    >
      <div class="play-icon">▶</div>
    </div>
  </div>
  <p class="tutorial-title">{{ video.title }}</p>
</div>

      </div>
    </div>
  </div>
</template>

<style scoped>
.pointer-events-none {
  pointer-events: none;
}

.faq-page {
  margin: auto;
  padding: 3rem 1rem;
  font-family: 'Inter', sans-serif;
  background-color: aliceblue;
}
.faq-header {
  margin: 2% auto 4rem;
  text-align: center;
  max-width: 800px;
}

.main-title {
  font-size: 3rem;
  font-weight: 800;
  margin-bottom: 0.5rem;
  color: #253d4d;
  background: linear-gradient(90deg, #253d4d 0%, #1c82c2 100%);
  -webkit-background-clip: text;
  background-clip: text;
  -webkit-text-fill-color: transparent;
  animation: fadeIn 1s ease-in-out;
}

.brand {
  color: #0086D9;
  position: relative;
  display: inline-block;
  -webkit-text-fill-color: #0086D9;
}

.subtitle {
  color: #1c82c2;
  font-size: 1.4rem;
  font-weight: 400;
  margin-bottom: 2rem;
  animation: slideUp 0.7s ease-out;
}

.search-container {
  position: relative;
  max-width: 500px;
  margin: 0 auto;
  animation: fadeIn 1s ease-in-out 0.3s backwards;
  margin-left: 32%;

}

.search-input {
  width: 100%;
  padding: 1rem 2.5rem 1rem 1rem;
  border-radius: 30px;
  border: 2px solid #e0e6ed;
  background-color: white;
  font-family: 'Poppins', sans-serif;
  font-size: 1rem;
  transition: all 0.3s ease;
}

.search-input:focus {
  outline: none;
  border-color: #0086D9;
  box-shadow: 0 0 0 3px rgba(0, 134, 217, 0.2);
  transform: translateY(-1px);
}



.search-icon {
  position: absolute;
  right: 20px;
  top: 50%;
  transform: translateY(-50%);
  color: #1c82c2;
  pointer-events: none;
}
.search-icon svg.icon {
  width: 20px;
  height: 20px;
  stroke-width: 2.2;
}
.faq-section {
  display: flex;
  align-items: flex-start;
  gap: 3rem;
  margin: 0 auto;
  max-width: 1200px;
}

.faq-image {
  flex: 0 0 40%;
  position: sticky;
  top: 2rem;
}

.floating-image {
  width: 100%;
  animation: float 6s ease-in-out infinite;
  border-radius: 10px;
  box-shadow: 0 10px 30px rgba(28, 130, 194, 0.15);
  transition: transform 0.3s ease;
}

.floating-image:hover {
  transform: scale(1.02);
}

.faq-list {
  flex: 0 0 60%;
}

.loading-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 2rem;
}

.loading-spinner {
  width: 40px;
  height: 40px;
  border: 4px solid #f3f3f3;
  border-top: 4px solid #0086D9;
  border-radius: 50%;
  animation: spin 1s linear infinite;
  margin-bottom: 1rem;
}

.faq-item {
  background: white;
  border-radius: 16px;
  margin-bottom: 1.2rem;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
  transition: all 0.3s ease;
  overflow: hidden;
  cursor: pointer;
  border-left: 4px solid transparent;
}

.faq-item:hover {
  box-shadow: 0 8px 24px rgba(0, 134, 217, 0.15);
  transform: translateY(-3px);
  border-left: 4px solid #0086D9;
}

.faq-question {
  padding: 1.5rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-weight: 600;
  color: #263a4e;
  line-height: 1.4;
}

.symbol {
  color: #0086D9;
  transition: transform 0.3s ease;
}

.symbol.rotated {
  transform: rotate(180deg);
}

.faq-answer {
  color: #737373;
  font-size: 1rem;
  line-height: 1.7;
  padding: 0 1.5rem 1.5rem;
  background: linear-gradient(to bottom, rgba(238, 244, 250, 0.5) 0%, white 100%);
  border-top: 1px solid #f0f4f8;
}

.divider {
  margin: 4rem auto;
  border: none;
  height: 1px;
  background: linear-gradient(to right, transparent, #e0e6ed, transparent);
  max-width: 80%;
}

.tutorials-section {
  max-width: 1200px;
  margin: 0 auto;
}

.tutorials-title {
  margin-bottom: 2rem;
  color: #0086D9;
  font-size: 1.8rem;
  font-weight: 700;
  text-align: center;
  position: relative;
  display: inline-block;
  left: 50%;
  transform: translateX(-50%);
}

.tutorials-title::after {
  content: '';
  position: absolute;
  bottom: -10px;
  left: 0;
  width: 100%;
  height: 3px;
  background: linear-gradient(to right, transparent, #0086D9, transparent);
}

.tutorials-list {
  display: flex;
  gap: 2rem;
  flex-wrap: wrap;
  justify-content: center;
}

.tutorial-card {
  width: 320px;
  border-radius: 16px;
  overflow: hidden;
  background-color: white;
  box-shadow: 0 10px 25px rgba(0, 81, 131, 0.2);
  transition: all 0.4s ease;
  animation: fadeInUp 0.8s ease-out forwards;
  opacity: 0;
  transform: translateY(30px);
}

.tutorial-card:hover {
  transform: translateY(-6px) scale(1.02);
  box-shadow: 0 15px 35px rgba(0, 81, 131, 0.3);
}

.video-container {
  position: relative;
  overflow: hidden;
}

.video-container video {
  width: 100%;
  display: block;
  transition: transform 0.5s ease;
}

.play-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.4);
  display: flex;
  justify-content: center;
  align-items: center;
  opacity: 0;
  transition: opacity 0.3s ease;
}

.play-icon {
  width: 60px;
  height: 60px;
  background-color: rgba(255, 255, 255, 0.9);
  border-radius: 50%;
  display: flex;
  justify-content: center;
  align-items: center;
  color: #0086D9;
  font-size: 1.5rem;
  transform: scale(0.8);
  transition: transform 0.3s ease;
}

.video-container:hover .play-overlay {
  opacity: 1;
}

.video-container:hover .play-icon {
  transform: scale(1);
}

.video-container:hover video {
  transform: scale(1.05);
}

.tutorial-title {
  padding: 1.2rem;
  text-align: center;
  font-weight: 600;
  color: #253d4d;
}

.no-results {
  text-align: center;
  padding: 2rem;
  color: #737373;
  font-style: italic;
  animation: fadeIn 0.5s ease-in-out;
}

/* Animations */
@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

@keyframes slideUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes float {
  0% {
    transform: translateY(0px);
  }
  50% {
    transform: translateY(-15px);
  }
  100% {
    transform: translateY(0px);
  }
}

@keyframes spin {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Liste des animations */
.list-enter-active,
.list-leave-active {
  transition: all 0.5s ease;
}

.list-enter-from,
.list-leave-to {
  opacity: 0;
  transform: translateY(30px);
}

/* Animation de slide pour les réponses FAQ */
.slide-enter-active,
.slide-leave-active {
  transition: all 0.4s ease;
  max-height: 500px;
  overflow: hidden;
}

.slide-enter-from,
.slide-leave-to {
  max-height: 0;
  opacity: 0;
}

/* Responsive design */
@media (max-width: 1024px) {
  .faq-section {
    flex-direction: column;
  }
  
  .faq-image {
    flex: 0 0 100%;
    max-width: 500px;
    margin: 0 auto 2rem;
    position: relative;
  }

  .faq-list {
    flex: 0 0 100%;
  }
}

@media (max-width: 768px) {
  .main-title {
    font-size: 2.2rem;
  }
  
  .subtitle {
    font-size: 1.2rem;
  }
  
  .tutorial-card {
    width: 280px;
  }
}

@media (max-width: 480px) {
  .faq-page {
    padding: 2rem 1rem;
  }
  
  .main-title {
    font-size: 1.8rem;
  }
  
  .subtitle {
    font-size: 1rem;
  }
}
</style>
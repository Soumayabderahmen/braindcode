<template>
  <div class="startup-page">

    <!-- Bloc 1 : Espace immersif 3D -->
    <section class="text-center mb-5 position-relative bg-white headstartup">
      <div class="container">
        <div class="position-absolute top-50 start-50 translate-middle text-white">
          <h2 class="startup-title" style="font-size: 3rem; z-index: 1;">
            Un espace de travail immersif<br><span class="highlight">en 3D</span>
          </h2>
<a href="#" class="btn-explore px-8 py-3 text-xl rounded-full transition-all hover:scale-105 hover:shadow-lg">
            Explorer l'espace 3D
            <i class="bi bi-arrow-right-circle ms-2"></i>
          </a>        </div>
      </div>
    </section>

    <!-- Bloc 2 : 12 étapes -->
    <section class="text-center my-5 bg-white">
      <h3 class="section-title">Un accompagnement structuré en <span class="highlight">12 étapes</span></h3>
      <img src="/asset/img/startup2.png" alt="Étapes du programme" class="img-fluid mt-4 mb-3" style="
    margin-left: 2%;
">
      <p class="section-description" style="
    margin-left: 25%;
">
        Le programme d'incubation Braindcode Startup Studio aide les startups en 12 étapes, de l'idée au lancement,
        avec des outils pratiques (Lean Canvas, Pitch Deck, Business Plan) et l'aide d'une IA pour accélérer et
        optimiser chaque étape !
      </p>
 <a href="#" style="margin-right: 3%;" class="btn-start px-6 py-3 rounded-lg transition-all hover:scale-105 hover:shadow-lg" v-motion :initial="{ opacity: 0 }" :enter="{ opacity: 1, transition: { duration: 600, delay: 600 } }">
          Commencer votre aventure
          <i class="bi bi-rocket-takeoff ms-2"></i>
        </a >    
      </section>

    <!-- Bloc 3 + 4 : Coachs -->
  <section class="coaching-section py-5">
  <div class="container">
    <!-- En-tête de section avec design amélioré -->
    <div class="section-header text-center mb-5">
      <h2 class="fw-bold mb-3">Coaching et mentorat personnalisé</h2>
      <div class="heading-underline mx-auto mb-4"></div>
      <p class="section-description mx-auto">
        Trouvez le coach idéal pour accélérer votre startup !<br>
        Nos experts vous accompagnent à chaque étape : stratégie, financement, marketing et développement produit.
      </p>
    </div>
    
    <!-- Coach Cards Slider avec design amélioré -->
    <div class="coach-slider position-relative">
      <div class="uk-position-relative uk-visible-toggle" tabindex="-1" uk-slider="autoplay: true; autoplay-interval: 3000; center: true">
        <div class="uk-slider-container">
          <ul class="uk-slider-items uk-child-width-1-1 uk-child-width-1-2@s uk-child-width-1-3@m uk-grid uk-grid-small">
            <li>
              <div class="coach-card h-100">
                <div class="coach-card-inner">
                  <div class="coach-img-container">
                    <img src="/asset/img/startup3.png" class="img-fluid" alt="Ahmed Larabi">
                    <div class="coach-rating">
                      <span class="star"><i class="bi bi-star-fill"></i></span>
                      <span class="rating">4.6</span>
                    </div>
                  </div>
                  <div class="coach-info">
                    <h4 class="coach-name">Ahmed Larabi</h4>
                    <div class="coach-specialty">
                      <span class="badge">Marketing digital</span>
                    </div>
                    <ul class="coach-details">
                      <li><i class="bi bi-clock"></i> +12 ans d'expérience</li>
                      <li><i class="bi bi-calendar-check"></i> Lundi, Mardi, Jeudi</li>
                    </ul>
                    <a href="#" class="btn-contact-coach">Contacter</a>
                  </div>
                </div>
              </div>
            </li>
            
            <li>
              <div class="coach-card h-100">
                <div class="coach-card-inner">
                  <div class="coach-img-container">
                    <img src="/asset/img/nadia.png" class="img-fluid" alt="Nadia Khemiri">
                    <div class="coach-rating">
                      <span class="star"><i class="bi bi-star-fill"></i></span>
                      <span class="rating">4.6</span>
                    </div>
                  </div>
                  <div class="coach-info">
                    <h4 class="coach-name">Nadia Khemiri</h4>
                    <div class="coach-specialty">
                      <span class="badge">Développement personnel</span>
                    </div>
                    <ul class="coach-details">
                      <li><i class="bi bi-clock"></i> +8 ans d'expérience</li>
                      <li><i class="bi bi-calendar-check"></i> Mercredi, Vendredi</li>
                    </ul>
                    <a href="#" class="btn-contact-coach">Contacter</a>
                  </div>
                </div>
              </div>
            </li>
            
            <li>
              <div class="coach-card h-100">
                <div class="coach-card-inner">
                  <div class="coach-img-container">
                    <img src="/asset/img/walid.png" class="img-fluid" alt="Walid Gharsalli">
                    <div class="coach-rating">
                      <span class="star"><i class="bi bi-star-fill"></i></span>
                      <span class="rating">4.6</span>
                    </div>
                  </div>
                  <div class="coach-info">
                    <h4 class="coach-name">Walid Gharsalli</h4>
                    <div class="coach-specialty">
                      <span class="badge">Leadership</span>
                    </div>
                    <ul class="coach-details">
                      <li><i class="bi bi-clock"></i> +8 ans d'expérience</li>
                      <li><i class="bi bi-calendar-check"></i> Mercredi, Vendredi</li>
                    </ul>
                    <a href="#" class="btn-contact-coach">Contacter</a>
                  </div>
                </div>
              </div>
            </li>
            
            <li>
              <div class="coach-card h-100">
                <div class="coach-card-inner">
                  <div class="coach-img-container">
                    <img src="/asset/img/amira.png" class="img-fluid" alt="Amira Kefi">
                    <div class="coach-rating">
                      <span class="star"><i class="bi bi-star-fill"></i></span>
                      <span class="rating">4.6</span>
                    </div>
                  </div>
                  <div class="coach-info">
                    <h4 class="coach-name">Amira Kefi</h4>
                    <div class="coach-specialty">
                      <span class="badge">UX/UI Design</span>
                    </div>
                    <ul class="coach-details">
                      <li><i class="bi bi-clock"></i> +6 ans d'expérience</li>
                      <li><i class="bi bi-calendar-check"></i> Mardi, Mercredi, Vendredi</li>
                    </ul>
                    <a href="#" class="btn-contact-coach">Contacter</a>
                  </div>
                </div>
              </div>
            </li>
          </ul>
        </div>
        
        <!-- Navigation du carrousel améliorée -->
        <a class="slider-nav slider-nav-prev" href uk-slidenav-previous uk-slider-item="previous">
          <i class="bi bi-chevron-left"></i>
        </a>
        <a class="slider-nav slider-nav-next" href uk-slidenav-next uk-slider-item="next">
          <i class="bi bi-chevron-right"></i>
        </a>
        
        <!-- Indicateurs de slide -->
        <ul class="uk-slider-nav uk-dotnav uk-flex-center mt-4"></ul>
      </div>
    </div>
    
    <!-- CTA après le slider -->
    <div class="text-center mt-5">
      <a href="#" class="btn-all-coaches">Voir tous nos coachs</a>
    </div>
  </div>
</section>


   <!-- Bloc 5 : Agent IA avec animations -->
    <section class="ai-section py-16 px-4 bg-white">
      <div class="container mx-auto">
        <h2 class="text-3xl font-bold text-center mb-12" v-motion :initial="{ opacity: 0 }" :enter="{ opacity: 1, transition: { duration: 600 } }">
          Agent IA <span class="text-primary">Intelligent</span>
        </h2>
        
        <div class="ai-content flex flex-col md:flex-row items-center max-w-4xl mx-auto">
          <div class="ai-image w-full md:w-1/3 flex justify-center mb-8 md:mb-0" v-motion :initial="{ opacity: 0, x: -50 }" :enter="{ opacity: 1, x: 0, transition: { duration: 800, delay: 200 } }">
            <div class="relative">
              <img src="/image/startup2/4.png" alt="Agent IA" class="w-48 ai-assistant-img">
              <div class="pulse-effect absolute top-0 left-0 w-full h-full"></div>
            </div>
          </div>
          
          <div class="ai-description w-full md:w-2/3" v-motion :initial="{ opacity: 0, x: 50 }" :enter="{ opacity: 1, x: 0, transition: { duration: 800, delay: 400 } }">
            <p class="text-center md:text-left text-lg mb-8">
              Bénéficiez d'un assistant virtuel toujours à votre écoute pour vous guider dans vos requêtes. 
              Accessible à tout moment, il vous aide à optimiser votre parcours d'apprentissage.
            </p>
            
            <div class="ai-features grid grid-cols-1 md:grid-cols-2 gap-6">
              <div v-for="(feature, index) in features" :key="index" 
                   class="feature-card border border-blue-200 bg-blue-50 rounded-lg p-5 transition-all duration-300 hover:shadow-md hover:bg-blue-100 hover:border-blue-300"
                   :class="{'appear-delayed': true}"
                   :style="{'animation-delay': `${index * 150}ms`}">
                <h4 class="font-semibold text-lg mb-2 text-primary">{{ feature.title }}</h4>
                <p>{{ feature.description }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
 <div class="text-center mt-8" data-aos="fade-up" data-aos-delay="800">
          <a href="/agentia" class="btn btn-primary px-5 py-2 rounded-lg shadow-lg hover:shadow-xl transition-all">
            Découvrir nos agents AI
            <i class="bi bi-box-arrow-up-right ms-2"></i>
          </a>
        </div>
    </section>

 <!-- Bloc 6 : Avantages IA avec animations -->
    <section class="features-section py-16 px-4 bg-gradient-light">
      <div class="container mx-auto">
        <h2 class="text-3xl font-bold text-center mb-12" v-motion :initial="{ opacity: 0 }" :enter="{ opacity: 1, transition: { duration: 600 } }">
          Nos avantages <span class="text-primary">exclusifs</span>
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
          <div v-for="(advantage, i) in advantages" :key="i" 
               class="feature-card bg-white rounded-xl shadow-md overflow-hidden transform transition-all duration-500 hover:shadow-xl hover:-translate-y-2"
               v-motion :initial="{ opacity: 0, y: 40 }" 
               :enter="{ opacity: 1, y: 0, transition: { duration: 800, delay: i * 200 } }">
            <div class="h-2 bg-gradient-to-r from-primary to-primary-light"></div>
            <div class="p-6 flex flex-col items-center text-center">
              <div class="icon-container mb-4 p-4 rounded-full bg-blue-50">
                <img :src="advantage.icon" :alt="advantage.title" class="h-16 animate-bounce-subtle">
              </div>
              <h3 class="text-xl font-bold text-primary mb-3">{{ advantage.title }}</h3>
              <p class="text-gray-700">{{ advantage.description }}</p>
            </div>
          </div>
        </div>
      </div>
    </section>

  </div>
</template>

<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'
const props = defineProps({
  features: Array
  
});

const coachs = ref([
  {
    name: "Ahmed Larabi",
    image: "/asset/img/startup3.png",
    description: "Spécialité : Marketing digital<br>Expérience : +12 ans<br>Disponibilité : Lundi, Mardi, Jeudi",
    rating: 4.6
  },
  {
    name: "Nadia Khemiri",
    image: "/asset/img/nadia.png",
    description: "Spécialité : Développement personnel<br>Expérience : +8 ans<br>Disponibilité : Mercredi, Vendredi",
    rating: 4.6
  },
  {
    name: "Walid Gharsalli",
    image: "/asset/img/walid.png",
    description: "Spécialité : Leadership<br>Expérience : +8 ans<br>Disponibilité : Mercredi, Vendredi",
    rating: 4.6
  },
  {
    name: "Amira Kefi",
    image: "/asset/img/amira.png",
    description: "Spécialité : UX/UI Design<br>Expérience : +6 ans<br>Disponibilité : Mardi, Mercredi, Vendredi",
    rating: 4.6
  }
]);

const advantages = ref([
  {
    title: "Suivi et Ajustement Continu",
    description: "Analysez les performances en temps réel et ajustez votre stratégie.",
    icon: "/image/startup2/a.gif"
  },
  {
    title: "Système de Recommandation Intelligent",
    description: "Suggestions personnalisées selon vos besoins.",
    icon: "/image/startup2/aa.gif"
  },
  {
    title: "Accompagnement en Temps Réel",
    description: "Soutien instantané par nos coachs et outils IA.",
    icon: "/image/startup2/aaa.gif"
  },
  {
    title: "Guide Personnalisé à Chaque Étape",
    description: "Conseils sur mesure pour surmonter vos défis.",
    icon: "/image/startup2/aaaa.gif"
  }
]);
</script>


<style scoped>
.coaching-section {
  background-color:aliceblue#bdd6ee;
  position: relative;
  overflow: hidden;
}

.coaching-section::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-image: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><rect width="100%" height="100%" fill="none"/><path d="M0,50 Q25,30 50,50 T100,50" stroke="%23f0f0f0" fill="none" stroke-width="2"/></svg>');
  background-size: 300px;
  opacity: 0.5;
  z-index: 0;
}

.section-header {
  position: relative;
  z-index: 1;
}

.coaching-section h2 {
  color: #2a2d45;
  font-size: 2.5rem;
  letter-spacing: -0.5px;
}

.heading-underline {
  width: 80px;
  height: 4px;
  background: linear-gradient(90deg, #4568dc, #b06ab3);
  border-radius: 2px;
}

.section-description {
  max-width: 700px;
  color: #5a5f73;
  font-size: 1.1rem;
  line-height: 1.6;
}

/* Styles des cartes de coach */
.coach-card {
  position: relative;
  padding: 0;
  border-radius: 16px;
  overflow: hidden;
  transition: all 0.3s ease;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
  background-color: white;
  margin: 15px 10px;
  z-index: 1;
}

.coach-card:hover {
  transform: translateY(-10px);
  box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
}

.coach-card-inner {
  display: flex;
  flex-direction: column;
  height: 100%;
}

.coach-img-container {
  position: relative;
  height: 230px;
  overflow: hidden;
}

.coach-img-container img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.8s ease;
}

.coach-card:hover .coach-img-container img {
  transform: scale(1.05);
}

.coach-rating {
  position: absolute;
  bottom: 10px;
  right: 10px;
  background-color: rgba(255, 255, 255, 0.9);
  padding: 5px 10px;
  border-radius: 20px;
  display: flex;
  align-items: center;
  font-weight: 600;
}

.star {
  color: #ffb400;
  margin-right: 5px;
}

.coach-info {
  padding: 1.5rem;
  display: flex;
  flex-direction: column;
  flex: 1;
}

.coach-name {
  font-weight: 700;
  margin-bottom: 10px;
  color: #2a2d45;
  font-size: 1.25rem;
}

.coach-specialty {
  margin-bottom: 15px;
}

.coach-specialty .badge {
  background: linear-gradient(90deg, #4568dc, #b06ab3);
  color: white;
  padding: 6px 12px;
  border-radius: 20px;
  font-weight: 500;
  font-size: 0.85rem;
}

.coach-details {
  list-style: none;
  padding: 0;
  margin: 0 0 20px 0;
  flex: 1;
}

.coach-details li {
  margin-bottom: 8px;
  color: #5a5f73;
  display: flex;
  align-items: center;
}

.coach-details li i {
  margin-right: 8px;
  color: #4568dc;
}

.btn-contact-coach {
  display: inline-block;
  background-color: transparent;
  color: #4568dc;
  border: 2px solid #4568dc;
  padding: 8px 20px;
  border-radius: 25px;
  font-weight: 600;
  text-decoration: none;
  transition: all 0.3s ease;
  text-align: center;
}

.btn-contact-coach:hover {
  background-color: #4568dc;
  color: white;
}

/* Navigation du slider */
.coach-slider {
  padding: 20px 0;
}

.slider-nav {
  width: 50px;
  height: 50px;
  background-color: white;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #2a2d45;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
  position: absolute;
  top: 45%;
  transform: translateY(-50%);
  z-index: 10;
  opacity: 0;
  transition: all 0.3s ease;
}

.coach-slider:hover .slider-nav {
  opacity: 1;
}

.slider-nav:hover {
  background-color: #4568dc;
  color: white;
}

.slider-nav-prev {
  left: -5px;
}

.slider-nav-next {
  right: -5px;
}

.uk-dotnav li a {
  background-color: #d1d5db;
  border: none;
  transition: all 0.3s ease;
}

.uk-dotnav li.uk-active a {
  background-color: #4568dc;
  transform: scale(1.2);
}

/* Bouton "Voir tous nos coachs" */
.btn-all-coaches {
  display: inline-block;
  background: linear-gradient(90deg, #4568dc, #b06ab3);
  color: white;
  padding: 12px 30px;
  border-radius: 30px;
  font-weight: 600;
  text-decoration: none;
  box-shadow: 0 8px 25px rgba(69, 104, 220, 0.3);
  transition: all 0.3s ease;
}

.btn-all-coaches:hover {
  transform: translateY(-3px);
  box-shadow: 0 12px 30px rgba(69, 104, 220, 0.4);
}

/* Responsive */
@media (max-width: 991px) {
  .coaching-section h2 {
    font-size: 2rem;
  }
  
  .coach-img-container {
    height: 200px;
  }
}

@media (max-width: 767px) {
  .coaching-section::before {
    opacity: 0.3;
  }
  
  .slider-nav {
    width: 40px;
    height: 40px;
    opacity: 1;
  }
  
  .slider-nav-prev {
    left: 5px;
  }
  
  .slider-nav-next {
    right: 5px;
  }
}

@media (max-width: 575px) {
  .coaching-section h2 {
    font-size: 1.8rem;
  }
  
  .section-description {
    width: 90% !important;
    font-size: 1rem;
  }
}
.startup-page {
  font-family: 'Poppins', sans-serif;
  color: #333;
  overflow: hidden;
}

/* Couleurs et dégradés */
.text-primary {
  color: #4268F6;
}

.text-primary-light {
  color: #7C96FF;
}

.text-primary-dark {
  color: #2A4CD0;
}

.bg-gradient-primary {
  background: linear-gradient(135deg, #4268F6 0%, #5E7FFF 100%);
}

.bg-gradient-light {
  background: linear-gradient(135deg, #F0F5FF 0%, #E6EEFF 100%);
}

.bg-light {
  background-color: #F9FAFF;
}

/* Hero section avec animation */
.hero-section {
  min-height: 600px;
  display: flex;
  align-items: center;
  position: relative;
}

.animated-background {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: linear-gradient(135deg, #4268F6 0%, #5E7FFF 100%);
  overflow: hidden;
  z-index: 1;
}

.animated-background::before {
  content: '';
  position: absolute;
  top: -50%;
  left: -50%;
  width: 200%;
  height: 200%;
  background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
  animation: rotate 20s linear infinite;
}

@keyframes rotate {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}

.wave-shape {
  position: absolute;
  bottom: -10px;
  left: 0;
  width: 100%;
  z-index: 2;
}

.wave-shape-bottom {
  position: absolute;
  bottom: -10px;
  left: 0;
  width: 100%;
}

/* Boutons avec animations */
.btn-explore {
  background-color: white;
  color: #4268F6;
  font-weight: bold;
  display: inline-flex;
  align-items: center;
  box-shadow: 0 4px 14px rgba(66, 104, 246, 0.2);
}

.btn-explore:hover {
  box-shadow: 0 6px 20px rgba(66, 104, 246, 0.3);
}

.btn-start {
  background-color: #4268F6;
  color: white;
  font-weight: bold;
  display: inline-flex;
  align-items: center;
  box-shadow: 0 4px 14px rgba(66, 104, 246, 0.2);
}

.btn-white {
  background-color: white;
  box-shadow: 0 4px 14px rgba(0, 0, 0, 0.1);
}

.btn-outline:hover {
  background-color: rgba(255, 255, 255, 0.2);
}

/* Animation pour les cartes */
.appear-delayed {
  opacity: 0;
  animation: fadeInUp 0.6s ease forwards;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Animation pour l'assistant IA */
.ai-assistant-img {
  animation: float 6s ease-in-out infinite;
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

.pulse-effect {
  border-radius: 50%;
  animation: pulse 2s infinite;
  background: radial-gradient(circle, rgba(66, 104, 246, 0.3) 0%, rgba(66, 104, 246, 0) 70%);
}

@keyframes pulse {
  0% {
    transform: scale(0.9);
    opacity: 0.7;
  }
  50% {
    transform: scale(1.1);
    opacity: 0.3;
  }
  100% {
    transform: scale(0.9);
    opacity: 0.7;
  }
}

/* Animation légère pour les icônes */
.animate-bounce-subtle {
  animation: bounce-subtle 3s infinite;
}

@keyframes bounce-subtle {
  0%, 100% {
    transform: translateY(0);
  }
  50% {
    transform: translateY(-8px);
  }
}

/* Styles spécifiques pour les cards de coach */
.coach-card {
  transition: all 0.3s ease;
}

.coach-card:hover {
  transform: translateY(-10px);
}

.coach-img-container {
  height: 180px;
  overflow: hidden;
}

/* Styles spécifiques pour les testimonials */
.testimonial-card {
  height: 100%;
  display: flex;
  flex-direction: column;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .hero-section {
    min-height: 450px;
  }
  
  .startup-title {
    font-size: 2.5rem !important;
  }
  
  .section-title {
    font-size: 2rem !important;
  }
}


</style>

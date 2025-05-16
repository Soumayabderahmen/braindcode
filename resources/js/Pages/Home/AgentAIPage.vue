<script setup>
import { ref, computed, onMounted } from 'vue';

const search = ref('');
const category = ref('');

const agents = ref([
  { name: "Growthy", desc: "Assistant campagnes publicitaires", image: "/asset/img/startup4.png", link: "/agentia2", category: "marketing" },
  { name: "MarkiBot", desc: "Assistant marketing digital", image: "/asset/img/startup4.png", link: "/agentia2", category: "marketing" },
  { name: "AdGenius", desc: "Assistant budgets marketing", image: "/asset/img/startup4.png", link: "/agentia2", category: "finance" },
  { name: "FinBot", desc: "Assistant gestion financière", image: "/asset/img/startup4.png", link: "/agentia2", category: "finance" },
  { name: "CashWise", desc: "Assistant trésorerie", image: "/asset/img/startup4.png", link: "/agentia2", category: "finance" },
  { name: "Budgetor", desc: "Assistant suivi des finances", image: "/asset/img/startup4.png", link: "/agentia2", category: "finance" },
  { name: "ComLink", desc: "Assistant stratégies de communication", image: "/asset/img/startup4.png", link: "/agentia2", category: "communication" },
  { name: "TalkyPro", desc: "Assistant relations publiques", image: "/asset/img/startup4.png", link: "/agentia2", category: "communication" },
  { name: "RapidoBot", desc: "Assistant CRM", image: "/asset/img/startup4.png", link: "/agentia2", category: "communication" },
]);

const filteredAgents = computed(() =>
  agents.value.filter(a =>
    (!search.value || a.name.toLowerCase().includes(search.value.toLowerCase())) &&
    (!category.value || a.category === category.value)
  )
);

// Animation lors du chargement
onMounted(() => {
  document.querySelectorAll('.agent-card-custom').forEach((card, index) => {
    setTimeout(() => {
      card.classList.add('animate-in');
    }, 100 * index);
  });
});
</script>

<template>
  <section class="agent-ai-section py-5">
    <div class="container">
      <div class="section-header text-center animate-fade-in">
        <h2 class="fw-bold text-primary mb-3">Nos Agents AI</h2>
        <div class="title-underline"></div>
        <p class="text-muted mb-5">
          Découvrez nos Agents AI : des solutions intelligentes pour les startups et coachs. Que ce soit pour gérer vos projets, former vos équipes ou booster vos ventes, nos agents sont conçus pour optimiser vos processus. Chaque agent est personnalisable pour s'adapter à vos besoins spécifiques, vous permettant de gagner du temps et d'améliorer l'efficacité de vos projets. Choisissez l'agent AI qui vous convient et transformez votre manière de travailler !
        </p>
      </div>
      
      <!-- Filtres avec animations -->
    <div class="filters-container animate-slide-up">
  <div class="d-flex justify-content-center mb-5 flex-wrap gap-3">
    <!-- Champ de recherche -->
  
      <div class="input-icon-wrapper">
        <i class="fas fa-search search-icon"></i>
        <input v-model="search" type="text" class="form-control pill-input" placeholder="Recherche">
     
    </div>
    <!-- Sélecteur de catégorie -->
    <div class="category-dropdown">
      <div class="select-wrapper">
        <select v-model="category" class="form-select pill-input">
          <option value="">Catégorie</option>
          <option value="marketing">Marketing</option>
          <option value="finance">Finance</option>
          <option value="communication">Communication</option>
        </select>
        <i class="fas fa-chevron-down select-icon"></i>
      </div>
    </div>
  </div>
</div>

      
      <!-- Grille avec animations -->
      <div class="row g-4">
        <div class="col-md-4" v-for="(agent, index) in filteredAgents" :key="index">
          <div class="agent-card-custom">
            <img :src="agent.image" :alt="agent.name" class="robot-img-custom pulse-animation" />
            <div class="agent-title-custom">{{ agent.name }}</div>
            <div class="agent-role-custom">{{ agent.desc }}</div>
            <div class="agent-desc-custom">
              Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna ali
            </div>
            <a :href="agent.link" class="voir-plus-btn-custom">Voir plus</a>
          </div>
        </div>
      </div>
      
      <!-- Pagination avec hover effects -->
      <div class="pagination-controls animate-fade-in">
        <a href="#" class="page-arrow prev"><i class="fas fa-chevron-left"></i></a>
        <a href="#" class="page-number active">1</a>
        <a href="#" class="page-number">2</a>
        <a href="#" class="page-number">3</a>
        <a href="#" class="page-arrow next"><i class="fas fa-chevron-right"></i></a>
      </div>
    </div>
  </section>
</template>

<style scoped>
.filters-container {
  background: transparent;
}

.input-icon-wrapper, .select-wrapper {
  position: relative;
  display: flex;
  align-items: center;
}

.pill-input {
  border: none;
  border-radius: 30px;
  background: white;
  padding: 12px 20px 12px 45px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
  font-size: 14px;
  color: #555;
  min-width: 230px;
  transition: all 0.2s ease-in-out;
}

.pill-input:focus {
  box-shadow: 0 6px 18px rgba(0, 0, 0, 0.12);
  outline: none;
}
.input-icon-wrapper .search-icon {
  position: absolute;
  left: 16px;
  top: 50%;
  transform: translateY(-50%);
  color: #bbb;
  font-size: 16px;
}

.search-icon {
  position: absolute;
  left: 16px;
  color: #b0bec5;
  font-size: 1.1rem;
  pointer-events: none;
}

.select-wrapper {
  position: relative;
}
.select-wrapper .select-icon {
  position: absolute;
  right: 16px;
  top: 50%;
  transform: translateY(-50%);
  color: #bbb;
  font-size: 16px;
  pointer-events: none;
}
.select-icon {
  position: absolute;
  right: 18px;
  color: #b0bec5;
  font-size: 1.1rem;
  pointer-events: none;
  top: 50%;
  transform: translateY(-50%);
}

.category-dropdown select.pill-input {
  appearance: none;
  -webkit-appearance: none;
  -moz-appearance: none;
  padding-right: 2.5rem;
}

input::placeholder, select {
  color: #b0bec5 !important;
  opacity: 1;
}

/* Responsive */
@media (max-width: 600px) {
  .d-flex.justify-content-center.mb-5.flex-wrap.gap-3 {
    flex-direction: column;
    gap: 1rem;
    align-items: stretch;
  }
}

.agent-ai-section {
  background: linear-gradient(135deg, #f8fbff 0%, #f0f7ff 100%);
  padding: 80px 0;
  position: relative;
  overflow: hidden;
}

.agent-ai-section::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-image: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%230066a1' fill-opacity='0.03' fill-rule='evenodd'/%3E%3C/svg%3E");
  pointer-events: none;
}

.section-header {
  margin-bottom: 40px;
}

h2 {
  color: #0066a1;
  font-size: 42px;
  font-weight: 800;
  margin-bottom: 15px;
  position: relative;
  letter-spacing: -0.5px;
}

.title-underline {
  width: 80px;
  height: 4px;
  background: linear-gradient(90deg, #0066a1, #3ea2f3);
  margin: 0 auto 25px;
  border-radius: 2px;
}

.text-muted {
  color: #586a82;
  max-width: 900px;
  margin: 0 auto 40px;
  line-height: 1.8;
  font-size: 16px;
}



.search-icon, .select-icon {
  position: absolute;
  color: #0066a1;
  top: 50%;
  transform: translateY(-50%);
}
.search-input input, .category-dropdown select {
  border: 1px solid #e4e4e4;
  border-radius: 30px;
  padding: 12px 20px 12px 40px;
  background: white;
  box-shadow: 0 3px 15px rgba(0, 0, 0, 0.05);
  transition: all 0.3s ease;
  font-size: 15px;
}

.search-input input:focus, .category-dropdown select:focus {
  border-color: #2196f3;
  box-shadow: 0 5px 20px rgba(33, 150, 243, 0.15);
  outline: none;
}


.category-dropdown {
  width: 260px;
}

.category-dropdown select {
  appearance: none;
  -webkit-appearance: none;
  -moz-appearance: none;
  background-color: white;
  padding-right: 40px;
}

/* Cards - Conservées comme demandé */
.agent-card-custom {
  background: #fff;
  border-radius: 22px;
  box-shadow: 0 8px 24px rgba(33, 150, 243, 0.08);
  padding: 32px 28px 28px 28px;
  text-align: center;
  display: flex;
  flex-direction: column;
  align-items: center;
  min-height: 420px;
  max-width: 330px;
  margin: auto;
  transition: all 0.5s cubic-bezier(0.165, 0.84, 0.44, 1);
  opacity: 0;
  transform: translateY(30px);
  cursor: pointer;
}

.agent-card-custom.animate-in {
  opacity: 1;
  transform: translateY(0);
}

.agent-card-custom:hover {
  box-shadow: 0 15px 35px rgba(33, 150, 243, 0.15);
  transform: translateY(-10px);
}

.robot-img-custom {
  width: 120px;
  height: 120px;
  object-fit: contain;
  margin-bottom: 18px;
  transition: all 0.3s ease;
}

.agent-title-custom {
  color: #3ea2f3;
  font-size: 22px;
  font-weight: 700;
  margin-bottom: 6px;
}

.agent-role-custom {
  color: #4b7496;
  font-size: 16px;
  font-weight: 600;
  margin-bottom: 12px;
}

.agent-desc-custom {
  color: #353535;
  font-size: 13px;
  line-height: 1.5;
  margin-bottom: 22px;
  min-height: 38px;
}

.voir-plus-btn-custom {
  background: linear-gradient(90deg, #2196f3, #0066a1);
  color: #fff;
  border-radius: 10px;
  padding: 10px 32px;
  text-decoration: none;
  font-weight: 600;
  font-size: 15px;
  transition: all 0.3s ease;
  display: inline-block;
  position: relative;
  overflow: hidden;
  z-index: 1;
}

.voir-plus-btn-custom:hover {
  transform: translateY(-2px);
  box-shadow: 0 7px 14px rgba(33, 150, 243, 0.25);
}

.voir-plus-btn-custom:before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
  transition: all 0.6s ease;
  z-index: -1;
}

.voir-plus-btn-custom:hover:before {
  left: 100%;
}

/* Pagination */
.pagination-controls {
  display: flex;
  justify-content: center;
  align-items: center;
  margin-top: 60px;
}

.page-arrow, .page-number {
  width: 45px;
  height: 45px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 5px;
  border-radius: 50%;
  border: 1px solid #2196f3;
  color: #2196f3;
  text-decoration: none;
  transition: all 0.3s ease;
  font-weight: 600;
}

.page-number.active {
  background: linear-gradient(135deg, #2196f3, #0066a1);
  color: white;
  border: none;
  box-shadow: 0 5px 15px rgba(33, 150, 243, 0.3);
}

.page-arrow:hover, .page-number:hover {
  background: #e6f4ff;
  transform: scale(1.1);
}

.row {
  margin-left: -15px;
  margin-right: -15px;
}

.col-md-4 {
  padding-left: 15px;
  padding-right: 15px;
  margin-bottom: 30px;
}

/* Animations */
.pulse-animation {
  animation: pulse 2s infinite;
}

@keyframes pulse {
  0% {
    transform: scale(1);
  }
  50% {
    transform: scale(1.05);
  }
  100% {
    transform: scale(1);
  }
}

.animate-fade-in {
  animation: fadeIn 1s ease forwards;
}

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

.animate-slide-up {
  animation: slideUp 0.8s ease forwards;
}

@keyframes slideUp {
  from { 
    opacity: 0;
    transform: translateY(30px);
  }
  to { 
    opacity: 1;
    transform: translateY(0);
  }
}

/* Media Queries pour la responsive */
@media (max-width: 768px) {
  .agent-ai-section {
    padding: 50px 0;
  }
  
  h2 {
    font-size: 32px;
  }
  
  .search-input, .category-dropdown {
    width: 100%;
    margin-bottom: 15px;
  }
  
  .filters-container .d-flex {
    flex-direction: column;
  }
  
  .search-input {
    margin-right: 0;
  }
}
</style>
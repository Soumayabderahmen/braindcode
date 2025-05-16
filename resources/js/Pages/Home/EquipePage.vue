<template>
  <div class="team-page">
    <!-- Header Hero avec design épuré et professionnel -->
    <section class="hero position-relative overflow-hidden mb-5">
      <div class="hero-image-container">
        <img src="/asset/img/equipe1.png" alt="Notre équipe" class="w-100 hero-image">
        <div class="overlay d-flex flex-column justify-content-center text-white p-4">
          <div class="container">
            <div class="row">
              <div class="col-lg-8 col-md-10">
                <h1 class="hero-title fw-bold mb-3">Notre équipe</h1>
                <p class="hero-subtitle mb-4">
                  Chez BraindCode, nous transformons des idées innovantes en solutions concrètes grâce à notre équipe talentueuse et passionnée.
                </p>
                <a href="#team-section" class="btn btn-primary rounded-pill px-4 py-2">Découvrir l'équipe</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <div class="container py-5">
      <!-- Section Fondateurs avec design premium -->
      <section id="founders-section" class="section-space">
        <div class="section-header text-center">
          <span class="section-badge">Les visionnaires</span>
          <h2 class="section-title">Nos fondateurs</h2>
          <div class="section-divider"></div>
          <p class="section-subtitle">
            Deux esprits visionnaires qui ont créé BraindCode avec une mission claire: transformer le numérique pour mieux servir l'humanité.
          </p>
        </div>

        <div class="row g-5">
          <div v-for="(founder, index) in founders" :key="index" class="col-md-6">
            <div class="founder-card">
              <div class="row g-0">
                <div class="col-lg-5">
                  <div class="founder-image-container">
                    <img :src="founder.image" :alt="founder.name" class="founder-image">
                  </div>
                </div>
                <div class="col-lg-7">
                  <div class="founder-content p-4">
                    <h3 class="founder-name">{{ founder.name }}</h3>
                    <p class="founder-role mb-3">{{ founder.role }}</p>
                    <p class="founder-bio mb-4">Visionnaire et expert en technologie avec plus de 10 ans d'expérience dans l'innovation numérique.</p>
                    <div class="social-links">
                      <a href="#" class="social-link"><i class="bi bi-linkedin"></i></a>
                      <a href="#" class="social-link"><i class="bi bi-twitter"></i></a>
                      <a href="#" class="social-link"><i class="bi bi-envelope"></i></a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Section Chefs d'équipe avec design uniforme -->
      <section id="team-section" class="section-space">
        <div class="section-header text-center">
          <span class="section-badge">Notre leadership</span>
          <h2 class="section-title">Chefs d'équipe</h2>
          <div class="section-divider"></div>
          <p class="section-subtitle">
           Nos chefs d’équipe pilotent la stratégie et l’innovation, en guidant leurs collaborateurs vers l’excellence.      </p>
        </div>

        <div class="row g-4">
          <div v-for="(chef, index) in chefs" :key="index" class="col-md-6 col-lg-3">
            <div class="team-card h-100">
              <div class="team-image-wrapper">
                <img :src="chef.image" :alt="chef.name" class="team-image">
                <div class="team-overlay">
                  <div class="social-links">
                    <a href="#" class="social-link"><i class="bi bi-linkedin"></i></a>
                    <a href="#" class="social-link"><i class="bi bi-twitter"></i></a>
                  </div>
                </div>
              </div>
              <div class="team-content">
                <h4 class="member-name">{{ chef.name }}</h4>
                <p class="member-role">{{ chef.role }}</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Section Équipe avec style cohérent -->
      <section class="section-space">
        <div class="section-header text-center">
          <span class="section-badge">Notre force</span>
          <h2 class="section-title">L'équipe BraindCode</h2>
          <div class="section-divider"></div>
          <p class="section-subtitle">
            Une équipe jeune, pluridisciplinaire et engagée, donnant vie à nos projets avec passion et expertise.
          </p>
        </div>

        <div class="row g-4">
          <div v-for="(member, index) in members" :key="index" class="col-md-6 col-lg-3">
            <div class="team-card h-100">
              <div class="team-image-wrapper">
                <img :src="member.image" :alt="member.name" class="team-image">
                <div class="team-overlay">
                  <div class="social-links">
                    <a href="#" class="social-link"><i class="bi bi-linkedin"></i></a>
                    <a href="#" class="social-link"><i class="bi bi-twitter"></i></a>
                  </div>
                </div>
              </div>
              <div class="team-content">
                <h4 class="member-name">{{ member.name }}</h4>
                <p class="member-role">{{ member.role }}</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Citation d'équipe avec design premium -->
      <section class="team-quote text-center my-5">
        <div class="quote-content mx-auto">
          <div class="quote-icon">
            <i class="bi bi-quote"></i>
          </div>
          <p class="quote-text">
            "L'innovation naît de la diversité des perspectives. Chez BraindCode, notre force réside dans notre capacité à unir nos talents uniques pour créer des solutions exceptionnelles."
          </p>
          <div class="section-divider mx-auto my-4"></div>
          <p class="quote-author">Michael KEBAIL ALI, Fondateur & CEO</p>
        </div>
      </section>
      
      <!-- Section CTA -->
      <section class="cta-section text-center p-5">
        <h3 class="cta-title mb-4">Rejoignez l'aventure BraindCode</h3>
        <p class="cta-text mb-4">Nous sommes toujours à la recherche de talents passionnés pour renforcer notre équipe.</p>
        <a href="/carrieres" class="btn btn-primary rounded-pill px-4 py-2" style="background-color: black;">Voir nos offres d'emploi</a>
      </section>
    </div>

    
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

// Données des membres de l'équipe
const founders = [
  { name: 'Michael KEBAIL ALI', role: 'Fondateur & CEO', image: '/asset/img/founder1.png' },
  { name: 'Jonathan CHARBIT', role: 'Co-fondateur', image: '/asset/img/m2.png' }
];
const chefs = [
  { name: 'Souhail BEN ALI', role: 'Chef d’équipe UX/UI', image: '/asset/img/m3.png' },
  { name: 'Imen KAMMOUN', role: 'Cheffe d’équipe UX/UI', image: '/asset/img/m4.png' },
  { name: 'Mouna TOUMI', role: 'Cheffe d’équipe Développement Web', image: '/asset/img/m5.png' },
  { name: 'Ahmed MESKINE', role: 'Chef d’équipe Développement Web', image: '/asset/img/m6.png' }
];

const members = [
  { name: 'Islem ABDERAHMEN', role: 'Ingénieure logiciel & IA', image: '/asset/img/m10.png' },
  { name: 'Soumaya ABDERAHMEN', role: 'Ingénieure Cloud & DevOps', image: '/asset/img/m10.png' },
  { name: 'Oumaima JLIDI', role: 'Ingénieure Fullstack JS & IA', image: '/asset/img/m11.png' },
  { name: 'Marwa KAABAR', role: 'Ingénieure Fullstack JS & IA', image: '/asset/img/m12.png' },
  { name: 'Yessin MELLITI', role: 'Développeur Web', image: '/asset/img/m13.png' },
  { name: 'Fakhri CHARGUI', role: 'Développeur Web', image: '/asset/img/m14.png' },
  { name: 'Imen OTHMANI', role: 'Designer UX/UI', image: '/asset/img/m7.png' },
  { name: 'Nour KHIARI', role: 'Développeuse Web', image: '/asset/img/m8.png' }
];

// Animation de l'assistant - améliorée avec interactions fluides
onMounted(() => {
  const assistantIcon = document.querySelector('.assistant-icon');
  const assistantBubble = document.querySelector('.assistant-bubble');
  
  if (assistantIcon && assistantBubble) {
    // Affichage initial après 3 secondes
    setTimeout(() => {
      assistantBubble.classList.remove('d-none');
      assistantBubble.classList.add('bubble-animate');
      
      // Masquer après 5 secondes
      setTimeout(() => {
        assistantBubble.classList.remove('bubble-animate');
        assistantBubble.classList.add('bubble-hide');
        
        setTimeout(() => {
          assistantBubble.classList.add('d-none');
          assistantBubble.classList.remove('bubble-hide');
        }, 500);
      }, 5000);
    }, 3000);
    
    // Interactions au survol
    assistantIcon.addEventListener('mouseover', () => {
      assistantBubble.classList.remove('d-none');
      assistantBubble.classList.add('bubble-animate');
    });
    
    assistantIcon.addEventListener('mouseout', () => {
      setTimeout(() => {
        assistantBubble.classList.remove('bubble-animate');
        assistantBubble.classList.add('bubble-hide');
        
        setTimeout(() => {
          assistantBubble.classList.add('d-none');
          assistantBubble.classList.remove('bubble-hide');
        }, 500);
      }, 2000);
    });
  }
  
  // Animation de défilement fluide pour les ancres
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
      e.preventDefault();
      
      const targetId = this.getAttribute('href').substring(1);
      const targetElement = document.getElementById(targetId);
      
      if (targetElement) {
        window.scrollTo({
          top: targetElement.offsetTop - 80,
          behavior: 'smooth'
        });
      }
    });
  });
  
  // Animation d'apparition des sections au scroll
  const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -100px 0px'
  };
  
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('section-visible');
        observer.unobserve(entry.target);
      }
    });
  }, observerOptions);
  
  document.querySelectorAll('section').forEach(section => {
    section.classList.add('section-hidden');
    observer.observe(section);
  });
});
</script>

<style scoped>
/* Importation des polices et icons */
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
@import url('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css');

/* Reset et styles globaux */
.team-page {
  font-family: 'Inter', sans-serif;
  color: #192339;
  background-color: #FAFBFF;
}

/* Variables de couleurs pour un design cohérent */
:root {
  --primary-color: #2563EB;
  --primary-dark: #1E40AF;
  --secondary-color: #F9FAFB;
  --accent-color: #EAB308;
  --text-primary: #192339;
  --text-secondary: #4B5563;
  --text-light: #9CA3AF;
  --shadow-sm: 0 5px 15px rgba(0, 0, 0, 0.05);
  --shadow-md: 0 10px 25px rgba(0, 0, 0, 0.1);
  --shadow-lg: 0 20px 40px rgba(0, 0, 0, 0.15);
}

/* Section spacing */
.section-space {
  margin-bottom: 120px;
  padding-top: 20px;
}

/* Header Hero avec design moderne */
.hero {
  min-height: 550px;
  position: relative;
}

.hero-image-container {
  position: relative;
  height: 550px;
  overflow: hidden;
}

.hero-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  filter: brightness(0.9);
}

.overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(90deg, rgba(0, 0, 30, 0.85) 0%, rgba(20, 20, 100, 0.65) 100%);
  z-index: 1;
}

.hero-title {
  font-size: 4rem;
  font-weight: 700;
  margin-bottom: 1rem;
  line-height: 1.2;
  letter-spacing: -0.02em;
  color: white;
  text-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

.hero-subtitle {
  font-size: 1.25rem;
  font-weight: 400;
  color: rgba(255, 255, 255, 0.9);
  max-width: 600px;
  line-height: 1.6;
}

/* Section headers */
.section-header {
  margin-bottom: 60px;
}

.section-badge {
  display: inline-block;
  padding: 0.5rem 1.25rem;
  background-color: rgba(37, 99, 235, 0.1);
  color: var(--primary-color);
  border-radius: 50px;
  font-weight: 600;
  font-size: 0.875rem;
  margin-bottom: 1.25rem;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.section-title {
  font-size: 2.5rem;
  font-weight: 700;
  margin-bottom: 1.5rem;
  letter-spacing: -0.02em;
  color: var(--text-primary);
}

.section-divider {
  height: 4px;
  width: 60px;
  background: linear-gradient(90deg, var(--primary-color), var(--primary-dark));
  border-radius: 2px;
  margin: 0 auto 1.5rem;
}

.section-subtitle {
  font-size: 1.125rem;
  color: var(--text-secondary);
  max-width: 700px;
  margin: 0 auto;
  line-height: 1.6;
}

/* Cartes de fondateurs avec design premium */
.founder-card {
  background-color: white;
  border-radius: 16px;
  overflow: hidden;
  box-shadow: var(--shadow-md);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  border: 1px solid rgba(235, 238, 251, 0.8);
}

.founder-card:hover {
  transform: translateY(-8px);
  box-shadow: var(--shadow-lg);
}



.founder-image {
  height: 100%;
  width: 100%;
  object-fit: cover;
  transition: transform 0.5s ease;
}

.founder-card:hover .founder-image {
  transform: scale(1.03);
}

.founder-content {
  padding: 2rem;
}

.founder-name {
  font-size: 1.75rem;
  font-weight: 700;
  color: var(--text-primary);
  margin-bottom: 0.5rem;
}

.founder-role {
  font-size: 1rem;
  font-weight: 600;
  color: var(--primary-color);
  margin-bottom: 1.25rem;
}

.founder-bio {
  color: var(--text-secondary);
  line-height: 1.6;
}

/* Cartes d'équipe avec design uniforme */
.team-card {
  background-color: white;
  border-radius: 16px;
  overflow: hidden;
  box-shadow: var(--shadow-sm);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  border: 1px solid rgba(235, 238, 251, 0.8);
}

.team-card:hover {
  transform: translateY(-8px);
  box-shadow: var(--shadow-md);
}



.team-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.6s ease;
}

.team-card:hover .team-image {
  transform: scale(1.05);
}

.team-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(to top, rgba(37, 99, 235, 0.8), transparent 70%);
  opacity: 0;
  display: flex;
  align-items: flex-end;
  justify-content: center;
  padding-bottom: 30px;
  transition: opacity 0.3s ease;
}

.team-card:hover .team-overlay {
  opacity: 1;
}

.social-links {
  display: flex;
  gap: 12px;
}

.social-link {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
  background-color: white;
  border-radius: 50%;
  color: var(--primary-color);
  font-size: 18px;
  transition: all 0.2s ease;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.social-link:hover {
  background-color: var(--primary-color);
  color: rgb(190, 143, 143);
  transform: scale(1.1);
}

.team-content {
  padding: 1.5rem;
  text-align: center;
}

.member-name {
  font-size: 1.25rem;
  font-weight: 700;
  color: var(--text-primary);
  margin-bottom: 0.5rem;
}

.member-role {
  font-size: 0.95rem;
  font-weight: 500;
  color: var(--primary-color);
  margin-bottom: 0;
}

/* Section citation avec design premium */
.team-quote {
  background-color: white;
  border-radius: 20px;
  box-shadow: var(--shadow-md);
  padding: 4rem 2rem;
  position: relative;
  margin: 100px 0;
  border: 1px solid rgba(235, 238, 251, 0.8);
}

.quote-content {
  max-width: 800px;
  position: relative;
}

.quote-icon {
  font-size: 4rem;
  color: var(--primary-color);
  opacity: 0.15;
  position: absolute;
  top: -60px;
  left: 50%;
  transform: translateX(-50%);
}

.quote-text {
  font-size: 1.5rem;
  font-weight: 500;
  font-style: italic;
  line-height: 1.6;
  color: var(--text-primary);
  margin-bottom: 2rem;
}

.quote-author {
  font-weight: 700;
  font-size: 1.125rem;
  color: var(--text-primary);
}

/* Section CTA */
.cta-section {
  background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
  border-radius: 20px;
  padding: 4rem 2rem !important;
  margin: 80px 0;
  color: white;
  box-shadow: var(--shadow-md);
}

.cta-title {
  font-size: 2rem;
  font-weight: 700;
}

.cta-text {
  font-size: 1.125rem;
  max-width: 600px;
  margin: 0 auto;
  opacity: 0.9;
}

/* Boutons avec design moderne */
.btn-primary {
  background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
  border: none;
  box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
  font-weight: 600;
  letter-spacing: 0.5px;
  transition: all 0.3s ease;
}

.btn-primary:hover {
  transform: translateY(-3px);
  box-shadow: 0 8px 16px rgba(37, 99, 235, 0.35);
}








@keyframes fadeInUp {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}

@keyframes fadeOutDown {
  from { opacity: 1; transform: translateY(0); }
  to { opacity: 0; transform: translateY(10px); }
}

/* Animation d'apparition des sections */
.section-hidden {
  opacity: 0;
  transform: translateY(30px);
  transition: opacity 0.6s ease, transform 0.6s ease;
}

.section-visible {
  opacity: 1;
  transform: translateY(0);
}

/* Responsive adjustments */
@media (max-width: 992px) {
  .hero-title {
    font-size: 3rem;
  }
  
  .section-title {
    font-size: 2rem;
  }
  
  .quote-text {
    font-size: 1.25rem;
  }
}

@media (max-width: 768px) {
  .hero {
    min-height: 450px;
  }
  
  .hero-image-container {
    height: 450px;
  }
  
  .hero-title {
    font-size: 2.5rem;
  }
  
  .founder-card .row {
    flex-direction: column;
  }
  
.founder-image-container {
  height: 100%;
  min-height: 220px; /* ← Réduit */
  width: 100%;
  overflow: hidden;
}

.team-image-wrapper {
  position: relative;
  height: 240px; /* ← Réduit */
  overflow: hidden;
}

  
  .section-space {
    margin-bottom: 80px;
  }
  
  .team-quote {
    padding: 3rem 1.5rem;
    margin: 60px 0;
  }
}

@media (max-width: 576px) {
  .hero-title {
    font-size: 2rem;
  }
  
  .hero-subtitle {
    font-size: 1rem;
  }
  
  .section-title {
    font-size: 1.75rem;
  }
  
 
}
</style>

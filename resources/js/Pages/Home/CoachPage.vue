<script setup>
import { ref, onMounted } from 'vue';

const experts = [
  {
    name: "Ahmed Landolsi",
    role: "UI/UX Designer",
    image: "/asset/img/ahmed.png",
  },
  {
    name: "Asma Iaazizi",
    role: "UI/UX Designer",
    image: "/asset/img/asma.png",
  },
  {
    name: "Mounir Salem",
    role: "Full stack developer",
    image: "/asset/img/mounir.png",
  },
  {
    name: "Ameni Samoudi",
    role: "Front-end",
    image: "/asset/img/ameni.png",
  },
  {
    name: "Mohamed Alfa",
    role: "Graphic designer",
    image: "/asset/img/mohamed.png",
  },
  {
    name: "Samer Khiari",
    role: "Marketer",
    image: "/asset/img/samer.png",
  },
  {
    name: "Ala Bensalem",
    role: "UI/UX Designer",
    image: "/asset/img/ala.png",
  },
  {
    name: "Ismail Fekih",
    role: "Back-end",
    image: "/asset/img/ismail.png",
  },
  {
    name: "Alma Habibi",
    role: "UI/UX Designer",
    image: "/asset/img/alma.png",
  },
];

const whyCoachReasons = [
  {
    title: 'Aidez les Startups à réussir',
    description: 'Apportez vos conseils aux entrepreneurs.',
    icon: 'bi-lightbulb-fill'
  },
  {
    title: 'Booster votre visibilité',
    description: 'Faites-vous connaître des startups et investisseurs.',
    icon: 'bi-graph-up-arrow'
  },
  {
    title: 'Monétisez votre expertise',
    description: 'Proposez du coaching ou des formations.',
    icon: 'bi-currency-exchange'
  },
  {
    title: 'Accédez à un réseau dynamique',
    description: 'Échangez avec d\'autres experts.',
    icon: 'bi-people-fill'
  }
];

const features = [
  {
    image: '/asset/img/coach2.png',
    title: 'Suivi & Gestion des Startups',
    animation: 'fade-right'
  },
  {
    image: '/asset/img/coach3.png',
    title: 'Évaluation & Validation',
    animation: 'fade-up'
  },
  {
    image: '/asset/img/coach4.png',
    title: 'Planification des sessions',
    animation: 'fade-up'
  },
  {
    image: '/asset/img/coach5.png',
    title: 'Networking & Mise en Relation',
    animation: 'fade-left'
  }
];

const isVisible = ref(false);

onMounted(() => {
  isVisible.value = true;
  
  // Initialize AOS animation library
  if (typeof AOS !== 'undefined') {
    AOS.init({
      duration: 1000,
      once: true,
      offset: 100
    });
  }
  
  // Initialize intersection observer for scroll animations
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('in-view');
      }
    });
  }, { threshold: 0.1 });
  
  document.querySelectorAll('.animate-on-scroll').forEach(el => {
    observer.observe(el);
  });
});
</script>

<template>
  <div class="coach-page">
    <!-- Hero Section with Parallax -->
    <section class="hero-section position-relative overflow-hidden" data-aos="fade-in">
      <div class="parallax-bg"></div>
      <div class="container position-relative z-index-1">
        <div class="row min-vh-80 align-items-center">
          <div class="col-lg-7 text-start py-5" data-aos="fade-right" data-aos-delay="200">
            <h1 class="hero-title mb-4" style="color:">
              <span class="text-gradient">Encadré par les meilleurs experts et mentors</span>
            </h1 >
            <p class="hero-subtitle mb-5">
              BrainCode Startup Studio réunit des experts et mentors de haut niveau à travers tout le pays.
            </p>
            <a href="#" class="btn-primary-gradient px-4 py-3 rounded-pill">
              Devenir mentor
              <i class="bi bi-arrow-right ms-2"></i>
            </a>
          </div>
        </div>
      </div>
    </section>

    <!-- Pourquoi devenir coach - Cards with hover effect -->
    <section class="why-coach py-5">
      <div class="container">
        <div class="text-center mb-5">
          <h3 class="section-title position-relative" data-aos="fade-up">
            Pourquoi Devenir <span class="text-gradient">Coach</span> ?
          </h3>
          <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">
            Partagez votre expertise, inspirez et façonnez l'avenir !
          </p>
          <div class="title-underline"></div>
        </div>
        
        <div class="row mt-5">
          <div 
            v-for="(reason, index) in whyCoachReasons" 
            :key="index" 
            class="col-md-3 mb-4"
            data-aos="fade-up"
            :data-aos-delay="index * 100"
          >
            <div class="reason-card h-100">
              <div class="card-icon-wrapper">
                <i :class="`bi ${reason.icon}`"></i>
              </div>
              <h6 class="reason-title">{{ reason.title }}</h6>
              <p class="reason-desc">{{ reason.description }}</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Fonctionnalités - Modern layout with animation -->
    <section class="features-section py-5">
      <div class="container">
        <h3 class="section-title text-center position-relative mb-5" data-aos="fade-up">
          Fonctionnalités Clés de l'<span class="text-gradient">Espace Coach</span>
        </h3>
        
        <div class="row justify-content-center text-center gy-4">
          <div 
            v-for="(feature, index) in features" 
            :key="index" 
            class="col-md-3 col-6"
            :data-aos="feature.animation"
            :data-aos-delay="index * 100"
          >
            <div class="feature-item">
              <div class="feature-icon">
                <img :src="feature.image" :alt="feature.title" class="img-fluid mb-3" />
              </div>
              <p class="feature-text">{{ feature.title }}</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Nos Experts - Card with hover effects -->
    <section class="experts-section py-5 position-relative overflow-hidden">
      <div class="background-pattern"></div>
      <div class="container position-relative z-index-1">
        <h3 class="section-title text-center mb-5" data-aos="fade-up">
          Nos <span class="text-gradient">experts</span>
        </h3>
        
        <div class="row gy-4">
          <div 
            v-for="(expert, index) in experts" 
            :key="index" 
            class="col-lg-4 col-md-6"
            data-aos="fade-up"
            :data-aos-delay="(index % 3) * 100"
          >
            <div class="expert-card">
              <div class="expert-image-wrapper">
                <img :src="expert.image" class="img-fluid" :alt="expert.name" />
                <div class="expert-overlay">
                  <div class="expert-social">
                    <a href="#" class="social-icon"><i class="bi bi-linkedin"></i></a>
                    <a href="#" class="social-icon"><i class="bi bi-globe"></i></a>
                  </div>
                </div>
              </div>
              <div class="expert-info">
                <h5 class="expert-name">{{ expert.name }}</h5>
                <p class="expert-role">{{ expert.role }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Appel à l'action - Parallax Effect -->
    <section class="cta-section text-center py-5" data-aos="fade-up">
      <div class="container">
        <div class="cta-content py-4">
          <h3 class="cta-title mb-4" data-aos="zoom-in">Devenez coach chez <span class="text-gradient">BrainCode</span> !</h3>
          <p style="color: white;" class="cta-desc mb-4" data-aos="zoom-in" data-aos-delay="100">
            Rejoignez-nous pour accompagner les startups dans leur croissance.
          </p>
          <a href="#" class="btn-primary-gradient px-4 py-3 rounded-pill" data-aos="zoom-in" data-aos-delay="200">
            Postuler maintenant
            <i class="bi bi-arrow-right ms-2"></i>
          </a>
        </div>
      </div>
    </section>

    <!-- Pourquoi nous rejoindre - Modern design -->
    <section class="why-join-section py-5">
      <div class="container">
        <div class="why-join-wrapper position-relative overflow-hidden">
          <div class="why-join-bg"></div>
          <div class="why-join-content py-5 px-4">
            <h4 class="why-join-title mb-4" data-aos="fade-right">Pourquoi nous rejoindre ?</h4>
            
            <div class="row g-4" data-aos="fade-up" data-aos-delay="100">
              <div class="col-md-6">
                <ul class="benefits-list">
                  <li class="benefit-item animate-on-scroll">
                    <span class="benefit-icon"><i class="bi bi-check-circle-fill"></i></span>
                    <span class="benefit-text">Partagez votre savoir-faire.</span>
                  </li>
                  <li class="benefit-item animate-on-scroll">
                    <span class="benefit-icon"><i class="bi bi-check-circle-fill"></i></span>
                    <span class="benefit-text">Développez votre expertise.</span>
                  </li>
                </ul>
              </div>
              <div class="col-md-6">
                <ul class="benefits-list">
                  <li class="benefit-item animate-on-scroll">
                    <span class="benefit-icon"><i class="bi bi-check-circle-fill"></i></span>
                    <span class="benefit-text">Rejoignez des événements exclusifs.</span>
                  </li>
                  <li class="benefit-item animate-on-scroll">
                    <span class="benefit-icon"><i class="bi bi-check-circle-fill"></i></span>
                    <span class="benefit-text">Collaborez avec les meilleurs.</span>
                  </li>
                </ul>
              </div>
            </div>
            
            <div class="text-center mt-5" data-aos="fade-up" data-aos-delay="200">
              <a href="#" class="btn-secondary-gradient px-4 py-3 rounded-pill">
                Postuler en tant que coach
                <i class="bi bi-arrow-right ms-2"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
    </section>

    
  </div>
</template>

<style scoped>
/* Base Styles */
@import url('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css');

.coach-page {
  font-family: 'Poppins', sans-serif;
  color: #333;
  overflow-x: hidden;
}

.z-index-1 {
  z-index: 1;
}

.min-vh-80 {
  min-height: 80vh;
}

/* Typography */
.section-title {
  font-weight: 700;
  font-size: 2.5rem;
  margin-bottom: 1.5rem;
}

.section-subtitle {
  font-size: 1.2rem;
  color: #6c757d;
  max-width: 700px;
  margin: 0 auto;
}

.text-gradient {
  background: linear-gradient(90deg, #3f51b5, #2196f3);
  -webkit-background-clip: text;
  background-clip: text;
  color: transparent;
}
.highlight{
color: linear-gradient(90deg, #3f51b5, #2196f3);

}
.title-underline {
  width: 80px;
  height: 4px;
  background: linear-gradient(90deg, #3f51b5, #2196f3);
  margin: 1rem auto;
  border-radius: 2px;
}

/* Buttons */
.btn-primary-gradient {
  background: linear-gradient(90deg, #3f51b5, #2196f3);
  color: white;
  text-decoration: none;
  font-weight: 600;
  display: inline-block;
  transition: all 0.3s ease;
  border: none;
  box-shadow: 0 4px 15px rgba(63, 81, 181, 0.3);
}

.btn-primary-gradient:hover {
  transform: translateY(-3px);
  box-shadow: 0 8px 25px rgba(63, 81, 181, 0.4);
}

.btn-secondary-gradient {
  background: linear-gradient(90deg, #3f51b5, #2196f3);
  color: white;
  text-decoration: none;
  font-weight: 600;
  display: inline-block;
  transition: all 0.3s ease;
  border: none;
  box-shadow: 0 4px 15px rgba(93, 34, 255, 0.3);
}

.btn-secondary-gradient:hover {
  transform: translateY(-3px);
  box-shadow: 0 4px 15px rgba(93, 34, 255, 0.3);
}

/* Hero Section */
.hero-section {
  position: relative;
  padding: 100px 0;
  color: white;
}

.parallax-bg {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-image: url('/asset/img/bgcoach.png');
  background-size: cover;
  background-position: center;
  background-attachment: fixed;
  opacity: 0.9;
  filter: brightness(0.7);
}

.hero-title {
  font-size: 3.5rem;
  font-weight: 800;
  line-height: 1.2;
  margin-bottom: 1.5rem;
}



.hero-subtitle {
  font-size: 1.3rem;
  max-width: 600px;
}

/* Why Coach Section */
.why-coach {
  background-color: #f8f9fa;
  padding: 80px 0;
}

.reason-card {
  background: white;
  border-radius: 15px;
  padding: 30px 20px;
  text-align: center;
  transition: all 0.3s ease;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
  height: 100%;
}

.reason-card:hover {
  transform: translateY(-10px);
  box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
}

.card-icon-wrapper {
  width: 70px;
  height: 70px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, #3f51b5, #2196f3);
  border-radius: 50%;
  margin: 0 auto 20px;
  color: white;
  font-size: 28px;
}

.reason-title {
  font-weight: 700;
  margin-bottom: 15px;
  color: #333;
  font-size: 1.2rem;
}

.reason-desc {
  color: #6c757d;
  font-size: 0.95rem;
}

/* Features Section */
.features-section {
  padding: 80px 0;
  background-color: white;
}

.feature-item {
  transition: all 0.3s ease;
  padding: 20px;
}

.feature-item:hover {
  transform: translateY(-5px);
}

.feature-icon {
  height: 100px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 15px;
}

.feature-icon img {
  max-height: 80px;
  transition: all 0.3s ease;
}

.feature-item:hover .feature-icon img {
  transform: scale(1.1);
}

.feature-text {
  font-weight: 600;
  color: #3f51b5;
  font-size: 1rem;
}

/* Experts Section */
.experts-section {
  background-color: #f8f9fa;
  padding: 80px 0;
}

.background-pattern {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-image: radial-gradient(#3f51b5 1px, transparent 1px);
  background-size: 20px 20px;
  opacity: 0.05;
}

.expert-card {
  background: white;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
  transition: all 0.3s ease;
}

.expert-card:hover {
  transform: translateY(-10px);
  box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
}

.expert-image-wrapper {
  position: relative;
  overflow: hidden;
}

.expert-image-wrapper img {
  width: 100%;
  transition: all 0.5s ease;
}

.expert-card:hover .expert-image-wrapper img {
  transform: scale(1.05);
}

.expert-overlay {
  position: absolute;
  bottom: -50px;
  left: 0;
  width: 100%;
  padding: 10px;
  background: linear-gradient(to top, rgba(250, 246, 246, 0.7), transparent);
  transition: all 0.3s ease;
  opacity: 0;
}

.expert-card:hover .expert-overlay {
  bottom: 0;
  opacity: 1;
}

.expert-social {
  display: flex;
  justify-content: center;
  gap: 15px;
}

.social-icon {
  width: 35px;
  height: 35px;
  border-radius: 50%;
  background: white;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #3f51b5;
  transition: all 0.3s ease;
}

.social-icon:hover {
  background: #3f51b5;
  color: white;
  transform: translateY(-3px);
}

.expert-info {
  padding: 20px;
  text-align: center;
}

.expert-name {
  font-weight: 700;
  margin-bottom: 5px;
  color: #333;
}

.expert-role {
  color: #3f51b5;
  font-size: 0.9rem;
  margin: 0;
}

/* CTA section */
.cta-section {
  background: linear-gradient(135deg, rgba(63, 81, 181, 0.9), rgba(33, 150, 243, 0.9)), url('/asset/img/bgcoach.png');
  background-size: cover;
  background-position: center;
  background-attachment: fixed;
  color: white;
  padding: 100px 0;
}

.cta-content {
  max-width: 700px;
  margin: 0 auto;
}

.cta-title {
  font-size: 2.8rem;
  font-weight: 800;
}

.cta-desc {
  font-size: 1.2rem;
  margin-bottom: 2rem;
}

/* Why Join Section */
.why-join-section {
  padding: 80px 0;
  background-color: white;
}

.why-join-wrapper {
  border-radius: 15px;
  overflow: hidden;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.why-join-bg {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-image: url('/asset/img/bgpq.png');
  background-size: cover;
  background-position: center;
  opacity: 0.2;
}

.why-join-content {
  position: relative;
  z-index: 1;
}

.why-join-title {
  font-size: 2.2rem;
  font-weight: 700;
  color: #333;
  position: relative;
  display: inline-block;
}

.why-join-title:after {
  content: "";
  position: absolute;
  bottom: -10px;
  left: 0;
  width: 80px;
  height: 4px;
  background: linear-gradient(135deg, #3f51b5, #2196f3);
  border-radius: 2px;
}

.benefits-list {
  list-style: none;
  padding: 0;
  margin: 0;
}

.benefit-item {
  display: flex;
  align-items: center;
  margin-bottom: 20px;
  transform: translateX(-20px);
  opacity: 0;
  transition: all 0.5s ease;
}

.benefit-item.in-view {
  transform: translateX(0);
  opacity: 1;
}

.benefit-icon {
  width: 30px;
  height: 30px;
  min-width: 30px;
  border-radius: 50%;
  background: linear-gradient(135deg, #3f51b5, #2196f3);
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 14px;
  margin-right: 15px;
}

.benefit-text {
  font-size: 1rem;
  color: #333;
}

/* Assistant */
.assistant-wrapper {
  position: fixed;
  right: 20px;
  bottom: 20px;
  display: flex;
  align-items: flex-end;
  z-index: 10;
  opacity: 0;
  transform: translateY(20px);
  transition: all 0.5s ease;
}

.assistant-wrapper.visible {
  opacity: 1;
  transform: translateY(0);
}

.assistant-image {
  width: 80px;
  height: 80px;
  object-fit: cover;
  border-radius: 50%;
  border: 3px solid #3f51b5;
  box-shadow: 0 5px 15px rgba(63, 81, 181, 0.3);
  cursor: pointer;
  transition: all 0.3s ease;
  animation: pulse 2s infinite;
}

.assistant-image:hover {
  transform: scale(1.1);
  box-shadow: 0 8px 25px rgba(63, 81, 181, 0.4);
}

.assistant-bubble {
  background: white;
  padding: 8px 15px;
  border-radius: 20px;
  box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
  margin-right: 10px;
  font-size: 0.9rem;
  position: relative;
  opacity: 0;
  transform: translateX(20px);
  transition: all 0.3s ease 0.5s;
}

.assistant-bubble:after {
  content: "";
  position: absolute;
  right: -8px;
  bottom: 10px;
  width: 0;
  height: 0;
  border-left: 10px solid white;
  border-top: 8px solid transparent;
  border-bottom: 8px solid transparent;
}

.assistant-wrapper.visible .assistant-bubble {
  opacity: 1;
  transform: translateX(0);
}

/* Animations */
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

@keyframes pulse {
  0% {
    box-shadow: 0 0 0 0 rgba(63, 81, 181, 0.4);
  }
  70% {
    box-shadow: 0 0 0 10px rgba(63, 81, 181, 0);
  }
  100% {
    box-shadow: 0 0 0 0 rgba(63, 81, 181, 0);
  }
}

/* Responsive adjustments */
@media (max-width: 991px) {
  .hero-title {
    font-size: 2.8rem;
  }
  
  .section-title {
    font-size: 2rem;
  }
  
  .cta-title {
    font-size: 2.2rem;
  }
}

@media (max-width: 767px) {
  .hero-title {
    font-size: 2.2rem;
  }
  
  .section-title {
    font-size: 1.8rem;
  }
  
  .cta-title {
    font-size: 1.8rem;
  }
  
  .why-join-title {
    font-size: 1.8rem;
  }
}
</style>
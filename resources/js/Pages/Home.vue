
<script setup>
import { ref, onMounted, computed } from "vue";
import { Head } from "@inertiajs/vue3";


// Theme state with improved persistence (keeping from original code)
const isDarkMode = ref(false);

// Enhanced theme detection and persistence (keeping from original code)
onMounted(() => {
  const savedTheme = localStorage.getItem("theme");
  const prefersDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
  
  // Priority: Saved preference > System preference
  isDarkMode.value = savedTheme 
    ? savedTheme === "dark" 
    : prefersDarkMode;

  document.documentElement.classList.toggle('dark', isDarkMode.value);
  
  // Animation setup for testimonials
  setTimeout(() => {
    testimonials.value.forEach((testimonial, index) => {
      testimonial.visible = true;
    });
  }, 500);
});

// Les témoignages avec des données plus réalistes et des ratings variés
const testimonials = ref([
  {
    id: 1,
    company: "TechNova",
    logo: "asset/img/abs.png",
    text: "Grâce à BrainCode, nous avons pu accélérer notre développement et lever 1,2M€ en moins de 6 mois. L'accompagnement personnalisé a été un facteur décisif dans notre réussite.",
    rating: 5,
    author: "Marie Dupont, CEO",
    visible: false
  },
  {
    id: 2,
    company: "EcoSolutions",
    logo: "asset/img/abs.png",
    text: "Le réseau d'experts de BrainCode nous a permis de surmonter des obstacles techniques majeurs et d'améliorer considérablement notre produit final.",
    rating: 4,
    author: "Thomas Martin, CTO",
    visible: false
  },
  {
    id: 3,
    company: "HealthConnect",
    logo: "asset/img/abs.png",
    text: "En rejoignant BrainCode, nous avons non seulement bénéficié d'un excellent mentorat, mais aussi d'une communauté solidaire d'entrepreneurs qui nous a soutenus à chaque étape.",
    rating: 5,
    author: "Julie Leblanc, Fondatrice",
    visible: false
  }
]);
const onImgError = (event) => {
  event.target.src = 'asset/img/abs.png'; // ou '/assets/img/abs.png' si tu veux l’ancienne
};

// Fonction pour générer les étoiles de rating
const getRatingStars = (rating) => {
  let stars = [];
  for (let i = 1; i <= 5; i++) {
    stars.push(i <= rating);
  }
  return stars;
};

// Animation pour les cartes au survol
const hoverCard = (testimonialId) => {
  const updatedTestimonials = testimonials.value.map(t => {
    if (t.id === testimonialId) {
      return { ...t, hover: true };
    }
    return { ...t, hover: false };
  });
  testimonials.value = updatedTestimonials;
};

const resetHover = () => {
  const updatedTestimonials = testimonials.value.map(t => {
    return { ...t, hover: false };
  });
  testimonials.value = updatedTestimonials;
};

// Conserver les autres données du code original
const services = [
  {
    icon: "/image/startup/mentoring.png",
    title: "Accompagnement personnalisé",
    description: "Coaching par des entrepreneurs expérimentés"
  },
  {
    icon: "/image/startup/chart.png",
    title: "Suivi précis d'indicateurs",
    description: "KPIs et tableaux de bord pour mesurer votre progression"
  },
  {
    icon: "/image/startup/network.png",
    title: "Réseau d'experts",
    description: "Accès à notre communauté de spécialistes par domaine"
  },
  {
    icon: "/image/startup/funding.png",
    title: "Accès aux financements",
    description: "Mise en relation avec investisseurs et business angels"
  }
];

const beneficiaries = [
  {
    image: "/image/startup/startups-group.jpeg",
    title: "Startups",
    description: "Accélérez votre développement et atteignez vos objectifs plus rapidement grâce à notre programme structuré.",
    buttonText: "Candidater au programme"
  },
  {
    image: "/image/startup/coaches-group.jpeg",
    title: "Coachs",
    description: "Rejoignez notre réseau de mentors et partagez votre expertise avec la prochaine génération d'entrepreneurs.",
    buttonText: "Devenir coach"
  },
  {
    image: "/image/startup/investors-group.jpeg",
    title: "Investisseurs",
    description: "Découvrez des opportunités d'investissement présélectionnées dans des startups à fort potentiel de croissance.",
    buttonText: "Explorer les opportunités"
  }
];
</script>
<template>
 <section class="py-16 md:py-24" style="background: linear-gradient(to bottom, #F1F9FF)">
      <div class="max-w-6xl mx-auto px-6 flex flex-col md:flex-row items-center">
        <div class="md:w-1/2 mb-10 md:mb-0">
          <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-6">
            <span class="text-custom-blue ">Lancez, développez et</span> <br>
            <span class="text-custom-blue ">financez votre startup avec</span><br>
            <span class="text-blue-500">BraindCode</span>
          </h1>
          <p class="text-lg mb-8 ">
            Transformez votre idée en entreprise prospère grâce à notre programme d'accompagnement complet.
            </p>

          <a href="#" class="inline-block bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-8 rounded-lg transform transition hover:scale-105">
            Découvrir la plateforme
          </a>
        </div>
        <div class="md:w-1/2">
          <img 
            src="/image/startup/entrepreneur.png" 
            alt="Entrepreneur illustration" 
            class="max-w-full h-auto"
          />
        </div>
      </div>
    </section>
  <section class="py-16 bg-white ">
      <div class="max-w-5xl mx-auto px-6 text-center">
        <div class="flex items-center justify-center mb-4">
          <h2 class="text-3xl font-bold text-gray-900 text-custom-blue">
            Qu'est-ce que Braincode Startup Studio
          </h2>
          <img 
            src="/image/startup/rocket-icon.png" 
            alt="Rocket icon" 
            class="w-10 h-10 ml-3"
          />
        </div>
        
        <p class="text-lg text-gray-700  max-w-3xl mx-auto mb-8">
          BrainCode Startup Studio est un programme d'accélération innovant qui accompagne les entrepreneurs à chaque étape du développement de leur startup, de l'idéation au financement. Notre écosystème unique réunit mentors, investisseurs et experts pour maximiser vos chances de succès.
        </p>
        
        <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg transition hover:shadow-lg">
          Découvrir l'équipe
        </button>
      </div>
    </section>
  <section class="py-16 bg-gray-50" style="background: linear-gradient(to bottom, #F1F9FF)">
    <div class="max-w-6xl mx-auto px-6">
      <h2 class="text-3xl font-bold text-center mb-12 text-gray-900 text-custom-blue">
        Que proposons-nous?
      </h2>
      
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div 
          v-for="(service, index) in services" 
          :key="index"
          class="bg-white  rounded-lg shadow-md p-6 transition hover:shadow-lg hover:transform hover:scale-105"
        >
          <div class="flex justify-center mb-4">
            <img :src="service.icon" :alt="service.title" class="w-16 h-16" />
          </div>
          <h3 class="text-lg font-semibold text-center mb-2 text-gray-900 text-custom-blue">
            {{ service.title }}
          </h3>
          <p class="text-gray-600  text-center">
            {{ service.description }}
          </p>
        </div>
      </div>
    </div>
  </section>   
   <section class="py-16 bg-white ">
    <div class="max-w-6xl mx-auto px-6">
      <h2 class="text-3xl font-bold text-center mb-12 text-gray-900 text-custom-blue">
        Qui peut bénéficier de BrainCode Startup Studio
      </h2>
      
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div 
          v-for="(item, index) in beneficiaries" 
          :key="index"
          class="bg-white  rounded-lg shadow-md overflow-hidden transition hover:shadow-xl"
        >
          <img :src="item.image" :alt="item.title" class="w-full h-48 object-cover" />
          <div class="p-6">
            <h3 class="text-xl font-bold mb-3 text-center text-gray-900 text-custom-blue">
              {{ item.title }}
            </h3>
            <p class="text-gray-600  mb-6 text-center">
              {{ item.description }}
            </p>
            <div class="text-center">
              <button class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition">
                {{ item.buttonText }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
<section class="py-16 bg-gray-50" style="background: linear-gradient(to bottom, #F1F9FF 50%, white 100%)">
    <div class="max-w-6xl mx-auto px-6">
      <div class="text-center mb-12">
        <h2 class="text-3xl font-bold text-custom-blue mb-4">Ce que disent nos startups</h2>
        <p class="text-lg text-gray-600 max-w-3xl mx-auto">
          Découvrez les témoignages de celles et ceux qui ont fait l'expérience de notre programme d'incubation.
        </p>
      </div>
      
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div 
          v-for="testimonial in testimonials" 
          :key="testimonial.id"
          class="relative overflow-hidden"
          @mouseenter="hoverCard(testimonial.id)"
          @mouseleave="resetHover()"
        >
          <!-- Carte avec animation -->
          <div 
            class="bg-white rounded-xl shadow-lg p-6 h-full transition-all duration-500 ease-in-out"
            :class="{
              'transform translate-y-0': testimonial.visible,
              'transform translate-y-16 opacity-0': !testimonial.visible,
              'transform scale-105 shadow-xl': testimonial.hover
            }"
            :style="{
              'transition-delay': `${testimonial.id * 150}ms`,
              'background': 'linear-gradient(135deg, #ffffff, #f0f7ff)'
            }"
          >
            <!-- En-tête de carte -->
            <div class="flex items-center mb-4">
              <div class="bg-blue-50 p-3 rounded-full mr-4">
<img 
  :src="testimonial.logo" 
  @error="onImgError"
/>

               
              </div>
              <div>
                <h3 class="font-bold text-xl text-custom-blue">{{ testimonial.company }}</h3>
                <p class="text-gray-600 text-sm">{{ testimonial.author }}</p>
              </div>
            </div>
            
            <!-- Contenu du témoignage -->
            <div class="mb-4 relative">
              <svg class="absolute text-blue-100 -top-2 -left-2 w-8 h-8 opacity-50" fill="currentColor" viewBox="0 0 24 24">
                <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
              </svg>
              <p class="text-gray-700 text-base italic pl-6">
                {{ testimonial.text }}
              </p>
            </div>
            
            <!-- Étoiles de notation avec animation -->
            <div class="flex items-center">
              <div 
                v-for="(filled, index) in getRatingStars(testimonial.rating)" 
                :key="index"
                class="transition-all duration-300 ease-in-out"
                :class="{
                  'scale-110': testimonial.hover
                }"
                :style="{
                  'transition-delay': `${index * 50}ms`
                }"
              >
                <svg 
                  :class="filled ? 'text-yellow-400' : 'text-gray-300'" 
                  class="w-5 h-5 mr-1" 
                  fill="currentColor" 
                  viewBox="0 0 20 20"
                >
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118l-2.8-2.034c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Bouton amélioré avec animation -->
      <div class="text-center mt-10">
        <a 
          href="/startinc" 
          class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg transition-all duration-300 hover:shadow-lg transform hover:scale-105"
        >
          Découvrir nos startups incubées
          <svg class="w-4 h-4 inline-block ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
          </svg>
        </a>
      </div>
    </div>
  </section>
</template>



<style scoped>
.text-custom-blue {
  color: #003E64;
}

.bg-noise {
  background-image: 
    linear-gradient(to right, rgba(255,255,255,0.05) 1px, transparent 1px),
    linear-gradient(to bottom, rgba(255,255,255,0.05) 1px, transparent 1px);
  background-size: 30px 30px;
}

@keyframes float {
  0% {
    transform: translateY(0px);
  }
  50% {
    transform: translateY(-5px);
  }
  100% {
    transform: translateY(0px);
  }
}

@keyframes pulse {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: 0.8;
  }
}

.animate-float {
  animation: float 3s ease-in-out infinite;
}

.animate-pulse-slow {
  animation: pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}
</style>
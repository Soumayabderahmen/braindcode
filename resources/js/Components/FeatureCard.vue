<template>
    <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition duration-300 overflow-hidden feature-card">
      <div class="p-4 bg-blue-50 image-container">
        <img :src="image" :alt="`Icône de ${title}`" class="h-32 mx-auto feature-image" />
      </div>
      <div class="p-6 card-content">
        <h3 class="text-xl font-semibold text-blue-600 mb-4 card-title">{{ title }}</h3>
        <ul class="space-y-3 text-gray-600 benefits-list">
          <li 
            v-for="(benefit, index) in benefits" 
            :key="index" 
            class="flex items-start benefit-item"
            :style="{ animationDelay: `${index * 100 + 300}ms` }"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mr-2 mt-0.5 check-icon" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            <span>{{ benefit }}</span>
          </li>
        </ul>
        <button class="mt-6 w-full py-2 px-4 bg-blue-100 hover:bg-blue-600 text-blue-600 hover:text-white rounded-md transition-all duration-300 cta-button">
          En savoir plus
        </button>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: 'FeatureCard',
    props: {
      image: {
        type: String,
        required: true
      },
      title: {
        type: String,
        required: true
      },
      benefits: {
        type: Array,
        required: true
      }
    }
  }
  </script>
  
  <style scoped>
  /* Card hover animations */
  .feature-card {
    transform: translateY(0);
    transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
  }
  
  .feature-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
  }
  
  /* Image animations */
  .image-container {
    overflow: hidden;
    transition: all 0.3s ease;
  }
  
  .feature-image {
    transition: transform 0.6s ease;
  }
  
  .feature-card:hover .feature-image {
    transform: scale(1.1) rotate(3deg);
  }
  
  /* Title animations */
  .card-title {
    position: relative;
    display: inline-block;
  }
  
  .card-title::after {
    content: '';
    position: absolute;
    width: 0;
    height: 2px;
    bottom: -4px;
    left: 0;
    background-color: currentColor;
    transition: width 0.4s ease;
  }
  
  .feature-card:hover .card-title::after {
    width: 100%;
  }
  
  /* Benefits list animation */
  .benefit-item {
    opacity: 0;
    transform: translateX(-10px);
    animation: slideIn 0.5s ease forwards;
  }
  
  @keyframes slideIn {
    to {
      opacity: 1;
      transform: translateX(0);
    }
  }
  
  /* Check icon animation */
  .check-icon {
    transition: transform 0.3s ease;
  }
  
  .feature-card:hover .check-icon {
    transform: scale(1.2);
  }
  
  /* CTA Button animation */
  .cta-button {
    position: relative;
    overflow: hidden;
  }
  
  .cta-button::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.2);
    transition: left 0.5s ease;
  }
  
  .feature-card:hover .cta-button::before {
    left: 100%;
  }
  
  /* Card content animation */
  .card-content {
    transition: transform 0.3s ease;
  }
  
  .feature-card:hover .card-content {
    transform: translateY(-5px);
  }
  
  /* Pulse animation for the whole card on page load */
  @keyframes cardPulse {
    0% { box-shadow: 0 0 0 0 rgba(37, 99, 235, 0.2); }
    70% { box-shadow: 0 0 0 15px rgba(37, 99, 235, 0); }
    100% { box-shadow: 0 0 0 0 rgba(37, 99, 235, 0); }
  }
  
  .feature-card {
    animation: cardPulse 2s infinite;
    animation-delay: 1s;
  }
  
  .feature-card:hover {
    animation: none;
  }
  </style>
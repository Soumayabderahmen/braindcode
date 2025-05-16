<template>
  <section class="agent-details py-5">
    <div class="container">
      <!-- Intro -->
      <h2 class="fw-bold text-primary mb-4">Bonjour ! Je suis <span class="text-gradient">Growthy</span></h2>
      <p class="text-muted mb-4 fs-5">Assistant en campagnes publicitaires</p>
      <p class="text-muted">
        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
      </p>

      <!-- Responsabilités -->
      <div class="mt-5">
        <h4 class="fw-bold mb-4">Responsabilités</h4>
        <div class="row g-4">
          <div class="col-md-4" v-for="(block, index) in responsibilities" :key="index">
            <div class="border rounded-4 p-4 shadow-sm h-100 bg-white">
              <h5 class="fw-bold mb-3">{{ block.title }}</h5>
              <ul class="list-unstyled ps-3">
                <li v-for="(item, i) in block.items" :key="i" class="mb-2">
                  <i class="bi bi-check-circle-fill text-primary me-2"></i>{{ item }}
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <!-- Autres agents -->
      <div class="mt-5">
        <h4 class="fw-bold mb-4">Tu pourrais aussi aimer</h4>
        <div class="row g-4">
          <div class="col-md-4" v-for="agent in suggestedAgents" :key="agent.name">
            <div class="card shadow-sm border-0 text-center">
              <img :src="agent.image" :alt="agent.name" class="card-img-top">
              <div class="card-body">
                <h5 class="card-title">{{ agent.name }}</h5>
                <p class="card-text">{{ agent.desc }}</p>
                <a :href="agent.link" class="btn btn-primary btn-sm">Voir plus</a>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>
</template>

<script setup>
const responsibilities = [
  {
    title: "Optimisation des campagnes publicitaires",
    items: [
      "Analyser les performances des publicités (Google Ads, Facebook Ads...)",
      "Recommander des améliorations pour le ROI",
      "Ajuster automatiquement budgets et audiences"
    ]
  },
  {
    title: "Génération et qualification de leads",
    items: [
      "Identifier les prospects via l’analyse comportementale",
      "Automatiser l’envoi de messages personnalisés",
      "Intégrer les leads dans le CRM"
    ]
  },
  {
    title: "Veille et recommandations en marketing digital",
    items: [
      "Suivre les tendances du marché",
      "Fournir des insights SEO et mots-clés",
      "Alerter et ajuster en temps réel"
    ]
  }
];

const suggestedAgents = [
  {
    name: "MarkiBot",
    desc: "Assistant marketing digital",
    image: "/asset/img/markibot.png",
    link: "/agentia2"
  },
  {
    name: "AdGenius",
    desc: "Assistant budgets marketing",
    image: "/asset/img/markibot.png",
    link: "/agentia2"
  },
  {
    name: "FinBot",
    desc: "Assistant gestion financière",
    image: "/asset/img/markibot.png",
    link: "/agentia2"
  }
];
</script>

<style scoped>
.agent-details {
  background: #f8fbff;
}

.text-gradient {
  background: linear-gradient(90deg, #007bff, #00c6ff);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}
</style>

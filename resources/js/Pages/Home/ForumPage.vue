<template>

    <div class="forum-container">
      <!-- Header section -->
      <div class="header-section">
        <div class="header-content">
          <h1 class="main-title">Bienvenue sur le Forum Braindcode !</h1>
          <p class="subtitle">Un espace d'échange et de collaboration entre startups, coachs, investisseurs et experts du domaine.</p>
          
          <!-- Search bar -->
          <div class="search-bar">
            <input type="text" placeholder="Chercher quelque chose..." />
            <button class="search-button">
              <span class="search-icon">🔍</span>
            </button>
          </div>
          
          <!-- Statistics -->
          <div class="statistics">
            <span>4,151 Sujets</span>
            <span>10,884 Réponses</span>
            <span>5,000 Membres</span>
            
            <div class="user-avatars">
              <img v-for="user in onlineUsers" 
                :key="user.id" 
                :src="user.avatar" 
                :alt="user.name"
                class="avatar" />
            </div>
          </div>
        </div>
      </div>
      
      <!-- Discussion Spaces section -->
      <div class="section">
        <h2 class="section-title">Les Espaces de Discussion</h2>
        <div class="discussion-spaces">
          <div v-for="space in discussionSpaces" :key="space.id" class="space-card">
            <img :src="space.iconSrc" :alt="space.title" class="space-icon" />
            <span class="space-title">{{ space.title }}</span>
          </div>
        </div>
      </div>
      
      <!-- Why Participate section -->
      <div class="section">
        <h2 class="section-title">Pourquoi Participer ?</h2>
        <p class="participation-subtitle">Partagez votre expertise, inspirez les entrepreneurs et façonnez l'avenir des startups !</p>
        
       <div class="row g-4 justify-content-center">
          <div v-for="reason in reasons" :key="reason.title" class="col-lg-3 col-md-6">
            <div class="card h-100 border-0 shadow-sm">
              <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                  <div class="icon-box bg-primary bg-opacity-10 p-2 rounded me-3">
                    <i class="bi" :class="reason.icon"></i>
                  </div>
                  <h5 class="fw-bold mb-0">{{ reason.title }}</h5>
                </div>
                <p class="text-muted mb-0">{{ reason.desc }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Join Community section -->
      <div class="section join-section">
        <h2 class="section-title">Rejoignez la Communauté !</h2>
        <p class="join-subtitle">Créez un compte et commencez à échanger dès maintenant !<br>Parce qu'ensemble, nous allons plus loin.</p>
        
        <button class="join-button">S'inscrire</button>
      </div>
    </div>
 
  </template>
  
  <script setup>
  import { defineAsyncComponent, ref, onMounted } from 'vue';
  const reasons = [
  { title: 'Réponses Rapides', desc: 'Répondez aux questions et aidez à résoudre les problèmes de vos pairs.', icon: 'bi-lightning-charge' },
  { title: 'Partage d\'Expérience', desc: 'Partagez vos expériences pour aider les autres à grandir et à innover.', icon: 'bi-share' },
  { title: 'Opportunités Business', desc: 'Faites croître votre réseau et explorez des opportunités d\'affaires.', icon: 'bi-graph-up-arrow' },
  { title: 'Ressources Exclusives', desc: 'Accédez à des ressources exclusives et bénéficiez de conseils de leaders du marché.', icon: 'bi-star' }
];
        const onlineUsers= [
          { id: 1, avatar: 'image/forum/1.jpeg', name: 'User 1' },
          { id: 2, avatar: 'image/forum/2.jpeg', name: 'User 2' },
          { id: 3, avatar: 'image/forum/3.jpeg', name: 'User 3' }
        ];
       const discussionSpaces =  [
          {
            id: 1,
            title: 'Startup & Entreprenariat',
            iconSrc: 'image/forum/a.png'
          },
          {
            id: 2,
            title: 'Formation & Apprentissage',
            iconSrc: 'image/forum/b.png'
          },
          {
            id: 3,
            title: 'Investissement & Financement',
            iconSrc: 'image/forum/c.png'
          },
          {
            id: 4,
            title: 'Outils & Technologies',
            iconSrc: 'image/forum/d.png'
          },
          {
            id: 5,
            title: 'Intelligence Artificielle & Innovation',
            iconSrc: 'image/forum/e.png'
          },
          {
            id: 6,
            title: 'Événements & Opportunités',
            iconSrc: 'image/forum/f.png'
          }
        ]
      ;
  </script>
  
  <style scoped>
  .transition-hover {
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.transition-hover:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
}

.icon-box {
  width: 40px;
  height: 40px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.icon-box i {
  font-size: 1.25rem;
  color: #1a3c6e;
}
  .forum-container {
    font-family: 'Arial', sans-serif;
    max-width: 100%;
    margin: 0 auto;
    color: #333;
    padding: 20px 20px;
  }
  
  .header-section {
    background: linear-gradient(to right, #e6f3ff, #ffffff);
    padding: 40px 20px;
    border-radius: 8px;
    margin-bottom: 30px;
  }
  
  .main-title {
    color: #0078d4;
    margin-bottom: 10px;
    font-size: 28px;
  }
  
  .subtitle {
    color: #555;
    margin-bottom: 25px;
  }
  
  .search-bar {
    display: flex;
    margin-bottom: 25px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    border-radius: 4px;
    overflow: hidden;
  }
  
  .search-bar input {
    flex-grow: 1;
    padding: 12px 15px;
    border: none;
    outline: none;
  }
  
  .search-button {
    background-color: #0078d4;
    color: white;
    border: none;
    padding: 0 15px;
    cursor: pointer;
  }
  
  .statistics {
    display: flex;
    justify-content: space-between;
    color: #666;
    align-items: center;
  }
  
  .user-avatars {
    display: flex;
    margin-left: 10px;
  }
  
  .avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #ddd;
    margin-right: -10px;
    border: 2px solid white;
    object-fit: cover;
  }
  
  .section {
    margin-bottom: 60px;
  }
  
  .section-title {
    color: #0078d4;
    text-align: center;
    margin-bottom: 40px;
    font-size: 28px;
  }
  
  .discussion-spaces {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
  }
  
  .space-card {
    background-color: white;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    padding: 30px;
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    transition: transform 0.3s, box-shadow 0.3s;
    cursor: pointer;
  }
  
  .space-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
  }
  
  .space-icon {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    margin-bottom: 25px;
    background-color: #e6f3ff;
    object-fit: contain;
    padding: 20px;
    transition: transform 0.3s;
  }
  
  .space-card:hover .space-icon {
    transform: scale(1.05);
  }
  
  .space-title {
    font-size: 18px;
    font-weight: 600;
    color: #333;
  }
  
  .participation-subtitle {
    text-align: center;
    margin-bottom: 30px;
    color: #555;
  }
  
  .benefits-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
  }
  
  .benefit-card {
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
    padding: 20px;
    text-align: center;
  }
  
  .benefit-card h3 {
    color: #0078d4;
    margin-bottom: 10px;
    font-size: 16px;
  }
  
  .benefit-card p {
    color: #666;
    font-size: 14px;
  }
  
  .join-section {
    text-align: center;
    padding: 40px 20px;
    background-color: #f9f9f9;
    border-radius: 8px;
  }
  
  .join-subtitle {
    margin-bottom: 30px;
    color: #555;
  }
  
  .join-button {
    background-color: #0078d4;
    color: white;
    border: none;
    padding: 12px 30px;
    font-size: 16px;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.2s;
  }
  
  .join-button:hover {
    background-color: #0062ab;
  }
  
  /* Responsive adjustments */
  @media (max-width: 1100px) {
    .space-icon {
      width: 130px;
      height: 130px;
    }
  }
  
  @media (max-width: 900px) {
    .discussion-spaces, .benefits-grid {
      grid-template-columns: repeat(2, 1fr);
    }
    
    .space-icon {
      width: 120px;
      height: 120px;
    }
  }
  
  @media (max-width: 600px) {
    .discussion-spaces, .benefits-grid {
      grid-template-columns: 1fr;
    }
    
    .statistics {
      flex-direction: column;
      gap: 10px;
    }
    
    .user-avatars {
      justify-content: center;
      margin-top: 10px;
    }
    
    .space-icon {
      width: 140px;
      height: 140px;
    }
  }
  </style>
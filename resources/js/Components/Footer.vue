<template>
    <footer class="footer">
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

      <div class="footer-container">
        <!-- Bloc gauche -->
        <div class="footer-left">
          <h2>BraindCode Startup Studio :</h2>
          <p>Des idées claires, des illustrations épurées</p>
          <div class="social-icons" style="margin-top: 33%;">
            <i class="bx bxl-facebook-circle"></i>
            <i class="bx bxl-instagram"></i>
            <i class="bx bxl-twitter"></i>
            <i class="bx bxl-youtube"></i>
          </div>
        </div>
  
        <!-- Bloc liens -->
        <div class="footer-links">
          <h3 style="color:#fff">Liens utiles</h3>
          <ul>
            <li><Link href="#">Startup</Link></li>
            <li><Link href="#">Investisseur</Link></li>
            <li><Link href="#">Coach</Link></li>
            <li><Link href="#">À Propos</Link></li>
          </ul>
        </div>
  
        <div class="footer-links">
          <h3>Soutien</h3>
          <ul>
            <li><Link :href="route('faq')">FAQ</Link></li>
            <li><Link :href="route('contactus')">Contact</Link></li>
            <li><Link href="#">Termes et conditions</Link></li>
            <li><Link href="#">Politique de confidentialité</Link></li>
          </ul>
        </div>
  
        <!-- Bloc contact -->
        <div class="footer-contact">
          <h3>Contactez-nous</h3>
          <ul>
            <li><i class="bx bx-phone"></i> +216 99532366</li>
            <li><i class="bx bx-envelope"></i> braindcode@gmail.com</li>
            <li><i class="bx bx-globe"></i> braind-code.com</li>
            <li><i class="bx bx-map"></i> France</li>
          </ul>
        </div>
      </div>
    </footer>
  </template>
  
  <script setup>
  import { Link } from '@inertiajs/vue3'
  </script>
  
  <style scoped>
  .footer {
    background-color: rgb(19, 127, 209);
    ;
    color: white;
    padding: 3rem 2rem;
  }
  
  .footer-container {
    max-width: 1200px;
    margin: auto;
    display: flex;
    flex-wrap: wrap;
    gap: 2rem;
    justify-content: space-between;
  }
  
  .footer-left h2 {
    font-size: 20px;
    margin-bottom: 0.5rem;
  }
  
  .footer-left p {
    font-size: 14px;
    margin-bottom: 1rem;
  }
  
  .social-icons i {
    font-size: 20px;
    margin-right: 10px;
    cursor: pointer;
    color: white;
  }
  
  .footer-links,
  .footer-contact {
    flex: 1;
    min-width: 200px;
  }
  
  .footer-links h3,
  .footer-contact h3 {
    margin-bottom: 0.5rem;
    font-weight: bold;
    font-size: 16px;
  }
  
  .footer-links ul,
  .footer-contact ul {
    list-style: none;
    padding: 0;
  }
  
  .footer-links li,
  .footer-contact li {
    margin: 6px 0;
    font-size: 14px;
  }
  
  .footer-contact i {
    margin-right: 6px;
  }
  </style>
  
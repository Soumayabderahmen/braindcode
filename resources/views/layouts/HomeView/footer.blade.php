<footer class="footer">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <div class="footer-container">
        <!-- Bloc gauche -->
        <div class="footer-left">
            <div class="footer-brand">
                <h2 style='color: #f8f9fa;text-align: justify;'>BraindCode Startup Studio</h2>
                <p>Des idées claires, des illustrations épurées</p>
            </div>
            <div class="social-icons">
                <a href="#"><i class="bx bxl-facebook-circle"></i></a>
                <a href="#"><i class="bx bxl-instagram"></i></a>
                <a href="#"><i class="bx bxl-twitter"></i></a>
                <a href="#"><i class="bx bxl-youtube"></i></a>
            </div>
        </div>

        <div class="footer-nav">
            <!-- Liens utiles -->
            <div class="footer-links">
                <h3 style="color: #ffffff;">Liens utiles</h3>
                <ul>
                    <li><a href="{{ route('startup') }}">Startup</a></li>
                    <li><a href="{{ route('startinc') }}">Startup incubé</a></li>
                    <li><a href="{{ route('equipe') }}">Equipe</a></li>
                    <li><a href="#">À Propos</a></li>
                </ul>
            </div>

            <!-- Soutien -->
            <div class="footer-links">
                <h3 style="color: #ffffff;">Soutien</h3>
                <ul>
                    <li><a href="{{ route('faq') }}">FAQ</a></li>
                    <li><a href="{{ route('contactus') }}">Contact</a></li>
                    <li><a href="{{ route('tuto1') }}">Guide</a></li>
                    <li><a href="#">Politique de confidentialité</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div class="footer-contact">
                <h3 style="color: #ffffff;">Contactez-nous</h3>
                <ul>
                    <li><a href="tel:06.14.18.92.25"><i class="bx bx-phone"></i> 06.14.18.92.25</a></li>
                    <li><a href="mailto:braindcode@gmail.com"><i class="bx bx-envelope"></i> braindcode@gmail.com</a></li>
                    <li><a href="https://braind-code.com" target="_blank"><i class="bx bx-globe"></i> braind-code.com</a></li>
                    <li><i class="bx bx-map"></i> France</li>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="footer-bottom">
        <p>&copy; 2025 BraindCode Startup Studio. Tous droits réservés.</p>
    </div>
</footer>

<style>
    .footer {
        background: linear-gradient(135deg, #0f6ab4 0%, #1a88cd 100%);
        color: white;
        padding: 4rem 2rem 1rem;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .footer-container {
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        flex-wrap: wrap;
        gap: 2rem;
        justify-content: space-between;
    }

    .footer-left {
        flex: 1;
        min-width: 280px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .footer-brand h2 {
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 0.5rem;
        position: relative;
        padding-bottom: 12px;
    }

    .footer-brand h2:after {
        content: '';
        position: absolute;
        left: 0;
        bottom: 0;
        height: 3px;
        width: 60px;
        background-color: rgba(255, 255, 255, 0.7);
    }

    .footer-brand p {
        font-size: 16px;
        margin-bottom: 1.5rem;
        color: rgba(255, 255, 255, 0.9);
        font-style: italic;
    }

    .social-icons {
        display: flex;
        margin-top: 2rem;
    }

    .social-icons a {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        margin-right: 12px;
        border-radius: 50%;
        background-color: rgba(255, 255, 255, 0.1);
        transition: all 0.3s ease;
    }

    .social-icons a:hover {
        background-color: rgba(255, 255, 255, 0.3);
        transform: translateY(-3px);
    }

    .social-icons i {
        font-size: 22px;
        color: white;
    }

    .footer-nav {
        flex: 2;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-around;
        gap: 2rem;
    }

    .footer-links,
    .footer-contact {
        flex: 1;
        min-width: 180px;
    }

    .footer-links h3,
    .footer-contact h3 {
        margin-bottom: 1.2rem;
        font-weight: 600;
        font-size: 18px;
        position: relative;
        padding-bottom: 10px;
    }

    .footer-links h3:after,
    .footer-contact h3:after {
        content: '';
        position: absolute;
        left: 0;
        bottom: 0;
        height: 2px;
        width: 40px;
        background-color: rgba(255, 255, 255, 0.5);
    }

    .footer-links ul,
    .footer-contact ul {
        list-style: none;
        padding: 0;
    }

    .footer-links li,
    .footer-contact li {
        margin: 12px 0;
        font-size: 15px;
    }

    .footer-links a,
    .footer-contact a {
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-block;
    }

    .footer-links a:hover,
    .footer-contact a:hover {
        color: white;
        transform: translateX(5px);
    }

    .footer-contact i {
        margin-right: 8px;
        font-size: 16px;
    }

    .footer-bottom {
        margin-top: 3rem;
        padding-top: 1.5rem;
        text-align: center;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    .footer-bottom p {
        font-size: 14px;
        color: rgba(255, 255, 255, 0.7);
    }

    @media (max-width: 768px) {
        .footer-container {
            flex-direction: column;
        }
        
        .footer-nav {
            flex-direction: column;
            gap: 2rem;
        }
        
        .social-icons {
            margin-top: 1rem;
            margin-bottom: 2rem;
        }
    }
</style>
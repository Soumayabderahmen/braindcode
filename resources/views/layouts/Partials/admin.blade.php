<aside id="layout-menu" class="aside layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img src="{{ asset('assets/img/dash/logo.png') }}" alt="">
            </span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="menu-toggle-icon ti ti-text-wrap-disabled d-none d-xl-block ti-sm align-middle"></i>
            <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
        </a>
    </div>
    
    <!-- Ajout des icons de Boxicons -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />

    <ul class="menu-inner py-1">
        <!-- Tableau de bord -->
        <li class="menu-item">
            <a href="{{ route('admin.dashboard') }}" class="menu-link">
                <i class="menu-icon bx bx-home-circle"></i>
                <div data-i18n="Tableau de bord">Tableau de bord</div>
            </a>
        </li>

        <!-- Bloc Utilisateurs -->
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon bx bx-user-circle"></i>
                <div data-i18n="Utilisateurs">Utilisateurs</div>
                <i class="menu-toggle-icon bx bx-chevron-down"></i>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="" class="menu-link">
                        <i class="menu-icon menu-sub-icon bx bx-circle"></i>
                        <div data-i18n="Startup">Startup</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="" class="menu-link">
                        <i class="menu-icon menu-sub-icon bx bx-circle"></i>
                        <div data-i18n="Investisseur">Investisseur</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="" class="menu-link">
                        <i class="menu-icon menu-sub-icon bx bx-circle"></i>
                        <div data-i18n="Coach">Coach</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Bloc Agent IA -->
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon bx bx-bot"></i>
                <div data-i18n="Agent IA">Agent IA</div>
                <i class="menu-toggle-icon bx bx-chevron-down"></i>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="" class="menu-link">
                        <i class="menu-icon menu-sub-icon bx bx-circle"></i>
                        <div data-i18n="Agent AI">Agent AI</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="" class="menu-link">
                        <i class="menu-icon menu-sub-icon bx bx-circle"></i>
                        <div data-i18n="Agent IA généraliste">Agent IA généraliste</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Bloc Chatbot IA -->
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon bx bx-message-dots"></i>
                <div data-i18n="Chatbot IA">Chatbot IA</div>
                <i class="menu-toggle-icon bx bx-chevron-down"></i>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="{{ route('admin.chatbot.index') }}" class="menu-link">
                        <i class="menu-icon menu-sub-icon bx bx-circle"></i>
                        <div data-i18n="Chatbot IA">Chatbot IA</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('admin.chatbot.reactions') }}" class="menu-link">
                        <i class="menu-icon menu-sub-icon bx bx-circle"></i>
                        <div data-i18n="Réactions Chatbot">Réactions Chatbot</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('admin.chatbot.management') }}" class="menu-link">
                        <i class="menu-icon menu-sub-icon bx bx-circle"></i>
                        <div data-i18n="Gestion Chatbot">Gestion Chatbot</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Bloc Planification -->
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon bx bx-calendar"></i>
                <div data-i18n="Planification">Planification</div>
                <i class="menu-toggle-icon bx bx-chevron-down"></i>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="" class="menu-link">
                        <i class="menu-icon menu-sub-icon bx bx-circle"></i>
                        <div data-i18n="Calendrier">Calendrier</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="" class="menu-link">
                        <i class="menu-icon menu-sub-icon bx bx-circle"></i>
                        <div data-i18n="Réservation">Réservation</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Support et FAQs -->
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon bx bx-support"></i>
                <div data-i18n="Support">Support</div>
                <i class="menu-toggle-icon bx bx-chevron-down"></i>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="{{ route('admin.support.messages') }}" class="menu-link">
                        <i class="menu-icon menu-sub-icon bx bx-circle"></i>
                        <div data-i18n="Messages Support">Messages Support</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('admin.faqs.index') }}" class="menu-link">
                        <i class="menu-icon menu-sub-icon bx bx-circle"></i>
                        <div data-i18n="FAQs">FAQs</div>
                    </a>
                </li>
            </ul>
        </li>
    </ul>

    <div class="user-profile-container mt-auto">
        <div class="user-profile" id="user-profile">
            <div class="user-avatar" title="{{ Auth::user()->name ?? 'Utilisateur' }}">
                {{ Auth::user()->name[0] ?? 'U' }}
            </div>
            <div class="user-info">
                <span class="user-name">{{ Auth::user()->name ?? 'Utilisateur' }}</span>
                <span class="user-role">{{ ucfirst(Auth::user()->role ?? 'Administrateur') }}</span>
            </div>
        </div>
    </div>
</aside>

<!-- Script JavaScript pour le fonctionnement des sous-menus -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialisation - s'assurer que tous les sous-menus sont fermés au départ
    const allSubMenus = document.querySelectorAll('.menu-sub');
    allSubMenus.forEach(menu => {
        menu.style.display = 'none';
    });
    
    // Sélectionner tous les éléments avec la classe menu-toggle
    const menuToggles = document.querySelectorAll('.menu-link.menu-toggle');
    
    // Ajouter un écouteur d'événements à chaque élément
    menuToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const parentItem = this.parentElement;
            const subMenu = parentItem.querySelector('.menu-sub');
            const toggleIcon = this.querySelector('.menu-toggle-icon');
            
            // Toggle class 'open' sur l'élément parent
            const isOpen = parentItem.classList.toggle('open');
            
            // Afficher/masquer le sous-menu avec animation
            if (subMenu) {
                if (isOpen) {
                    subMenu.style.display = 'block';
                    // Pour permettre l'animation, on attend un peu avant de définir maxHeight
                    setTimeout(() => {
                        subMenu.style.maxHeight = subMenu.scrollHeight + 'px';
                    }, 10);
                    // Rotation de l'icône de flèche - utiliser une classe pour la rotation
                    if (toggleIcon) toggleIcon.classList.add('rotate-icon');
                } else {
                    subMenu.style.maxHeight = '0px';
                    // Retour de l'icône à sa position initiale
                    if (toggleIcon) toggleIcon.classList.remove('rotate-icon');
                    // Attendre que l'animation se termine avant de masquer
                    setTimeout(() => {
                        subMenu.style.display = 'none';
                    }, 300); // La même durée que la transition CSS
                }
            }
        });
    });

    // Active menu item based on current URL
    const currentUrl = window.location.href;
    const menuLinks = document.querySelectorAll('.menu-link');
    
    menuLinks.forEach(link => {
        if (link.href === currentUrl) {
            link.parentElement.classList.add('active');
            
            // If it's a submenu item, open its parent
            const parentMenuItemEl = link.closest('.menu-item').parentElement;
            if (parentMenuItemEl.classList.contains('menu-sub')) {
                const parentMenuItem = parentMenuItemEl.parentElement;
                parentMenuItem.classList.add('open');
                parentMenuItemEl.style.display = 'block';
                parentMenuItemEl.style.maxHeight = parentMenuItemEl.scrollHeight + 'px';
                const toggleIcon = parentMenuItem.querySelector('.menu-toggle-icon');
                if (toggleIcon) toggleIcon.classList.add('rotate-icon');
            }
        }
    });
});
</script>

<style>
/* Styles de base du menu */
.aside.layout-menu {
    height: 100vh;
    position: fixed;
    top: 0;
    left: 0;
    width: 250px;
    background-color: #ffffff;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    display: flex;
    flex-direction: column;
    z-index: 1000;
    overflow-y: auto;
    transition: width 0.3s ease;
}

/* Style du logo */
.app-brand {
    padding: 1.25rem 1.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid #e6e8eb;
}

.app-brand-logo img {
    max-height: 40px;
}

/* Styles pour les items de menu */
.menu-inner {
    padding: 1rem 0;
    list-style-type: none;
    margin: 0;
}

.menu-item {
    position: relative;
    margin: 0.25rem 0.5rem;
}

.menu-link {
    display: flex;
    align-items: center;
    padding: 0.75rem 1rem;
    color: #697a8d;
    border-radius: 0.375rem;
    transition: all 0.2s ease;
    text-decoration: none;
    cursor: pointer;
}

.menu-link:hover {
    background-color: rgba(59, 130, 246, 0.08);
    color: #3b82f6;
}

.menu-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 1.5rem;
    height: 1.5rem;
    margin-right: 0.75rem;
    font-size: 1.25rem;
    color: #697a8d;
    transition: all 0.2s ease;
}

.menu-toggle-icon {
    margin-left: auto;
    font-size: 1.25rem;
    color: #697a8d;
    transition: transform 0.3s ease;
}

/* Nouvelle classe pour la rotation de l'icône */
.rotate-icon {
    transform: rotate(180deg);
}

/* Styles pour les sous-menus */
.menu-sub {
    overflow: hidden;
    max-height: 0;
    transition: max-height 0.3s ease;
    padding-left: 1rem;
    list-style: none;
    margin: 0;
}

.menu-item.open .menu-sub {
    max-height: 500px;
}

.menu-sub .menu-link {
    padding: 0.5rem 1rem;
    font-size: 0.9rem;
}

.menu-sub-icon {
    width: 1.25rem;
    height: 1.25rem;
    margin-right: 0.5rem;
    font-size: 0.75rem;
    color: #8c97a6;
}

/* Styles pour l'item de menu actif */
.menu-item.active > .menu-link {
    background-color: rgba(59, 130, 246, 0.1);
    color: #3b82f6;
}

.menu-item.active > .menu-link .menu-icon,
.menu-item.active > .menu-link .menu-sub-icon {
    color: #3b82f6;
}

/* Styles pour le profil utilisateur */
.user-profile-container {
    margin-top: auto;
    border-top: 1px solid #e6e8eb;
    padding: 1rem;
    background-color: #f8fafc;
}

.user-profile {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    transition: all 0.3s ease;
}

.user-avatar {
    width: 40px;
    height: 40px;
    min-width: 40px;
    background-color: #3b82f6;
    color: white;
    font-weight: bold;
    font-size: 1.2rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.user-info {
    display: flex;
    flex-direction: column;
    font-size: 0.9rem;
    overflow: hidden;
    white-space: nowrap;
}

.user-name {
    font-weight: 600;
    color: #1e293b;
}

.user-role {
    font-size: 0.8rem;
    color: #64748b;
}

/* Styles pour le menu en mode réduit/collapsed */
.menu-collapsed .layout-menu {
    width: 70px;
}

.menu-collapsed .menu-inner .menu-link div,
.menu-collapsed .user-info {
    display: none;
}

.menu-collapsed .menu-toggle-icon {
    display: none;
}

.menu-collapsed .menu-icon {
    margin-right: 0;
}

.menu-collapsed .menu-item:hover {
    position: relative;
}

.menu-collapsed .menu-item:hover .menu-link div {
    display: block;
    position: absolute;
    left: 60px;
    top: 0;
    background: #ffffff;
    padding: 0.75rem 1rem;
    border-radius: 0.375rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    white-space: nowrap;
    z-index: 1001;
}

/* Animation pour sous-menus */
@keyframes slideDown {
    0% {
        opacity: 0;
        transform: translateY(-10px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

.menu-item.open .menu-sub {
    animation: slideDown 0.3s ease forwards;
}

/* Effet de surbrillance lors du survol */
.menu-link:hover .menu-icon,
.menu-link:hover .menu-sub-icon {
    color: #3b82f6;
}
</style>
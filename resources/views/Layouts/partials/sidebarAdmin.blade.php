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

    <ul class="menu-inner py-1">
        <!-- Tableau de bord -->
        <li class="menu-item">
            <a href="{{ route('admin.dashboard') }}" class="menu-link">
                <i class="menu-icon">
                    <span class="iconify" data-icon="material-symbols:dashboard-outline-rounded" data-inline="false"></span>
                </i>
                <div data-i18n="Tableau de bord">Tableau de bord</div>
            </a>
        </li>

        <!-- Bloc Utilisateurs -->
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon">
                    <span class="iconify" data-icon="mdi:account-group" data-inline="false"></span>
                </i>
                <div data-i18n="Utilisateurs">Utilisateurs</div>
                <i class="menu-toggle-icon ti ti-chevron-down"></i>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="{{ route('admin.startups') }}" class="menu-link">
                        <i class="menu-icon menu-sub-icon">
                            <span class="iconify" data-icon="mdi:circle-outline" data-inline="false"></span>
                        </i>
                        <div data-i18n="Startup">Startup</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('admin.investisseurs') }}" class="menu-link">
                        <i class="menu-icon menu-sub-icon">
                            <span class="iconify" data-icon="mdi:circle-outline" data-inline="false"></span>
                        </i>
                        <div data-i18n="Investisseur">Investisseur</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('admin.coaches') }}" class="menu-link">
                        <i class="menu-icon menu-sub-icon">
                            <span class="iconify" data-icon="mdi:circle-outline" data-inline="false"></span>
                        </i>
                        <div data-i18n="Coach">Coach</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Bloc IA -->
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon">
                    <span class="iconify" data-icon="fluent:bot-24-regular" data-inline="false"></span>
                </i>
                <div data-i18n="Intelligence Artificielle">Intelligence Artificielle</div>
                <i class="menu-toggle-icon ti ti-chevron-down"></i>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="{{ route('admin.listAgent') }}" class="menu-link">
                        <i class="menu-icon menu-sub-icon">
                            <span class="iconify" data-icon="mdi:circle-outline" data-inline="false"></span>
                        </i>
                        <div data-i18n="Agent AI">Agent AI</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="" class="menu-link">
                        <i class="menu-icon menu-sub-icon">
                            <span class="iconify" data-icon="mdi:circle-outline" data-inline="false"></span>
                        </i>
                        <div data-i18n="Agent IA généraliste">Agent IA généraliste</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Bloc Planification -->
        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon">
                    <span class="iconify" data-icon="uil:schedule" data-inline="false"></span>
                </i>
                <div data-i18n="Planification">Planification</div>
                <i class="menu-toggle-icon ti ti-chevron-down"></i>
            </a>
            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="{{ route('startup.calendar') }}" class="menu-link">
                        <i class="menu-icon menu-sub-icon">
                            <span class="iconify" data-icon="mdi:circle-outline" data-inline="false"></span>
                        </i>
                        <div data-i18n="Calendrier">Calendrier</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('admin.reservations') }}" class="menu-link">
                        <i class="menu-icon menu-sub-icon">
                            <span class="iconify" data-icon="mdi:circle-outline" data-inline="false"></span>
                        </i>
                        <div data-i18n="Réservation">Réservation</div>
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
                    // Pour la flèche unique, on utilise rotate(180deg) pour pointer vers le haut
                    if (toggleIcon) toggleIcon.style.transform = 'rotate(180deg)';
                } else {
                    subMenu.style.maxHeight = '0px';
                    // Pour la flèche unique, on la remet à sa position par défaut (vers le bas)
                    if (toggleIcon) toggleIcon.style.transform = 'rotate(0)';
                    // Attendre que l'animation se termine avant de masquer
                    setTimeout(() => {
                        subMenu.style.display = 'none';
                    }, 300); // La même durée que la transition CSS
                }
            }
        });
    });
});
</script>

<style>
/* Styles de base */
.user-profile-container {
    margin-top: auto;
    border-top: 1px solid #e2e8f0;
    padding: 1rem;
    background-color: #f8fafc;
    border-radius: 0 0 0.5rem 0.5rem;
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
    transition: opacity 0.2s ease, max-width 0.2s ease;
}

.user-name {
    font-weight: 600;
    color: #1e293b;
}

.user-role {
    font-size: 0.8rem;
    color: #64748b;
}

/* Styles pour le menu collapsed */
.menu-collapsed .user-profile {
    justify-content: center;
}

.menu-collapsed .user-info {
    max-width: 0;
    opacity: 0;
    visibility: hidden;
}

/* Styles pour les sous-menus */
.menu-sub {
    overflow: hidden;
    transition: max-height 0.3s ease;
    padding-left: 0;
    max-height: 0;
    list-style: none;
}

.menu-item.open .menu-sub {
    max-height: 500px; /* Valeur arbitraire suffisamment grande */
}

/* Nouvelle flèche unique pour ouvrir/fermer */
.menu-toggle-icon {
    transition: transform 0.3s ease;
    margin-left: auto;
    font-size: 1rem;
    color: #94a3b8;
}

.menu-item.open .menu-toggle-icon {
    transform: rotate(180deg);
}

/* Styles pour les sous-éléments de menu */
.menu-sub .menu-link {
    padding: 0.625rem 1rem;
    display: flex;
    align-items: center;
    font-size: 0.9rem;
    color: #64748b;
    border-radius: 0.25rem;
    transition: all 0.2s ease;
}

.menu-sub .menu-link:hover {
    background-color: rgba(59, 130, 246, 0.05);
    color: #3b82f6;
}

/* Style pour indiquer les éléments actifs */
.menu-item.active > .menu-link {
    background-color: rgba(59, 130, 246, 0.1);
    color: #3b82f6;
}

.menu-item.active > .menu-link i.menu-icon {
    color: #3b82f6;
}

/* Style pour le hover des éléments du menu */
.menu-link:hover {
    background-color: rgba(59, 130, 246, 0.05);
}

/* Style pour les éléments du menu collapsés */
.collapsed .menu-item:hover::after {
    content: attr(data-title);
    position: absolute;
    left: 70px;
    top: 50%;
    transform: translateY(-50%);
    background: #005183;
    color: white;
    padding: 5px 10px;
    border-radius: 4px;
    font-size: 14px;
    white-space: nowrap;
    z-index: 10;
}

/* Styles pour la structure principale du menu */
.menu-link {
    display: flex;
    align-items: center;
    padding: 0.625rem 1rem;
    color: #64748b;
    border-radius: 0.25rem;
    transition: all 0.2s ease;
    cursor: pointer;
}

.menu-link.menu-toggle {
    justify-content: space-between;
}

.menu-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 1.5rem;
    height: 1.5rem;
    margin-right: 0.75rem;
    font-size: 1.25rem;
    color: #64748b;
}

.menu-sub-icon {
    width: 1.25rem;
    height: 1.25rem;
    margin-right: 0.5rem;
    font-size: 0.8rem;
    color: #94a3b8;
}

/* Style pour les cercles des sous-menus */
.menu-sub-icon .iconify {
    font-size: 0.75rem;
    color: #94a3b8;
}

/* Ajout de couleurs pour surbrillance */
.menu-link:hover .menu-icon,
.menu-link:hover .menu-sub-icon .iconify {
    color: #3b82f6;
}

/* Ajout d'un style pour les items de menu actifs */
.menu-item.active .menu-sub-icon .iconify {
    color: #3b82f6;
}
</style>
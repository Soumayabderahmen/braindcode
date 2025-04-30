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
    <link
      href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
      rel="stylesheet"
    />

    <ul class="menu-inner py-1">
        <!-- Dashboards -->
        <li class="menu-item">
            <a href="{{ route('dashboard.coach') }}"
                class="menu-link">
                <i class="menu-icon">
                    <span class="iconify" data-icon="material-symbols:dashboard-outline-rounded" data-inline="false"></span>
                </i>
                <div data-i18n="Tableau de bord">Tableau de bord</div>
            </a>
        </li>
       
    </ul>
    
    <!-- Profil utilisateur -->
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

<style>
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
    min-width: 40px; /* Empêche le rétrécissement */
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
</style>

<!-- <script>
document.addEventListener("DOMContentLoaded", () => {
    const layoutMenu = document.getElementById('layout-menu');
    
    // Fonction pour vérifier et ajuster l'état collapsed
    const toggleCollapsedState = () => {
        const isCollapsed = layoutMenu.classList.contains('collapsed');
        
        if (isCollapsed) {
            layoutMenu.classList.add('menu-collapsed');
        } else {
            layoutMenu.classList.remove('menu-collapsed');
        }
    };
    
    // Observer les changements de classe sur le menu
    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (mutation.attributeName === 'class') {
                toggleCollapsedState();
            }
        });
    });
    
    observer.observe(layoutMenu, { attributes: true });
    
    // État initial
    toggleCollapsedState();
    
    // Pour tester le comportement (à retirer en production)
    document.querySelector('.layout-menu-toggle').addEventListener('click', () => {
        layoutMenu.classList.toggle('collapsed');
    });
});
</script> -->
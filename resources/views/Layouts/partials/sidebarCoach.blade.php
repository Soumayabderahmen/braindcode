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
        <!-- Dashboards -->
        <li class="menu-item">
            <a href="{{ route('dashboard') }}"
                class="menu-link">
                <i class="menu-icon">
                    <span class="iconify" data-icon="material-symbols:dashboard-outline-rounded" data-inline="false"></span>
                </i>
                <div data-i18n="Tableau de bord">Tableau de bord</div>
            </a>
        </li>

        <!-- Layouts -->

        {{-- <li class="menu-item">
            <a href=""
                class="menu-link">
                <i class="menu-icon">
                    <span class="iconify" data-icon="covid:symptoms-virus-headache-2" data-inline="false"></span>
                </i>
                <div data-i18n="Formation">Formation</div>
            </a>
        </li> --}}
        <li class="menu-item">
            <a href="{{ route('coach.agentia') }}"
                class="menu-link">
                <i class="menu-icon">
                    <span class="iconify" data-icon="mage:robot" data-inline="false"></span>
                </i>
                <div data-i18n="Agent Ai">Agent Ai</div>
            </a>
        </li>
        <li class="menu-item">
            <a href="{{ route('coach.calendar') }}"
                class="menu-link">
                <i class="menu-icon">
                    <span class="iconify" data-icon="solar:calendar-line-duotone" data-inline="false"></span>
                </i>
                <div data-i18n="Calendrier">Calendrier</div>
            </a>
        </li>
        <li class="menu-item">
            <a href="{{ route('coach.reservations') }}"
                class="menu-link">
                <i class="menu-icon">
                    <span class="iconify" data-icon="material-symbols:dashboard-outline-rounded" data-inline="false"></span>
                </i>
                <div data-i18n="Liste des Réservations">Liste des Réservations</div>
            </a>
        </li>
        <li class="menu-item">
            <a href="{{ route('coach.availability.index') }}"
                class="menu-link">
                <i class="menu-icon">
                    <span class="iconify" data-icon="solar:calendar-line-duotone" data-inline="false"></span>
                </i>
                <div data-i18n="Gestion de Disponibilité"> Gestion de Disponibilité</div>
            </a>
        </li>
       
        {{-- <li class="menu-item">
            <a href=""
                class="menu-link">
                <i class="menu-icon">
                    <span class="iconify" data-icon="hugeicons:message-multiple-01" data-inline="false"></span>
                </i>
                <div data-i18n="Messagerie ">Messagerie </div>
            </a>
        </li> --}}
        {{-- <li class="menu-item">
            <a href=""
                class="menu-link">
                <i class="menu-icon">
                    <span class="iconify" data-icon="fluent:bot-sparkle-48-regular" data-inline="false"></span>
                </i>
                <div data-i18n="Agent IA généraliste ">Agent IA généraliste </div>
            </a>
        </li> --}}
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

</style>
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

        <li class="menu-item">
            <a href=""
                class="menu-link">
                <i class="menu-icon">
                    <span class="iconify" data-icon="covid:symptoms-virus-headache-2" data-inline="false"></span>
                </i>
                <div data-i18n="Formation">Formation</div>
            </a>
        </li>
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
            <a href="{{ route('startup.calendar') }}"
                class="menu-link">
                <i class="menu-icon">
                    <span class="iconify" data-icon="solar:calendar-line-duotone" data-inline="false"></span>
                </i>
                <div data-i18n="Calendrier">Calendrier</div>
            </a>
        </li>
        <li class="menu-item">
            <a href="{{ route('admin.reservations') }}"
                class="menu-link">
                <i class="menu-icon">
                    <span class="iconify" data-icon="material-symbols:dashboard-outline-rounded" data-inline="false"></span>
                </i>
                <div data-i18n="Réservation">Réservation</div>
            </a>
        </li>
        <li class="menu-item">
            <a href=""
                class="menu-link">
                <i class="menu-icon">
                    <span class="iconify" data-icon="charm:messages" data-inline="false"></span>
                </i>
                <div data-i18n="Forum">Forum</div>
            </a>
        </li>
        <li class="menu-item">
            <a href=""
                class="menu-link">
                <i class="menu-icon">
                    <span class="iconify" data-icon="fe:document" data-inline="false"></span>
                </i>
                <div data-i18n="Ressources ">Ressources </div>
            </a>
        </li>
        <li class="menu-item">
            <a href=""
                class="menu-link">
                <i class="menu-icon">
                    <span class="iconify" data-icon="hugeicons:message-multiple-01" data-inline="false"></span>
                </i>
                <div data-i18n="Messagerie ">Messagerie </div>
            </a>
        </li>
        <li class="menu-item">
            <a href=""
                class="menu-link">
                <i class="menu-icon">
                    <span class="iconify" data-icon="fluent:bot-sparkle-48-regular" data-inline="false"></span>
                </i>
                <div data-i18n="Agent IA généraliste ">Agent IA généraliste </div>
            </a>
        </li>
    </ul>
</aside>
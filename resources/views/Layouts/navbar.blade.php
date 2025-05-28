<nav id="navbar" class="navbar layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
    id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="ti ti-menu-2 ti-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <!-- Search -->
        <div class="navbar-nav align-items-center">
            <div class="nav-item navbar-search-wrapper titleSide mb-0">
<button type="button" class="btn btn-top shadow-none bg-transp" onclick="window.history.back();">
  <i class="bi bi-chevron-left"></i>
</button>
                <h1 class="">@yield('title')</h1>
            </div>
        </div>
        <!-- /Search -->

        <ul class="navbar-nav flex-row align-items-center ms-auto mt-0" id="navbar-nav">

            <!-- robot -->
<li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-0">
    <a class="nav-link dropdown-toggle hide-arrow btn-top"
       href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        <img src="{{ asset('assets/img/dash/robot.png') }}" alt="">
    </a>
    <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="{{ route('coach.agentia') }}">Voir l'agent IA</a></li>
    </ul>
</li>

            <!-- / robot-->
<notifications :user="@js(auth()->user())"></notifications>


          
<li class="nav-item navbar-dropdown dropdown-user dropdown mt-0" style="margin-top: 0px !important;">
    <a class="nav-link dropdown-toggle hide-arrow btn-profil" href="javascript:void(0);"
        data-bs-toggle="dropdown">
        <img src="{{ asset('assets/img/dash/entreprise.png') }}" alt="">
    </a>
    <ul class="dropdown-menu dropdown-menu-end">
        <li>
        <li><a class="dropdown-item" href="{{ route('profile.update') }}"></a></li>
            <a class="dropdown-item text-start" href="">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="18" viewBox="0 0 14 18" fill="none">
                    <path d="M10.0004 4.19937C10.0004 4.99513 9.68429 5.75829 9.1216 6.32098C8.55892 6.88366 7.79576 7.19977 7 7.19977C6.20424 7.19977 5.44108 6.88366 4.8784 6.32098C4.31571 5.75829 3.9996 4.99513 3.9996 4.19937C3.9996 3.40362 4.31571 2.64046 4.8784 2.07777C5.44108 1.51509 6.20424 1.19897 7 1.19897C7.79576 1.19897 8.55892 1.51509 9.1216 2.07777C9.68429 2.64046 10.0004 3.40362 10.0004 4.19937ZM1 15.4953C1.02571 13.921 1.66916 12.4198 2.79158 11.3156C3.914 10.2113 5.42546 9.59247 7 9.59247C8.57454 9.59247 10.086 10.2113 11.2084 11.3156C12.3308 12.4198 12.9743 13.921 13 15.4953C11.1179 16.3592 9.07088 16.8047 7 16.8011C4.85891 16.8011 2.82664 16.3338 1 15.4953Z" stroke="#474747" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <span class="">Profil</span>
            </a>
        </li>

        <!-- Formulaire de déconnexion -->
        <li>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="dropdown-item text-start">
                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="20" viewBox="0 0 17 20" fill="none">
                        <path d="M9.996 19H3C1.895 19 1 17.849 1 16.429V3.57C1 2.151 1.895 1 3 1H10M12.5 13.5L16 10L12.5 6.5M6 9.996H16" stroke="#474747" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <span class="">Déconnexion</span>
                </button>
            </form>
        </li>
    </ul>
</li>

        </ul>
    </div>
</nav>
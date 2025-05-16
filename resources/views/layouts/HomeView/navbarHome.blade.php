<header class="bg-white shadow-sm px-6 py-4 flex items-center justify-between">
  <!-- Logo -->
  <a href="/" class="transform transition hover:scale-105 z-10">
    <img src="/image/logos/BraindCode.png" class="logo-image" alt="Logo" />
  </a>

  <!-- Navigation principale -->
  <nav class="flex items-center space-x-6 text-sm font-semibold text-blue-500">
    <a href="{{ route('startup') }}" class="hover:text-blue-700">Startup</a>
    <a href="{{ route('coach') }}" class="hover:text-blue-700">Coach</a>
    <a href="{{ route('investisseur') }}" class="hover:text-blue-700">Investisseur</a>
    <a href="{{ route('forum') }}" class="hover:text-blue-700">Forum</a>
    <a href="{{ route('formation') }}" class="hover:text-blue-700">Formation</a>
    <a href="{{ route('resources') }}" class="hover:text-blue-700">Ressources</a>

    @php $user = Auth::user(); @endphp
    @if ($user)
      <div class="w-10 h-10 rounded-full bg-blue-500/20 flex items-center justify-center text-blue-300 font-semibold">
        @if ($user->profile_image)
          <img class="h-10 w-10 rounded-full" src="{{ $user->profile_image }}" alt="User" />
        @else
          {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
        @endif
      </div>
    @else
      <a href="{{ route('login') }}" class="nav-link">Connexion</a>
      @if(Route::has('register'))
        <a href="{{ route('register') }}" class="btn-primary">Inscription</a>
      @endif
    @endif
     <!-- @auth
    <div class="relative">
        <button @click="showingNavigationDropdown = !showingNavigationDropdown" class="flex items-center text-gray-700 hover:text-gray-900">
            <span class="me-2">{{ Auth::user()->name }}</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
            </svg>
        </button>
        <div x-show="showingNavigationDropdown" class="absolute right-0 mt-2 w-48 bg-white border rounded-md shadow-lg">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Se déconnecter</button>
            </form>
        </div>
    </div>
@else
    <a href="{{ route('login') }}" class="hidden md:flex items-center gap-2 font-medium text-[#0093EE] hover:text-blue-800">
        <img src="/images/user-icon.png" alt="User Icon" class="w-5 h-5" />
        Se connecter
    </a>
@endauth -->
  </nav>
</header>

<style scoped>
  .logo-image { width: 200px; height: auto; transition: all 0.3s ease; }
  .nav-link, .btn-primary {
    padding: 0.5rem 1rem; border-radius: 0.375rem; font-weight: 500;
    transition: all 0.2s ease;
  }
  .nav-link { color: rgb(55, 65, 81); }
  .nav-link:hover { background-color: rgb(229, 231, 235); }
  .btn-primary {
    color: white;
    background-image: linear-gradient(to right, rgb(37, 99, 235), rgb(29, 78, 216));
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
  }
  .btn-primary:hover {
    background-image: linear-gradient(to right, rgb(29, 78, 216), rgb(30, 64, 175));
  }
</style>

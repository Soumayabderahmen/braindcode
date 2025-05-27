<header x-data="{ menuOpen: false }" class="bg-white shadow-sm px-4 py-3 flex items-center justify-between relative z-50">
  <!-- Logo -->
  <a href="/" class="transform transition hover:scale-105">
    <img src="/image/logos/BraindCode.png" class="logo-image" alt="Logo" />
  </a>

  <!-- Burger menu (mobile) -->
  <button @click="menuOpen = !menuOpen" class="md:hidden text-blue-600 focus:outline-none text-2xl">
    <i class="fas fa-bars"></i>
  </button>

  <!-- Navigation principale (desktop) -->
  <nav class="hidden md:flex items-center space-x-6 text-sm font-semibold text-blue-500">
    <a href="{{ route('startup') }}" class="hover:text-blue-700">Startup</a>
    <a href="{{ route('coach') }}" class="hover:text-blue-700">Coach</a>
    <a href="{{ route('investisseur') }}" class="hover:text-blue-700">Investisseur</a>
    <a href="{{ route('forum') }}" class="hover:text-blue-700">Forum</a>
    <a href="{{ route('formation') }}" class="hover:text-blue-700">Formation</a>
    <a href="{{ route('resources') }}" class="hover:text-blue-700">Ressources</a>

    @auth
      <div class="w-10 h-10 rounded-full bg-blue-500/20 flex items-center justify-center text-blue-300 font-semibold">
        @if (Auth::user()->profile_image)
          <img class="h-10 w-10 rounded-full" src="{{ Auth::user()->profile_image }}" alt="User" />
        @else
          {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
        @endif
      </div>
    @else
      <a href="{{ route('login') }}" class="nav-link">Connexion</a>
      @if(Route::has('register'))
        <a href="{{ route('register') }}" class="btn-primary">Inscription</a>
      @endif
    @endauth
  </nav>

  <!-- Menu mobile -->
  <div x-show="menuOpen" @click.outside="menuOpen = false"
       x-transition
       class="md:hidden absolute top-full left-0 w-full bg-white shadow-lg py-4 px-6 z-40">
    <a href="{{ route('startup') }}" class="block py-2 text-blue-600 hover:text-blue-800">Startup</a>
    <a href="{{ route('coach') }}" class="block py-2 text-blue-600 hover:text-blue-800">Coach</a>
    <a href="{{ route('investisseur') }}" class="block py-2 text-blue-600 hover:text-blue-800">Investisseur</a>
    <a href="{{ route('forum') }}" class="block py-2 text-blue-600 hover:text-blue-800">Forum</a>
    <a href="{{ route('formation') }}" class="block py-2 text-blue-600 hover:text-blue-800">Formation</a>
    <a href="{{ route('resources') }}" class="block py-2 text-blue-600 hover:text-blue-800">Ressources</a>

    @auth
      <div class="mt-4 flex items-center gap-2">
        @if (Auth::user()->profile_image)
          <img class="h-10 w-10 rounded-full" src="{{ Auth::user()->profile_image }}" alt="User" />
        @else
          <div class="h-10 w-10 bg-blue-500 text-white rounded-full flex items-center justify-center font-semibold">
            {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
          </div>
        @endif
      </div>
    @else
      <a href="{{ route('login') }}" class="block mt-4 text-blue-600 font-medium">Connexion</a>
      @if(Route::has('register'))
        <a href="{{ route('register') }}" class="mt-2 inline-block bg-blue-600 text-white px-4 py-2 rounded-lg">Inscription</a>
      @endif
    @endauth
  </div>
</header>


<style scoped>
.logo-image {
  width: 180px;
  height: auto;
}
</style>

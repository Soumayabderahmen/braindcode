<!DOCTYPE html>
<html lang="fr" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact" dir="ltr"
    data-theme="theme-default" data-asset-path="../../asset/" data-template="vertical-menu-template">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title> @yield('title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="description" content="@yield('page_description')" />
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="" />
    <link rel="apple-touch-icon" href="">
    <!--bbotstrap-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!--icons-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!--fonts-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link href="https://fonts.cdnfonts.com/css/sf-pro-display" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-Avb2QiuDEEvB4bZJYdft2mNjVShBftLdPG8FJ0V7irTLQ8Uo0qcPxh4Plq7G5tGm0rU+1SPhVotteLpBERwTkw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @yield('css')
    <!--css-->
    <link rel="stylesheet" href="{{ asset('asset/css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/css/style.css') }}">

 <style>
  html, body {
    margin: 0;
    padding: 0;
    height: 100%;
    font-family: 'Poppins', 'SF Pro Display', sans-serif;
  }

  body.loading {
    overflow: hidden;
  }

  /* Masquer tout sauf le loader */
  body.loading > *:not(.page-loader) {
    display: none !important;
  }

  .page-loader {
    position: fixed;
    z-index: 999999;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    transition: opacity 0.5s ease, visibility 0.5s ease;
  }

  .page-loader.fade-out {
    opacity: 0;
    visibility: hidden;
  }

  .loader-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 20px;
  }

  .loader-animation {
    width: 100px;
    height: 100px;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .loader-logo {
    width: 80px;
    height: 80px;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .loader-pulse {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: rgba(0, 123, 255, 0.2);
    position: absolute;
    animation: pulse 1.5s ease-in-out infinite;
  }

  .loader-pulse:before {
    content: '';
    position: absolute;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: rgba(0, 123, 255, 0.4);
    top: 10px;
    left: 10px;
    animation: pulse 1.5s ease-in-out infinite;
    animation-delay: -0.5s;
  }

  .loader-pulse:after {
    content: '';
    position: absolute;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(0, 123, 255, 0.6);
    top: 20px;
    left: 20px;
    animation: pulse 1.5s ease-in-out infinite;
    animation-delay: -1s;
  }

  @keyframes pulse {
    0% {
      transform: scale(0.8);
      opacity: 0.8;
    }
    50% {
      transform: scale(1);
      opacity: 1;
    }
    100% {
      transform: scale(0.8);
      opacity: 0.8;
    }
  }

  .loader-text {
    font-size: 16px;
    color: #495057;
    font-weight: 500;
    letter-spacing: 0.5px;
  }

  .dot-animation {
    display: inline-block;
    animation: dots 1.5s infinite;
  }

  @keyframes dots {
    0%, 20% { content: '.'; }
    40% { content: '..'; }
    60%, 100% { content: '...'; }
  }

  .loader-progress {
    width: 200px;
    height: 4px;
    background-color: rgba(0, 0, 0, 0.1);
    border-radius: 4px;
    overflow: hidden;
    margin-top: 10px;
  }

  .loader-progress-bar {
    height: 100%;
    width: 0%;
    background: linear-gradient(90deg, #007bff, #6610f2);
    border-radius: 4px;
    animation: progress 2s ease-in-out infinite;
  }

  @keyframes progress {
    0% { width: 0%; }
    50% { width: 70%; }
    100% { width: 100%; }
  }

  /* Animation d'apparition du contenu */
  .content {
    opacity: 0;
    animation: fadeIn 0.5s ease forwards;
    animation-delay: 0.3s;
  }

  @keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
  }
</style>

    @routes
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
</head>


<body class="loading">


<div class="page-loader">
  <div class="loader-content">
    <div class="loader-animation">
      <div class="loader-logo">
        <!-- Vous pouvez remplacer ceci par votre logo SVG ou garder l'animation -->
        <div class="loader-pulse"></div>
      </div>
    </div>
    <div class="loader-text">Chargement<span class="dot-animation">...</span></div>
    <div class="loader-progress">
      <div class="loader-progress-bar"></div>
    </div>
  </div>
</div>

  @include('layouts.HomeView.navbarHome')
  <div id="app">
    @yield('content')
  </div>
  @include('layouts.HomeView.footer')

<script>
  window.addEventListener('load', () => {
    // Simuler la progression de chargement
    const progressBar = document.querySelector('.loader-progress-bar');
    let progress = 0;
    const interval = setInterval(() => {
      progress += 5;
      progressBar.style.width = `${progress}%`;
      
      if (progress >= 100) {
        clearInterval(interval);
        setTimeout(() => {
          const loader = document.querySelector('.page-loader');
          loader.classList.add('fade-out');
          
          setTimeout(() => {
            document.body.classList.remove('loading');
          });
        });
      }
    });
  });
</script>


    <!--script bootstrap-->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.min.js" integrity="sha384-VQqxDN0EQCkWoxt/0vsQvZswzTHUVOImccYmSyhJTp7kGtPed0Qcx8rK9h9YEgx+" crossorigin="anonymous"></script>
    @yield('script')
</body>



</html>
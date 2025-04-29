<template>
    <div
      class="light-style layout-navbar-fixed layout-menu-fixed layout-compact"
      dir="ltr"
      data-theme="theme-default"
      data-assets-path="../../assets/"
      data-template="vertical-menu-template"
    >
      <div>
        <slot></slot>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: "FullWidthLayout",
    mounted() {
      // Charger dynamiquement les CSS
      this.loadStyles([
        "https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap",
        "https://fonts.googleapis.com/css2?family=Lato:wght@100;300;400;700;900&display=swap",
        "https://fonts.cdnfonts.com/css/sf-pro-display",
        "https://fonts.cdnfonts.com/css/century-gothic-paneuropean",
        "/assets/vendor/fonts/fontawesome.css",
        "/assets/vendor/fonts/tabler-icons.css",
        "/assets/vendor/fonts/flag-icons.css",
        "/assets/vendor/css/rtl/core.css",
        "/assets/vendor/css/rtl/theme-default.css",
        "/assets/css/demo.css",
        "/assets/css/auth.css",
      ]);
  
      // Charger dynamiquement les JS
      this.loadScripts([
        "https://code.iconify.design/3/3.1.0/iconify.min.js",
        "/assets/vendor/libs/jquery/jquery.js",
        "/assets/vendor/libs/popper/popper.js",
        "/assets/vendor/js/bootstrap.js",
        "/assets/vendor/libs/node-waves/node-waves.js",
        "/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js",
        "/assets/vendor/libs/hammer/hammer.js",
        "/assets/vendor/libs/i18n/i18n.js",
        "/assets/vendor/libs/typeahead-js/typeahead.js",
        "/assets/vendor/js/menu.js",
        "/assets/vendor/libs/plyr/plyr.js",
        "/assets/js/main.js",
      ]);
    },
    methods: {
      loadStyles(urls) {
        urls.forEach(url => {
          const link = document.createElement("link");
          link.rel = "stylesheet";
          link.href = url;
          document.head.appendChild(link);
        });
      },
      loadScripts(urls) {
        urls.forEach(url => {
          const script = document.createElement("script");
          script.src = url;
          script.async = true;
          document.body.appendChild(script);
        });
      }
    }
  };
  </script>
  
  <style scoped>
  /* Tu peux ajouter ici ton style spécifique si nécessaire */
  </style>
  
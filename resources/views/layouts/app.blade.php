<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('titulo')</title>
    <!-- Bootstrap -->
    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <!-- jQuery UI CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/css/jquery-ui.min.css') }}">
    <!-- Select2 CSS -->
    <link href="{{ asset('vendor/css/select2.min.css') }}" rel="stylesheet" />
    <!-- Lucide Icons -->
    <script src="{{ asset('vendor/js/lucide.min.js') }}"></script>
    <!-- Fonts (Poppins local) -->
    <link href="{{ asset('vendor/css/fonts-local.css') }}" rel="stylesheet">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/css/dataTables.bootstrap5.min.css') }}">
    <script src="{{ asset('vendor/js/tailwindcss.js') }}"></script>
    <link href="{{ asset('vendor/css/bootstrap-4.5.2.min.css') }}" rel="stylesheet">

    <style>
        #mainContent {
            transform-origin: top left;
            /* para que el scale se haga desde la esquina */
        }
    </style>

</head>

<!--<body class="min-h-screen bg-gray-100">-->
<body class="bg-[#e6f4f3] text-gray-900">
    @include('layouts._partials.menu')


    <div class="flex min-h-screen"> <!-- Contenedor para sidebar + contenido -->
        @include('layouts._partials.sidebar') <!-- Tu sidebar expandible -->



        <!--<main class="flex-1 p-8 pb-20 transition-all duration-300 ease-in-out">

    <div class="max-w-full mx-auto">-->

        <main id="mainContent" class="flex-1 pt-20 px-8 pb-20 transition-all duration-300 ease-in-out transform">
            <div class="max-w-full mx-auto">


                @yield('contenido')
                {{-- <div class="container mt-2">
            @if ($rutaActual !== 'inicio')
                <div class="boton-volver mt-3" style="position: fixed; bottom: 20px; right: 20px; z-index: 9999;">
                    <a href="{{ route($rutaAnterior) }}"
                        class="d-flex justify-content-center align-items-center rounded-circle bg-secondary text-white shadow-lg"
                        style="width: 48px; height: 48px; transition: all 0.3s;"
                        onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                        class="bi bi-arrow-left" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M15 8a.5.5 0 0 1-.5.5H3.707l3.147 3.146a.5.5 0 0 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 0 1 .708.708L3.707 7.5H14.5A.5.5 0 0 1 15 8z" />
                        </svg>
                    </a>
                </div>
            @endif

            <!-- I begin to speak only when I am certain what I will say is not better left unsaid. - Cato the Younger -->
        </div> --}}
            </div>
        </main>
    </div>
    @include('components.boton-volver')

    @include('layouts._partials.footer')
    <style>
        #mainContent {
            padding-top: 5rem !important;
            /* fuerza el padding-top sin afectar padding otros lados */
        }
    </style>
    <!-- jQuery (solo una vez) -->
    <script src="{{ asset('vendor/js/jquery-3.6.0.min.js') }}"></script>
    <!-- jQuery UI JS -->
    <script src="{{ asset('vendor/js/jquery-ui-1.13.2.min.js') }}"></script>
    <!-- Bootstrap Bundle JS -->
    <script src="{{ asset('vendor/js/bootstrap.bundle.min.js') }}"></script>
    <!-- Tailwind (CDN local) -->
    <script src="{{ asset('vendor/js/tailwindcss.js') }}"></script>
    <!-- Select2 JS -->
    <script src="{{ asset('vendor/js/select2.min.js') }}"></script>
    <!-- AOS CSS -->
    <link href="{{ asset('vendor/css/aos.min.css') }}" rel="stylesheet">
    <!-- DataTables JS -->
    <script src="{{ asset('vendor/js/dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/js/dataTables.bootstrap5.min.js') }}"></script>

    <!-- Lucide Iconos -->
    <script>
        if (window.lucide) {
            lucide.createIcons();
        } else {
            console.error('Lucide no se cargó correctamente');
        }
    </script>


    <script>
        window.addEventListener('scroll', () => {
            const footer = document.querySelector('footer');
            const boton = document.querySelector('.boton-volver');
            if (!footer || !boton) return;

            const footerRect = footer.getBoundingClientRect();
            const windowHeight = window.innerHeight;

            if (footerRect.top < windowHeight) {
                const overlap = windowHeight - footerRect.top;
                boton.style.bottom = (20 + overlap) + 'px';
            } else {
                boton.style.bottom = '20px';
            }
        });
    </script>

    <!-- Script del botón menú -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const menuBtn = document.getElementById("menuBtn");
            const dropdownMenu = document.getElementById("dropdownMenu");

            if (menuBtn && dropdownMenu) {
                menuBtn.addEventListener("click", function() {
                    dropdownMenu.classList.toggle("hidden");
                });
            }
        });
    </script>


        <script>
            window.addEventListener('load', ajustarPadding);
            window.addEventListener('resize', ajustarPadding);

            function ajustarPadding() {
                const header = document.querySelector('header'); // Cambia selector si tu header no es <header>
                const container = document.querySelector('.container');

                if (header && container) {
                    const alturaHeader = header.offsetHeight;
                    container.style.paddingTop = alturaHeader + 'px';
                }
            }
        </script>


    <!-- Scripts adicionales desde las vistas -->
    @stack('scripts')

      @stack('modales')

</body>
</html>
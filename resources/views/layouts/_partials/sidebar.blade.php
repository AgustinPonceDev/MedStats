<!-- Sidebar global -->
<aside id="sidebar"
    class="fixed top-16 left-0 h-[calc(100vh-4rem)] w-20 bg-white shadow-xl flex flex-col transition-all duration-300 ease-in-out z-40 border-r border-gray-100 sidebar-colapsado collapsed">

    <!-- Header del sidebar -->
    <div class="flex items-center justify-between px-4 h-16 border-b border-gray-100 shrink-0">
        <span id="sidebar-title" class="font-bold text-[#1B7D8F] text-lg hidden whitespace-nowrap overflow-hidden transition-all duration-300">Menú</span>
        <button id="toggleSidebar" class="p-2 rounded-lg hover:bg-gray-50 text-gray-500 hover:text-[#1B7D8F] focus:outline-none transition-colors mx-auto">
            <i data-lucide="menu" class="w-6 h-6"></i>
        </button>
    </div>

    <!-- Contenido Scrollable -->
    <div class="flex-1 overflow-y-auto overflow-x-hidden py-4 space-y-1 px-3 custom-scrollbar">

        <!-- Botón volver -->
        @php
            $rutaActual = request()->route() ? request()->route()->getName() : '';
            $rutaAnterior = 'inicio'; // Default

            switch ($rutaActual) {
                case 'stocks.index':
                case 'pacientes.index':
                case 'estadisticas':
                case 'camas.index':
                case 'cirugias.estadisticas':
                case 'stocks.estadisticasstock':
                case 'ajustes':
                    $rutaAnterior = 'inicio';
                    break;

                // Rutas que vuelven a Ajustes
                case 'usuarios.index':
                case 'camas.listar':
                case 'medicamentos.index':
                case 'UsuarioPerfil.index':
                case 'empleados.index':
                case 'salas.index':
                case 'habitaciones.index':
                case 'quirofanos.index':
                case 'procedimientos.index':
                case 'profesion.index':
                case 'tipoAnestesias.index':
                case 'ocupacionCamas.index':
                case 'perfiles.index':
                case 'especialidades.index':
                    $rutaAnterior = 'ajustes';
                    break;

                // Perfiles
                case 'perfiles.create':
                case 'perfiles.edit':
                    $rutaAnterior = 'perfiles.index';
                    break;

                // Profesión
                case 'profesion.create':
                case 'profesion.edit':
                case 'profesion.show':
                    $rutaAnterior = 'profesion.index';
                    break;

                // Procedimientos
                case 'procedimientos.create':
                case 'procedimientos.edit':
                case 'procedimientos.show':
                    $rutaAnterior = 'procedimientos.index';
                    break;

                // Camas
                case 'camas.create':
                case 'camas.edit':
                case 'camas.show':
                    $rutaAnterior = 'camas.listar';
                    break;

                // Empleados
                case 'empleados.create':
                case 'empleados.edit':
                case 'empleados.show':
                    $rutaAnterior = 'empleados.index';
                    break;

                // Stocks
                case 'stocks.create':
                case 'stocks.edit':
                case 'stocks.show':
                    $rutaAnterior = 'stocks.index';
                    break;

                // Tipo Anestesias
                case 'tipoAnestesias.create':
                case 'tipoAnestesias.edit':
                    $rutaAnterior = 'tipoAnestesias.index';
                    break;

                // Pacientes
                case 'pacientes.create':
                case 'pacientes.edit':
                case 'pacientes.show':
                case 'pacientes.asignar':
                    if (url()->previous() && str_contains(url()->previous(), route('pacientes.index'))) {
                        $rutaAnterior = 'pacientes.index';
                    } else {
                        $id = request()->route('id') ?? request()->route('paciente') ?? null;
                        if ($id) {
                            $rutaAnterior = ['persona.ver', ['id' => $id]];
                        } else {
                            $rutaAnterior = 'pacientes.index';
                        }
                    }
                    break;

                // Ocupación de camas
                case 'ocupacionCamas.create':
                case 'ocupacionCamas.edit':
                case 'ocupacionCamas.show':
                case 'ocupacionCamas.darAlta':
                    $rutaAnterior = 'ocupacionCamas.index';
                    break;

                // Medicamentos
                case 'medicamentos.create':
                case 'medicamentos.edit':
                    $rutaAnterior = 'medicamentos.index';
                    break;

                // Usuarios
                case 'usuarios.create':
                case 'usuarios.edit':
                case 'usuarios.show':
                    $rutaAnterior = 'usuarios.index';
                    break;

                // Habitaciones
                case 'habitaciones.create':
                case 'habitaciones.edit':
                    $rutaAnterior = 'habitaciones.index';
                    break;

                // UsuarioPerfil
                case 'UsuarioPerfil.create':
                case 'UsuarioPerfil.edit':
                    $rutaAnterior = 'UsuarioPerfil.index';
                    break;

                // Salas
                case 'salas.create':
                case 'salas.edit':
                    $rutaAnterior = 'salas.index';
                    break;

                // Cirugías
                case 'cirugias.create':
                case 'cirugias.edit':
                case 'cirugias.show':
                    $rutaAnterior = 'cirugias.index';
                    break;

                // Diagnóstico por imágenes
                case 'estudios_medicos.index':
                case 'estudios_medicos.create':
                case 'estudios_medicos.edit':
                case 'estudios_medicos.show':
                    $rutaAnterior = 'estudios_medicos.index';
                    break;

                // Quirófanos
                case 'quirofanos.create':
                case 'quirofanos.edit':
                case 'quirofanos.show':
                    $rutaAnterior = 'quirofanos.index';
                    break;

                // Especialidades
                case 'especialidades.create':
                case 'especialidades.edit':
                    $rutaAnterior = 'especialidades.index';
                    break;

                // Por defecto
                default:
                    $rutaAnterior = 'inicio';
                    break;
            }
        @endphp

        @if ($rutaActual !== 'inicio')
            <a href="#" onclick="event.preventDefault(); history.back();"
               class="sb-link flex items-center gap-3 p-3 rounded-xl text-gray-600 hover:bg-gray-50 hover:text-[#1B7D8F] transition-all group relative"
               title="Volver">
                <i data-lucide="arrow-left" class="w-6 h-6 flex-shrink-0 transition-transform group-hover:-translate-x-1"></i>
                <span class="link-text text-[13px] leading-tight font-medium hidden opacity-0 transition-opacity duration-300">Volver</span>
            </a>
            <div class="my-2 border-t border-gray-100 mx-2"></div>
        @endif

        <!-- Links Principales -->
        @php
            // El link "Estadísticas" apunta a Cirugías por defecto (comportamiento de
            // siempre, incluido Admin). Pero si el usuario NO tiene acceso a Cirugías
            // y SÍ a Estudios Médicos (ej. el rol "Diagnóstico por Imágenes"), no tiene
            // sentido mandarlo a una página de estadísticas de cirugías vacía — va
            // directo a Estadísticas de Stock, que es lo que realmente puede usar.
            $rutaEstadisticas = 'cirugias.estadisticas';
            if (!Auth::user()->hasAccess('cirugias') && Auth::user()->hasAccess('estudios_medicos')) {
                $rutaEstadisticas = 'estudios_medicos.estadisticas';
            }

            // 'match': rutas que pertenecen a la sección (el item se resalta también en
            // sus pantallas internas: crear, editar, ver). 'except': rutas que pertenecen a otra.
            $menuItems = [
                ['route' => 'stocks.index', 'title' => 'Insumos', 'icon' => 'package', 'access' => 'insumos',
                    'match' => ['stocks.*'], 'except' => ['stocks.estadisticasstock']],
                ['route' => $rutaEstadisticas, 'title' => 'Estadísticas', 'icon' => 'bar-chart-2', 'access' => 'estadisticas',
                    'match' => ['cirugias.estadisticas', 'estudios_medicos.estadisticas', 'stocks.estadisticasstock', 'estadisticas']],
                ['route' => 'pacientes.index', 'title' => 'Pacientes', 'icon' => 'users', 'access' => 'pacientes',
                    'match' => ['pacientes.*', 'persona.*']],
                ['route' => 'camas.index', 'title' => 'Camas', 'icon' => 'bed', 'access' => 'camas',
                    'match' => ['camas.index']],
                ['route' => 'cirugias.index', 'title' => 'Cirugías', 'icon' => 'activity', 'access' => 'cirugias',
                    'match' => ['cirugias.*'], 'except' => ['cirugias.estadisticas']],
                ['route' => 'estudios_medicos.index', 'title' => 'Diagnóstico por imágenes', 'icon' => 'image', 'access' => 'estudios_medicos',
                    'match' => ['estudios_medicos.*'], 'except' => ['estudios_medicos.estadisticas']],
                ['route' => 'trazabilidad.index', 'title' => 'Trazabilidad', 'icon' => 'route', 'access' => 'trazabilidad',
                    'match' => ['trazabilidad.*']],
            ];

            $itemAjustes = ['route' => 'ajustes', 'title' => 'Ajustes', 'icon' => 'settings', 'access' => null,
                'match' => ['ajustes', 'usuarios.*', 'camas.listar', 'camas.create', 'camas.edit', 'camas.show', 'medicamentos.*',
                    'UsuarioPerfil.*', 'empleados.*', 'salas.*', 'habitaciones.*', 'quirofanos.*', 'procedimientos.*',
                    'profesion.*', 'tipoAnestesias.*', 'ocupacionCamas.*', 'especialidades.*', 'servicios.*',
                    'paises.*', 'provincias.*', 'codigos_postales.*', 'estudios.*']];

            $esActivo = function (array $item) {
                return request()->routeIs($item['match']) && !request()->routeIs($item['except'] ?? []);
            };
        @endphp

        @foreach($menuItems as $item)
            @if(Auth::user()->hasAccess($item['access']))
                @include('layouts._partials.sidebar-link', ['item' => $item, 'activo' => $esActivo($item)])
            @endif
        @endforeach

        <div class="my-2 border-t border-gray-100 mx-2"></div>
        @include('layouts._partials.sidebar-link', ['item' => $itemAjustes, 'activo' => $esActivo($itemAjustes)])

    </div>

    <!-- Footer del Sidebar (Logout) -->
    <div class="p-3 border-t border-gray-100 shrink-0">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="sb-link w-full flex items-center gap-3 p-3 rounded-xl text-red-500 hover:bg-red-50 transition-all group relative"
                title="Cerrar Sesión">
                <i data-lucide="log-out" class="w-6 h-6 flex-shrink-0"></i>
                <span class="link-text text-[13px] leading-tight font-medium hidden opacity-0 transition-opacity duration-300">Cerrar Sesión</span>
            </button>
        </form>
    </div>

</aside>

<style>
    /* Colapsado: íconos centrados en los 80px del sidebar */
    /* Al cargar una página no se anima el sidebar: queda como el usuario lo dejó */
    .sb-sin-animacion #sidebar, .sb-sin-animacion #mainContent, .sb-sin-animacion #footer,
    .sb-sin-animacion #sidebar .link-text, .sb-sin-animacion #sidebar-title { transition: none !important; }
    #sidebar.collapsed .sb-link { justify-content: center; }
    #sidebar.collapsed .sb-link .link-text { display: none; }
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: #e5e7eb;
        border-radius: 20px;
    }
    .custom-scrollbar:hover::-webkit-scrollbar-thumb {
        background-color: #d1d5db;
    }
</style>

<script>
    (function () {
        const KEY = 'sidebar-expanded';
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('toggleSidebar');
        const linkTexts = document.querySelectorAll('#sidebar .link-text');
        const sidebarTitle = document.getElementById('sidebar-title');

        function leerEstado() {
            try { return localStorage.getItem(KEY) === 'true'; } catch (e) { return false; }
        }
        function guardarEstado(expanded) {
            try { localStorage.setItem(KEY, expanded ? 'true' : 'false'); } catch (e) {}
        }

        // instant = true: aplica el estado sin animaciones ni destellos (carga de página)
        function setSidebarState(expanded, instant) {
            const mainContent = document.getElementById('mainContent');
            const footer = document.getElementById('footer');
            const margen = expanded ? '16rem' : '5rem';

            sidebar.classList.toggle('w-64', expanded);
            sidebar.classList.toggle('sidebar-expandido', expanded);
            sidebar.classList.toggle('w-20', !expanded);
            sidebar.classList.toggle('sidebar-colapsado', !expanded);
            sidebar.classList.toggle('collapsed', !expanded);

            linkTexts.forEach(el => {
                if (expanded) {
                    el.classList.remove('hidden');
                    if (instant) el.classList.remove('opacity-0');
                    else setTimeout(() => el.classList.remove('opacity-0'), 50);
                } else {
                    el.classList.add('opacity-0', 'hidden');
                }
            });

            if (sidebarTitle) {
                if (expanded) {
                    sidebarTitle.classList.remove('hidden');
                    if (instant) sidebarTitle.classList.remove('opacity-0');
                    else setTimeout(() => sidebarTitle.classList.remove('opacity-0'), 50);
                } else {
                    sidebarTitle.classList.add('hidden');
                }
            }

            if (mainContent) mainContent.style.marginLeft = margen;
            if (footer) {
                footer.style.marginLeft = margen;
                footer.style.width = 'calc(100% - ' + margen + ')';
            }
        }

        // Estado recordado: se aplica ya mismo al sidebar (sin animar) para que no "salte" al cambiar de página
        document.documentElement.classList.add('sb-sin-animacion');
        const expandido = leerEstado();
        setSidebarState(expandido, true);

        document.addEventListener('DOMContentLoaded', function () {
            if (window.lucide) lucide.createIcons();

            // main y footer ya existen: les aplicamos el mismo estado, sin animación
            setSidebarState(leerEstado(), true);
            requestAnimationFrame(() => requestAnimationFrame(() => {
                document.documentElement.classList.remove('sb-sin-animacion');
                const footer = document.getElementById('footer');
                if (footer) footer.style.transition = 'all 0.3s ease-in-out';
            }));

            // Solo el botón del menú cambia (y guarda) el estado; navegar no lo toca
            toggleBtn.addEventListener('click', function () {
                const nuevo = !sidebar.classList.contains('w-64');
                setSidebarState(nuevo, false);
                guardarEstado(nuevo);
            });
        });
    })();
</script>

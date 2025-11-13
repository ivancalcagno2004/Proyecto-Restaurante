<header class="sticky top-0 w-full z-50">
    <nav class="bg-gray-800 shadow-lg">
        <div class="container mx-auto flex justify-between items-center">
            <!-- Logo -->
            <a href="{{ url('/') }}" class="flex items-center text-white text-lg font-semibold">
                <img src="{{ asset('images/logo.png') }}" alt="Logo La Azucena" class="h-20 mr-2">
                POS Restaurante
            </a>

            <!-- Botón de menú para pantallas pequeñas -->
            <button id="menu-toggle" class="text-gray-300 hover:text-white focus:outline-none lg:hidden">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                </svg>
            </button>

            <!-- Menú de navegación -->
            <div id="menu" class="hidden lg:flex flex-col lg:flex-row lg:items-center lg:space-x-4">
                @if (url()->current() !== url('/map'))
                <a href="{{ url('/map') }}" class="text-gray-300 hover:text-white mx-2">Ver Salón</a>
                @endif

                @if (Route::currentRouteName() !== 'mesas.index')
                <a href="{{ route('mesas.index') }}" class="text-gray-300 hover:text-white mx-2">Mesas</a>
                @endif

                @if (Route::currentRouteName() !== 'productos.index')
                <a href="{{ route('productos.index') }}" class="text-gray-300 hover:text-white mx-2">Productos</a>
                @endif

                @if (Route::currentRouteName() !== 'pedidos.index')
                <a href="{{ route('pedidos.index') }}" class="text-gray-300 hover:text-white mx-2">Pedidos</a>
                @endif
            </div>
        </div>
    </nav>
</header>

<script>
    // Script para manejar el menú en pantallas pequeñas
    document.getElementById('menu-toggle').addEventListener('click', function() {
        const menu = document.getElementById('menu');
        menu.classList.toggle('hidden');
    });

</script>

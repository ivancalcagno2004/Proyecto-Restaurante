<header>
    <nav class="bg-gray-800 p-4">
        <div class="container mx-auto flex justify-between items-center">
            <a href="{{ url('/') }}" class="text-white text-lg font-semibold">POS Restaurante</a>
            <div>
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

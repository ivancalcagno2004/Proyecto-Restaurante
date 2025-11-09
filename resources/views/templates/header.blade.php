<header>
    <nav class="bg-gray-800 p-4">
        <div class="container mx-auto flex justify-between items-center">
            <a href="{{ url('/') }}" class="text-white text-lg font-semibold">POS Restaurante</a>
            <div>
                <a href="{{ route('mesas.index') }}" class="text-gray-300 hover:text-white mx-2">Mesas</a>
                <a href="{{ route('productos.index') }}" class="text-gray-300 hover:text-white mx-2">Productos</a>
                <a href="{{ route('pedidos.index') }}" class="text-gray-300 hover:text-white mx-2">Pedidos</a>
            </div>
        </div>
    </nav>
</header>
@extends('pages.inicio')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Editar Productos del Pedido #{{ $pedido->id }}</h1>
    <hr>
    <h1 class="text-xl text-gray mb-4">Cliente: {{ $pedido->mesa->nombre }}</h1>

    <form action="{{ route('pedidos.update-productos', $pedido->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="bg-white p-6 rounded-lg shadow-md overflow-x-auto">
            <h2 class="text-xl font-bold text-gray-700 mb-4">Productos en el Pedido</h2>
            <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-md">
                <thead>
                    <tr class="bg-gray-100 text-gray-700 uppercase text-sm">
                        <th class="px-6 py-3 text-center">Producto</th>
                        <th class="px-6 py-3 text-center">Descripción</th>
                        <th class="px-6 py-3 text-center">Cantidad</th>
                        <th class="px-6 py-3 text-center">Eliminar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pedido->productos as $producto)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-6 py-4 text-gray-800 text-center">{{ $producto->nombre }}</td>
                        <td class="px-6 py-4 text-gray-800 text-center">
                            @if($producto->descripcion)
                            {{ $producto->descripcion }}
                            @else
                            <span class="text-gray-400 italic">Sin descripción</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-800 text-center">
                            @if($producto->stock >= 1)
                            <input type="number" name="productos[{{ $producto->id }}][cantidad]" value="{{ $producto->pivot->cantidad }}" min="1" max="{{$producto->stock}}" class="w-20 border border-gray-300 rounded-lg px-2 py-1">
                            @else
                            <input type="number" name="productos[{{ $producto->id }}][cantidad]" value="{{ $producto->pivot->cantidad }}" min="1" max="{{$producto->stock}}" class="w-20 border border-gray-300 rounded-lg px-2 py-1" disabled>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-800 text-center">
                            <input type="checkbox" name="productos[{{ $producto->id }}][eliminar]" value="1">
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Filtro por categoría -->
        <div class="mb-4 mt-8">
            <h2 class="text-xl font-bold text-gray-700 mb-4">Agregar Productos al Pedido</h2>
            <label for="categoria" class="block text-gray-700 font-medium mb-2">Filtrar por Categoría</label>
            <select id="categoria" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="all">Todas las Categorías</option>
                @foreach ($categorias as $categoria)
                @if($categoria === "plato_principal")
                <option value="{{ $categoria }}">Plato Principal</option>
                @else
                <option value="{{ $categoria }}">{{ ucfirst($categoria) }}</option>
                @endif
                @endforeach
            </select>
        </div>

        <!-- Selección de productos -->
        <div class="mb-4 overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-md">
                <thead>
                    <tr class="bg-gray-100 text-gray-700 uppercase text-sm">
                        <th class="px-6 py-3 text-center">Producto</th>
                        <th class="px-6 py-3 text-center">Descripción</th>
                        <th class="px-6 py-3 text-center">Precio</th>
                        <th class="px-6 py-3 text-center">Cantidad</th>
                    </tr>
                </thead>
                <tbody id="productos-tbody">
                    @foreach ($productos as $categoria => $productosCategoria)
                    <!-- Encabezado de la categoría -->
                    <tr class="bg-gray-200">
                        <td colspan="4" class="px-6 py-3 text-left font-bold text-gray-700">
                            @if($categoria === "plato_principal")
                            Plato Principal
                            @else
                            {{ ucfirst($categoria) }}
                            @endif
                        </td>
                    </tr>
                    <!-- Productos de la categoría -->
                    @foreach ($productosCategoria as $producto)
                    <tr class="border-b hover:bg-gray-50" data-categoria="{{ $categoria }}">
                        <td class="px-6 py-4 text-gray-800 text-center">
                            <label>
                                @if($producto->stock >= 1)
                                <input type="checkbox" name="nuevos_productos[{{ $producto->id }}][id]" value="{{ $producto->id }}" class="nuevo-producto-checkbox">
                                @else
                                <input type="checkbox" disabled>
                                @endif
                                {{ $producto->nombre }}
                            </label>
                        </td>
                        <td class="px-6 py-4 text-gray-800 text-center">
                            @if($producto->descripcion)
                            {{ $producto->descripcion }}
                            @else
                            <span class="text-gray-400 italic">Sin descripción</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-800 text-center">
                            ${{ number_format($producto->precio, 2) }}
                        </td>
                        <td class="px-6 py-4 text-gray-800 text-center">
                            @if($producto->stock >= 1)
                            <input type="number" name="nuevos_productos[{{ $producto->id }}][cantidad]" value="1" min="1" max="{{ $producto->stock }}" class="w-20 border border-gray-300 rounded-lg px-2 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500 nuevo-producto-cantidad" disabled>
                            @else
                            <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded-full">Sin Stock</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6 mb-18 flex justify-end align-center fixed bottom-0 left-0 w-full bg-white p-4 border-t border-gray-200">
            <a href="{{ url()->previous() }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition mr-2">
                Cancelar
            </a>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition cursor-pointer">
                Guardar Cambios
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const categoriaSelect = document.getElementById('categoria');
        const productosRows = document.querySelectorAll('#productos-tbody tr');

        categoriaSelect.addEventListener('change', (e) => {
            const categoriaSeleccionada = e.target.value;

            productosRows.forEach(row => {
                const categoriaProducto = row.getAttribute('data-categoria');
                if (categoriaSeleccionada === 'all' || categoriaProducto === categoriaSeleccionada) {
                    row.style.display = ''; // Mostrar fila
                } else {
                    row.style.display = 'none'; // Ocultar fila
                }
            });
        });

        // Habilitar/deshabilitar el campo de cantidad según el checkbox
        const checkboxes = document.querySelectorAll('.nuevo-producto-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', (e) => {
                const cantidadInput = e.target.closest('tr').querySelector('.nuevo-producto-cantidad');
                cantidadInput.disabled = !e.target.checked;
            });
        });
    });

</script>
@endsection

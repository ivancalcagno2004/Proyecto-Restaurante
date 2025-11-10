@extends('pages.inicio')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Crear Pedido para {{ $mesa->nombre }}</h1>

    <div class="bg-white p-6 rounded-lg shadow-md">
        <form action="{{ route('pedidos.store') }}" method="POST">
            @csrf

            <!-- Información de la mesa -->
            <input type="hidden" name="mesa_id" value="{{ $mesa->id }}">

            <!-- Selección de productos -->
            <div class="mb-4">
                <label for="productos" class="block text-gray-700 font-medium mb-2">Seleccionar Productos</label>
                <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-md">
                    <thead>
                        <tr class="bg-gray-100 text-gray-700 uppercase text-sm">
                            <th class="px-6 py-3 text-center">Producto</th>
                            <th class="px-6 py-3 text-center">Precio</th>
                            <th class="px-6 py-3 text-center">Cantidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($productos as $producto)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-4 text-gray-800 text-center">
                                <label>
                                    <input type="checkbox" name="productos[{{ $producto->id }}][id]" value="{{ $producto->id }}">
                                    {{ $producto->nombre }}
                                </label>
                            </td>
                            <td class="px-6 py-4 text-gray-800 text-center">
                                ${{ number_format($producto->precio, 2) }}
                            </td>
                            <td class="px-6 py-4 text-gray-800 text-center">
                                @if($producto->stock >= 1)
                                <input type="number" name="productos[{{ $producto->id }}][cantidad]" value="1" min="1" max="{{$producto->stock}}" class="w-20 border border-gray-300 rounded-lg px-2 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500" disabled>
                                @else
                                <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded-full">Sin Stock</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Botón de guardar -->
            <div class="flex justify-end">
                <a href="{{ route('mesas.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition mr-2">
                    Cancelar
                </a>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition cursor-pointer">
                    Guardar Pedido
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Habilitar/deshabilitar el campo de cantidad según el checkbox
    document.addEventListener('DOMContentLoaded', () => {
        const checkboxes = document.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', (e) => {
                const cantidadInput = e.target.closest('tr').querySelector('input[type="number"]');
                cantidadInput.disabled = !e.target.checked;
            });
        });
    });

</script>
@endsection

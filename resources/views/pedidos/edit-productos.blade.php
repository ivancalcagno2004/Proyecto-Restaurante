@extends('pages.inicio')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Editar Productos del Pedido #{{ $pedido->id }}</h1>
    <hr>
    <h1 class="text-xl text-gray mb-4">Cliente: {{ $pedido->mesa->nombre }}</h1>

    <form action="{{ route('pedidos.update-productos', $pedido->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-bold text-gray-700 mb-4">Productos en el Pedido</h2>
            <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-md">
                <thead>
                    <tr class="bg-gray-100 text-gray-700 uppercase text-sm">
                        <th class="px-6 py-3 text-center">Producto</th>
                        <th class="px-6 py-3 text-center">Cantidad</th>
                        <th class="px-6 py-3 text-center">Eliminar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pedido->productos as $producto)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-6 py-4 text-gray-800 text-center">{{ $producto->nombre }}</td>
                        <td class="px-6 py-4 text-gray-800 text-center">
                            <input type="number" name="productos[{{ $producto->id }}][cantidad]" value="{{ $producto->pivot->cantidad }}" min="1" class="w-20 border border-gray-300 rounded-lg px-2 py-1">
                        </td>
                        <td class="px-6 py-4 text-gray-800 text-center">
                            <input type="checkbox" name="productos[{{ $producto->id }}][eliminar]" value="1">
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md mt-6">
            <h2 class="text-xl font-bold text-gray-700 mb-4">Agregar Nuevos Productos</h2>
            <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-md">
                <thead>
                    <tr class="bg-gray-100 text-gray-700 uppercase text-sm">
                        <th class="px-6 py-3 text-center">Producto</th>
                        <th class="px-6 py-3 text-center">Cantidad</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($productos as $producto)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-6 py-4 text-gray-800 text-center">
                            <label>
                                <input type="checkbox" name="nuevos_productos[{{ $producto->id }}][id]" value="{{ $producto->id }}" class="nuevo-producto-checkbox">
                                {{ $producto->nombre }}
                            </label>
                        </td>
                        <td class="px-6 py-4 text-gray-800 text-center">
                            <input type="number" name="nuevos_productos[{{ $producto->id }}][cantidad]" value="1" min="1" class="w-20 border border-gray-300 rounded-lg px-2 py-1 nuevo-producto-cantidad" disabled>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6 mb-15 flex justify-end">
            <a href="{{ route('pedidos.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition mr-2">
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

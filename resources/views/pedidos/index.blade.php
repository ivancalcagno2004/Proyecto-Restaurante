@extends('pages.inicio')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Pedidos</h1>

    <!-- Botón flotante para crear un predido -->
    <a href="{{ route('pedidos.select-mesa') }}" class="fixed bottom-28 right-33 bg-blue-500 text-white p-4 rounded-full shadow-lg hover:bg-blue-600 transition cursor-pointer font-bold text-3xl">
        +
    </a>

    <div class="mt-6 overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-md">
            <thead>
                <tr class="bg-gray-100 text-gray-700 uppercase text-sm">
                    <th class="px-6 py-3 text-center">ID</th>
                    <th class="px-6 py-3 text-center">Mesa</th>
                    <th class="px-6 py-3 text-center">Estado</th>
                    <th class="px-6 py-3 text-center">Total</th>
                    <th class="px-6 py-3 text-center">Fecha</th>
                    <th class="px-6 py-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @if($pedidos->isEmpty())
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                        <span class="text-gray-400 italic">No hay Pedidos Registrados</span>
                    </td>
                </tr>
                @else
                @foreach ($pedidos as $pedido)
                <tr class="border-b hover:bg-gray-50">
                    <!-- ID del pedido -->
                    <td class="px-6 py-4 text-gray-800 text-center">{{ $pedido->id }}</td>

                    <!-- Mesa asociada -->
                    <td class="px-6 py-4 text-gray-800 text-center">
                        {{ $pedido->mesa->nombre ?? 'Sin asignar' }}
                    </td>

                    <!-- Estado del pedido -->
                    <td class="px-6 py-4 text-center">
                        @if (isset($quiereEditar) && $quiereEditar && $pedidoEdit->id == $pedido->id)
                        <form action="{{ route('pedidos.update', $pedido->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <select name="estado" class="border border-gray-300 rounded px-2 py-1">
                                <option value="pendiente" {{ $pedido->estado === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="en_preparacion" {{ $pedido->estado === 'en_preparacion' ? 'selected' : '' }}>Preparando</option>
                                <option value="servido" {{ $pedido->estado === 'servido' ? 'selected' : '' }}>Servido</option>
                                <option value="facturado" {{ $pedido->estado === 'facturado' ? 'selected' : '' }}>Facturado</option>
                            </select>
                            <button type="submit" class="bg-green-500 text-white px-2 py-1 rounded ml-2 hover:bg-green-600 transition cursor-pointer">
                                Guardar
                            </button>
                        </form>
                        @else
                        <span class="estado-label">
                            @if ($pedido->estado === 'pendiente')
                            <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full">Pendiente</span>
                            @elseif ($pedido->estado === 'en_preparacion')
                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full">Preparando</span>
                            @elseif ($pedido->estado === 'servido')
                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full">Servido</span>
                            @elseif ($pedido->estado === 'facturado')
                            <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full">Facturado</span>
                            @endif
                        </span>
                        @endif
                    </td>

                    <!-- Total del pedido -->
                    <td class="px-6 py-4 text-gray-800 text-center">
                        ${{ number_format($pedido->total, 2) }}
                    </td>

                    <!-- Fecha del pedido -->
                    <td class="px-6 py-4 text-gray-800 text-center">
                        {{ $pedido->created_at->format('d/m/Y H:i') }}
                    </td>

                    <!-- Acciones -->
                    <td class="px-6 py-4 text-center">
                        @if (isset($quiereEditar) && $quiereEditar && $pedidoEdit->id == $pedido->id)
                        <a href="{{ route('pedidos.index') }}" class="bg-gray-500 text-white px-2 py-1 rounded ml-2 hover:bg-gray-600 transition cursor-pointer">Cancelar</a>
                        <a href="{{ route('pedidos.edit-productos', $pedido->id) }}" class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600 transition cursor-pointer">
                            Editar Productos
                        </a>
                        @else
                        <a href="{{ route('pedidos.show', $pedido->id) }}" class="bg-green-500 text-white px-2 py-1 rounded hover:bg-green-600 transition">
                            Ver Detalles
                        </a>
                        <a href="{{ route('pedidos.edit', $pedido->id) }}" class="bg-blue-500 text-white px-2 py-1 rounded ml-2 hover:bg-blue-600 transition cursor-pointer">
                            Editar
                        </a>
                        @endif
                        <form action="{{ route('pedidos.destroy', $pedido->id) }}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded ml-2 hover:bg-red-600 transition cursor-pointer">
                                Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection

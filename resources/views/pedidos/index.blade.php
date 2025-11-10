@extends('pages.inicio')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Pedidos</h1>

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
                @foreach ($pedidos as $pedido)
                <tr class="border-b hover:bg-gray-50">
                    <!-- ID del pedido -->
                    <td class="px-6 py-4 text-gray-800 text-center">{{ $pedido->id }}</td>

                    <!-- Mesa asociada -->
                    <td class="px-6 py-4 text-gray-800 text-center">
                        {{ $pedido->mesa->nombre ?? 'Sin asignar' }}
                    </td>

                    <!-- Estado del pedido -->
                    <td class="px-6 py-4 text-gray-800 text-center uppercase">
                        {{ $pedido->estado }}
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
                    <td class="px-6 py-4 text-gray-800 text-center">
                        <a href="{{ route('pedidos.show', $pedido->id) }}" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition mr-2">
                            Ver Detalles
                        </a>
                        <a href="{{ route('pedidos.edit', $pedido->id) }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition mr-2">
                            Editar
                        </a>
                        <form action="{{ route('pedidos.destroy', $pedido->id) }}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition cursor-pointer">
                                Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

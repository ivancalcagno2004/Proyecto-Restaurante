@extends('pages.inicio')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Detalles del Pedido #{{ $pedido->id }}</h1>

    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-bold text-gray-700 mb-4">Información del Pedido</h2>
        <p><strong>Mesa:</strong> {{ $pedido->mesa->nombre ?? 'Sin asignar' }}</p>
        <p><strong>Estado:</strong> {{ ucfirst($pedido->estado) }}</p>
        <p><strong>Total:</strong> ${{ number_format($pedido->total, 2) }}</p>
        <p><strong>Fecha:</strong> {{ $pedido->created_at->format('d/m/Y H:i') }}</p>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md mt-6">
        <h2 class="text-xl font-bold text-gray-700 mb-4">Productos en el Pedido</h2>
        @if ($pedido->productos->isEmpty())
        <p>No hay productos en este pedido.</p>
        @else
        <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-md">
            <thead>
                <tr class="bg-gray-100 text-gray-700 uppercase text-sm">
                    <th class="px-6 py-3 text-center">Producto</th>
                    <th class="px-6 py-3 text-center">Descripción</th>
                    <th class="px-6 py-3 text-center">Cantidad</th>
                    <th class="px-6 py-3 text-center">Precio Unitario</th>
                    <th class="px-6 py-3 text-center">Subtotal</th>
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
                    <td class="px-6 py-4 text-gray-800 text-center">{{ $producto->pivot->cantidad }}</td>
                    <td class="px-6 py-4 text-gray-800 text-center">${{ number_format($producto->pivot->subtotal / $producto->pivot->cantidad, 2) }}</td>
                    <td class="px-6 py-4 text-gray-800 text-center">
                        ${{ number_format($producto->pivot->subtotal, 2) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

    <div class="mt-6">
        <a href="{{ route('pedidos.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">
            Volver a la lista de pedidos
        </a>
        <a href="{{ route('pedidos.edit-productos', $pedido->id) }}" class="bg-blue-500 text-white px-4 py-2 rounded ml-2 hover:bg-blue-600 transition cursor-pointer">
            Editar Productos
        </a>
    </div>
</div>
@endsection

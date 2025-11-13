@extends('pages.inicio')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Seleccionar Mesa</h1>

    @if ($mesas->isEmpty())
    <div class="bg-yellow-100 text-yellow-800 px-4 py-3 rounded-lg">
        <p>No hay mesas disponibles en este momento.</p>
    </div>
    @else
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach ($mesas as $mesa)
        <div class="bg-white border border-gray-200 rounded-lg shadow-md p-4 text-center">
            <h2 class="text-xl font-bold text-gray-700 mb-2">{{ $mesa->nombre }}</h2>
            <p class="text-gray-500 mb-4">Capacidad: {{ $mesa->capacidad }} personas</p>
            <a href="{{ route('pedidos.create', $mesa->id) }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                Seleccionar
            </a>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection

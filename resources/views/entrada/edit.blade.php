@extends('pages.inicio')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-4">Editar Precio de Entrada</h2>
        <form action="{{ route('entrada.update') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="precioEntrada" class="block text-gray-700 font-bold mb-2">Precio de Entrada Actual:</label>
                <input type="number" id="precioEntrada" name="precioEntrada" value="{{ $precioEntrada }}" min="0" step="0.01" class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition cursor-pointer">Actualizar Precio</button>
        </form>
    </div>
</div>
@if(session('success'))
<div class="bg-green-500 text-white p-4 rounded mb-4">
    {{ session('success') }}
</div>
@endif
@endsection

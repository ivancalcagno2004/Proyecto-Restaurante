@extends('pages.inicio')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Crear Nueva Mesa</h1>

    <div class="bg-white p-6 rounded-lg shadow-md">
        <form action="{{ route('mesas.store') }}" method="POST">
            @csrf

            <!-- Nombre de la mesa -->
            <div class="mb-4">
                <label for="nombre" class="block text-gray-700 font-medium mb-2">Nombre de la Mesa</label>
                <input type="text" name="nombre" id="nombre" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Ejemplo: Mesa 1" required>
                @error('nombre')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Estado inicial -->
            <div class="mb-4">
                <label for="estado" class="block text-gray-700 font-medium mb-2">Estado Inicial</label>
                <select name="estado" id="estado" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="disponible">Disponible</option>
                    <option value="ocupada">Ocupada</option>
                    <option value="reservada">Reservada</option>
                </select>
                @error('estado')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Capacidad de la mesa -->
            <div class="mb-4">
                <label for="capacidad" class="block text-gray-700 font-medium mb-2">Capacidad de la Mesa</label>
                <input type="number" name="capacidad" id="capacidad" value="4" min="1" max="20" class="w-20 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Ejemplo: 4" required>
                @error('capacidad')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Botón de enviar -->
            <div class="flex justify-end">
                <a href="{{ route('mesas.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition mr-2 cursor-pointer">
                    Cancelar
                </a>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition cursor-pointer">
                    Guardar Mesa
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

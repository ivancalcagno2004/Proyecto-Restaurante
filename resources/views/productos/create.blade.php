@extends('pages.inicio')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Crear Nuevo Producto</h1>

    <div class="bg-white p-6 rounded-lg shadow-md">
        <form action="{{ route('productos.store') }}" method="POST">
            @csrf

            <!-- Nombre del producto -->
            <div class="mb-4">
                <label for="nombre" class="block text-gray-700 font-medium mb-2">Nombre del Producto</label>
                <input type="text" name="nombre" id="nombre" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Ejemplo: Pepsi Lata" required>
                @error('nombre')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Descripción del producto -->
            <div class="mb-4">
                <label for="descripcion" class="block text-gray-700 font-medium mb-2">Descripción</label>
                <textarea name="descripcion" id="descripcion" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Ejemplo: Bebida gaseosa de 350ml"></textarea>
                @error('descripcion')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Precio del producto -->
            <div class="mb-4">
                <label for="precio" class="block text-gray-700 font-medium mb-2">Precio</label>
                <input type="number" name="precio" id="precio" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Ejemplo: 2500" step="0.01" required>
                @error('precio')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Categoría del producto -->
            <div class="mb-4">
                <label for="categoria" class="block text-gray-700 font-medium mb-2">Categoría</label>
                <select name="categoria" id="categoria" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="entrada">Entrada</option>
                    <option value="plato_principal">Plato Principal</option>
                    <option value="bebida">Bebida</option>
                    <option value="postre">Postre</option>
                </select>
                @error('categoria')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Stock del producto -->
            <div class="mb-4">
                <label for="stock" class="block text-gray-700 font-medium mb-2">Stock</label>
                <input type="number" name="stock" id="stock" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Ejemplo: 10" min="0" required>
                @error('stock')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Botón de enviar -->
            <div class="flex justify-end">
                <a href="{{ route('productos.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition mr-2 cursor-pointer">
                    Cancelar
                </a>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition cursor-pointer">
                    Guardar Producto
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

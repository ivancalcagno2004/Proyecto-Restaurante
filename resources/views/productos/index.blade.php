@extends('pages.inicio')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Productos</h1>

    <a href="{{ route('productos.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
        Crear un producto nuevo
    </a>

    <div class="mt-6 overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-md">
            <thead>
                <tr class="bg-gray-100 text-gray-700 uppercase text-sm">
                    <th class="px-6 py-3 text-center">Nombre</th>
                    <th class="px-6 py-3 text-center">Descripción</th>
                    <th class="px-6 py-3 text-center">Precio</th>
                    <th class="px-6 py-3 text-center">Categoría</th>
                    <th class="px-6 py-3 text-center">Stock</th>
                    <th class="px-6 py-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($productos as $producto)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-6 py-4 text-gray-800 text-center">
                        @if (isset($quiereEditar) && $quiereEditar && $productoEdit->id == $producto->id)
                        <form action="{{ route('productos.update', $producto->id) }}" method="POST">
                            @csrf
                            <input type="text" name="nombre" value="{{ $producto->nombre }}" min="1" max="20" class="w-40 border border-gray-300 rounded px-2 py-1">
                            <button type="submit" class="bg-green-500 text-white px-2 py-1 rounded ml-2 hover:bg-green-600 transition cursor-pointer mt-2">
                                Guardar
                            </button>
                        </form>
                        @else
                        {{ $producto->nombre }}
                        @endif
                    </td>
                    <td class="px-6 py-4 text-gray-800 text-center">
                        @if (isset($quiereEditar) && $quiereEditar && $productoEdit->id == $producto->id)
                        <form action="{{ route('productos.update', $producto->id) }}" method="POST">
                            @csrf
                            <textarea cols="30" name="descripcion" class="border border-gray-300 rounded px-2 py-1">{{ $producto->descripcion }}</textarea>
                            <button type="submit" class="bg-green-500 text-white px-2 py-1 rounded ml-2 hover:bg-green-600 transition cursor-pointer">
                                Guardar
                            </button>
                        </form>
                        @else
                        @if($producto->descripcion)
                        {{ $producto->descripcion }}
                        @else
                        <span class="text-gray-400 italic">Sin descripción</span>
                        @endif {{-- cierra if --}}
                        @endif{{-- cierra if --}}
                    </td>
                    <td class="px-6 py-4 text-gray-800 text-center">
                        @if (isset($quiereEditar) && $quiereEditar && $productoEdit->id == $producto->id)
                        <form action="{{ route('productos.update', $producto->id) }}" method="POST">
                            @csrf
                            <input type="number" name="precio" value="{{ $producto->precio }}" class="w-40 border border-gray-300 rounded px-2 py-1">
                            <button type="submit" class="bg-green-500 text-white px-2 py-1 rounded ml-2 hover:bg-green-600 transition cursor-pointer mt-2">
                                Guardar
                            </button>
                        </form>
                        @else
                        $ {{ $producto->precio }}
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if (isset($quiereEditar) && $quiereEditar && $productoEdit->id == $producto->id)
                        <form action="{{ route('productos.update', $producto->id) }}" method="POST">
                            @csrf
                            <select name="categoria" class="border border-gray-300 rounded px-2 py-1">
                                <option value="entrada" {{ $producto->categoria === 'entrada' ? 'selected' : '' }}>Entrada</option>
                                <option value="plato_principal" {{ $producto->categoria === 'plato_principal' ? 'selected' : '' }}>Plato Principal</option>
                                <option value="bebida" {{ $producto->categoria === 'bebida' ? 'selected' : '' }}>Bebida</option>
                                <option value="postre" {{ $producto->categoria === 'postre' ? 'selected' : '' }}>Postre</option>
                            </select>
                            <button type="submit" class="bg-green-500 text-white px-2 py-1 rounded ml-2 hover:bg-green-600 transition cursor-pointer mt-2">
                                Guardar
                            </button>
                        </form>
                        @else
                        <span class="categoria-label">
                            @if ($producto->categoria === 'entrada')
                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full">Entrada</span>
                            @elseif ($producto->categoria === 'plato_principal')
                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full">Plato Principal</span>
                            @elseif ($producto->categoria === 'bebida')
                            <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full">Bebida</span>
                            @elseif ($producto->categoria === 'postre')
                            <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full">Postre</span>
                            @endif
                        </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-gray-800 text-center">
                        @if (isset($quiereEditar) && $quiereEditar && $productoEdit->id == $producto->id)
                        <form action="{{ route('productos.update', $producto->id) }}" method="POST">
                            @csrf
                            <input type="number" name="stock" value="{{ $producto->stock }}" class="w-40 border border-gray-300 rounded px-2 py-1">
                            <button type="submit" class="bg-green-500 text-white px-2 py-1 rounded ml-2 hover:bg-green-600 transition cursor-pointer mt-2">
                                Guardar
                            </button>
                        </form>
                        @else
                        {{ $producto->stock }}
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center flex items-center">
                        @if (isset($quiereEditar) && $quiereEditar && $productoEdit->id == $producto->id)
                        <a href="{{route('productos.index')}}" class="bg-gray-500 text-white px-2 py-1 rounded ml-2 hover:bg-gray-600 transition cursor-pointer">Cancelar</a>
                        @else
                        <a href=" {{ route('productos.edit', $producto->id) }}" class="bg-blue-500 text-white px-2 py-1 rounded ml-2 hover:bg-blue-600 transition cursor-pointer">
                            Editar
                        </a>
                        @endif
                        <form action="{{ route('productos.destroy', $producto) }}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded ml-2 hover:bg-red-600 transition cursor-pointer">
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

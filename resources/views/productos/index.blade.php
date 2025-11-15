@extends('pages.inicio')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Productos</h1>

    <!-- Botón flotante para crear un producto -->
    <a href="{{ route('productos.store') }}" onclick="event.preventDefault(); document.getElementById('create-product-form').submit();" class="fixed bottom-28 right-5 bg-blue-500 text-white p-4 rounded-full shadow-lg hover:bg-blue-600 transition cursor-pointer font-bold text-3xl text-center align-middle">
        +
    </a>

    <!-- Botón flotante para desplazarse al fondo -->
    <a onclick="scrollToBottom()" class="fixed bottom-28 right-20 bg-gray-500 text-white p-4 rounded-full shadow-lg hover:bg-gray-600 transition cursor-pointer font-bold text-3xl">
        ↓
    </a>

    <form id="create-product-form" action="{{ route('productos.store') }}" method="POST" style="display: none;">
        @csrf
    </form>
    <!-- Filtro por categoría -->
    <div class="mb-4">
        <label for="categoria" class="block text-gray-700 font-medium mb-2">Filtrar por Categoría</label>
        <select id="categoria" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="all">Todas las Categorías</option>
            <option value="entrada">Entrada</option>
            <option value="plato_principal">Plato Principal</option>
            <option value="bebida">Bebida</option>
            <option value="postre">Postre</option>
        </select>
    </div>

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
            <tbody id="productos-tbody">
                @foreach ($productos as $categoria => $productosCategoria)
                <!-- Encabezado de la categoría -->
                <tr class="bg-gray-200">
                    <td colspan="6" class="px-6 py-3 text-left font-bold text-gray-700">
                        @if($categoria === "plato_principal")
                        Plato Principal
                        @else
                        {{ ucfirst($categoria) }}
                        @endif
                    </td>
                </tr>
                @foreach ($productosCategoria as $producto)
                {{-- @dd($producto) --}}
                <tr id="producto-{{ $producto->id }}" class="border-b hover:bg-gray-50" data-categoria="{{ $categoria }}">
                    <form action="{{ route('productos.update', $producto->id) }}" method="POST">
                        @csrf
                        <td class="px-6 py-4 text-gray-800 text-center">
                            @if (isset($quiereEditar) && $quiereEditar && $productoEdit->id == $producto->id)
                            <input type="text" name="nombre" value="{{ $producto->nombre }}" min="1" max="20" class="w-40 border border-gray-300 rounded px-2 py-1">
                            @else
                            {{ $producto->nombre }}
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-800 text-center">
                            @if (isset($quiereEditar) && $quiereEditar && $productoEdit->id == $producto->id)
                            <textarea cols="30" rows="1" name="descripcion" class="border border-gray-300 rounded px-2 py-1">{{ $producto->descripcion }}</textarea>
                            @else
                            @if($producto->descripcion)
                            {{ $producto->descripcion }}
                            @else
                            <span class="text-gray-400 italic">Sin descripción</span>
                            @endif
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-800 text-center">
                            @if (isset($quiereEditar) && $quiereEditar && $productoEdit->id == $producto->id)
                            <input type="number" name="precio" value="{{ $producto->precio }}" class="w-40 border border-gray-300 rounded px-2 py-1">
                            @else
                            $ {{ number_format($producto->precio, 2) }}
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if (isset($quiereEditar) && $quiereEditar && $productoEdit->id == $producto->id)
                            <select name="categoria" class="border border-gray-300 rounded px-2 py-1">
                                <option value="entrada" {{ $producto->categoria === 'entrada' ? 'selected' : '' }}>Entrada</option>
                                <option value="plato_principal" {{ $producto->categoria === 'plato_principal' ? 'selected' : '' }}>Plato Principal</option>
                                <option value="bebida" {{ $producto->categoria === 'bebida' ? 'selected' : '' }}>Bebida</option>
                                <option value="postre" {{ $producto->categoria === 'postre' ? 'selected' : '' }}>Postre</option>
                            </select>
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
                            <input type="number" name="stock" value="{{ $producto->stock }}" class="w-40 border border-gray-300 rounded px-2 py-1">
                            @else
                            @if($producto->stock >= 1)
                            {{ $producto->stock }}
                            @else
                            <span class="text-gray-400 italic">Sin stock</span>
                            @endif
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center flex items-center align-middle justify-center">
                            @if (isset($quiereEditar) && $quiereEditar && $productoEdit->id == $producto->id)
                            <button type="submit" class="bg-green-500 text-white px-2 py-1 rounded hover:bg-green-600 transition cursor-pointer">
                                Guardar
                            </button>
                            <a href="{{route('productos.index')}}" class="bg-gray-500 text-white px-2 py-1 rounded ml-2 hover:bg-gray-600 transition cursor-pointer">Cancelar</a>
                            @else
                            <a href=" {{ route('productos.edit', $producto->id) }}" class="bg-blue-500 text-white px-2 py-1 rounded ml-2 hover:bg-blue-600 transition cursor-pointer">
                                Editar
                            </a>
                            <form action="{{ route('productos.destroy', $producto) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded ml-2 hover:bg-red-600 transition cursor-pointer">
                                    Eliminar
                                </button>
                            </form>
                            @endif
                        </td>
                    </form>
                </tr>
                @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const categoriaSelect = document.getElementById('categoria');
        const productosRows = document.querySelectorAll('#productos-tbody tr');

        categoriaSelect.addEventListener('change', (e) => {
            const categoriaSeleccionada = e.target.value;

            productosRows.forEach(row => {
                const categoriaProducto = row.getAttribute('data-categoria');
                if (categoriaSeleccionada === 'all' || categoriaProducto === categoriaSeleccionada) {
                    row.style.display = ''; // Mostrar fila
                } else {
                    row.style.display = 'none'; // Ocultar fila
                }
            });
        });

        const productoId = document.getElementById('producto-{{ $producto->id }}');

        @if(isset($quiereEditar) && $quiereEditar && isset($productoEdit))
        const productoRow = document.getElementById(`producto-{{ $productoEdit->id }}`);
        if (productoRow) {
            // Desplazar la página hacia la fila
            productoRow.scrollIntoView({
                behavior: 'smooth', // Desplazamiento suave
                block: 'center' // Centrar la fila en la vista
            });

            // Resaltar la fila para que sea más visible
            productoRow.classList.add('bg-yellow-100');
            setTimeout(() => productoRow.classList.remove('bg-yellow-100'), 3000); // Quitar el resaltado después de 3 segundos
        }
        @endif
    });

    function scrollToBottom() {
        window.scrollTo({
            top: document.body.scrollHeight
            , behavior: 'smooth'
        });
    }

</script>
@endsection

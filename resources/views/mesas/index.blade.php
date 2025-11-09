@extends('pages.inicio')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Mesas</h1>

    <a href="{{ route('mesas.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
        Crear nueva mesa
    </a>

    <div class="mt-6">
        <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-md">
            <thead>
                <tr class="bg-gray-100 text-gray-700 uppercase text-sm">
                    <th class="px-6 py-3 text-left">Capacidad</th>
                    <th class="px-6 py-3 text-left">Nombre</th>
                    <th class="px-6 py-3 text-center">Estado</th>
                    <th class="px-6 py-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($mesas as $mesa)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-6 py-4 text-gray-800">{{ $mesa->capacidad }}</td>
                    <td class="px-6 py-4 text-gray-800">{{ $mesa->nombre }}</td>
                    <td class="px-6 py-4 text-center">
                        @if (isset($quiereEditar) && $quiereEditar && $mesaEdit->id == $mesa->id)
                        <form action="{{ route('mesas.update', $mesa->id) }}" method="POST">
                            @csrf
                            <select name="estado" class="border border-gray-300 rounded px-2 py-1">
                                <option value="disponible" {{ $mesa->estado === 'disponible' ? 'selected' : '' }}>Disponible</option>
                                <option value="ocupada" {{ $mesa->estado === 'ocupada' ? 'selected' : '' }}>Ocupada</option>
                                <option value="reservada" {{ $mesa->estado === 'reservada' ? 'selected' : '' }}>Reservada</option>
                            </select>
                            <button type="submit" class="bg-green-500 text-white px-2 py-1 rounded ml-2 hover:bg-green-600 transition cursor-pointer">
                                Guardar
                            </button>
                        </form>
                        @else
                        <span class="estado-label">
                            @if ($mesa->estado === 'disponible')
                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full">Disponible</span>
                            @elseif ($mesa->estado === 'ocupada')
                            <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full">Ocupada</span>
                            @elseif ($mesa->estado === 'reservada')
                            <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full">Reservada</span>
                            @endif
                        </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <a href="{{ route('mesas.edit', $mesa->id) }}" class="text-blue-500 hover:underline">
                            Editar
                        </a>
                        <form action="{{ route('mesas.destroy', $mesa) }}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:underline ml-2 cursor-pointer">
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

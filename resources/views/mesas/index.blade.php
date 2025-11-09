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
                    <th class="px-6 py-3 text-left">ID</th>
                    <th class="px-6 py-3 text-left">Nombre</th>
                    <th class="px-6 py-3 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($mesas as $mesa)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-6 py-4 text-gray-800">{{ $mesa->id }}</td>
                    <td class="px-6 py-4 text-gray-800">{{ $mesa->nombre }}</td>
                    <td class="px-6 py-4 text-center">
                        <a href="{{ route('mesas.edit', $mesa) }}" class="text-blue-500 hover:underline">
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
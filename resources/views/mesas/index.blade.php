@extends('pages.inicio')

@section('content')
<h1>Mesas</h1>
<a href="{{ route('mesas.create') }}">Crear nueva mesa</a>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($mesas as $mesa)
        <tr>
            <td>{{ $mesa->id }}</td>
            <td>{{ $mesa->nombre }}</td>
            <td>
                <a href="{{ route('mesas.edit', $mesa) }}">Editar</a>
                <form action="{{ route('mesas.destroy', $mesa) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Eliminar</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
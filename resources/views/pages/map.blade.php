@extends('pages.inicio')

@section('content')

<div class="container mx-auto py-2">
    <h6 class="font-bold text-gray-800 ">Mapa del Salón</h6>
    <div class="border border-gray-300 rounded-lg shadow-md">
        <canvas id="salonCanvas" width="1535" height="725" class=""></canvas>

        <!-- Botón flotante para crear una nueva mesa -->
        <a href="{{ route('mesas.create') }}" class="fixed bottom-28 right-18 bg-blue-500 text-white px-6 py-4 rounded-full shadow-lg hover:bg-blue-600 transition cursor-pointer font-bold text-3xl text-center">
            +
        </a>

        <!-- Botón flotante para configuración -->
        <a href="{{ route('entrada.edit') }}" class="fixed bottom-28 right-40 bg-gray-500 text-white p-4 rounded-full shadow-lg hover:bg-gray-600 transition cursor-pointer font-bold text-3xl">
            ⚙️
        </a>
    </div>
</div>

<script>
    // Pasar las mesas desde Blade al archivo JavaScript
    const mesas = @json($mesas);

</script>

<!-- Importar el archivo Salon.js -->
@vite(['resources/js/Salon.js'])
@endsection

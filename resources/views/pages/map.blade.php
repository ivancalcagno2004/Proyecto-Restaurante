@extends('pages.inicio')

@section('content')

<div class="container mx-auto py-2">
    <h6 class="font-bold text-gray-800 ">Mapa del Salón</h6>
    <div class="border border-gray-300 rounded-lg shadow-md">
        <canvas id="salonCanvas" width="1535" height="725" class=""></canvas>
    </div>
</div>

<script>
    // Pasar las mesas desde Blade al archivo JavaScript
    const mesas = @json($mesas);

</script>

<!-- Importar el archivo Salon.js -->
@vite(['resources/js/Salon.js'])
@endsection

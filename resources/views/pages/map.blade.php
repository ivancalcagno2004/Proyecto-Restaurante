@extends('pages.inicio')

@section('content')

<div class="container mx-auto py-4">
    <h6 class="font-bold text-gray-800 ">Mapa del Salón</h6>
    <div class="border border-gray-300 rounded-lg shadow-md">
        <canvas id="salonCanvas" width="800" height="600" class=""></canvas>
    </div>
</div>
@endsection

@extends('dashboard')

@section('content')
<div class="min-h-screen p-10 bg-slate-900">

    <div class="max-w-3xl mx-auto bg-slate-800 p-8 rounded-xl shadow-lg">

        <h1 class="text-4xl font-bold text-white mb-4">Consultar descuento</h1>

        @if ($errors->any())
            <div class="bg-red-900 p-3 rounded text-red-300 mb-4">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('descuentos.consultar') }}" method="POST">
            @csrf

            <label for="juego" class="text-white text-lg">Escribe el nombre del juego</label>

            <input type="text" id="juego" name="juego"
                class="w-full mt-2 p-3 rounded bg-slate-700 text-white placeholder-gray-400">

            <button class="w-full mt-5 bg-blue-600 hover:bg-blue-700 text-white p-3 rounded">
                Consultar
            </button>
        </form>

    </div>

</div>
@endsection

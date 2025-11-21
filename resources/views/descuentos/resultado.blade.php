@extends('dashboard')

@section('content')
<div class="min-h-screen bg-slate-900 p-10">

    <div class="max-w-3xl mx-auto bg-slate-800 p-8 rounded-xl shadow-xl">

        <a href="{{ route('descuentos.index') }}" class="text-blue-400 hover:text-blue-300">
            ← Volver
        </a>

        <h1 class="text-3xl font-bold text-white mb-8">
            Resultados para: {{ $data['name'] }}
        </h1>

        <div class="grid grid-cols-2 gap-6">

            <!-- Precio Actual -->
            <div class="p-4 bg-slate-700 rounded border-l-4 border-green-500">
                <p class="text-slate-400">Precio actual</p>
                <p class="text-xl font-bold text-green-400">
                    ${{ number_format($data['price_current_usd'], 2) }} USD
                </p>
                <p class="text-sm text-green-300">
                    Bs. {{ number_format($data['price_current_bob'], 2) }}
                </p>
            </div>

            <!-- Precio Original -->
            <div class="p-4 bg-slate-700 rounded border-l-4 border-red-500">
                <p class="text-slate-400">Precio original</p>
                <p class="text-xl font-bold text-red-400">
                    ${{ number_format($data['price_original_usd'], 2) }} USD
                </p>
                <p class="text-sm text-red-300">
                    Bs. {{ number_format($data['price_original_bob'], 2) }}
                </p>
            </div>

            <!-- Descuento -->
            <div class="p-4 bg-slate-700 rounded border-l-4 border-yellow-500">
                <p class="text-slate-400">Descuento actual</p>
                <p class="text-2xl font-bold text-yellow-400">
                    {{ $data['discount_percent'] }}%
                </p>
            </div>

            <!-- Rating -->
            <div class="p-4 bg-slate-700 rounded border-l-4 border-purple-500">
                <p class="text-slate-400">Rating</p>
                <p class="text-2xl font-bold text-purple-400">
                    {{ $data['rating'] }} 
                </p>
            </div>

        </div>

        {{-- Predicción --}}
        @if($data['prediction'] == 1)
            <div class="mt-8 bg-red-900 p-6 rounded border border-red-600">
                <p class="text-red-300 text-2xl font-bold"> Sí tendrá oferta</p>
                <p class="text-slate-300 mt-1">
                    Probabilidad: {{ number_format($data['probability'] * 100, 2) }}%
                </p>
            </div>
        @else
            <div class="mt-8 bg-blue-900 p-6 rounded border border-blue-600">
                <p class="text-blue-300 text-2xl font-bold"> No tendrá oferta</p>
                <p class="text-slate-300 mt-1">
                    Probabilidad: {{ number_format($data['probability'] * 100, 2) }}%
                </p>
            </div>
        @endif

    </div>

</div>
@endsection

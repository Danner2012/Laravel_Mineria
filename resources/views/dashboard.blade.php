@extends('layouts.dashboard')

@section('content')

    {{-- ============================================= --}}
    {{--  TÍTULO PRINCIPAL --}}
    {{-- ============================================= --}}
    <div class="mb-8">
      <h1 class="text-white text-3xl font-bold tracking-tight text-center">DASHBOARD</h1>
      <p class="text-text-secondary text-center">Visión general de tu tienda de videojuegos</p>
    </div>

    {{-- ============================================= --}}
    {{--  FORMULARIO DE CONSULTA --}}
    {{-- ============================================= --}}
    @if ($errors->any())
        <div class="bg-red-900 text-red-300 p-4 rounded mb-6">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('dashboard.consultarIA') }}" method="POST" class="mb-10 max-w-xl mx-auto">
        @csrf

        <label class="text-white font-bold text-lg">Consultar juego</label>
        <input type="text" name="juego" value="{{ old('juego') }}"
              class="w-full mt-2 p-3 rounded bg-surface-dark text-white">

        <button class="mt-4 w-full bg-blue-600 hover:bg-blue-700 text-white p-3 rounded">
            Consultar 
        </button>
    </form>

    @if(isset($data))

    {{-- ============================================= --}}
    {{--   INFORMACIÓN DEL JUEGO --}}
    {{-- ============================================= --}}
    <div class="bg-surface-dark border border-surface-dark/50 p-6 rounded-lg mb-10 max-w-3xl mx-auto">

        <h2 class="text-white text-2xl font-bold text-center">{{ $data['name'] }}</h2>

        <div class="mt-6 space-y-2 text-center">
            <p class="text-text-secondary">
                Precio Actual:
                <span class="text-green-400 font-bold">${{ $data['price_current_usd'] }} USD</span>
                — Bs {{ $data['price_current_bob'] }}
            </p>

            <p class="text-text-secondary">
                Precio Original:
                <span class="text-red-400 font-bold">${{ $data['price_original_usd'] }} USD</span>
                — Bs {{ $data['price_original_bob'] }}
            </p>

            <p class="text-yellow-400 font-bold">
                Descuento actual: {{ $data['discount_percent'] }}%
            </p>

            <p class="text-purple-400 font-bold">
                Rating: {{ $data['rating'] }} 
            </p>
        </div>
    </div>

    {{-- ============================================= --}}
    {{--   MODELOS IA - EN FILA DE 3 TARJETAS --}}
    {{-- ============================================= --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">

        {{-- RANDOM FOREST --}}
        <div class="bg-surface-dark p-6 rounded border border-blue-600">
            <h3 class="text-xl text-white font-bold mb-2 text-center">Random Forest</h3>

            <p class="text-white text-center">
                Predicción:
                @if($data['models']['random_forest']['prediction'] == 1)
                    <span class="text-green-400">Sí tendrá oferta</span>
                @else
                    <span class="text-red-400">No tendrá oferta</span>
                @endif
            </p>

            <p class="text-text-secondary text-center">
                Probabilidad: {{ number_format($data['models']['random_forest']['probability'] * 100, 2) }}%
            </p>
        </div>

        {{-- XGBOOST --}}
        <div class="bg-surface-dark p-6 rounded border border-orange-600">
            <h3 class="text-xl text-white font-bold mb-2 text-center">XGBoost</h3>

            <p class="text-white text-center">
                Predicción:
                @if($data['models']['xgboost']['prediction'] == 1)
                    <span class="text-green-400">Sí tendrá oferta</span>
                @else
                    <span class="text-red-400">No tendrá oferta</span>
                @endif
            </p>

            <p class="text-text-secondary text-center">
                Probabilidad: {{ number_format($data['models']['xgboost']['probability'] * 100, 2) }}%
            </p>
        </div>

        {{-- LIGHTGBM --}}
        <div class="bg-surface-dark p-6 rounded border border-purple-600">
            <h3 class="text-xl text-white font-bold mb-2 text-center">LightGBM</h3>

            <p class="text-white text-center">
                Predicción:
                @if($data['models']['lightgbm']['prediction'] == 1)
                    <span class="text-green-400">Sí tendrá oferta</span>
                @else
                    <span class="text-red-400">No tendrá oferta</span>
                @endif
            </p>

            <p class="text-text-secondary text-center">
                Probabilidad: {{ number_format($data['models']['lightgbm']['probability'] * 100, 2) }}%
            </p>
        </div>

    </div>

    {{-- ============================================= --}}
    {{--    GRÁFICO DE PROBABILIDADES --}}
    {{-- ============================================= --}}
    <div class="bg-surface-dark p-6 rounded border border-cyan-600 mb-12 max-w-3xl mx-auto">
        <h3 class="text-xl text-white font-bold mb-4 text-center">Comparativa de Probabilidades</h3>
        <canvas id="probChart"></canvas>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const ctx = document.getElementById('probChart').getContext('2d');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Random Forest', 'XGBoost', 'LightGBM'],
                datasets: [{
                    label: 'Probabilidad (%)',
                    data: [
                        {{ $data['models']['random_forest']['probability'] * 100 }},
                        {{ $data['models']['xgboost']['probability'] * 100 }},
                        {{ $data['models']['lightgbm']['probability'] * 100 }}
                    ],
                    backgroundColor: ['#3b82f6', '#f97316', '#a855f7'],
                    borderWidth: 1,
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: { color: 'white' }
                    },
                    x: {
                        ticks: { color: 'white' }
                    }
                }
            }
        });
    </script>


    {{-- ============================================= --}}
    {{--   SEMÁFORO DE DESCUENTO --}}
    {{-- ============================================= --}}
    <div class="bg-surface-dark p-6 rounded border border-green-600 mb-12 max-w-md mx-auto text-center">

        <h3 class="text-2xl text-white font-bold mb-4">Nivel de Descuento</h3>

        <div class="flex justify-center">
            <canvas id="discountGauge" width="200" height="200"></canvas>
        </div>

        <p id="discountLevel" class="text-lg font-bold mt-4"></p>
        <p class="text-text-secondary mt-1">Porcentaje de descuento del juego</p>

    </div>

    <script>
        const discountPercent = {{ $data['discount_percent'] }};
        const gaugeCtx = document.getElementById('discountGauge').getContext('2d');

        let gaugeColor = '#ff3333';
        let levelText  = 'Descuento Bajo';

        if (discountPercent >= 50) {
            gaugeColor = '#22c55e';
            levelText = 'Descuento Alto';
        } else if (discountPercent >= 20) {
            gaugeColor = '#eab308';
            levelText = 'Descuento Medio';
        }

        document.getElementById("discountLevel").innerHTML =
            `${levelText} — <span class='text-white font-bold'>${discountPercent}%</span>`;

        new Chart(gaugeCtx, {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [discountPercent, 100 - discountPercent],
                    backgroundColor: [gaugeColor, "#1f2937"],
                    circumference: 180,
                    rotation: 270,
                    borderWidth: 0,
                }]
            },
            options: {
                plugins: {
                    legend: { display: false },
                    tooltip: { enabled: false }
                },
                cutout: '70%',
            }
        });
    </script>


    {{-- ============================================= --}}
    {{--   RADAR CHART --}}
    {{-- ============================================= --}}
    <div class="bg-surface-dark p-6 rounded border border-purple-600 mb-12 max-w-xl mx-auto text-center">
        <h3 class="text-xl text-white font-bold mb-4">Perfil del Juego (Radar Chart)</h3>

        <div class="flex justify-center">
            <canvas id="radarChart" width="330" height="330"></canvas>
        </div>
    </div>

    <script>
        function normalize(v, max) { return (v / max) * 100; }

        const radarCtx = document.getElementById('radarChart').getContext('2d');

        new Chart(radarCtx, {
            type: 'radar',
            data: {
                labels: [
                    'Rating',
                    'Descuento %',
                    'Popularidad (Reviews)',
                    'Cercanía a Oferta',
                    'Relación Precio'
                ],
                datasets: [{
                    label: 'Perfil del Juego',
                    data: [
                        normalize({{ $data['rating'] }}, 10000),
                        {{ $data['discount_percent'] }},
                        normalize({{ $data['reviews_count'] }}, 200000),
                        100 - normalize({{ $data['days_until_sale'] }}, 30),
                        Math.round(({{ $data['price_current_usd'] }} / {{ $data['price_original_usd'] }}) * 100)
                    ],
                    backgroundColor: 'rgba(147, 51, 234, 0.25)',
                    borderColor: '#a855f7',
                    borderWidth: 2,
                    pointBackgroundColor: '#c084fc'
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                scales: {
                    r: {
                        angleLines: { color: 'rgba(255,255,255,0.1)' },
                        grid: { color: 'rgba(255,255,255,0.1)' },
                        suggestedMin: 0,
                        suggestedMax: 100,
                        ticks: { display: false },
                        pointLabels: {
                            color: 'white',
                            font: { size: 12 }
                        }
                    }
                }
            }
        });
    </script>

    {{-- ============================================= --}}
    {{--   PRECIO ACTUAL VS ORIGINAL --}}
    {{-- ============================================= --}}
    <div class="bg-surface-dark p-6 rounded border border-amber-600 mb-12 max-w-xl mx-auto">
        <h3 class="text-xl text-white font-bold mb-4 text-center">
            Comparación de Precio (Bs {{ $data['price_original_bob'] }} → Bs {{ $data['price_current_bob'] }})
        </h3>

        <div class="flex justify-center">
            <canvas id="priceChart" width="330" height="260"></canvas>
        </div>

        <p class="text-center mt-4 text-text-secondary">
            Visualización clara del precio original frente al precio actual.
        </p>
    </div>

    <script>
        const priceCtx = document.getElementById('priceChart').getContext('2d');

        new Chart(priceCtx, {
            type: 'bar',
            data: {
                labels: ['Precio Original', 'Precio Actual'],
                datasets: [{
                    data: [
                        {{ $data['price_original_bob'] }},
                        {{ $data['price_current_bob'] }}
                    ],
                    backgroundColor: ['#dc2626', '#16a34a'],
                    borderColor: ['#b91c1c', '#15803d'],
                    borderWidth: 2,
                    borderRadius: 10
                }]
            },
            options: {
                plugins: { legend: { display: false }},
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { color: 'white' },
                        grid: { color: 'rgba(255,255,255,0.1)' }
                    },
                    x: {
                        ticks: { color: 'white' },
                        grid: { display: false }
                    }
                }
            }
        });
    </script>

    @endif

@endsection

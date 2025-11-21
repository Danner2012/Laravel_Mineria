@extends('layouts.dashboard')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-white">Catálogo de Juegos</h1>
            <p class="text-text-secondary mt-1">Explora nuestra colección de {{ $juegos->total() }} juegos</p>
        </div>
    </div>

    <!-- Filtros y Búsqueda -->
    <div class="bg-surface-dark rounded-lg p-4 border border-surface-dark/50">
        <form method="GET" action="{{ route('juegos.index') }}" class="flex flex-wrap gap-4">
            <!-- Búsqueda -->
            <div class="flex-1 min-w-[200px]">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}"
                    placeholder="Buscar por nombre..." 
                    class="w-full px-4 py-2 bg-background-dark border border-surface-dark rounded-lg text-white placeholder-text-secondary focus:outline-none focus:ring-2 focus:ring-primary"
                >
            </div>

            <!-- Filtro por género -->
            <select 
                name="genre" 
                class="px-4 py-2 bg-background-dark border border-surface-dark rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-primary"
            >
                <option value="">Todos los géneros</option>
                @foreach($genres as $genre)
                    <option value="{{ $genre }}" {{ request('genre') == $genre ? 'selected' : '' }}>
                        {{ $genre }}
                    </option>
                @endforeach
            </select>

            <!-- Filtro por tendencia -->
            <select 
                name="player_trend" 
                class="px-4 py-2 bg-background-dark border border-surface-dark rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-primary"
            >
                <option value="">Todas las tendencias</option>
                <option value="growing" {{ request('player_trend') == 'growing' ? 'selected' : '' }}>Creciente</option>
                <option value="falling" {{ request('player_trend') == 'falling' ? 'selected' : '' }}>Decreciente</option>
                <option value="stable" {{ request('player_trend') == 'stable' ? 'selected' : '' }}>Estable</option>
            </select>

            <!-- Ordenamiento -->
            <select 
                name="sort_by" 
                class="px-4 py-2 bg-background-dark border border-surface-dark rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-primary"
            >
                <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Nombre</option>
                <option value="rating" {{ request('sort_by') == 'rating' ? 'selected' : '' }}>Calificación</option>
                <option value="price_current" {{ request('sort_by') == 'price_current' ? 'selected' : '' }}>Precio</option>
                <option value="discount_percent" {{ request('sort_by') == 'discount_percent' ? 'selected' : '' }}>Descuento</option>
                <option value="reviews_count" {{ request('sort_by') == 'reviews_count' ? 'selected' : '' }}>Reseñas</option>
            </select>

            <select 
                name="sort_order" 
                class="px-4 py-2 bg-background-dark border border-surface-dark rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-primary"
            >
                <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Ascendente</option>
                <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Descendente</option>
            </select>

            <button 
                type="submit" 
                class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors font-medium"
            >
                Filtrar
            </button>

            @if(request()->hasAny(['search', 'genre', 'player_trend']))
                <a 
                    href="{{ route('juegos.index') }}" 
                    class="px-6 py-2 bg-surface-dark text-text-secondary rounded-lg hover:bg-surface-dark/80 transition-colors border border-surface-dark/50"
                >
                    Limpiar
                </a>
            @endif
        </form>
    </div>

    <!-- Grid de Juegos -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($juegos as $juego)
            <div class="bg-surface-dark rounded-lg overflow-hidden border border-surface-dark/50 hover:border-primary/50 transition-all duration-200 hover:shadow-lg hover:shadow-primary/20">
                <!-- Imagen del juego -->
                <div class="aspect-video bg-background-dark flex items-center justify-center overflow-hidden">
                    @if($juego->image_url)
                        <img src="{{ $juego->image_url }}" alt="{{ $juego->name }}" class="w-full h-full object-cover">
                    @elseif($juego->image_path)
                        <img src="{{ asset('storage/' . $juego->image_path) }}" alt="{{ $juego->name }}" class="w-full h-full object-cover" onerror="this.parentElement.innerHTML='<div class=\'text-text-secondary text-center p-4 w-full h-full flex flex-col items-center justify-center\'><span class=\'material-symbols-outlined text-6xl\'>sports_esports</span><p class=\'mt-2 text-sm\'>Sin imagen</p></div>'">
                    @else
                        <div class="text-text-secondary text-center p-4 w-full h-full flex flex-col items-center justify-center">
                            <span class="material-symbols-outlined text-6xl">sports_esports</span>
                            <p class="mt-2 text-sm">Sin imagen</p>
                        </div>
                    @endif
                </div>

                <!-- Información del juego -->
                <div class="p-4">
                    <h3 class="text-white font-semibold text-lg mb-2 line-clamp-2">{{ $juego->name }}</h3>
                    
                    <div class="space-y-2 mb-3">
                        @if($juego->genre)
                            <span class="inline-block px-2 py-1 bg-primary/20 text-primary text-xs rounded">
                                {{ $juego->genre }}
                            </span>
                        @endif

                        @if($juego->rating)
                            <div class="flex items-center gap-1 text-yellow-400">
                                <span class="material-symbols-outlined text-sm">star</span>
                                <span class="text-sm font-medium">{{ number_format($juego->rating, 1) }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Precios -->
                    <div class="flex items-center gap-2 mb-2">
                        @if($juego->discount_percent > 0)
                            <span class="text-text-secondary line-through text-sm">${{ number_format($juego->price_original, 2) }}</span>
                            <span class="px-2 py-1 bg-red-500/20 text-red-400 text-xs rounded font-semibold">
                                -{{ number_format($juego->discount_percent, 0) }}%
                            </span>
                        @endif
                        <span class="text-white font-bold text-lg">${{ number_format($juego->price_current, 2) }}</span>
                    </div>

                    <!-- Información adicional -->
                    <div class="flex items-center justify-between text-xs text-text-secondary mt-3 pt-3 border-t border-surface-dark/50">
                        @if($juego->reviews_count)
                            <span>{{ number_format($juego->reviews_count) }} reseñas</span>
                        @endif
                        @if($juego->player_trend)
                            <span class="px-2 py-1 rounded 
                                {{ $juego->player_trend == 'growing' ? 'bg-green-500/20 text-green-400' : '' }}
                                {{ $juego->player_trend == 'falling' ? 'bg-red-500/20 text-red-400' : '' }}
                                {{ $juego->player_trend == 'stable' ? 'bg-blue-500/20 text-blue-400' : '' }}
                            ">
                                {{ $juego->player_trend == 'growing' ? '↑ Creciente' : '' }}
                                {{ $juego->player_trend == 'falling' ? '↓ Decreciente' : '' }}
                                {{ $juego->player_trend == 'stable' ? '→ Estable' : '' }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <span class="material-symbols-outlined text-6xl text-text-secondary mb-4">search_off</span>
                <p class="text-text-secondary text-lg">No se encontraron juegos</p>
            </div>
        @endforelse
    </div>

    <!-- Paginación -->
    <div class="mt-6">
        {{ $juegos->links() }}
    </div>
</div>
@endsection


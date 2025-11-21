<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;

class JuegosController extends Controller
{
    public function index(Request $request)
    {
        // Obtener nombres únicos de juegos, excluyendo Dota y Warframe
        $nombresUnicos = Game::distinct()
            ->where('name', 'not like', '%Dota%')
            ->where('name', 'not like', '%Warframe%')
            ->pluck('name');
        
        // Para cada nombre, obtener el ID del juego que tiene imagen o el primero
        $idsUnicos = [];
        foreach ($nombresUnicos as $nombre) {
            $juego = Game::where('name', $nombre)
                ->orderByRaw('CASE WHEN image_path IS NOT NULL THEN 0 ELSE 1 END')
                ->orderBy('id')
                ->first();
            
            if ($juego) {
                $idsUnicos[] = $juego->id;
            }
        }

        // Construir la consulta principal
        $query = Game::whereIn('id', $idsUnicos);

        // Búsqueda por nombre
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filtro por género
        if ($request->has('genre') && $request->genre) {
            $query->where('genre', $request->genre);
        }

        // Filtro por tendencia de jugadores
        if ($request->has('player_trend') && $request->player_trend) {
            $query->where('player_trend', $request->player_trend);
        }

        // Ordenamiento: primero los que tienen imagen, luego por el criterio seleccionado
        $sortBy = $request->get('sort_by', 'name');
        $sortOrder = $request->get('sort_order', 'asc');
        
        // Ordenar primero por si tiene imagen (los que tienen imagen primero)
        $query->orderByRaw('CASE WHEN image_path IS NOT NULL THEN 0 ELSE 1 END');
        
        // Luego ordenar por el criterio seleccionado
        if (in_array($sortBy, ['name', 'rating', 'price_current', 'discount_percent', 'reviews_count'])) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            // Si no hay criterio válido, ordenar por nombre por defecto
            $query->orderBy('name', 'asc');
        }

        // Paginación
        $juegos = $query->paginate(20)->withQueryString();

        // Obtener géneros únicos para el filtro
        $genres = Game::whereNotNull('genre')
            ->distinct()
            ->pluck('genre')
            ->sort()
            ->values();

        return view('juegos.index', compact('juegos', 'genres'));
    }

    public function show($id)
    {
        $juego = Game::findOrFail($id);
        return view('juegos.show', compact('juego'));
    }
}


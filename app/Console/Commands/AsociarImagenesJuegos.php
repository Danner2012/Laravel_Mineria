<?php

namespace App\Console\Commands;

use App\Models\Game;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class AsociarImagenesJuegos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'juegos:asociar-imagenes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Asocia las imágenes de la carpeta juegosimg con los juegos en la base de datos';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando asociación de imágenes...');

        // Crear directorio de destino si no existe
        $destinoDir = storage_path('app/public/juegos');
        if (!File::exists($destinoDir)) {
            File::makeDirectory($destinoDir, 0755, true);
            $this->info("Directorio creado: {$destinoDir}");
        }

        // Obtener todas las imágenes de la carpeta juegosimg
        $imagenesPath = base_path('juegosimg');
        
        if (!File::exists($imagenesPath)) {
            $this->error("La carpeta juegosimg no existe en: {$imagenesPath}");
            return 1;
        }

        $imagenes = File::files($imagenesPath);
        $this->info("Encontradas " . count($imagenes) . " imágenes");

        $asociados = 0;
        $noEncontrados = [];

        foreach ($imagenes as $imagen) {
            $nombreArchivo = $imagen->getFilename();
            $nombreSinExtension = pathinfo($nombreArchivo, PATHINFO_FILENAME);
            
            $this->line("Procesando: {$nombreArchivo}");

            // Intentar encontrar el juego por nombre
            $juego = $this->buscarJuego($nombreSinExtension);

            if (!$juego) {
                $noEncontrados[] = $nombreArchivo;
                $this->warn("  ⚠ No se encontró juego para: {$nombreArchivo}");
                continue;
            }

            // Copiar imagen al directorio de destino
            $extension = $imagen->getExtension();
            $nuevoNombre = Str::slug($juego->name) . '.' . $extension;
            $rutaDestino = $destinoDir . '/' . $nuevoNombre;

            try {
                // Solo copiar si no existe
                if (!File::exists($rutaDestino)) {
                    File::copy($imagen->getPathname(), $rutaDestino);
                }
                
                // Actualizar TODOS los juegos con el mismo nombre
                $rutaImagen = 'juegos/' . $nuevoNombre;
                $actualizados = Game::where('name', $juego->name)
                    ->update(['image_path' => $rutaImagen]);

                $asociados += $actualizados;
                $this->info("  ✓ Asociado: {$juego->name} -> {$nuevoNombre} ({$actualizados} registros actualizados)");
            } catch (\Exception $e) {
                $this->error("  ✗ Error al copiar imagen: " . $e->getMessage());
            }
        }

        $this->newLine();
        $this->info("═══════════════════════════════════════");
        $this->info("Resumen:");
        $this->info("  ✓ Imágenes asociadas: {$asociados}");
        
        if (count($noEncontrados) > 0) {
            $this->warn("  ⚠ No encontrados: " . count($noEncontrados));
            $this->line("  Archivos sin asociar:");
            foreach ($noEncontrados as $archivo) {
                $this->line("    - {$archivo}");
            }
        }

        $this->info("═══════════════════════════════════════");
        
        return 0;
    }

    /**
     * Busca un juego por nombre basado en el nombre del archivo
     */
    private function buscarJuego($nombreArchivo)
    {
        // Normalizar el nombre del archivo
        $nombreNormalizado = $this->normalizarNombre($nombreArchivo);

        // Buscar coincidencia exacta primero
        $juego = Game::whereRaw('LOWER(name) = ?', [strtolower($nombreNormalizado)])->first();
        
        if ($juego) {
            return $juego;
        }

        // Buscar por coincidencia parcial (contiene)
        $juego = Game::whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($nombreNormalizado) . '%'])->first();
        
        if ($juego) {
            return $juego;
        }

        // Casos especiales de nombres comunes
        $casosEspeciales = [
            'dota2' => 'Dota 2',
            'dota' => 'Dota 2',
            'theforest' => 'The Forest',
            'forest' => 'The Forest',
        ];

        $nombreLower = strtolower($nombreNormalizado);
        foreach ($casosEspeciales as $clave => $nombreJuego) {
            if (strpos($nombreLower, $clave) !== false) {
                $juego = Game::whereRaw('LOWER(name) = ?', [strtolower($nombreJuego)])->first();
                if ($juego) {
                    return $juego;
                }
            }
        }

        // Buscar por palabras clave comunes
        $palabrasClave = $this->extraerPalabrasClave($nombreArchivo);
        
        foreach ($palabrasClave as $palabra) {
            if (strlen($palabra) < 3) continue; // Ignorar palabras muy cortas
            
            $juego = Game::whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($palabra) . '%'])->first();
            
            if ($juego) {
                return $juego;
            }
        }

        return null;
    }

    /**
     * Normaliza el nombre del archivo para buscar coincidencias
     */
    private function normalizarNombre($nombre)
    {
        // Remover guiones bajos y guiones, convertir a espacios
        $nombre = str_replace(['_', '-'], ' ', $nombre);
        
        // Remover extensiones comunes y palabras técnicas
        $nombre = preg_replace('/\s*(first|cover|art|webp|metacard|capsule|616x353)\s*/i', ' ', $nombre);
        
        // Remover números al final (como "2" en "DOTA2")
        // Pero mantenerlos si son parte del nombre del juego
        
        // Limpiar espacios múltiples
        $nombre = preg_replace('/\s+/', ' ', $nombre);
        
        return trim($nombre);
    }

    /**
     * Extrae palabras clave del nombre del archivo
     */
    private function extraerPalabrasClave($nombre)
    {
        $nombre = $this->normalizarNombre($nombre);
        return explode(' ', $nombre);
    }
}

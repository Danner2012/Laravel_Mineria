<?php

namespace Database\Seeders;

use App\Models\Game;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class JuegosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvPath = base_path('estimsito.csv');
        
        if (!File::exists($csvPath)) {
            $this->command->error('El archivo estimsito.csv no existe en la raíz del proyecto.');
            return;
        }

        $this->command->info('Leyendo archivo CSV...');
        
        $file = fopen($csvPath, 'r');
        
        // Leer la primera línea (encabezados)
        $headers = fgetcsv($file);
        
        $this->command->info('Importando juegos...');
        
        $count = 0;
        $batch = [];
        $batchSize = 500; // Insertar en lotes para mejor rendimiento
        
        while (($row = fgetcsv($file)) !== false) {
            // Verificar que la fila tenga datos
            if (count($row) < count($headers)) {
                continue;
            }
            
            $data = array_combine($headers, $row);
            
            // Preparar los datos para insertar
            $juego = [
                'name' => $data['name'] ?? null,
                'rating' => !empty($data['rating']) ? (float) $data['rating'] : null,
                'price_original' => !empty($data['price_original']) ? (float) $data['price_original'] : 0,
                'price_current' => !empty($data['price_current']) ? (float) $data['price_current'] : 0,
                'discount_percent' => !empty($data['discount_percent']) ? (float) $data['discount_percent'] : 0,
                'days_until_sale' => !empty($data['days_until_sale']) ? (int) (float) $data['days_until_sale'] : null,
                'genre' => $data['genre'] ?? null,
                'reviews_count' => !empty($data['reviews_count']) ? (int) (float) $data['reviews_count'] : null,
                'player_trend' => $data['player_trend'] ?? null,
                'will_be_discounted' => isset($data['will_be_discounted']) && $data['will_be_discounted'] == '1',
                'image_url' => null,
                'image_path' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            
            // Validar que el nombre no esté vacío
            if (empty($juego['name'])) {
                continue;
            }
            
            $batch[] = $juego;
            
            // Insertar en lotes
            if (count($batch) >= $batchSize) {
                DB::table('juegos')->insert($batch);
                $count += count($batch);
                $this->command->info("Importados {$count} juegos...");
                $batch = [];
            }
        }
        
        // Insertar los registros restantes
        if (!empty($batch)) {
            DB::table('juegos')->insert($batch);
            $count += count($batch);
        }
        
        fclose($file);
        
        $this->command->info("¡Importación completada! Se importaron {$count} juegos.");
    }
}


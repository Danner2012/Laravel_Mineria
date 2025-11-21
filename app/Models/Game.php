<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $table = 'juegos';
    
    protected $fillable = [
        'name',
        'rating',
        'price_original',
        'price_current',
        'discount_percent',
        'days_until_sale',
        'genre',
        'reviews_count',
        'player_trend',
        'will_be_discounted',
        'image_url',
        'image_path',
    ];
    
    protected $casts = [
        'rating' => 'decimal:2',
        'price_original' => 'decimal:2',
        'price_current' => 'decimal:2',
        'discount_percent' => 'decimal:2',
        'days_until_sale' => 'integer',
        'reviews_count' => 'integer',
        'will_be_discounted' => 'boolean',
    ];
}

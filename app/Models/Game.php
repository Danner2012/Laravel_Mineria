<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $table = 'juegos_ofertas'; // nombre real
    protected $fillable = ['game', 'discount', 'price_current', 'rating', 'price_original', 'will_be_discounted'];
    public $timestamps = false; // si no usás created_at y updated_at
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    // propietats de productes, nom, id, preu, foto, si esta publicat
    protected $fillable = [
        'product_id',
        'name',
        'slug',
        'price',
        'published',
        'image_url',
        'share_url',
    ];

    public function category()
    {   // aqui creem una funciona, cada producte perteneix a una categoria
        return $this->belongsTo(Category::class);
    }
}

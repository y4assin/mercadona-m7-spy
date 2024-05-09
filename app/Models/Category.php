<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    // propiedades de categoria, nom , id, etç
    protected $fillable = [
        'name',
        'external_id',
        'parent_id',
        'parent_name'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CategorieProduit extends Model
{
    use HasFactory;

    protected $table = 'categorie_produits';

    protected $fillable = [
        'nom',
        'description',
        'image',
    ];

    public function produits(): HasMany
    {
        return $this->hasMany(Produit::class, 'id_categories');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Produit extends Model
{
    use HasFactory;

    protected $table = 'produits';

    public $timestamps = false;

    protected $fillable = [
        'nom',
        'stock',
        'image',
        'description',
        'prix_unitaire',
        'statut',
        'date_ajout',
        'id_categories',
    ];

    protected function casts(): array
    {
        return [
            'prix_unitaire' => 'decimal:2',
            'date_ajout' => 'datetime',
        ];
    }

    public function categorie(): BelongsTo
    {
        return $this->belongsTo(CategorieProduit::class, 'id_categories');
    }

    public function commandeProduits(): HasMany
    {
        return $this->hasMany(CommandeProduit::class, 'id_produit');
    }

    public function produitsPaniers(): HasMany
    {
        return $this->hasMany(ProduitsPanier::class, 'id_produit');
    }
}

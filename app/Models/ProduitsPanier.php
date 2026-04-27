<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProduitsPanier extends Model
{
    use HasFactory;

    protected $table = 'produits_paniers';

    protected $fillable = [
        'id_produit',
        'id_panier',
        'quantite',
    ];

    public function produit(): BelongsTo
    {
        return $this->belongsTo(Produit::class, 'id_produit');
    }

    public function panier(): BelongsTo
    {
        return $this->belongsTo(Panier::class, 'id_panier');
    }
}

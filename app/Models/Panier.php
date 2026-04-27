<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Panier extends Model
{
    use HasFactory;

    protected $table = 'paniers';

    protected $fillable = [
        'id_utilisateur',
    ];

    public function utilisateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_utilisateur');
    }

    public function produitsPaniers(): HasMany
    {
        return $this->hasMany(ProduitsPanier::class, 'id_panier');
    }

    public function produits(): BelongsToMany
    {
        return $this->belongsToMany(Produit::class, 'produits_paniers', 'id_panier', 'id_produit')
            ->withPivot('quantite')
            ->withTimestamps();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Commande extends Model
{
    use HasFactory;

    protected $table = 'commandes';

    protected $fillable = [
        'date_commande',
        'statut',
        'adresse_livraison',
        'date_livraison',
        'id_utilisateur',
        'montant_total',
        'statut_livraison',
    ];

    protected function casts(): array
    {
        return [
            'date_commande' => 'datetime',
            'date_livraison' => 'datetime',
        ];
    }

    public function utilisateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_utilisateur');
    }

    public function paiement(): HasOne
    {
        return $this->hasOne(Paiement::class, 'id_commande');
    }

    public function commandeProduits(): HasMany
    {
        return $this->hasMany(CommandeProduit::class, 'id_commande');
    }

    public function produits(): BelongsToMany
    {
        return $this->belongsToMany(Produit::class, 'commande_produits', 'id_commande', 'id_produit')
            ->withPivot('quantite')
            ->withTimestamps();
    }
}

<?php

namespace App\Models;

use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmailContract
{
    use HasFactory;
    use MustVerifyEmail;
    use Notifiable;

    protected $table = 'utilisateurs';

    protected $fillable = [
        'nom_complet',
        'email',
        'mot_de_passe',
        'telephone',
        'adresse',
        'id_role',
        'name',
        'password',
    ];

    protected $hidden = [
        'mot_de_passe',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'mot_de_passe' => 'hashed',
        ];
    }

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->nom_complet,
            set: fn (string $value) => ['nom_complet' => $value],
        );
    }

    protected function password(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->mot_de_passe,
            set: fn (string $value) => ['mot_de_passe' => $value],
        );
    }

    public function getAuthPassword(): string
    {
        return $this->mot_de_passe;
    }

    public function initials(): string
    {
        return collect(preg_split('/\s+/', trim($this->nom_complet)))
            ->filter()
            ->take(2)
            ->map(fn (string $part) => mb_strtoupper(mb_substr($part, 0, 1)))
            ->implode('');
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'id_role');
    }

    /**
     * Vérifier si l'utilisateur a une permission spécifique
     */
    public function hasPermission(string $permissionNom): bool
    {
        if (!$this->role) {
            return false;
        }

        return $this->role->permissions()
            ->where('permissions.nom', $permissionNom)
            ->exists();
    }

    /**
     * Vérifier si l'utilisateur est un administrateur
     */
    public function isAdmin(): bool
    {
        return $this->role && in_array($this->role->nom, [
            'Super Admin',
            'Admin Articles',
            'Admin Utilisateurs'
        ]);
    }

    /**
     * Vérifier si l'utilisateur est Super Admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->role && $this->role->nom === 'Super Admin';
    }

    public function commandes(): HasMany
    {
        return $this->hasMany(Commande::class, 'id_utilisateur');
    }

    public function panier(): HasOne
    {
        return $this->hasOne(Panier::class, 'id_utilisateur');
    }
}

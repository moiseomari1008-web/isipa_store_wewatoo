<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';

    protected $fillable = [
        'nom',
        'description',
    ];

    public function utilisateurs(): HasMany
    {
        return $this->hasMany(User::class, 'id_role');
    }

    public function attributions(): HasMany
    {
        return $this->hasMany(Attribution::class, 'id_role');
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'attributions', 'id_role', 'id_permission')
            ->withTimestamps();
    }
}

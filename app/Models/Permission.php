<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Permission extends Model
{
    use HasFactory;

    protected $table = 'permissions';

    protected $fillable = [
        'nom',
        'description',
    ];

    public function attributions(): HasMany
    {
        return $this->hasMany(Attribution::class, 'id_permission');
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'attributions', 'id_permission', 'id_role')
            ->withTimestamps();
    }
}

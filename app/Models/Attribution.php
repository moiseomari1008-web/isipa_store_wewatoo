<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attribution extends Model
{
    use HasFactory;

    protected $table = 'attributions';

    protected $fillable = [
        'id_role',
        'id_permission',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'id_role');
    }

    public function permission(): BelongsTo
    {
        return $this->belongsTo(Permission::class, 'id_permission');
    }
}

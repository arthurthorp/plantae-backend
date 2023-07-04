<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plantation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'cultivation',
        'planting_date',
        'estimate_harvest_date',
        'plantation_size'
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'plantations_users');
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Developer extends Model
{
    /** @use HasFactory<\Database\Factories\DeveloperFactory> */
    use HasFactory;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
        'game_id'
    ];
    
    protected $casts = [
        'hired' => 'boolean',
        'salary' => 'decimal:2',
        'xp' => 'integer',
        'hired_at' => 'datetime',
        'fired_at' => 'datetime',
    ];

    public function game():BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function projects():HasMany
    {
        return $this->HasMany(Project::class);
    }

    protected function activeProject(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->projects()->where('development_completed', false)->exists()
        );
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Project extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectFactory> */
    use HasFactory;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
        'game_id'
    ];
    protected $casts = [
        'budget' => 'decimal:2',
        'complexity' => 'integer',
        'completed' => 'boolean',
        'generation_progress' => 'integer',
        'generation_completed' => 'boolean',
        'generation_started_at' => 'datetime',
        'generation_completed_at' => 'datetime',
        'development_progress' => 'integer',
        'development_completed' => 'boolean',
        'development_started_at' => 'datetime',
        'development_completed_at' => 'datetime',
    ];

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function seller(): HasOne
    {
        return $this->hasOne(Seller::class);
    }
    
    public function developer(): HasOne
    {
        return $this->hasOne(Developer::class);
    }
}

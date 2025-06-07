<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Game extends Model
{
    /** @use HasFactory<\Database\Factories\GameFactory> */
    use HasFactory;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
        'user_id'
    ];

    protected $casts = [
        'date_start' => 'datetime',
        'date_current' => 'datetime',
        'cash_start' => 'decimal:2',
        'cash_current' => 'decimal:2',
    ];

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function developers():HasMany
    {
        return $this->hasMany(Developer::class);
    }

    public function sellers():HasMany
    {
        return $this->hasMany(Seller::class);
    }

    public function projects():HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function cashPlus(float $amount): void
    {
        $this->cash_current += $amount;
        $this->save();
    }
    
    public function cashMinus(float $amount): void
    {
        $this->cash_current -= $amount;
        $this->save();
    }

    public function updateMonthExpenses(float $amount): void
    {
        $this->cash_month_expenses += $amount;
        $this->save();
    }

}

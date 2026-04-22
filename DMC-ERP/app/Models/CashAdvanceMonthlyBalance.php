<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CashAdvanceMonthlyBalance extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'month',
        'carryover_balance',
        'added_budget',
        'opening_balance',
        'released_total',
        'expense_total',
        'remaining_balance',
        'remarks',
        'prepared_by',
        'finalized_at',
    ];

    protected $casts = [
        'carryover_balance' => 'decimal:2',
        'added_budget' => 'decimal:2',
        'opening_balance' => 'decimal:2',
        'released_total' => 'decimal:2',
        'expense_total' => 'decimal:2',
        'remaining_balance' => 'decimal:2',
        'finalized_at' => 'datetime',
    ];

    public function preparer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'prepared_by');
    }
}

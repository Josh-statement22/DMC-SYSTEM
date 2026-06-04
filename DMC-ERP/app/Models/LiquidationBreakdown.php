<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LiquidationBreakdown extends Model
{
    use HasFactory;

    protected $fillable = [
        'liquidation_id',
        'liquidation_expense_id',
        'date',
        'category_id',
        'category',
        'particulars',
        'amount',
        'remarks',
        'posted_cash_advance_request_id',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function liquidation(): BelongsTo
    {
        return $this->belongsTo(Liquidation::class);
    }

    public function sourceExpense(): BelongsTo
    {
        return $this->belongsTo(LiquidationExpense::class, 'liquidation_expense_id');
    }

    public function postedRequest(): BelongsTo
    {
        return $this->belongsTo(CashAdvanceRequest::class, 'posted_cash_advance_request_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LiquidationExpense extends Model
{
    use HasFactory;

    protected $table = 'liquidation_expenses';

    protected $fillable = [
        'liquidation_id',
        'expense_date',
        'category_id',
        'particular_id',
        'transaction_details',
        'description',
        'amount',
        'receipt_path',
        'transaction_type',
    ];

    protected $casts = [
        'expense_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function liquidation(): BelongsTo
    {
        return $this->belongsTo(Liquidation::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function particular(): BelongsTo
    {
        return $this->belongsTo(Particular::class, 'particular_id');
    }
}

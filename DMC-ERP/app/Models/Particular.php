<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Particular extends Model
{
    use HasFactory;

    protected $table = 'particulars';

    protected $fillable = [
        'category_id',
        'particular_name',
        'description',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function liquidationExpenses(): HasMany
    {
        return $this->hasMany(LiquidationExpense::class, 'particular_id');
    }
}

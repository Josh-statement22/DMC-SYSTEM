<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CashAdvanceRequestAudit extends Model
{
    use HasFactory;

    protected $fillable = [
        'cash_advance_request_id',
        'action',
        'old_status',
        'new_status',
        'remarks',
        'acted_by',
        'meta',
        'acted_at',
    ];

    protected $casts = [
        'meta' => 'array',
        'acted_at' => 'datetime',
    ];

    public function request(): BelongsTo
    {
        return $this->belongsTo(CashAdvanceRequest::class, 'cash_advance_request_id');
    }

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'acted_by');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CashAdvanceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_no',
        'requester_id',
        'requested_amount',
        'approved_amount',
        'purpose',
        'request_date',
        'date_needed',
        'status',
        'accounting_remarks',
        'reviewed_by',
        'submitted_at',
        'reviewed_at',
        'released_at',
        'liquidation_due_date',
    ];

    protected $casts = [
        'requested_amount' => 'decimal:2',
        'approved_amount' => 'decimal:2',
        'request_date' => 'date',
        'date_needed' => 'date',
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'released_at' => 'datetime',
        'liquidation_due_date' => 'date',
    ];

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(CashAdvanceRequestAttachment::class);
    }

    public function audits(): HasMany
    {
        return $this->hasMany(CashAdvanceRequestAudit::class);
    }
}

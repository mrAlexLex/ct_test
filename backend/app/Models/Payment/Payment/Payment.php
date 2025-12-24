<?php

namespace app\Models\Payment\Payment;

use app\Models\Payment\Payment\Enums\PaymentStatusEnum;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    use PaymentConstants;
    use PaymentScopes;

    protected $fillable = [
        'user_id',
        'amount',
        'currency',
        'method',
        'status',
        'transaction_id',
        'metadata',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'metadata' => 'array',
        'status' => PaymentStatusEnum::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}


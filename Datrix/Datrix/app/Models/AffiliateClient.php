<?php

namespace App\Models;


use App\Models\User;
use App\Models\Status;
use App\Models\PaymentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AffiliateClient extends Model
{
    use HasFactory;
    /**
     * Get the user that owns the AffiliateClient
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public $fillable = [
        'name',
        'type',
        'contact',
        'project_name',
        'user_id',
        'status',
        'amount',
        'commission',
        'payment_status_id',


    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function paymentStatus()
    {
        return $this->belongsTo(PaymentStatus::class, 'payment_status_id');
    }
}

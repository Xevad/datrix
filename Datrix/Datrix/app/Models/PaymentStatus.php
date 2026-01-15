<?php

namespace App\Models;

use App\Models\AffiliateClient;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function affiliateClient()
    {
        return $this->hasMany(AffiliateClient::class);
    }
}

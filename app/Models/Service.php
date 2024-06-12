<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    protected $fillable = [
        'transaction_id',
        'service',
        'amount',
        'add_amount',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}

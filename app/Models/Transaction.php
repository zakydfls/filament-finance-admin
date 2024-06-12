<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_name',
        'customer_phone',
        'customer_car',
        'customer_car_number',
        'service_type',
        'spare_part',
        'category_id',
        'service_amount',
        'add_service_amount',
        'spare_part_amount',
        'add_spare_part_amount',
        'date',
        'note',
        'image',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function spareParts()
    {
        return $this->hasMany(SparePart::class);
    }
}

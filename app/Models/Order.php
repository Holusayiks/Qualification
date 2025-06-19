<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        "id",
        "product",
        "status",
        "nomer",
        "auto_type",
        "ride_id",
        "auto_id",
        "weigth",
        "driver_id",
        "supplier_id",
        "point_A",
        "point_B",
        "station_id",
        "date_of_start",
        "distance",
        "vutratu",
    ];
    public $table = "orders";
    public function auto(): BelongsTo
    {
        return $this->belongsTo(Auto::class);
    }
    public function ride(): HasOne
    {
        return $this->hasOne(Ride::class, 'ride_id', 'id');
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }
    public function station(): BelongsTo
    {
        return $this->belongsTo(Station::class);
    }
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
}

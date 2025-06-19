<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ride extends Model
{

    use HasFactory;
    protected $guarded = [];

    public $table = "rides";
    public function driver(): HasOne
    {
        return $this->hasOne(Driver::class, 'id', 'driver_id');
    }
    public function orders(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'id', 'ride_id');
    }
    public function station_start(): HasOne
    {
        return $this->hasOne(Station::class, 'id', 'station_start_id');
    }
    public function station_end(): HasOne
    {
        return $this->hasOne(Station::class, 'id', 'station_end_id');
    }
}

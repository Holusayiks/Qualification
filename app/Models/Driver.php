<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Driver extends Model
{
    use HasFactory;
    protected $guarded = [];
    public $table = "drivers";

    public function user(): HasOne
    {
        return $this->hasOne(User::class,'id','user_id');
    }

    // public function auto(): HasOne
    // {
    //     return $this->hasOne(Auto::class);
    // }
    public function auto(): BelongsTo
    {
        return $this->belongsTo(Auto::class, 'id', 'driver_id');
    }
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
    public function station(): HasOne
    {
        return $this->hasOne(Station::class, 'id', 'station_id');
    }
    public function rides(): BelongsTo
    {
        return $this->belongsTo(Ride::class);
    }

}

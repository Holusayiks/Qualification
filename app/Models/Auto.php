<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Auto extends Model
{
    use HasFactory;
    protected $guarded = [];
    public $table = "autos";
    // public function driver(): BelongsTo
    // {
    //     return $this->belongsTo(Driver::class);
    // }
    public function driver(): HasOne
    {
        return $this->hasOne(Driver::class,'id','driver_id');
    }
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
    public function station(): BelongsTo
    {
        return $this->belongsTo(Station::class);
    }
}

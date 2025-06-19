<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Hash;

class Station extends Model
{
    use HasFactory;
    protected $guarded = [];
    public $table = "stations";
    public function dispetcher(): HasOne
    {
        return $this->hasOne(Dispetcher::class);
    }
    public function autos(): HasMany
    {
        return $this->hasMany(Auto::class);
    }
    public function drivers(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }
    public function menedger(): HasOne
    {
        return $this->HasOne(Menedger::class);
    }
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}

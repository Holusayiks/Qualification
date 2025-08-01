<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Dispetcher extends Model
{
    use HasFactory;
    protected $guarded = [];
    public $table = "dispetchers";
    public function user(): HasOne
    {
        return $this->hasOne(User::class,'id','user_id');
    }
    public function station(): BelongsTo
    {
        return $this->belongsTo(Station::class);
    }
}

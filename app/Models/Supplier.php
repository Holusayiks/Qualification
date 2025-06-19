<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Supplier extends Model
{
    use HasFactory;
    protected $guarded = [];
    public $table = "suppliers";
    public function user(): HasOne
    {
        return $this->hasOne(User::class,'id','user_id');
    }
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}

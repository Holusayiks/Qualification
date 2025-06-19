<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class InsuranceAccountJournal extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $fillable = [
        "id",
        "current_sum",
        "user_id",
    ];
    public $table = "insurance_account_journal";
    public function user(): HasOne
    {
        return $this->hasOne(User::class,'id','user_id');
    }
}

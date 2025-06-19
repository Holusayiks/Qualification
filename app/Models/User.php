<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'role',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function dispetcher(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function menedger(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function insurance_account_journal(): BelongsTo
    {
        return $this->belongsTo(InsuranceAccountJournal::class);
    }
}

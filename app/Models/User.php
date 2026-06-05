<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama',
        'email',
        'pin',
        'password',
        'nomor_hp',
        'foto',
        'role',
        'verified',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'pin' => 'hashed',
            'verified' => 'datetime',
        ];
    }

    /**
     * Get the nasabah temp for the user.
     */
    public function nasabahTemp()
    {
        return $this->hasOne(NasabahTemp::class);
    }

    /**
     * Get the nasabah for the user.
     */
    public function nasabah()
    {
        return $this->hasOne(Nasabah::class);
    }

    /**
     * Get the admin operasional for the user.
     */
    public function adminOperasional()
    {
        return $this->hasOne(AdminOperasional::class);
    }

    /**
     * Get the admin utama for the user.
     */
    public function adminUtama()
    {
        return $this->hasOne(AdminUtama::class);
    }

    /**
     * Get the otps for the user.
     */
    public function otps()
    {
        return $this->hasMany(Otp::class);
    }
}

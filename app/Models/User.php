<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'password_set_at',
        'role',
        'seksi_id',
        'no_hp',
        'alamat',
        'provider',
        'provider_id',
    ];

    /**
     * True kalau user ini BELUM PERNAH sadar/tahu password-nya sendiri —
     * biasanya akun yang dibuat otomatis lewat Google login (password-nya
     * di-generate random oleh sistem). Dipakai buat nampilin form
     * "Set Password" (tanpa current_password) di halaman profil, dan buat
     * PasswordController memutuskan validasi mana yang dipakai.
     */
    public function belumPernahSetPassword(): bool
    {
        return is_null($this->password_set_at);
    }

    /**
     * Hanya terisi kalau role = petugas_seksi.
     */
    public function seksi(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Seksi::class, 'seksi_id');
    }

    public function permohonanDiajukan(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Permohonan::class, 'user_id');
    }

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
            'password_set_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}

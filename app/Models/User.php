<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailVerificationCode;
use App\Notifications\CustomResetPassword;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasUuids, SoftDeletes, HasRoles;

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPassword($token));
    }

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'name', 'email', 'phone', 'password', 'avatar',
        'preferred_language', 'preferred_theme', 'is_active',
        'last_login_at', 'points_balance',
        'email_verification_code', 'email_verification_code_expires_at',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at'                 => 'datetime',
            'email_verification_code_expires_at' => 'datetime',
            'last_login_at'                     => 'datetime',
            'is_active'                         => 'boolean',
            'points_balance'                    => 'decimal:2',
            'password'                          => 'hashed',
        ];
    }

    public function addresses()     { return $this->hasMany(Address::class); }
    public function cartItems()     { return $this->hasMany(CartItem::class); }
    public function wishlistItems() { return $this->hasMany(WishlistItem::class); }
    public function orders()        { return $this->hasMany(Order::class); }
    public function reviews()       { return $this->hasMany(Review::class); }
    public function couponUsages()  { return $this->hasMany(CouponUsage::class); }

    public function defaultAddress(): ?Address
    {
        return $this->addresses()->where('is_default', true)->first();
    }

    public function cartCount(): int
    {
        return $this->cartItems()->sum('quantity');
    }

    public function wishlistCount(): int
    {
        return $this->wishlistItems()->count();
    }

    /**
     * Generate a 6-digit verification code and send it to the user's email.
     */
    public function sendEmailVerificationCode(): void
    {
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $this->update([
            'email_verification_code'            => $code,
            'email_verification_code_expires_at'  => now()->addMinutes(15),
        ]);

        Mail::to($this->email)->send(new EmailVerificationCode($this->name, $code));
    }

    /**
     * Verify the given code against the stored one.
     */
    public function verifyEmailCode(string $code): bool
    {
        if (!$this->email_verification_code) {
            return false;
        }

        if ($this->email_verification_code_expires_at && $this->email_verification_code_expires_at->isPast()) {
            return false;
        }

        if ($this->email_verification_code !== $code) {
            return false;
        }

        $this->update([
            'email_verified_at'                  => now(),
            'email_verification_code'            => null,
            'email_verification_code_expires_at'  => null,
        ]);

        return true;
    }
}

<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;
use Spatie\OneTimePasswords\Models\Concerns\HasOneTimePasswords;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasOneTimePasswords, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'airtable_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = ['remember_token'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'phone_verified_at' => 'datetime',
        ];
    }

    /**
     * Get the user's full name
     */
    public function getNameAttribute(): string
    {
        return trim(($this->first_name ?? '').' '.($this->last_name ?? ''));
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * Create or update user from Airtable data
     */
    public static function createFromAirtable(array $airtableData): self
    {
        $phone = $airtableData['fields']['phoneNumber'] ?? null;

        if (! $phone) {
            throw new \Exception('Phone number is required');
        }

        // Find existing user or create new one
        $user = static::firstOrNew(['phone' => $phone]);

        $user->fill([
            'airtable_id' => $airtableData['id'],
            'first_name' => $airtableData['fields']['firstName'] ?? null,
            'last_name' => $airtableData['fields']['lastName'] ?? null,
            'email' => $airtableData['fields']['email'] ?? null,
            'phone' => $phone,
        ]);

        $user->save();

        return $user;
    }

    public function routeNotificationForVonage(Notification $notification): string
    {
        return static::formatNorwegianPhone($this->phone);
    }

    /**
     * Format Norwegian phone number to international format
     */
    private static function formatNorwegianPhone(?string $phone): ?string
    {
        if (! $phone) {
            return null;
        }

        // Remove any whitespace and non-numeric characters except +
        $cleanPhone = preg_replace('/[^\d+]/', '', $phone);

        // Handle different input formats:
        // 1. "+4798765432" - already formatted
        if (Str::startsWith($cleanPhone, '+47') && strlen($cleanPhone) === 11) {
            return $cleanPhone;
        }

        // 2. "4798765432" - country code + number
        if (Str::startsWith($cleanPhone, '47') && strlen($cleanPhone) === 10) {
            return '+'.$cleanPhone;
        }

        // 3. "98765432" - just the 8-digit number
        if (strlen($cleanPhone) === 8 && ctype_digit($cleanPhone)) {
            return '+47'.$cleanPhone;
        }

        // Return original if format doesn't match expected patterns
        return $phone;
    }
}

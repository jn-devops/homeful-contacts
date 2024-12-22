<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use TheIconic\NameParser\Parser;

/**
 * Class User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $mobile
 * @property Contact $contact
 * @property string $first_name
 * @property string $last_name
 *
 * @method int getKey()
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    use HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'mobile',
        'password',
    ];

    protected $appends = [
        'first_name',
        'last_name'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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
        ];
    }

    public function contact(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function getFirstNameAttribute(): string
    {
        $parser = new Parser;
        $name = $parser->parse($this->name);

        return $name->getFirstname();
    }

    public function getLastNameAttribute(): string
    {
        $parser = new Parser;
        $name = $parser->parse($this->name);

        return $name->getLastname();
    }
}

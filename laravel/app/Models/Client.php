<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model
{
    use HasFactory;
    use HasApiTokens;
    protected $table = 'clients';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'email',
        'password',
        'latitude',
        'longitude'
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function cars(): HasMany
    {
        return $this->hasMany(Car::class, 'owner_id', 'id');
    }

    public function carRentals()
    {
        return $this->hasMany(CarRental::class, 'client_id', 'id');
    }
}

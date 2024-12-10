<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Client extends Model
{
    use HasApiTokens;
    protected $table = 'clients';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'email',
        'password'
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
}

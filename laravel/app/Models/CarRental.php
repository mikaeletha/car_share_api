<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarRental extends Model
{
    use HasFactory;
    protected $table = 'car_rentals';

    protected $primaryKey = 'id';

    protected $fillable = [
        'car_id',
        'client_id',
        'borrowed_at',
        'returned_at',
        'borrowed_latitude',
        'borrowed_longitude',
        'returned_latitude',
        'returned_longitude',
    ];

    public function car()
    {
        return $this->belongsTo(Car::class, 'car_id', 'id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }
}

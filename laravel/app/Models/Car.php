<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Car extends Model
{
    use HasFactory;

    protected $table = 'cars';

    protected $primaryKey = 'id';

    protected $fillable = [
        'model',
        'manufacturer',
        'year',
        'owner_id',
        'fuel_type',
        'status',
        'latitude',
        'longitude'
    ];

    const FUEL_TYPES = ['gasoline', 'diesel', 'ethanol', 'gnv', 'electric', 'hydrogen'];

    const STATUSES = ['available', 'not_available'];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'owner_id', 'id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kamar extends Model
{
    use HasFactory;

    protected $table = 'kamar';

    protected $fillable = [
        'nomor_kamar',
        'harga_bulanan',
        'status',
    ];

    protected $casts = [
        'harga_bulanan' => 'decimal:2',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'kamar_id');
    }
}

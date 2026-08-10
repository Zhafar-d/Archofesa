<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'room_id',
        'room_code',
        'monthly_rate',
        'status',
        'payment_method',
        'payment_status',
        'move_in_date',
        'move_out_date',
        'notes',
        'admin_notes',
        'owner_notes',
    ];

    protected $casts = [
        'move_in_date' => 'date',
        'move_out_date' => 'date',
    ];

    public function getStepAktifAttribute()
    {
        $urutan = ['pending', 'menunggu_pembayaran', 'dibayar', 'menunggu_konfirmasi_owner', 'siap_huni', 'dihuni'];

        return array_search($this->status, $urutan, true);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}

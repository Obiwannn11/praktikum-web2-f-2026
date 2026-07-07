<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Borrow extends Model
{
    protected $fillable = [
        'user_id',
        'book_id',
        'date_start',
        'date_end',
    ];

    // Cast: ubah otomatis kolom tanggal peminjaman menjadi objek tanggal (Carbon)
    protected $casts = [
        'date_start' => 'date',
        'date_end' => 'date',
    ];

    // Relasi: satu peminjaman merujuk ke satu buku (belongsTo)
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    // Relasi: satu peminjaman dilakukan oleh satu user (belongsTo)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

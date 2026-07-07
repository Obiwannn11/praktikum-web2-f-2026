<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'category_id',
        'title',
        'writer',
        'release_date',
    ];

    // Cast: ubah otomatis kolom release_date menjadi objek tanggal (Carbon)
    protected $casts = [
        'release_date' => 'date',
    ];

    // Relasi: satu buku dimiliki oleh satu kategori (belongsTo)
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relasi: satu buku bisa dipinjam berkali-kali (hasMany)
    public function borrow()
    {
        return $this->hasMany(Borrow::class);
    }
}

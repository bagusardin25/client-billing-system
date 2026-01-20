<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory;

    protected $table = 'clients';

    protected $fillable = [
        'nama_client',
        'perusahaan',
        'status_pembayaran',
        'bulan',
        'no_telepon',
        'tagihan',
        'kode_client',
        'alamat',
        'jabatan',
    ];

    protected $casts = [
        'status_pembayaran' => 'integer',
        'tagihan' => 'integer',
    ];

    /**
     * Get the invoices for the client.
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'id_client');
    }

    /**
     * Get the cabang usaha for the client.
     */
    public function cabangUsaha(): HasMany
    {
        return $this->hasMany(CabangUsaha::class, 'id_client');
    }

    /**
     * Get status pembayaran label.
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status_pembayaran) {
            0 => 'Belum Lunas',
            1 => 'Lunas',
            default => 'Unknown',
        };
    }
}

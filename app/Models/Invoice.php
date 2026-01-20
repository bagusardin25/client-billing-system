<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    use HasFactory;

    protected $table = 'invoice';

    protected $fillable = [
        'kode_invoive',
        'id_client',
        'nama_client',
        'bulan',
        'tahun',
        'tagihan',
        'tanggal_pembayaran',
        'status_pembayaran',
    ];

    protected $casts = [
        'tagihan' => 'integer',
        'status_pembayaran' => 'integer',
    ];

    /**
     * Get the client that owns the invoice.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'id_client');
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

    /**
     * Get formatted tagihan.
     */
    public function getFormattedTagihanAttribute(): string
    {
        return 'Rp ' . number_format($this->tagihan, 0, ',', '.');
    }
}

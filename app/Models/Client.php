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
        'jenis_layanan',
    ];

    protected $casts = [
        'status_pembayaran' => 'integer',
        'tagihan' => 'integer',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($client) {
            if (empty($client->kode_client)) {
                $client->kode_client = self::generateKodeClient();
            }
        });
    }

    /**
     * Generate unique kode_client (CLN001, CLN002, etc.)
     */
    public static function generateKodeClient(): string
    {
        $lastClient = self::whereNotNull('kode_client')
            ->where('kode_client', 'LIKE', 'CLN%')
            ->orderByRaw("CAST(SUBSTRING(kode_client, 4) AS UNSIGNED) DESC")
            ->first();

        if ($lastClient && preg_match('/CLN(\d+)/', $lastClient->kode_client, $matches)) {
            $nextNumber = intval($matches[1]) + 1;
        } else {
            $nextNumber = 1;
        }

        return 'CLN' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

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

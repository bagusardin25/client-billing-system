<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CabangUsaha extends Model
{
    use HasFactory;

    protected $table = 'cabang_usaha';

    protected $fillable = [
        'id_client',
        'nama_perusahaan',
        'website',
        'no_telepon',
        'alamat',
    ];

    /**
     * Get the client that owns this cabang usaha.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'id_client');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pemasukan extends Model
{
    use HasFactory;

    protected $table = 'pemasukan';

    protected $fillable = [
        'id_user',
        'id_jenis_biaya',
        'nama_biaya1',
        'keterangan',
        'nominal',
        'tanggal',
    ];

    protected $casts = [
        'nominal' => 'integer',
    ];

    /**
     * Get the user that created this pemasukan.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * Get the jenis biaya for this pemasukan.
     */
    public function jenisBiaya(): BelongsTo
    {
        return $this->belongsTo(JenisBiaya::class, 'id_jenis_biaya');
    }

    /**
     * Get formatted nominal.
     */
    public function getFormattedNominalAttribute(): string
    {
        return 'Rp ' . number_format($this->nominal, 0, ',', '.');
    }
}

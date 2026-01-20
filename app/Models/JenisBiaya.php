<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisBiaya extends Model
{
    use HasFactory;

    protected $table = 'jenis_biaya';

    protected $fillable = [
        'nama_biaya',
    ];

    /**
     * Get the pemasukan for this jenis biaya.
     */
    public function pemasukan(): HasMany
    {
        return $this->hasMany(Pemasukan::class, 'id_jenis_biaya');
    }

    /**
     * Get the pengeluaran for this jenis biaya.
     */
    public function pengeluaran(): HasMany
    {
        return $this->hasMany(Pengeluaran::class, 'id_jenis_biaya');
    }
}

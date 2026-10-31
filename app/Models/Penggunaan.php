<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * 
 *
 * @property int $id
 * @property int $pelanggan_id
 * @property string $bulan
 * @property int $tahun
 * @property int $meter_awal
 * @property int $meter_akhir
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $jumlah_meter
 * @property-read \App\Models\Pelanggan $pelanggan
 * @property-read \App\Models\Tagihan|null $tagihan
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penggunaan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penggunaan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penggunaan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penggunaan whereBulan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penggunaan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penggunaan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penggunaan whereMeterAkhir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penggunaan whereMeterAwal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penggunaan wherePelangganId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penggunaan whereTahun($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Penggunaan whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Penggunaan extends Model
{
    use HasFactory;

    protected $table = 'penggunaan';

    protected $fillable = [
        'pelanggan_id',
        'bulan',
        'tahun',
        'meter_awal',
        'meter_akhir',
    ];

    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class); // foreign key 'pelanggan_id' di tabel 'penggunaan'
    }

    public function tagihan(): HasOne
    {
        return $this->hasOne(Tagihan::class); // foreign key 'penggunaan_id' di tabel 'tagihan'
    }
     public function getJumlahMeterAttribute()
    {
        return $this->meter_akhir - $this->meter_awal;
    }
}
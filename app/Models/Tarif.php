<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * 
 *
 * @property int $id
 * @property int $daya
 * @property numeric $tarif_perkwh
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Pelanggan> $pelanggans
 * @property-read int|null $pelanggans_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tarif newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tarif newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tarif query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tarif whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tarif whereDaya($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tarif whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tarif whereTarifPerkwh($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tarif whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Tarif extends Model
{
    use HasFactory;

 protected $table = 'tarifs';

    protected $fillable = ['daya', 'tarif_perkwh'];

    protected $casts = ['tarif_perkwh' => 'decimal:2'];

    public function pelanggans(): HasMany
    {
        return $this->hasMany(Pelanggan::class); // foreign key 'tarif_id' di tabel 'pelanggan'
    }
}
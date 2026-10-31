<?php
// app/Models/Pelanggan.php
namespace App\Models;

use Faker\Provider\ar_EG\Person;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Testing\Fluent\Concerns\Has;

/**
 * 
 *
 * @property int $id
 * @property int|null $tarif_id
 * @property string $nama_pelanggan
 * @property string $username
 * @property string $password
 * @property string $alamat
 * @property string $nomor_kwh
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Pembayaran> $pembayaran
 * @property-read int|null $pembayaran_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Penggunaan> $pengguna
 * @property-read int|null $pengguna_count
 * @property-read \App\Models\Tarif|null $tarifs
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pelanggan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pelanggan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pelanggan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pelanggan whereAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pelanggan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pelanggan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pelanggan whereNamaPelanggan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pelanggan whereNomorKwh($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pelanggan wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pelanggan whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pelanggan whereTarifId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pelanggan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pelanggan whereUsername($value)
 * @mixin \Eloquent
 */
class Pelanggan extends Authenticatable
{
    use HasFactory;
    protected $guard = 'pelanggan';
    protected $table = 'pelanggan';
    protected $fillable = ['nama_pelanggan', 'username', 'password', 'alamat', 'nomor_kwh', 'tarif_id'];
    protected $hidden = ['password', 'remember_token'];

    public function tarifs()
    {
        return $this->belongsTo(Tarif::class, 'tarif_id', 'id'); // foreign key 'tarif_id' di tabel 'pelanggan'
    }
        public function pengguna()
    {
        return $this->hasMany(Penggunaan::class);
    }
    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class);
    }
}
    
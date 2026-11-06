<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cabang extends Model
{
    protected $table = 'cabang';

    protected $primaryKey = 'id_cabang';

    public $timestamps = false;

    protected $fillable = [
        'kode_cabang', 'nama_cabang', 'provinsi_text', 'kota_text', 'alamat1', 'alamat2',
        'pic', 'telp', 'email', 'maps', 'google_maps', 'cabang_cesco', 'status',
        'created_date', 'created_by', 'updated_last', 'updated_by', 'tampil_front_end',
    ];
}

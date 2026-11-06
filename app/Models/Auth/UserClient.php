<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Foundation\Auth\User as Authenticatable;

#[Fillable([
    'id_kustomer',
    'other_customer',
    'customers',
    'username',
    'email',
    'fullname',
    'type',
    'company',
    'foto',
    'password',
    'expired_date',
    'otomatis_pengirim',
    'loc_code_otomatis',
    'perusahaan_pengirim',
    'alamat_otomatis',
    'phone',
    'customer_admin',
    'lokasi_asal',
    'report_awb_loc',
    'report_awb_by_booking',
    'warehouse',
    'departement_id',
    'show_invoice',
])]
#[Hidden(['password', 'remember_token'])]
class UserClient extends Authenticatable
{
    public $timestamps = false;

    protected $table = 'users_client';

    protected $primaryKey = 'id_user';

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'created_date' => 'datetime',
            'created_time' => 'datetime',
            'expired_date' => 'date',
        ];
    }
}

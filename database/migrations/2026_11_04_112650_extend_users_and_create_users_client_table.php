<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->unsignedInteger('legacy_id_user')->nullable()->unique();
            $table->string('username', 20)->nullable()->unique();
            $table->string('namalengkap', 100)->nullable();
            $table->string('phone')->nullable();
            $table->string('telp', 20)->nullable();
            $table->string('foto', 100)->nullable();
            $table->string('level', 32)->nullable()->index();
            $table->unsignedInteger('id_jabatan')->nullable();
            $table->string('add_operational', 1)->default('N');
            $table->string('blokir', 1)->default('N')->index();
            $table->date('tanggaldibuat')->nullable();
            $table->time('jamdibuat')->nullable();
            $table->unsignedInteger('jumlahlogin')->default(0);
            $table->date('tanggalkadaluarsa')->nullable();
            $table->unsignedInteger('id_cabang')->nullable()->index();
            $table->string('forget_password', 1)->default('N');
            $table->unsignedInteger('intercity_area')->nullable();
            $table->unsignedInteger('id_kustomer')->nullable()->index();
            $table->string('area', 1000)->nullable();
            $table->unsignedInteger('delivery_customer')->nullable();
            $table->string('delivery_area', 500)->nullable();
            $table->string('area_pulau', 16)->nullable();
            $table->text('area_prov')->nullable();
            $table->string('handle_service', 150)->nullable();
            $table->string('mobil', 1)->default('N');
            $table->string('menu_tambahan', 150)->nullable();
            $table->string('menu_pengurangan', 150)->nullable();
            $table->unsignedTinyInteger('tambahan_admin')->nullable();
            $table->string('warehouse', 1)->default('N');
            $table->date('tanggal_lahir')->nullable();
            $table->date('tanggal_bergabung')->nullable();
            $table->string('pendidikan_terakhir', 75)->nullable();
            $table->string('tempat_tinggal', 75)->nullable();
            $table->string('tampil_tracking_kurir', 1)->nullable();
            $table->string('email')->nullable()->change();
        });

        Schema::create('users_client', function (Blueprint $table): void {
            $table->increments('id_user');
            $table->unsignedInteger('id_kustomer')->nullable()->index();
            $table->string('other_customer', 1)->default('N');
            $table->string('customers', 100)->nullable();
            $table->string('username', 50)->nullable()->unique();
            $table->string('email', 100)->nullable()->unique();
            $table->string('fullname', 100)->nullable();
            $table->string('type', 20)->default('all');
            $table->string('company', 100)->nullable();
            $table->string('foto', 100)->nullable();
            $table->string('password', 255);
            $table->string('blocked', 1)->default('N')->index();
            $table->timestamp('created_date')->nullable()->useCurrent();
            $table->timestamp('created_time')->nullable()->useCurrent();
            $table->date('expired_date')->nullable();
            $table->string('forget_password', 1)->default('N');
            $table->unsignedTinyInteger('otomatis_pengirim')->nullable();
            $table->text('loc_code_otomatis')->nullable();
            $table->string('perusahaan_pengirim', 100)->nullable();
            $table->text('alamat_otomatis')->nullable();
            $table->string('phone', 13)->nullable();
            $table->unsignedTinyInteger('customer_admin')->nullable();
            $table->unsignedSmallInteger('lokasi_asal')->nullable();
            $table->integer('report_awb_loc');
            $table->unsignedTinyInteger('report_awb_by_booking')->nullable();
            $table->string('warehouse', 1)->default('N');
            $table->string('departement_id', 125)->nullable();
            $table->string('show_invoice', 1)->default('N');
            $table->rememberToken();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users_client');

        Schema::table('users', function (Blueprint $table): void {
            $table->dropUnique(['legacy_id_user']);
            $table->dropUnique(['username']);
            $table->dropIndex(['level']);
            $table->dropIndex(['blokir']);
            $table->dropIndex(['id_cabang']);
            $table->dropIndex(['id_kustomer']);
            $table->dropColumn([
                'legacy_id_user',
                'username',
                'namalengkap',
                'phone',
                'telp',
                'foto',
                'level',
                'id_jabatan',
                'add_operational',
                'blokir',
                'tanggaldibuat',
                'jamdibuat',
                'jumlahlogin',
                'tanggalkadaluarsa',
                'id_cabang',
                'forget_password',
                'intercity_area',
                'id_kustomer',
                'area',
                'delivery_customer',
                'delivery_area',
                'area_pulau',
                'area_prov',
                'handle_service',
                'mobil',
                'menu_tambahan',
                'menu_pengurangan',
                'tambahan_admin',
                'warehouse',
                'tanggal_lahir',
                'tanggal_bergabung',
                'pendidikan_terakhir',
                'tempat_tinggal',
                'tampil_tracking_kurir',
            ]);
            $table->string('email')->nullable(false)->change();
        });
    }
};

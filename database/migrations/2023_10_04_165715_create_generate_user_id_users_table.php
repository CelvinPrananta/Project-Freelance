<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGenerateUserIdUsersTable extends Migration
{
     /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
            CREATE TRIGGER sinkronisasi_data AFTER INSERT ON users FOR EACH ROW
            BEGIN
                INSERT INTO mode_aplikasi(name,user_id,email,tema_aplikasi) VALUES (NEW.name,NEW.user_id,NEW.email,NEW.tema_aplikasi);
                INSERT INTO daftar_pegawai(name,user_id,email,username,employee_id,role_name,avatar) VALUES (NEW.name,NEW.user_id,NEW.email,NEW.username,NEW.employee_id,NEW.role_name,NEW.avatar);
            END
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER "sinkronisasi_data"');
    }
}
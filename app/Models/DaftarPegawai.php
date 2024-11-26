<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DaftarPegawai extends Model
{
    use HasFactory;

    protected $table = 'daftar_pegawai';

    protected $fillable = [
        'name',
        'user_id',
        'email',
        'username',
        'employee_id',
        'role_name',
        'avatar'
    ];
}

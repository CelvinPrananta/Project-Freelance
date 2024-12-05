<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataSatuan extends Model
{
    use HasFactory;

    protected $table = 'data_satuan';
    protected $fillable = ['id','kode_barang', 'nama_barang','created_at','updated_at'];
}
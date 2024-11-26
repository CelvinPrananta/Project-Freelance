<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'type',
        'notifiable_type',
        'notifiable_id',
        'data',
        'read_at',
        'created_at',
        'updated_at'
    ];
    protected $casts = [
        'id' => 'string',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'notifiable_id');
    }
}

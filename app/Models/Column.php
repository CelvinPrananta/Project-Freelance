<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Column extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'board_id',
        'previous_id',
        'next_id',
        'position',
        'created_at',
        'deleted_at'
    ];

    protected $dates = ['deleted_at'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'board_id',
        'previous_id',
        'next_id',
        'created_at',
        'updated_at',
    ];

    public function board()
    {
        return $this->belongsTo(Board::class);
    }

    public function cards()
    {
        return $this->hasMany(Card::class)
            ->orderBy("previous_id");
    }

    public function previousColumn()
    {
        return $this->belongsTo(Column::class, 'previous_id');
    }

    public function nextColumn()
    {
        return $this->belongsTo(Column::class, 'next_id');
    }

    public function card()
    {
        return $this->hasMany(Card::class);
    }
}
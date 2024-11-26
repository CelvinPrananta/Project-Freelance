<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TitleChecklists extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'cards_id',
        'percentage',
        'position',
        'created_at',
        'deleted_at'
    ];

    protected $dates = ['deleted_at'];

    public function checklists()
    {
        return $this->hasMany(Checklists::class, 'title_checklists_id');
    }

    public function cards()
    {
        return $this->belongsTo(Card::class);
    }
}
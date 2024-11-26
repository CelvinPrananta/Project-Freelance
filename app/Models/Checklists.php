<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Checklists extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'is_active',
        'title_checklists_id',
        'position',
        'created_at',
        'deleted_at'
    ];

    protected $dates = ['deleted_at'];

    public function titleChecklists()
    {
        return $this->belongsTo(TitleChecklists::class);
    }

    public function titleChecklist()
    {
        return $this->belongsTo(TitleChecklists::class, 'title_checklists_id');
    }
}
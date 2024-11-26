<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Card extends Model
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
        'description',
        'pattern',
        'column_id',
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
        'previous_id',
        'next_id',
        'created_at',
        'updated_at',
    ];

    public function board()
    {
        return $this->belongsTo(Column::class);
    }

    public function previousCard()
    {
        return $this->belongsTo(Card::class, 'previous_id');
    }

    public function nextCard()
    {
        return $this->belongsTo(Card::class, 'next_id');
    }

    public function history()
    {
        return $this->hasMany(CardHistory::class);
    }

    public function column()
    {
        return $this->belongsTo(Column::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, "card_user", "card_id", "user_id");
    }

    public function titleChecklists()
    {
        return $this->hasMany(TitleChecklists::class, 'cards_id');
    }
}

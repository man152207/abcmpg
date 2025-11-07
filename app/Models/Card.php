<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'card_number',
        'USD',
    ];

    public function clients()
    {
        return $this->hasMany(Client::class, 'account', 'card_number');
    }
}

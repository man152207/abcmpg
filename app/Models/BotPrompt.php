<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BotPrompt extends Model
{
    protected $fillable = [
        'key',
        'prompt_text',
        'is_active',
    ];
}

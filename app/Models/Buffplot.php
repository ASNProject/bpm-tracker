<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Buffplot extends Model
{
    protected $fillable = [
        'user_id',
        'idx',
        'value',
    ];
}

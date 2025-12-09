<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bpm extends Model
{
    /**
     * fillable
     * 
     * @var array
     */
    protected $fillable = [
        'user_id',
        'record_id',
        'age',
        'gender',
        'status',
        'bpm',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $table = 'game';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name', 'other', 'created_at','updated_at'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logs extends Model
{
    use HasFactory;

    protected $table = 't_log';
	public $timestamps = true;

    protected $fillable = [
        'user', 'activity', 'stat', 'created_at', 'updated_at'
    ];
}

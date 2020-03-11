<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    protected $table = 'song';
    protected $fillable = ['url'];
    public $timestamps = false;
}

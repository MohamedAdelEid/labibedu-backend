<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    protected $fillable = [
        'name',
        'address',
    ];
}
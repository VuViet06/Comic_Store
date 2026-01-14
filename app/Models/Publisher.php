<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Publisher extends Model
{

    protected $fillable = [
        'name',
        'slug',
        'country',
    ];

   
    public function comics()
    {
        return $this->hasMany(Comic::class);
    }
}



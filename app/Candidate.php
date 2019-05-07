<?php

namespace App;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    protected $fillable = ['name', 'gender', 'email', 'phone', 'file', 'status'];

    public function getFileAttribute($value)
    {
        return asset('storage/pdf/'.$value);
    }

    public function getStatusAttribute($value)
    {
        return Str::Title($value);
    }
}

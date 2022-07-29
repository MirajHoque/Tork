<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    //Mass assignment
    protected $fillable = [
        'name',
        'mobile',
        'email',
        'address',
        'thumbnail_image',
    ];

    Public function multipleImage(){
        return $this->hasMany(MultipleImage::class);
    }
}

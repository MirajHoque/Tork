<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MultipleImage extends Model
{
    use HasFactory;

    //Mass Assignment
    protected $fillable = [
        'employee_id',
        'image',
    ];

    Public function employee(){
        return $this->hasMany(Employee::class);
    }
}

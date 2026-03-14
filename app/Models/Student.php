<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $table = 'studentdetails';
    protected $primaryKey = 'AutoID';

    protected $fillable = [
        'fName',
        'lName',
        'Age',
        'mobileNo',
        'Address',
        'Active',
        'studentemail',
        'studentpic'
    ];

    protected $casts = [
        'Age' => 'integer',
        'Active' => 'boolean'
    ];
}
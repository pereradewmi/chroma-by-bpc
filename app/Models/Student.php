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
        'studentpic',
        'guardian_name',
        'guardian_phone',
        'class_ids'
    ];

    protected $casts = [
        'Age' => 'integer',
        'Active' => 'integer'
    ];
}
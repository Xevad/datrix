<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;
    protected $fillable =[
        'co_name',
        'email',
        'cust_name',
        'contact',
        'subject',
        'details',
        'slug',
        'key',
        'status',
        'progress'


    ];
}

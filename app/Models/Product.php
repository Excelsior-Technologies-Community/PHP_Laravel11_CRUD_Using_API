<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    // Mass assignable fields
    protected $fillable = [
        'name',
        'detail',
        'status',
        'created_by',
        'updated_by'
    ];

    // Soft delete column
    protected $dates = ['deleted_at'];
}

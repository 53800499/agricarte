<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'farmer_id',
        'image',
    ];

    public function farmer()
    {
        return $this->belongsTo(User::class, 'farmer_id');
    }
}
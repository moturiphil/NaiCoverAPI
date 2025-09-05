<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;
    // Define fillable attributes
    protected $guarded = [];

    // Define relationship to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Define relationship to Policies
    public function policies()
    {
        return $this->hasMany(Policy::class);
    }
}
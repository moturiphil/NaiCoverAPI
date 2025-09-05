<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
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
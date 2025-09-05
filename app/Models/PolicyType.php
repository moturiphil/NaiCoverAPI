<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PolicyType extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * 
     * Using guarded as an empty array allows all fields to be mass assignable,
     * but it's safer to explicitly define $fillable for better security.
     */
    protected $guarded = [];

    /**
     * Relationship: A PolicyType has many PolicyAttributes
     */
    public function attributes()
    {
        return $this->hasMany(PolicyAttribute::class, 'policy_type_id');
    }

    /**
     * Relationship: A PolicyType has many Policies
     */
    public function policies()
    {
        return $this->hasMany(Policy::class, 'policy_type_id');
    }
}

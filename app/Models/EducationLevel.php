<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class EducationLevel extends Model
{
    use HasFactory;
    // link to education_levels table
    protected $table = 'education_levels';
    protected $fillable = ['name'];

    // education level belongs to one agent
    public function agents()
    {
        return $this->hasMany(Agent::class);
    }
}

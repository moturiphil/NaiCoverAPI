<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExperienceLevel;

class ExperienceController extends Controller
{
    //index
    public function index()
    {
        // get only id and the level
        $experienceLevels = ExperienceLevel::select('id', 'name')->get();
        return response()->json([
            'experience_levels' => $experienceLevels,
        ]);
    }
}

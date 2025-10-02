<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EducationLevel;

class EducationController extends Controller
{
    //index
    public function index()
    {
        // get only id and the level
        $educationLevels = EducationLevel::select('id', 'name')->get();
        return response()->json([
            'education_levels' => $educationLevels,
        ]);
    }
}

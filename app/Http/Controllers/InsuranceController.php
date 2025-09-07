<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\InsuranceResource;
use App\Models\Insurance;

class InsuranceController extends Controller
{
    public function index()
    {
        $insuranceProviders = Insurance::all();
        return InsuranceResource::collection($insuranceProviders);
    }
}

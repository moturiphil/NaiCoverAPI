<?php

namespace App\Http\Controllers;

use App\Http\Resources\PolicyResource;
use App\Models\Policy;

class PolicyController extends Controller
{
    //
    public function index()
    {
        $policies = Policy::all();

        return PolicyResource::collection($policies);
    }
}

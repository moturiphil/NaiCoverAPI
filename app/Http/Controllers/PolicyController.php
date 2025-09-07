<?php

namespace App\Http\Controllers;

use App\Models\Policy;
use Illuminate\Http\Request;
use App\Http\Resources\PolicyResource;

class PolicyController extends Controller
{
    //
    public function index()
    {
        $policies = Policy::all();
        return PolicyResource::collection($policies);
    }

}

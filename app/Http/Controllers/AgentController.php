<?php

namespace App\Http\Controllers;

use App\Http\Resources\AgentResource;
use App\Models\Agent;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    //
    public function index()
    {
        $agents = Agent::all();

        return AgentResource::collection($agents);
    }

    public function show(Agent $agent)
    {
        return new AgentResource($agent);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            // Add other validation rules
        ]);

        $agent = Agent::create($validated);

        return new AgentResource($agent);
    }

    public function update(Request $request, Agent $agent)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            // Add other validation rules
        ]);

        $agent->update($validated);

        return new AgentResource($agent);
    }

    public function destroy(Agent $agent)
    {
        $agent->delete();

        return response()->json(['message' => 'Agent deleted successfully']);
    }
}

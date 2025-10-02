<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Notifications\AgentUserEmailVefifyNotification;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone_no' => 'required|string|max:15|unique:users,phone_number',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'phone_number' => $request->phone_no,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('authToken')->accessToken;

        return response()->json([
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid login details'
            ], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $token = $user->createToken('authToken')->accessToken;

        return response()->json([
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    // Agent Registration Method
    public function agent_registration(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:15|unique:users,phone_number',
            'id_no' => 'required|string|max:50|unique:agents,id_number',
            'education_level_id' => 'required|exists:education_levels,id',
            'area_of_operation' => 'nullable|string|max:255',
            'experience_level_id' => 'required|exists:experience_levels,id',

            // Documents
            'idDocument' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'passportPhoto' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'cv' => 'nullable|file|mimes:pdf,doc,docx|max:4096',
            'clearanceCertificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'proficiencyCertificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Handle file uploads first
        $documents = [];
        if ($request->hasFile('id_document')) {
            $documents['id_document'] = $request->file('id_document')->store('documents/id', 'public');
        }
        if ($request->hasFile('passport_photo')) {
            $documents['passport_photo'] = $request->file('passport_photo')->store('documents/passport', 'public');
        }
        if ($request->hasFile('cv')) {
            $documents['cv'] = $request->file('cv')->store('documents/cv', 'public');
        }
        if ($request->hasFile('clearance_certificate')) {
            $documents['clearance_certificate'] = $request->file('clearance_certificate')->store('documents/clearance', 'public');
        }
        if ($request->hasFile('proficiency_certificate')) {
            $documents['proficiency_certificate'] = $request->file('proficiency_certificate')->store('documents/proficiency', 'public');
        }

        try {
            DB::beginTransaction();

            // Create user
            $user = User::create([
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'phone_number' => $request->phone,
                'email' => $request->email,
                'password' => Hash::make('defaultPassword123'), // temporary password
                'role' => 'agent',
            ]);

            // Create agent profile
            $agent = Agent::create([
                'user_id' => $user->id,
                'id_number' => $request->id_no,
                'education_level_id' => $request->education_level_id,
                'area_of_operation' => $request->area_of_operation,
                'experience_level_id' => $request->experience_level_id,
                'id_path' => $documents['id_document'] ?? null,
                'passport_photo_path' => $documents['passport_photo'] ?? null,
                'diploma_certificate_path' => $documents['diploma_certificate'] ?? null,
                'degree_certificate_path' => $documents['degree_certificate'] ?? null,
                'cv_path' => $documents['cv'] ?? null,
                'police_clearance_path' => $documents['clearance_certificate'] ?? null,
                'ira_certificate' => $documents['proficiency_certificate'] ?? null,
            ]);

            DB::commit();

            // Mail::to($user->email)->send(new AgentUserEmailVefifyNotification($user));

            return response()->json([
                'user' => $user,
                'agent' => $agent,
                'documents' => $documents,
                'message'=> 'Verification email sent. Please check your inbox to verify your email before registration.'
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Registration failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}

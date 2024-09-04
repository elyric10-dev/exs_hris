<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

use Carbon\Carbon;
use App\Models\User;
use App\Models\PersonalInformation;
use App\Models\RefreshToken;


class AuthenticationController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'user_role_id' => 'required|max:2',
            'manager_id' => 'nullable|max:2',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'contact_number' => 'required|string|max:20',
            'password' => 'required|string|min:8',
        ]);

        $creator_id = User::where('id', Auth::user()->id)->where('user_role_id', 1)->orWhere('user_role_id', 2)->first()->id;

        $user = User::create([
            'user_role_id' => $request->user_role_id,
            'manager_id' => $creator_id,
            'created_by_user_id' => $creator_id,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'email' => $request->email,
            // 'contact_number' => $request->contact_number,
        ]);

        $personalInformation = PersonalInformation::create([
            'user_id' => $user->id,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'contact_number' => $request->contact_number,
        ]);

        // Generate access token
        $accessToken = $user->createToken('access_token')->plainTextToken;

        // Generate refresh token
        $refreshToken = Str::random(60);

        // Store token with an expiration date of 2 weeks
        $user->refreshTokens()->create([
            'token' => hash('sha256', $refreshToken),
            'expires_at' => Carbon::now()->addWeeks(2),
        ]);

        $user->logs()->create([
            'action' => 'register',
            'details' => 'User registered successfully',
        ]);

        return response()->json([
            'user' => $user,
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'token_type' => 'Bearer'
        ], 201);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete(); // Revoke all tokens
        $request->user()->refreshTokens()->delete(); // Delete all refresh tokens

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'user_account' => 'required|string|max:100',
            'password' => 'required',
        ]);



        $logged_in = $this->attempt($request->user_account, $request->password);

        if ($logged_in->is_logged_in) {
            $user_id = $logged_in->data->id;

            $user = User::find($user_id);

            // Generate access token
            $accessToken = $user->createToken('access_token')->plainTextToken;

            // Generate refresh token
            $refreshToken = Str::random(60);

            // Store token with an expiration date of 2 weeks
            $user->refreshTokens()->create([
                'token' => hash('sha256', $refreshToken),
                'expires_at' => Carbon::now()->addWeeks(2),
            ]);

            return response()->json([
                'data' => $logged_in,
                'access_token' => $accessToken,
                'refresh_token' => $refreshToken,
                'token_type' => 'Bearer'
            ], 201);
        } else {
            return $logged_in;
        }
    }

    public function refresh(Request $request)
    {
        $request->validate([
            'refresh_token' => 'required|string'
        ]);

        $hashedToken = hash('sha256', $request->refresh_token);
        $refreshToken = RefreshToken::where('token', $hashedToken)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$refreshToken) {
            return response()->json([
                'error' => 'Invalid or expired refresh token'
            ], 401);
        }

        $user = $refreshToken->user;

        // Revoke the old refresh token
        $refreshToken->delete();

        // Generate new access and refresh tokens
        $accessToken = $user->createToken('auth_token')->plainTextToken;
        $newRefreshToken = Str::random(60);

        // Store the new refresh token in the database
        $user->refreshTokens()->create([
            'token' => hash('sha256', $newRefreshToken),
            'expires_at' => Carbon::now()->addWeeks(2),
        ]);

        return response()->json([
            'access_token' => $accessToken,
            'refresh_token' => $newRefreshToken,
            'token_type' => 'Bearer',
        ]);
    }


    public function attempt($user_account, $password)
    {
        $user = $this->findUser($user_account);

        if (!$user)
            return $this->jsonResponse(false);

        return $this->jsonResponse(
            Hash::check($password, $user->password),
            $user
        );
    }

    private function findUser($user_account)
    {
        return User::where('username', $user_account)
            ->orWhere('email', $user_account)->first()
            ?? PersonalInformation::where('contact_number', $user_account)->first()->user;
    }

    private function jsonResponse($is_logged_in, $data = null)
    {

        $response = ['is_logged_in' => $is_logged_in];
        $response[$is_logged_in ? 'data' : 'message'] = $data;

        if (!$is_logged_in)
            $response['message'] = 'Invalid username or password';

        return response()->json($response)->getData();

    }
}

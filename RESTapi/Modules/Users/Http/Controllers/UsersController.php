<?php

namespace Modules\Users\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\HasApiTokens;
use Modules\Users\Entities\Users;

class UsersController extends Controller
{

    /**
     * Log in a user and issue a token.
     */
    public function loginUser(Request $request)
    {
        if (in_array(HasApiTokens::class, class_uses_recursive(Users::class))) {
            $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);

            if (!Auth::attempt($request->only('email', 'password'))) {
                return response()->json(['message' => 'Invalid credentials'], 401);
            }

            $user = Users::where('email',$request->email)->first();
            if ($user) {
                $token = $user->createToken('Token Name',["*"],now()->addDay())->plainTextToken;

                return response()->json([
                    'message' => 'Login successful',
                    'token' => $token,
                    'user' => $user,
                ], 200);
            } else {
                return response()->json(['message' => 'User not found'], 404);
            }
        } else {
            return response()->json(['message' => 'HasApiTokens is not applied'], 500);
        }
    }

    public function registerUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => [
                'required',
                'string',
                'min:8',              // Minimum of 8 characters
                'regex:/[A-Z]/',      // At least one uppercase letter
                'regex:/[a-z]/',      // At least one lowercase letter
                'regex:/[0-9]/',      // At least one number
                'regex:/[@$!%*?&]/',  // At least one special character
            ],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = Users::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'User registered successfully', 'user' => $user], 201);
    }
}

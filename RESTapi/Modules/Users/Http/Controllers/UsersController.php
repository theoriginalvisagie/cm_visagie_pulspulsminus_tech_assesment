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
     * @OA\Post(
     *     path="/api/login-user",
     *     tags={"Authentication"},
     *     summary="Login user and generate access token",
     *     description="Logs in a user and returns a Sanctum access token along with user details.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="mail@mailssss.com"),
     *             @OA\Property(property="password", type="string", format="password", example="Camel1!ghts")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Login successful"),
     *             @OA\Property(property="token", type="string", example="10|6FbGXDZWuX2y39vM31uXVtGsBux45ihGmdqXTXFW14f96f11"),
     *             @OA\Property(property="user", type="object",
     *                 @OA\Property(property="id", type="integer", example=2),
     *                 @OA\Property(property="name", type="string", example="John"),
     *                 @OA\Property(property="surname", type="string", example="Doe"),
     *                 @OA\Property(property="email", type="string", example="mail@mailssss.com"),
     *                 @OA\Property(property="email_verified_at", type="string", format="nullable", example=null),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-03-24T12:05:17.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-03-24T13:22:03.000000Z"),
     *                 @OA\Property(property="deleted_at", type="string", format="nullable", example=null)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Invalid credentials")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="User not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="HasApiTokens not applied",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="HasApiTokens is not applied")
     *         )
     *     )
     * )
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

    /**
     * @OA\Post(
     *     path="/api/register-user",
     *     tags={"Authentication"},
     *     summary="Register a new user",
     *     description="Registers a new user and returns the created user data.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","surname","email","password"},
     *             @OA\Property(property="name", type="string", example="John"),
     *             @OA\Property(property="surname", type="string", example="Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="mail@mailssss.com"),
     *             @OA\Property(property="password", type="string", format="password", example="Camel1!ghts")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User registered successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User registered successfully"),
     *             @OA\Property(property="user", type="object",
     *                 @OA\Property(property="id", type="integer", example=4),
     *                 @OA\Property(property="name", type="string", example="John"),
     *                 @OA\Property(property="surname", type="string", example="Doe"),
     *                 @OA\Property(property="email", type="string", example="mail@mailssssss.com"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-03-24T14:15:25.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2025-03-24T14:15:25.000000Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation errors",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="email", type="array", @OA\Items(type="string", example="The email has already been taken."))
     *             )
     *         )
     *     )
     * )
     */
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
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $validator->errors()
            ], 422);
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

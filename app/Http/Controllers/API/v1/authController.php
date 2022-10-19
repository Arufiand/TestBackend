<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class authController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }
    
    public function login(Request $req)
    {
        $data_login = 'email';
        $validator = Validator::make(
            $req->all(),
            [
                $data_login => ['required'],
                'password' => ['required']
            ]
        );
        if ($validator->fails()) {
            return response()->json(
                [
                    'Status_Request' => false,
                    'Message' => $validator->errors()
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }
        try {
            $user = User::where($data_login, $req->$data_login)->first();
            if (!$user || !Hash::check($req->password, $user->password)) {
                return response()->json([
                    'Status' => false,
                    'Message' => "Wrong " . $data_login . " & Password Combination"
                ], Response::HTTP_UNAUTHORIZED);
            }
            $token = $user->createToken('Auth-Token', ['server:update'])->plainTextToken;
            $view = [
                "Username" => $user->name,
                "Email" => $user->email,
                "Token" => $token
            ];
            return response()->json([
                'Message' => "Login Success",
                'Status' => True,
                'Data' => $view,
            ], Response::HTTP_OK);
        } catch (QueryException $e) {
            return response()->json(
                [
                    'Status' => false,
                    'Message' => "Failed " . $e->errorInfo
                ],
                Response::HTTP_FORBIDDEN
            );
        }
    }

    public function register(Request $request)
    {

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'username' => 'required|string|max:255|unique:users',
            'phoneNumber' => 'required|string|min:8'
        ]);
        if ($validatedData) {
            try {
                $user = User::create([
                    'name' => $validatedData['name'],
                    'email' => $validatedData['email'],
                    'password' => Hash::make($validatedData['password']),
                    'username' => $validatedData['username'],
                    'fullName' => $validatedData['fullName'],
                    'phoneNumber' => $validatedData['phoneNumber'],
                ]);

                return response()->json([
                    'User_data' => $user,
                    'Status' => True,
                ], Response::HTTP_OK);
            } catch (QueryException $e) {
                return response()->json(
                    [
                        'Status' => false,
                        'Message' => "Failed " . $e->errorInfo
                    ],
                    Response::HTTP_NOT_ACCEPTABLE
                );
            }
        } else {
            return response()->json(
                [
                    'Status' => false,
                    'Message' => "Failed "
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }


    public function logout(Request $request)
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();
        // dd($user); /// Laravel Data Dump

        return response()->json([
            'Message' => "Logout Success",
            'Status' => True
        ], Response::HTTP_OK);
    }

    // 
    /**
     * JWT Method
     *
     * 
     */

    // public function login(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|string|email',
    //         'password' => 'required|string',
    //     ]);
    //     $credentials = $request->only('email', 'password');

    //     $token = Auth::attempt($credentials);
    //     if (!$token) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Unauthorized',
    //         ], 401);
    //     }

    //     $user = Auth::user();
    //     return response()->json([
    //             'status' => 'success',
    //             'user' => $user,
    //             'authorisation' => [
    //                 'token' => $token,
    //                 'type' => 'bearer',
    //             ]
    //         ]);

    // }

    // public function register(Request $request){
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|string|email|max:255|unique:users',
    //         'password' => 'required|string|min:6',
    //     ]);

    //     $user = User::create([
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'password' => Hash::make($request->password),
    //     ]);

    //     $token = Auth::login($user);
    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'User created successfully',
    //         'user' => $user,
    //         'authorisation' => [
    //             'token' => $token,
    //             'type' => 'bearer',
    //         ]
    //     ]);
    // }

    // public function logout()
    // {
    //     Auth::logout();
    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'Successfully logged out',
    //     ]);
    // }

    // public function refresh()
    // {
    //     return response()->json([
    //         'status' => 'success',
    //         'user' => Auth::user(),
    //         'authorisation' => [
    //             'token' => Auth::refresh(),
    //             'type' => 'bearer',
    //         ]
    //     ]);
    // }

}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;

class Logincontroller extends Controller
{
    public function register(Request $request)
    {
        //Validate data
        $data = $request->only('name', 'email', 'password');
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|max:50',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is valid, create new user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        //User created, return success response
        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data' => $user,
        ], Response::HTTP_OK);
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->only('username', 'password');

        $validator = Validator::make($credentials, [
            'username' => 'required|string',
            'password' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        try {
            // Ambil user dulu
            $user = User::where('username', $request->username)->first();

            if (!$user || !\Hash::check($request->password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Login credentials are invalid',
                ], 400);
            }

            // Mapping level ke angka
            $roleMap = [
                'superadmin' => 0,
                'admin' => 1,
                'editor' => 2,
                'user' => 3,
                'guest' => 4,
            ];

            $role = $user->level;
            $level = $roleMap[$role] ?? 99;

            // Masukkan level & role ke dalam token
            $customClaims = [
                'role' => $role,
                'level' => $level,
            ];

            $token = JWTAuth::claims($customClaims)->attempt($credentials);
            $user->update([
                'token' => $token,
                'statuslogin' => '1',
            ]);

            return response()->json([
                'success' => true,
                // 'userData' => [$this->manipulateData($user)],
                'accessToken' => $token,
                'role' => $role,
                'level' => $level,
            ]);
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Could not create token.',
            ], 500);
        }
    }

    private function manupulatedata($data)
    {

        //     id: 1,
        //     role: 'admin',
        //     password: 'admin',
        //     fullName: 'John Doe',
        //     username: 'johndoe',
        //     email: 'admin@mnc.com'

        return [
            'id' => $data['id_user'],
            'role' => $data['level'],
            'password' => $data['password'],
            'fullName' => $data['nama_lengkap'],
            'username' => $data['username'],
            'email' => $data['email'],
        ];
    }

    public function logout(Request $request)
    {
        //valid credential
        $validator = Validator::make($request->only('token'), [
            'token' => 'required',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is validated, do logout
        try {
            JWTAuth::invalidate($request->token);

            return response()->json([
                'success' => true,
                'message' => 'User has been logged out',
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, user cannot be logged out',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function get_user(Request $request)
    {
        if ($request->token == '') {
            return response()->json(['token required' => true]);
        }
        $decodedToken = User::where('token', $request->token)->get();
        // dd($decodedToken->first());
        if ($decodedToken->count() > 0) {
            return response()->json(['userData' => $this->manupulatedata($decodedToken->first())]);
        } else {
            return response()->json(['userData' => null], 404);
        }
    }
}

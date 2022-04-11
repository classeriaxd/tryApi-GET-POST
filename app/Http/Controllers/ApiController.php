<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

use App\Models\User;

use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{
    /**
     * Function to get the user details using user ID
     * Endpoint: http://127.0.0.1:8000/api/userDetails/{userID}
     */ 
    public function getUserDetails($userID)
    {
        $user = User::where('user_id', $userID)
            ->firstOrFail();
        return $user;
    }

    /**
     * Function to test whether user credentials is right through POST
     * Endpoint: http://127.0.0.1:8000/api/login
     * Form Data:
     *      email
     *      password
     */ 
    public function checkIfCredentialsMatch(Request $request)
    {
        $data = array();
        
        // Validate form, returns error 422 if validation fails
        $validation = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => 'required',
        ]);
        
        if ($validation->fails()) 
        {
            $data = [
                'status' => 422,
                'contents' => [
                    'message' => 'Validation Failed.', 
                    'errors' => $validation->errors(),
                ]
            ];
            return response()->json($data, $data['status']);
        }

        $credentials = $validation->validated();

        // Attempt to login user
        if (Auth::attempt($credentials)) 
        {
            Auth::loginUsingId(
                User::where('email', $credentials['email'])
                    ->value('user_id')
            );

            $data = [
                'status' => 200,
                'contents' => [
                    'message' => 'OK', 
                ]
            ];
        }
        else
            $data = [
                'status' => 400,
                'contents' => [
                    'message' => 'Incorrect Email or Password', 
                ]
            ]; 
        return response()->json($data, $data['status']);  
    }

    /**
     * Function to get User's profile picture metadata through GET
     * Endpoint: http://127.0.0.1:8000/api/userDetails/1/getProfilePictureMetadata
     */ 
    public function getProfilePictureMetadata($userID)
    {  
        $data = array();

        $user = User::where('user_id', $userID)->first();

        if ($user === NULL)
        {
            $data = [
                'status' => 404,
                'contents' => [
                    'message' => 'User not found.', 
                ]
            ];
            return response()->json($data, $data['status']);
        }
            
        // Get user's profile picture path and prepend public directory
        $userProfilePicture = 'public' . $user->profile_picture;
        
        if (Storage::exists($userProfilePicture)) 
        {
            $data = [
                'status' => 200,
                'contents' => [
                    'message' => 'OK', 
                    'fileName'=> basename($userProfilePicture),
                    'url' => Storage::path($userProfilePicture),
                    'size' => Storage::size($userProfilePicture),
                    'lastModified' => Storage::lastModified($userProfilePicture),
                    'width' => getimagesize(Storage::path($userProfilePicture))[0],
                    'height' => getimagesize(Storage::path($userProfilePicture))[1],
                ]
            ];
        }
        else
            $data = [
                'status' => 404,
                'contents' => [
                    'message' => 'Resource not found.', 
                ]
            ];

        return response()->json($data, $data['status']);
    }

    /**
     * Function to update User Details through PUT
     * Endpoint: http://127.0.0.1:8000/api/userDetails/1/updateUserDetails
     * x-www-form-urlencoded:
     *      name
     *      email
     *      password
     */
    public function updateUserDetails(Request $request, $userID)
    {
        $data = array();
        
        $user = User::where('user_id', $userID)->first();

        $validation = Validator::make($request->all(), [
            'name' => 'required|string|min:2',
            'email' => 'required|email|unique:App\Models\User,email,' . $user->user_id,
            'password' => ['required', Password::min(6)],
        ]);

        // Check if validation fails
        if ($validation->fails()) 
        {
            $data = [
                'status' => 422,
                'contents' => [
                    'message' => 'Validation Failed.', 
                    'errors' => $validation->errors(),
                ]
            ];
            return response()->json($data, $data['status']);
        }

        // Check if user does not exist
        if ($user === NULL) 
        {
            $data = [
                'status' => 404,
                'contents' => [
                    'message' => 'User not found.', 
                ]
            ];
            return response()->json($data, $data['status']);
        }

        $userData = $validation->validated();

        try 
        {
            User::where('user_id', $userID)->update([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make($userData['password']),
            ]);

            $data = [
                'status' => 200,
                'contents' => [
                    'message' => 'OK', 
                ]
            ];
            return response()->json($data, $data['status']);
        } 
        catch(\Illuminate\Database\QueryException $e)
        {
            $data = [
                'status' => 500,
                'contents' => [
                    'message' => 'User data failed to update.', 
                ]
            ];
            return response()->json($data, $data['status']);
        }
    }

    /**
     * Function to register a User through POST
     * Endpoint: http://127.0.0.1:8000/api/register
     * Form Data:
     *      name
     *      email
     *      password
     *      password_confirmation
     */
    public function registerUser(Request $request)
    {
        $data = array();

        $validation = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', 'min:2'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Check if validation fails
        if ($validation->fails()) 
        {
            $data = [
                'status' => 422,
                'contents' => [
                    'message' => 'Validation Failed.', 
                    'errors' => $validation->errors(),
                ]
            ];
            return response()->json($data, $data['status']);
        }

        $userData = $validation->validated();

        try 
        {
            User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make($userData['password'])
            ]);

            $data = [
                'status' => 200,
                'contents' => [
                    'message' => 'OK', 
                ]
            ];
            return response()->json($data, $data['status']);
        } 
        catch(\Illuminate\Database\QueryException $e)
        {
            $data = [
                'status' => 500,
                'contents' => [
                    'message' => 'User data failed to create.', 
                ]
            ];
            return response()->json($data, $data['status']);
        }
    }
}

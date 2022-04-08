<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
     * Function to get the user details using user ID
     * Endpoint: http://127.0.0.1:8000/api/login
     * Form Data:
     *      email
     *      password
     */ 
    public function loginThroughApi(Request $request)
    {
        // Validate form, returns error 422 if validation fails
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => 'required',
        ]);

        // Attempt to login user
        if (Auth::attempt($credentials)) 
        {
            $userId = User::where('email', $request->input('email'))
                    ->value('user_id');
            Auth::loginUsingId($userId);
            return response()->make(
                'Successful Login thru API eheh',
                200,
            );
        }
        else
            return response()->make(
                'Email or Password is incorrect',
                403,
            );
        
    }

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
            
        // Get user's profile picture path and append public directory
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
}

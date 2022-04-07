<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;

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
}

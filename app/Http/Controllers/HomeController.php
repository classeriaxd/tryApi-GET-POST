<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Str;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        //dd($user);
        return view('home', compact('user'));
    }

    public function updateProfilePicture()
    {
        $user = Auth::user();
        //dd($user);
        return view('updateProfilePicture', compact('user'));
    }

    public function storeProfilePicture(Request $request)
    {
        $request->validate([
            'profile_picture' => 'required|image|max:2048'
        ]);

        $finalPath = '/public/profile_pictures/';
        $dbPath = '/profile_pictures';
        $folder = '/' . uniqid() . '-' . now()->timestamp;

        // If user has no profile picture
        if (Auth::user()->profile_picture === NULL) 
        {
            $fileName = uniqid() . '-' . now()->timestamp . '.' . $request->file('profile_picture')->getClientOriginalExtension();

            $filePath = $request->file('profile_picture')->storeAs($finalPath . $folder , $fileName);

            User::where('user_id', Auth::user()->user_id)->update([
                'profile_picture' => $dbPath . $folder . '/' . $fileName,
            ]);
        }

        // If user has profile picture
        else if (Auth::user()->profile_picture !== NULL)
        {
            $fileName = uniqid() . '-' . now()->timestamp . '.' . $request->file('profile_picture')->getClientOriginalExtension();

            $filePath = $request->file('profile_picture')->storeAs($finalPath . $folder , $fileName);

            $userOldProfilePictureFolder = Str::of(User::where('user_id', Auth::user()->user_id)->value('profile_picture'))->dirname();

            // Delete Directory Contents then Delete Directory itself
            Storage::deleteDirectory('/public' . $userOldProfilePictureFolder, true);
            sleep(0.1);
            Storage::deleteDirectory('/public' . $userOldProfilePictureFolder);

            User::where('user_id', Auth::user()->user_id)->update([
                'profile_picture' => $dbPath . $folder . '/' . $fileName,
            ]);
        }

        return redirect()->action([HomeController::class, 'index']);
    }
}

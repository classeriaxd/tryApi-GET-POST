@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    <div class="row d-flex justify-content-center my-1">
                        <img src="@if($user->profile_picture == NULL) {{'/storage/profile_pictures/default/default_profile_picture.png'}} @else {{ '/storage' . $user->profile_picture }} @endif" style="max-height: 200px;max-width: 200px;">
                    </div>
                    <div class="row text-center">
                        <a href="{{route('updateProfilePicture')}}">
                            <button class="btn btn-primary">Update Profile Picture</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

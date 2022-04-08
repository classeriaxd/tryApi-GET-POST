@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Profile Picture') }}</div>

                <div class="card-body">
                    <h6>Current Profile Picture</h6>
                    <div class="row d-flex justify-content-center">
                        <img src="@if($user->profile_picture == NULL) {{'/storage/profile_pictures/default/default_profile_picture.png'}} @else {{ '/storage' . $user->profile_picture }} @endif" style="max-height: 200px;max-width: 200px;">
                    </div>
                    <div class="row">
                        <form method="POST" action="{{route('storeProfilePicture')}}" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <input class="form-control" type="file" name="profile_picture">
                            </div>
                            <div class="text-center">
                                <button class="btn btn-primary" type="submit">Update Profile Picture</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

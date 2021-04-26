@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-3 p-4">
            <img class="rounded-circle w-100 shadow" src="{{ $user->profile->profileImage() }}">
        </div>
        <div class="col-9 pt-4"> 
            <div class="d-flex justify-content-between align-items-baseline">
                <div class="d-flex align-items-center pb-3">
                    <div class="h3 mr-3">{{ $user->username }}</div>
                    @if(auth()->user()->id !== $user->id)
                        <follow-button user-id="{{ $user->id }}" follows="{{ $follows }}"></follow-button>
                    @endif
                </div>

                @can('update', $user->profile)
                    <a type="button" class="btn btn-outline-dark" href="/p/create">Add New Post</a>
                @endcan

            </div>

            @can('update', $user->profile)
                <a type="button" class="btn btn-outline-dark mb-2" href="/profile/{{ $user->id }}/edit">Edit Profile</a>
            @endcan

            <div class="d-flex">
                <div class="mr-5"><strong>{{ $postCount }}</strong> Posts</div>
                <div class="mr-5"><strong>{{ $followersCount }}</strong> Followers</div>
                <div class="mr-5"><strong>{{ $followingsCount }}</strong> Followings</div>
            </div>
            <h6 class="pt-4 font-weight-bold">{{ $user->profile->title }}</h6>
            <div>{{ $user->profile->description }}</div>
            <div><a href="{{ $user->profile->url }}">{{ $user->profile->url }}</a></div>
        </div>
    </div>

    <div class="row mt-5">
        @foreach($user->posts as $post)
        <div class="col-4 pb-4">
            <a href="/p/{{ $post->id }}">
                <img class="w-100 rounded shadow" src="/storage/{{$post->image}}">
            </a>
        </div>
        @endforeach
    </div>
</div>
@endsection

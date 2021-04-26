<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Facades\Image;

class ProfileController extends Controller
{
    // public function index($user)
    // {
    //     $user = User::findOrFail($user);
    //     return view('profiles.index', [
    //         'user' => $user,
    //     ]);
    // }

    public function index(User $user)
    {
        $follows = (auth()->user()) ? auth()->user()->following->contains($user->id) : false;
        $postCount = Cache::remember(
            'count.posts.' . $user->id, 
            now()->addSeconds(30), 
            function() use ($user) {
                $user->posts->count();
            });

        $followersCount = Cache::remember(
            'count.followers.' . $user->id, 
            now()->addSeconds(30), 
            function() use ($user) {
                $user->profile->followers->count();
            });

        $followingsCount = Cache::remember(
            'count.following.' . $user->id, 
            now()->addSeconds(30), 
            function() use ($user) {
                $user->following->count();
            });

        return view('profiles.index', compact('user', 'follows', 'postCount', 'followersCount', 'followingsCount'));
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user->profile);

        return view('profiles.edit', compact('user'));
    }

    public function update(User $user) {
        $this->authorize('update', $user->profile);
        
        $data = request()->validate([
            'title' => 'required',
            'description' => 'required',
            'url' => 'url',
            'image' => 'image'
        ]);

        if(request('image')) {
            $imagePath = request('image')->store('profiles', 'public');
            
            $image = Image::make(public_path("storage/{$imagePath}"))->fit(1000, 1000);
            $image->save();

            auth()->user()->profile->update([
                'title' => $data['title'],
                'description' => $data['description'],
                'url' => $data['url'],
                'image' => $imagePath
            ]);
        } else {
            auth()->user()->profile->update($data);
        }

        // if(request('image')) {
        //     $imagePath = request('image')->store('profiles', 'public');
            
        //     $image = Image::make(public_path("storage/{$imagePath}"))->fit(1000, 1000);
        //     $image->save();

        //     $imageArray = ['image', $imagePath];
        // } 

        // auth()->user()->profile->update([
        //     $data, 
        //     $imageArray ?? []
        // ]);

        return redirect("/profile/{$user->id}");
    }
}

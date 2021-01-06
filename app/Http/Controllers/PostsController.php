<?php

namespace App\Http\Controllers;

use App\Post;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;

class PostsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
        //ako user prati nečiji profil izlistaj postove navedenog profila inače generiraj do 3 slučajna posta sa slučajno odabranih profila
    {
        if (auth()->user()->following()->exists()){
            $users=auth()->user()->following()->pluck('profiles.user_id');

            $posts=Post::whereIn('user_id',$users)->with('user')->orderBy('created_at', 'DESC')->paginate(5);
        }
       else{
           $users=DB::table('users')->pluck('id');
           $posts=Post::whereIn('user_id',$users)->with('user')->inRandomOrder()->paginate(3);


       }

        return view ('posts.index', compact('posts'));

    }

    public function create()
    {
      return view('posts.create');
    }

    public function store(){

        $data=request()->validate([
            'caption'=> 'required',
            'image'=> 'required|image',
        ]);

        $imagePath= request('image')->store('uploads', 'public');

        $image= Image::make(public_path("storage/{$imagePath}"))->fit(1200,1200);
        $image->save();
        auth()->user()->posts()->create([
            'caption'=> $data['caption'],
            'image'=> $imagePath,
        ]);

        return redirect('/profile/' . auth()->user()->id);
    }

    public function show(\App\Post $post)
    {
        return view('posts.show', compact('post'));
    }
}

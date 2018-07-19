<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\User;
use App\Post;
use DB;

class UsersController extends Controller
{   

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth', ['except' => ['index','show']]);
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$posts= Post::all();
        //$posts = DB::select('select* FROM posts');
        //$post = Post::where('title','Post Two')->get();

       // $posts = Post::orderBy('title','desc')->take(2)->get();
       // $posts = Post::orderBy('title','desc')->get();

        if(auth()->user()->type == 'admin'){
            $users = User::orderBy('created_at','desc')->paginate(10);
            return view('userlist')->with('users', $users);
        }
        else{
            return redirect('/posts')->with('error', 'Unauthorized Page');
        }

    }
}

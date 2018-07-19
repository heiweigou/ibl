<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\User;
use App\Post;
use App\Rules\Ipinput;
use DB;

class PostsController extends Controller
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
            $posts = Post::orderBy('created_at','desc')->paginate(10);
            return view('posts.index')->with('posts', $posts);
        }
        else{
            $user_id = auth()->user()->id;
            $user = User::find($user_id);
            return view('posts.index')->with('posts',$user->posts);    
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)

    {
        include('C:\xampp\htdocs\sstool\app\Email.php');
        
        $this->validate($request, [
            //'name' =>'required',
            //'email' => 'required|email',
            'body' => ['required','max:5000',new IPinput],
            'cover_image' => 'image|nullable|max:1999'
        ]);

        //handle file upload
        if($request->hasFile('cover_image')){
            //get filename with the extension
            $filenameWithExt = $request->file('cover_image')->getClientOriginalName();
            //get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            //get just ext
            $extension = $request->file('cover_image')->getClientOriginalExtension();
            //filename to store
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            //upload image
            $path = $request->file('cover_image')->storeAs('public/cover_images', $fileNameToStore);
        }else{
            $fileNameToStore = 'noimage.jpg';
        }


        //create post
        $post = new Post;
        //$post->title = $request->input('title');
        $post->title = 'AdHoc Scan';
        $post->body = $request->input('body');
        $post->user_id = auth()->user()->id;
        $post->cover_image = $fileNameToStore;
        $post->save();

        return redirect('/posts')->with('success', 'Post Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {   
        $post = Post::find($id);
        return view('posts.show')->with('post',$post);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /*
    public function edit($id)
    {
        $post = Post::find($id);
        //check for correct user

        if(auth()->user()->type != 'admin'){
            return redirect('/posts')->with('error', 'Unauthorized Page');
        }

        return view('posts.edit')->with('post',$post);
    }
    */

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $user_id)
    {
        $this->validate($request, [
            //'name' =>'required',
            //'email' => 'required|email|distinct',
            'body' => ['required','max:5000',new IPinput],
            'cover_image' => 'image|nullable|max:1999'
        ]);

        //handle file upload
        if($request->hasFile('cover_image')){
            //get filename with the extension
            $filenameWithExt = $request->file('cover_image')->getClientOriginalName();
            //get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            //get just ext
            $extension = $request->file('cover_image')->getClientOriginalExtension();
            //filename to store
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            //upload image
            $path = $request->file('cover_image')->storeAs('public/cover_images', $fileNameToStore);
        }
        //find post
        $post = Post::find($id);
        $post->body = $request->input('body');
        if($request->hasFile('cover_image')){
            $post->cover_image = $fileNameToStore;
        }
        $post->save();

        return redirect('/posts')->with('success', 'Post Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /*
    public function destroy($id)
    {
        $post = Post::find($id);

        //check correct admin permission
        if(auth()->user()->type != 'admin'){
            return redirect('/posts')->with('error', 'Unauthorized Page');
        }

        
        if($post->cover_image !='noimage.jpg'){
            //delete image
            Storage::delete('public/cover_images/'.$post->cover_image);
        }
        
        $post->delete();

        return redirect('/posts')->with('success', 'Post Removed');
    }
    */
}

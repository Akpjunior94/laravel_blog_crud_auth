<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth'])->only(['store', 'destroy']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // there is a shortcut that can be used inplace of orderBy('created_at','desc') it is latest()
        $posts = Post::with(['user', 'likes'])->latest()->paginate(2); // This gets all the post from the database and paginates it.

        return view('pages.index', [
            'posts' => $posts
        ]);
    }

    public function show(Post $post)
    {
        return view('posts.show', [
            'post' => $post
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validate the form
        $this->validate($request, [
            'body'=>'required'
        ]);

        // create post
        $request->user()->posts()->create($request->only('body'));

        return back();

    }

    public function destroy(Post $post)
    {
        $post->delete();

        return back();
    }
}

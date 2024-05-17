<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostStoreRequest;
use App\Models\Post;
use Illuminate\Http\Request;

use Illuminate\Support\Str;

use Illuminate\Support\Facades\Storage;



class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {

    //     $posts = Post::latest()->paginate(5);
    //     return view('posts.index',compact('posts'))->with('i', (request()->input('page', 1) - 1) * 5);
    // }



    // public function index( $s_query = null)
    // {
    //     if ($s_query == '0') {
    //         // $s_query = null;
    //         $posts = Post::latest()->paginate(5);
    //      }else{
    //         // $posts = Post::latest()->paginate(5);
    //         $s_query = Post::filter($s_query)->paginate(5);
    //      }

    //     $posts = Post::latest()->paginate(5);
    //     return view('posts.index',compact('posts'))->with('i', (request()->input('page', 1) - 1) * 5);
    // }


    public function index(Request $request)
    {
        $s_query = $request->input('search');
        //    dd($posts);
        // dd($s_query);

        if ($s_query == '') {
            $posts = Post::latest()->paginate(5);
        } else {
            // dd($s_query);
            $posts = Post::where('name','like','%'.$s_query.'%')->orWhere('description','like','%'.$s_query.'%')->paginate(5);
        }



        return view('posts.index', compact('posts'))->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostStoreRequest $request, Post $post)
    {

        try {

            $post->pub_view = $request->pub_view == true ? '1':'0';

            $imageName = Str::random(32).".".$request->image->getClientOriginalExtension();

            // Create Post
            Post::create([
                'name' => $request->name,
                'image' => $imageName,
                'description' => $request->description,
                'pub_view'=> $request->pub_view,
            ]);



            // Save Image in Storage folder
            Storage::disk('public')->put($imageName, file_get_contents($request->image));

            // Return Json Response
            return response()->json([
                'message' => "Post successfully created."
            ],200);
            return redirect()->route('posts.index')->with('success', 'Post updated successfully');
        } catch (\Exception $e) {
            // Return Json Response
            return response()->json([
                'message' => "Something went really wrong!"
            ],500);

        }


    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return view('posts.show',compact('post'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        return view('posts.edit',compact('post'));
    }

    /**

    * Update the specified resource in storage.
    */

    public function update(Request $request, Post $post)
{
    $request->validate([
        'name' => 'required',
        'description' => 'required',
        'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);




    $input = $request->all();

    if ($request->hasFile('image')) {
        $destinationPath = 'storage';
        $image = $request->file('image');
        $imageName = date('YmdHis') . "." . $image->getClientOriginalExtension();
        $image->move($destinationPath, $imageName);
        $input['image'] = $imageName;

        // Delete the old image if it exists
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }
        } else {
            unset($input['image']);
        }

        $post->pub_view = $request->pub_view == true ? '1':'0';
        $post->update($input);

    return redirect()->route('posts.search')->with('success', 'Post updated successfully');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()->route('posts.index')->with('success','Post deleted successfully');
    }



}

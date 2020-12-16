<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Festival;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [];

        $posts = Post::with('getimages')->with('getCategory')->orderBy('id', 'DESC')->paginate(5);
        $categories = Category::orderBy('id', 'ASC')->get();
        $data['post'] = $posts;
        $data['category'] = $categories;

        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = [];
        $this->validate($request, [
            'category' => 'not_in:0|unique:posts'
        ]);

        $post = Post::create([
            'category' => $request['category']
        ]);

        if ($post) {
            $data['error'] = false;
            $data['message'] = "Post Create Successfully..!";
        } else {
            $data['error'] = true;
            $data['message'] = "Try Again..!";
        }
        return response()->json($data);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);

        if ($post->delete()) {
            $data['error'] = false;
            $data['message'] = "Post Deleted Successfully..!";
        } else {
            $data['error'] = true;
            $data['message'] = "Try Again..!";
        }
        return response()->json($data);
    }
    // get Festival Status Change

    public function changeStatus($id)
    {
        $post = Post::find($id);

        if ($post->status == 1) {
            $post->status = 0;
            $post->save();
            $data['error'] = false;
            $data['message'] = "Post Status Changed Successfully..!";
        } else {
            $post->status = 1;
            $post->save();
            $data['error'] = false;
            $data['message'] = "Post Status Changed Successfully..!";
        }
        return response()->json($data);
    }
}

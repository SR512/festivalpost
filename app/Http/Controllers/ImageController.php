<?php

namespace App\Http\Controllers;

use App\Models\Festival;
use App\Models\Post;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $imageName = time() . '.' . $request->file->getClientOriginalExtension();


        if ($request->type == 'festival') {
            $request->file->move(public_path('festival'), $imageName);
            $uploadimage = \App\Models\Image::create([
                'festival_id' => $request->id,
                'name' => $imageName,
            ]);
        } else {
            $request->file->move(public_path('post'), $imageName);
            $uploadimage = \App\Models\Image::create([
                'post_id' => $request->id,
                'name' => $imageName,
            ]);
        }

        if ($uploadimage) {
            $listimage = Post::with('getimages')->find($request->id);
        }

        return response()->json(['success' => 'You have successfully upload file.']);
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
        //
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\CustomCategory;
use App\Models\CustomImage;
use App\Models\Post;
use Illuminate\Http\Request;

class CustomImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [];
        $data['customimages'] = CustomImage::with('getCustomCategory')->orderBy('id', 'DESC')->paginate(10);
        $data['categories'] = CustomCategory::orderBy('id', 'DESC')->get();
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

        $imageName = time() . '.' . $request->file->getClientOriginalExtension();
        $data = getimagesize($request->file);
        $width = $data[0];
        $height = $data[1];

        $request->file->move(public_path('custom-post'), $imageName);

        $uploadimage = \App\Models\CustomImage::create([
            'custom_category_id' => $request->id,
            'image_one' => $imageName,
            'position_x' => $request->position_x,
            'position_y' => $request->position_y,
            'imgposition_x' => $request->imgposition_x,
            'imgposition_y' => $request->imgposition_y,
            'width' => $width,
            'height' => $height,
        ]);

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
    public
    function edit($id)
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
    public
    function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public
    function destroy($id)
    {
        //
    }
}

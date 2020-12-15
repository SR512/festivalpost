<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::orderBy('id', 'DESC')->paginate(10);
        return response()->json($categories);
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
            'category' => 'required'
        ]);

        $category = Category::create([
            'category' => $request['category'],
        ]);

        if ($category) {
            $data['error'] = false;
            $data['message'] = "Category Create Successfully..!";
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
        $data = [];

        $this->validate($request, [
            'category' => 'required'
        ]);

        $category = Category::find($id);
        $category->category = $request['category'];


        if ($category->save()) {
            $data['error'] = false;
            $data['message'] = "Category Updated Successfully..!";
        } else {
            $data['error'] = true;
            $data['message'] = "Try Again..!";
        }
        return response()->json($data);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::find($id);

        if ($category->delete()) {
            $data['error'] = false;
            $data['message'] = "Category Deleted Successfully..!";
        } else {
            $data['error'] = true;
            $data['message'] = "Try Again..!";
        }
        return response()->json($data);
    }

    public function changeStatus($id)
    {
        $category = Category::find($id);

        if ($category->status == 1) {
            $category->status = 0;
            $category->save();
            $data['error'] = false;
            $data['message'] = "Category Status Changed Successfully..!";
        } else {
            $category->status = 1;
            $category->save();
            $data['error'] = true;
            $data['message'] = "Try Again..!";
        }
        return response()->json($data);
    }

    // Search
    public function search($field, $query)
    {
        $data = Category::where($field, 'LIKE', "%$query%")->latest()->paginate(10);
        return response()->json($data);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Festival;
use App\Models\Post;
use Carbon\Carbon;
use DateTime;
use Image;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FestivalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $festivals = Festival::with('getimages')->orderBy('id', 'DESC')->paginate(10);

        return response()->json($festivals);
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
            'festival' => 'required',
            'date' => 'required',
            'info' => 'nullable|string'
        ]);

        $d = new DateTime($request['date']);

        $festival = Festival::create([
            'festival_name' => $request['festival'],
            'festival_date' => $d->format('Y-m-d'),
            'festival_info' => $request['info'],
            'festival_day' => $d->format('d'),
        ]);

        if ($festival) {
            $data['error'] = false;
            $data['message'] = "Festival Create Successfully..!";
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
            'festival' => 'required',
            'date' => 'required',
            'info' => 'nullable|string'
        ]);

        $d = new DateTime($request['date']);

        $festival = Festival::find($id);

        $festival->festival_name = $request['festival'];
        $festival->festival_date = $d->format('Y-m-d');
        $festival->festival_info = $request['info'] == null ? null : $request['info'];
        $festival->festival_day = $d->format('d');

        if ($festival->save()) {
            $data['error'] = false;
            $data['message'] = "Festival Updated Successfully..!";
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
        $festival = Festival::find($id);

        if ($festival->delete()) {
            $data['error'] = false;
            $data['message'] = "Festival Deleted Successfully..!";
        } else {
            $data['error'] = true;
            $data['message'] = "Try Again..!";
        }
        return response()->json($data);
    }

    // get Festival Status Change

    public function changeStatus($id)
    {
        $festival = Festival::find($id);

        if ($festival->status == 1) {
            $festival->status = 0;
            $festival->save();
            $data['error'] = false;
            $data['message'] = "Festival Status Changed Successfully..!";
        } else {
            $festival->status = 1;
            $festival->save();
            $data['error'] = false;
            $data['message'] = "Festival Status Changed Successfully..!";
        }
        return response()->json($data);
    }

    // get Festival

    public function getFestival()
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Authkey' => 'stock@123',
            'Accept-Encoding' => 'application/gzip',
        ])
            ->withBody('token', '$242y%2412%24AmYh4bNA6oGJ92ZJW5YwoO5CcxaaYeqcZZJ2lugAYtWACjOomCJC6')
            ->post('http://festivalpost.in/admin/api/userapi/v2/gethomepage');


        foreach ($response['festival'] as $list) {

            $d = new DateTime($list['fest_date']);

            $filename = public_path('/festival/' . basename($list['fest_image']));
            Image::make($list['fest_image'])->save($filename);

            $festival = Festival::create([
                'festival_name' => $list['fest_name'],
                'festival_date' => $d->format('Y-m-d'),
                'festival_info' => $list['fest_info'],
                'festival_day' => $d->format('d'),
            ]);

            if ($festival) {
                \App\Models\Image::create([
                    'festival_id' => $festival->id,
                    'name' => basename($list['fest_image']),
                ]);
            }
        }

        return $response;
    }

    // Filter Methods

    public function search($field, $query)
    {
        $data = Festival::with('getimages')->where($field, 'LIKE', "%$query%")->latest()->paginate(10);
        return response()->json($data);
    }
}

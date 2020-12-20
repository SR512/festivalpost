<?php

namespace App\Http\Controllers;

use App\Models\AppUser;
use App\Models\Business;
use App\Models\Festival;
use App\Models\Image;
use App\Models\Package;
use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;
use \Validator;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public $successStatus = 200;

    // Register

    public function register(Request $request)
    {
        $data = [];

        if ($this->checkUser($request->header('Authkey'))) {

            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:app_users',
                'mobile' => 'required|numeric|unique:app_users',
            ]);

            if ($validator->fails()) {
                $data['error'] = false;
                $data['message'] = $validator->errors();
                return response()->json($data, 401);
            }

            $access = new AppUser();
            $user = AppUser::create([
                'name' => $request['name'],
                'email' => $request['email'],
                'mobile' => $request['mobile'],
                'remember_token' => $access->createToken('MyAPP')->accessToken,
                'isLogin' => 1,
                'business_id' => 0,
            ]);

            $data['error'] = false;
            $data['message'] = "Register Successfully..!";
            $data['token'] = $user->remember_token;
            $data['user'] = $user;

            return response()->json($data, $this->successStatus);

        } else {
            $data['error'] = false;
            $data['message'] = "unauthorize user..!";
            return response()->json($data, $this->successStatus);
        }
    }

    // Login

    public function login(Request $request)
    {
        $data = [];

        if ($this->checkUser($request->header('Authkey'))) {

            $otp = 1234;
            $isUser = AppUser::where('mobile', $request['mobile'])->first();

            if ($isUser != null) {

                if ($isUser->isLogin != 1) {

                    $isUser->isLogin = 1;
                    $isUser->remember_token = $isUser->createToken('MyAPP')->accessToken;
                    $isUser->save();

                    $data['error'] = false;
                    $data['otp'] = $otp;
                    $data['user']['id'] = $isUser->id;
                    $data['user']['token'] = $isUser->remember_token;
                    $data['message'] = "Otp Send Successfully..!";
                } else {
                    $data['error'] = false;
                    $data['message'] = "Please Logout From Other Device..!";
                }
            } else {
                $data['error'] = true;
                $data['message'] = "User Not Found..!";
            }
            return response()->json($data, $this->successStatus);

        } else {
            $data['error'] = false;
            $data['message'] = "unauthorize user..!";
            return response()->json($data, $this->successStatus);
        }

    }

    // Re Send OTP

    public function resendotp(Request $request)
    {
        $data = [];

        if ($this->checkUser($request->header('Authkey'))) {

            $otp = 1234;
            $isUser = AppUser::where('mobile', $request['mobile'])->first();

            if ($isUser != null) {

                $data['error'] = false;
                $data['otp'] = $otp;
                $data['user']['id'] = $isUser->id;
                $data['user']['token'] = $isUser->remember_token;
                $data['message'] = "Otp Send Successfully..!";

            } else {
                $data['error'] = true;
                $data['message'] = "User Not Found..!";
            }
            return response()->json($data, $this->successStatus);

        } else {
            $data['error'] = false;
            $data['message'] = "unauthorize user..!";
            return response()->json($data, $this->successStatus);
        }
    }

    // Logout
    public function logout(Request $request)
    {
        $data = [];

        if ($this->checkUser($request->header('Authkey'))) {

            $isUser = AppUser::find($request['id']);

            if ($isUser != null) {
                $isUser->isLogin = 0;
                $isUser->remember_token = null;

                if ($isUser->save()) {
                    $data['error'] = false;
                    $data['message'] = "Logout Successfully..!";
                } else {
                    $data['error'] = true;
                    $data['message'] = "Not Logout..!";
                }
            } else {
                $data['error'] = true;
                $data['message'] = "User Not Found..!";
            }
            return response()->json($data, $this->successStatus);

        } else {
            $data['error'] = false;
            $data['message'] = "unauthorize user..!";
            return response()->json($data, $this->successStatus);
        }

    }

    //    Business Add

    public function savebusiness(Request $request)
    {
        $data = [];

        if ($this->checkUser($request->header('Authkey'))) {

            $business = Business::create([
                'user_id' => $request['id'],
                'business_name' => $request['business_name'],
                'business_email' => $request['business_email'],
                'business_number' => $request['business_number'],
                'business_website' => $request['business_website'],
                'business_address' => $request['business_address']
            ]);

            if ($business) {
                $data['error'] = false;
                $data['message'] = "Business Data Save Successfully..!";

            } else {
                $data['error'] = true;
                $data['message'] = "Try Again..!";

            }
            return response()->json($data, $this->successStatus);

        } else {
            $data['error'] = false;
            $data['message'] = "unauthorize user..!";
            return response()->json($data, $this->successStatus);
        }

    }

    // Hompage

    public function getHomepage(Request $request)
    {
        $data = [];
        $slider = [];
        $festival = [];
        $incidents = [];
        $current_business = [];

        if ($this->CheckToken($request['token']) && $this->checkUser($request->header('Authkey'))) {

            $todaysDate = Carbon::today()->toDateString();
            $lastdate = Carbon::now()->endOfMonth()->toDateString();;

            $istoken = AppUser::where('remember_token', $request['token'])->where('business_id', 0)->first();
            $current_business = Business::where('user_id', $istoken->id)->first();
            $sliderdata = Festival::with('image')->whereBetween('festival_date', [$todaysDate, $lastdate])->orderBy('festival_date', 'ASC')->get();
            $postdata = Post::with('getimages')->where('status', 0)->orderBy('id', 'asc')->get();


            $postlist = [];
            $sliderlist = [];

            if ($sliderdata != null) {
                foreach ($sliderdata as $key => $list) {
                    $sliderlist[$key]['id'] = $list->id;
                    $sliderlist[$key]['festival_name'] = $list->festival_name;
                    $sliderlist[$key]['festival_date'] = $list->festival_date;
                    $sliderlist[$key]['festival_info'] = $list->festival_info;
                    $sliderlist[$key]['festival_day'] = $list->festival_day;
                    $sliderlist[$key]['image'] = $list->image->name;
                    $slider = $sliderlist;
                }
            }
            if ($postdata != null) {
                foreach ($postdata as $key => $list) {
                    $postlist[$key]['title'] = $list->getCategory->category;
                    $postlist[$key]['imgurl'] = $list->getimages;
                    $incidents = $postlist;
                }
            }

            $data['error'] = false;
            $data['slider'] = $slider;
            $data['incidents'] = $incidents;
            $data['current_business'] = $current_business;

        } else {
            $data['error'] = false;
            $data['message'] = "unauthorize user..!";
        }

        return response()->json($data, $this->successStatus);

    }

    //    Get Data From ID

    public function getImage(Request $request)
    {
        $data = [];

        if ($this->CheckToken($request['token']) && $this->checkUser($request->header('Authkey'))) {

            if ($request->type == "festival") {
                $listdata = Image::where('festival_id', $request->id)->get();

            } else {
                $listdata = Image::where('post_id', $request->id)->get();
            }

            $data['error'] = false;
            $data['data'] = $listdata;


        } else {
            $data['error'] = false;
            $data['message'] = "unauthorize user..!";
        }

        return response()->json($data, $this->successStatus);

    }

    //    Get Packge

    public function getPackage(Request $request)
    {
        $data = [];

        if ($this->CheckToken($request['token']) && $this->checkUser($request->header('Authkey'))) {

            $packages = Package::where('status', 0)->get();
            $data['error'] = false;
            $data['data'] = $packages;


        } else {
            $data['error'] = false;
            $data['message'] = "unauthorize user..!";
        }

        return response()->json($data, $this->successStatus);

    }

    // Check Header
    public function checkUser($key)
    {
        $passkey = "festival@123";
        if ($key == $passkey) {
            return true;
        } else {
            return false;
        }
    }

    public function CheckToken($token)
    {
        $istoken = AppUser::where('remember_token', $token)->first();

        if ($istoken != null) {
            return true;
        } else {
            return false;
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Coupon;
use App\Models\Feeship;
use App\Models\Province;
use App\Models\Wards;
use Illuminate\Http\Request;
use App\Models\ManipulationActivity;
use Auth;
session_start();

class APIDeliveryController extends Controller
{
    public function getCityAddress(){
        $cities = City::get();
        $data = $cities->toArray();
         return response()->json([
            'data' =>  $data,
            'status_code' => 200,
            'message' => 'Thành Công !',
           ]);
    }

    public function getDistrictAddress(Request $request){
        $city = City::where("name_city", $request->name_city)->first();
        $districts = Province::where("matp", $city->matp)->get();
        $data = $districts->toArray();
        return response()->json([
            'data' =>  $data,
            'status_code' => 200,
            'message' => 'Thành Công !',
           ]);
    }

    public function getWardAddress(Request $request){
        $districts = Province::where("name_province", $request->name_province)->first();
        $wards = Wards::where("maqh", $districts->maqh)->get();
        $data = $wards->toArray();
        return response()->json([
            'data' =>  $data,
            'status_code' => 200,
            'message' => 'Thành Công !',
           ]);
    }

    public function getFeeShip(Request $request){
        $ward = Wards::where("name_ward", $request->name_ward)->first();
        $fee_ship = Feeship::where("fee_maxp", $ward->maxp)->first();
        if($fee_ship){
            $data = $fee_ship->toArray();
            return response()->json([
                'data' =>  $data,
                'status_code' => 200,
                'message' => 'Thành Công !',
            ]);
        }else{
            return response()->json([
                'data' =>  null,
                'status_code' => 404,
                'message' => 'Thành Công !',
            ]);
        }
    }

    public function getCouponProduct(Request $request){
        $coupon = Coupon::where("coupon_name_code", $request->name_code)->get();
        if($coupon){
            $data = $coupon->toArray();
            return response()->json([
                'data' => $data,
                'status_code' => 200,
                'message' => 'Thành Công !',
            ]);
        }else{
            return response()->json([
                'data' =>  null,
                'status_code' => 404,
                'message' => 'Thành Công !',
            ]);
        }
    }
}

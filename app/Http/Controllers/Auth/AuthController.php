<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\City;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends BaseController
{
    public function register(Request $request)
    {
        $inputs=$request->all();
//        $inputs['password']=bcrypt($inputs['password']);
//        $user=User::query()->create($inputs);
        $user=new User();
        $user->name=$inputs['name'];
        $user->lastname=$inputs['lastname'];
        $user->national_code=$inputs['national_code'];
        $user->phone=$inputs['phone'];
        $user->birthday=$inputs['birthday'];
        $user->gender=$inputs['gender'];
        $user->bank_number=$inputs['bank_number'];
        $user->email=$inputs['email'];
        $user->password=bcrypt($inputs['password']);
        $user->save();

        $address=new Address();
        $address->address=$inputs['address'];
        $address->province_id=$inputs['province_id'];
        $address->city_id=$inputs['city_id'];
        $address->company=$inputs['company'];
        $address->post_code=$inputs['post_code'];
        $address->user_id=$user->id;
        $address->save();
        $token=$user->createToken('myApp')->plainTextToken;
        return [
            'user'=>$user,
            'address'=>$address,
            'token'=>$token
        ];
    }

    public function login(Request $request)
    {
        if (Auth::attempt(['email'=>$request->email,'password'=>$request->password])){
            $user=Auth::user();
            $token=$user->createToken('myApp')->plainTextToken;
            return response()->json([
                'data'=>[
                    'token'=>$token,
                    'user'=>$user
                ]
            ]);
        }else{
            return response()->json([
                'data'=>[
                    'message'=>'not correct',
                    'status'=>'error'
                ]
            ],401);
        }
    }

    public function getAllCities($provinceId)
    {
        $cities=City::where('province_id',$provinceId)->get();
        $response=[
            'cities'=>$cities
        ];
        return response()->json($response,200);
    }

}

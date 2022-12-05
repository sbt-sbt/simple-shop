<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    public function successResponse($text,$code=200)
    {
        $response=[
            'data'=>[
                'message'=>$text,
                'status'=>$code
            ]
        ];
        return response()->json($response,$code);
    }

    public function erorrResponse($text,$code=404)
    {
        $response=[
            'data'=>[
                'message'=>$text,
                'status'=>$code
            ]
        ];
        return response()->json($response,$code);
    }
}

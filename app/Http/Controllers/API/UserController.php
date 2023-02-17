<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Models\Cart;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Session;
use Laravel\Passport\Token\Token;
use DB;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name'=>'required|max:191',
            'email'=>'required|email|max:191',
            'password'=>'required',
        ]);

        if($validator->fails())
        {
            return response()->json([
                'status'=> 422,
                'validate_err'=> $validator->messages(),
            ]);
        }
        else
        {
            $user = new User;
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->password = Hash::make($request->input('password'));
            $user->save();

            $user->token =  $user->createToken('MyApp')->accessToken;

            return response()->json([
                'data' => $user,
                'status'=> 200,
                'message'=>'User Added Successfully',
            ]);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email'=>'required|email|max:191',
            'password'=>'required',
        ]);

        if($validator->fails())
        {
            return response()->json([
                'status'=> 422,
                'validate_err'=> $validator->messages(),
            ]);
        } else {
            $user = User::where('email', $request->email)->first();
            if(Hash::check($request->password, $user->password)) { 
                
                $cartProducts = Cart::where('user_id', $user->id)->with(['product'])->get();
                $totalCartAmount = 0;
                if(isset($cartProducts) && !empty($cartProducts)) {
                    foreach($cartProducts as $cartProduct) {
                        $totalCartAmount = $totalCartAmount + ($cartProduct->qty * $cartProduct->product->price);
                    }
                }

                $user->token =  $user->createToken('MyApp')->accessToken;

                return response()->json([
                    'data' => $user,
                    'cart_count' => Cart::where('user_id', $user->id)->sum('qty'),
                    'total_cart_amount' => $totalCartAmount,
                    'status'=> 200,
                    'message'=>'User Logined Successfully',
                ]);   
            } 
            else{
                return response()->json([
                    'data' => [],
                    'message'=>'User credential wrong',
                ], Response::HTTP_BAD_REQUEST);
            }
        }
    }

    public function logout(Request $request)
    {
        $access_token = $request->header('Authorization');
        $auth_header = explode(' ', $access_token);
        $token = $auth_header[1];
        $token_parts = explode('.', $token);
        $token_header = $token_parts[1];
        $token_header_json = base64_decode($token_header);
        $token_header_array = json_decode($token_header_json, true);
        $token_id = $token_header_array['jti'];

        $user = DB::table('oauth_access_tokens')->where('id', $token_id)->update(['revoked' => true]);

        Session::flush();

        return response()->json([
            'status'=> 200,
            'message'=>'User Logout Successfully'
        ]);  
    }
}
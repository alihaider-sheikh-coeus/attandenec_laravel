<?php

namespace App\Http\Controllers;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    public function login(Request $request) {
       $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);
        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }
        $userCredentials = $request->only('email', 'password');
        if (Auth::attempt($userCredentials)) {
            $user = User::where('email', $request->email)->first();
            Session::put('user',$user);
            return ($user->is_hr)?redirect()->route('markattandence')->with('success','You successfully logged in to this website.'):redirect()->route('markattandence');
         }
        return redirect::to("login")->withErrors('Oopps! Wrong credentails!');

    }
    public function logout (Request $request) {
        $request->session()->flush();
        Auth::logout();
        return Redirect('login');
    }


}

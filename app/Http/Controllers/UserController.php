<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    public function loginView()
    {
        return view('login');
    }
    public function login(Request $request) {
//        dd($request->all());
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }
        $userCredentials = $request->only('email', 'password');
        if (Auth::attempt($userCredentials)) {
            $user = User::where('email', $request->email)->first();
            Session::put('user',$user);
            return ($user->is_hr)?redirect()->route('hr_dashboard'):redirect()->route('markattandence');

        }
        return redirect::to("login")->withSuccess('Oopps! You do not have access');

    }
    public function logout (Request $request) {
        $request->session()->flush();
        Auth::logout();
        return Redirect('login');
    }
    public function createUser()
    {
        return view('user.create');
    }
    public function store(Request $request)
    {
        $validations=$this->validateRequestFeilds($request);
        $cover = $request->file('profile-pic');
        $extension = $cover->getClientOriginalExtension();
        Storage::disk('public')->put($cover->getFilename().'.'.$extension,  File::get($cover));

        $status = DB::table('users')->insert([
            ['email' =>$request->email,
                'name' =>$request->name,
                'password'=>bcrypt($request->password),
                'profile_pic'=> $cover->getFilename().'.'.$extension,
                'designation_id'=>$request->designation_id,
                'is_hr'=>$request->has('is_hr')? 1:0,
                'salary'=>$request->salary,
                'department'=>$request->department,
                'created_at'=>Carbon::now()

            ],
        ]);
        session()->flash('created',"<span class='h4'>User</span> Created Successfully");
        return  ($status)?  redirect()->route('index_page') :"unable to create user";

    }
    public function edit($id)
    {
        $user=User::where('id',$id)->first();
        return view('user.edit',["user"=>$user]);
    }
    public function updateUser(User $user,Request $request)
    {

        $cover = $request->file('profile-pic');
        $extension = $cover->getClientOriginalExtension();
        Storage::disk('public')->put($cover->getFilename().'.'.$extension,  File::get($cover));

                $user->email=$request->email;
                $user->name =$request->name;
                $user->profile_pic= $cover->getFilename().'.'.$extension;
                $user->designation_id = $request->designation_id;
                $user->is_hr=$request->has('is_hr')? 1:0;
                $user->salary=$request->salary;
                $user->department=$request->department;
                $user->updated_at=Carbon::now();
        $status=$user->save();
        Session::flash('updated',"<span class='h4'>User</span> Updated Successfully");
    return  ($status)?  redirect()->route('index_page') :"unable to create user";
    }

    public function deleteUser(Request $request,$id) {
        $user=User::find($id);
        $response=false;
        if($user)
        {
            $response =(bool)($user->delete()) ;
        }
       return  ($response)?  redirect()->route('index_page'):"unable to delete user";

    }
    public function showallUser()
    {
        $users = User::orderBy('created_at','DESC')->get();
        return view('user.index', ['users' => $users]);

    }
    public function validateRequestFeilds($request)
    {
//        dd("in request");
        $validator = Validator::make($request->all(), [
            'name' => 'bail|required|min:3|max:255',
            'email' => 'required',
            'profile-pic'=> 'bail|mimes:jpg,jpeg,png,bmp,tiff|max:4096',
//            'profile_pic'=> 'string',
            'salary'=>'numeric',
//            'designation_id'=>'required|Rule::in(["manager", "developer","hr","CEO"])',
            'designation_id'=>'required',
            'password' => 'required|string|min:6'
        ]);
        if ($validator->fails()) {
            return redirect()->route('add')
                ->withErrors($validator)
                ->withInput();
        }
    }

}

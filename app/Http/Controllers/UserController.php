<?php

namespace App\Http\Controllers;

use App\Attandence;
use App\Http\Requests\CreateUserRequest;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    public $user_object;
    public function __construct()
    {
        $this->user_object = new User();
    }

    public function login(Request $request) {
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
            return ($user->is_hr)?redirect()->route('hr_dashboard')->with('success','You successfully logged in to this website.'):redirect()->route('markattandence');
         }
        return redirect::to("login")->withErrors('Oopps! You do not have access');

    }
    public function logout (Request $request) {
        $request->session()->flush();
        Auth::logout();
        return Redirect('login');
    }
    public function createUser()
    {
        $bosses=  DB::table('users')
            ->where('designation_id','=',1)
            ->pluck('name');
        return view('user.create',['bosses'=>$bosses]);
    }
    public function store(Request $request)
    {

        $validationResponse = $this->validateRequestFeilds($request);//function call
        if ($validationResponse['status'] == false) {
                return redirect()->route('add')
                ->withErrors($validationResponse['validator'])
                ->withInput();
        }

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
                'boss_name'=>$request->boss_name,
                'department'=>$request->department,
                'created_at'=>Carbon::now()
       ],
        ]);
        return  ($status)?  redirect()->route('index_page')->with('success','User Created Successfully') :redirect()->route('index_page')->with('Error',"unable to create user");

    }
    public function edit($id)
    {
        $user=User::where('id',$id)->first();
        return view('user.edit',["user"=>$user]);
    }
    public function updateUser(Request $request)
    {
        $check_for_password=($request->password)?true:false;
        $user= User::find($request->id);
        $validationResponse = $this->validateRequestFeildsforedit($request);//function call
        if ($validationResponse['status'] == false) {
            return redirect()->route('edit_user',['id'=>$user->id])->withErrors($validationResponse['validator'])
                ->withInput();
        }

        if($request->hasFile('profile-pic'))
    {
    $cover = $request->file('profile-pic');
    $extension = $cover->getClientOriginalExtension();
    Storage::disk('public')->put($cover->getFilename().'.'.$extension,  File::get($cover));
    $user->profile_pic= $cover->getFilename().'.'.$extension;
    }

                $user->email=$request->email;
                $user->name =$request->name;
                $user->designation_id = $request->designation_id;
                $user->is_hr=$request->has('is_hr')? 1:0;
                $user->salary=$request->salary;
                $user->department=$request->department;
                $user->updated_at=Carbon::now();
                 $user->password=($check_for_password)?$request->password:$user->password;
                $status=$user->save();
        return  ($status)?  redirect()->route('index_page')->with('success','User updated Successfully') :redirect()->route('index_page')->with('Error',"unable to delete user");
    }

    public function deleteUser(Request $request,$id) {
        $user=User::find($id);
        $response = $this->user_object-> delete_user_object($user);
        return  ($response)?  redirect()->route('index_page')->with('success','User Deleted Successfully') :redirect()->route('index_page')->with('Error',"unable to delete user");
    }
    public function showallUser()
    {
        $users = User::where('id', '!=', Auth::user()->id)->orderBy('created_at','DESC')->simplePaginate(2);
        return view('user.index', ['users' => $users]);
    }
    public function validateRequestFeilds($request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'bail|required|min:3|max:255',
            'email' => 'required|email',
            'profile-pic'=> 'bail|required|mimes:jpg,jpeg,png,bmp,tiff|max:4096',
            'salary'=>'numeric',
            'designation_id'=>'required',
            'password' => 'required|string|min:6'
        ]);
        $return = ['status' => true, 'validator' => $validator];
        if ($validator->fails()) {
            $return['status'] = false;
        }
        return $return;
    }
    public function validateRequestFeildsforedit($request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'bail|required|min:3|max:255',
            'email' => 'required|email',
            'profile-pic'=> 'bail|mimes:jpg,jpeg,png,bmp,tiff|max:4096',
            'salary'=>'numeric',
            'password' => 'confirmed|nullable|string|min:6|max:15',
            'designation_id'=>'required',
        ]);
        $return = ['status' => true, 'validator' => $validator];
        if ($validator->fails()) {
            $return['status'] = false;
        }
        return $return;
    }
}

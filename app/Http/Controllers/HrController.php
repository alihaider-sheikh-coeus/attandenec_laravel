<?php

namespace App\Http\Controllers;

use App\Designation;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class HrController extends Controller
{
    private $user_object;
    public function __construct()
    {
        $this->user_object = new User();
    }
    public function createUser()
    {
        $bosses = $this->user_object->userWithManagerDesignation();
        $designations = Designation::all();
        return view('user.create',['bosses'=>$bosses,'designations'=>$designations]);
    }
    public function store(Request $request)
    {

        $validationResponse = $this->validateRequestFeilds($request);//function call
        if ($validationResponse['status'] == false) {
            return redirect()->route('add')
                ->withErrors($validationResponse['validator'])
                ->withInput();
        }
//        dd($request);
        $picture=$this->storePicture($request);
        $status =$this->user_object->insertUser($request,$picture);
        return  ($status)?  redirect()->route('index_page')->with('success','User Created Successfully') :redirect()->route('index_page')->with('Error',"unable to create user");

    }
    public function edit($id)
    {
        $user=$this->user_object->getUserObjectwithid($id);
        $designations = Designation::all();
        return view('user.edit',["user"=>$user, 'designations'=>$designations]);
    }
    public function updateUser(Request $request)
    {
        $check_for_password=($request->password) ? true:false;
        $user= User::find($request->id);
        $validationResponse = $this->validateRequestFeildsforedit($request);//function call
        if ($validationResponse['status'] == false) {
            return redirect()->route('edit_user',['id'=>$user->id])->withErrors($validationResponse['validator'])
                ->withInput();
        }

        if($request->hasFile('profile-pic'))
        {
            $picture=$this->storePicture($request);
            $user->profile_pic= $picture["cover"]->getFilename().'.'.$picture["extension"];
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
        $response = $this->user_object->delete_user_object($user);
        return  ($response)?  redirect()->route('index_page')->with('success','User Deleted Successfully') :redirect()->route('index_page')->with('Error',"unable to delete user");
    }
    public function showallUser()
    {
        $users = User::where('id', '!=', Auth::user()->id)->orderBy('created_at','DESC')->simplePaginate(4);

        return view('user.index', ['users' => $users]);
    }
    public function validateRequestFeilds($request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'bail|required|min:3|max:255',
            'email' => 'required|email|unique:users',
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
            'email' => 'required|email|unique:users',
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
    public function storePicture($request)
    {
        $cover = $request->file('profile-pic');
        $extension = $cover->getClientOriginalExtension();
        Storage::disk('public')->put($cover->getFilename().'.'.$extension,  File::get($cover));
        return ["cover"=>$cover,"extension"=>$extension];
    }
}

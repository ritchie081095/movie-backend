<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Datatables;
use App\User;
use Illuminate\Support\Facades\Hash;
// use App\Events\ReloadDataEvent;

class UserManagementController extends Controller
{
    public function index(Request $request){
        $query = User::query();
        return DataTables::of($query)->make(true);
    }

    public function store(Request $request){
        $this->field_validate($request);
        $this->validate($request,
        [
            'email' => 'unique:users',
        ],[],
        [
            'email' => 'Email Address',
        ]
        );

        $roles = (array) $request->roles;
        $users = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'user_status' => $request->user_status,
            'password' => Hash::make($request->password),
            'roles' => (empty($roles)) ? null : $roles,
        ]);
    }

    public function update(Request $request, $id){
        $this->field_validate($request);
        $this->validate($request,
        [
            'email' => 'required|max:100|unique:users,email,' . $id,
        ],[],
        [
            'email' => 'Email Address',
        ]
        );

        $users = User::findOrFail($id);
        $users->name = $request->name;
        $users->username = $request->username;
        $users->email = $request->email;
        $users->user_status = $request->user_status;
        $users->roles = (empty($request->roles)) ? null : $request->roles;

        if($request->password){
            $users->password = Hash::make($request->password);
        }
        
        $users->save();
    }

    public function destroy($id){
        DB::table('users')->where('id', $id)->delete();
    }

    public function field_validate($request){
        return $this->validate($request,
        [
            'name' => 'required|max:100',
            'username' => 'required|max:100',
            'email' => 'required|max:50|email',
            'password' => 'sometimes|required|max:100|min:4',
        ],[],
        [
            'name' => 'Full name',
            'username' => 'Username',
            'email' => 'Email Address',
            'password' => 'Password',
        ]
        );
    }

    public function show($id){
        return User::where('id', $id)->first();
    }

    public function editaccountinfo(Request $request){
        $this->validate($request,
        [
            'name' => 'required|max:100',
            'username' => 'required|max:100|unique:users,username,' . $request->id,
            'email' => 'required|max:100|unique:users,email,' . $request->id,
        ],[],
        [
            'name' => 'Full name',
            'username' => 'Username',
            'email' => 'Email address',
        ]
        );

        if($request->changepassword){
            $this->validate($request,
            [
                // 'current_password' => 'required|password',
                'current_password' => ['required', function ($attribute, $value, $fail) {
                    if (!\Hash::check($value, \Auth::user()->password)) {
                        return $fail(__('The current password is incorrect.'));
                    }
                }],
                'new_password' => 'required',
                'repeat_password' => 'required|same:new_password',
            ],[],
            [
                'current_password' => 'Current password',
                'new_password' => 'New password',
                'repeat_password' => 'Repeat password',
            ]
            );
        }

        $query = User::findOrFail($request->id);
        $query->name = $request->name;
        $query->username = $request->username;
        $query->email = $request->email;

        if($request->changepassword){
            $query->password = Hash::make($request->repeat_password);
        }

        $query->save();
    }
}

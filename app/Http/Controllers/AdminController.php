<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function list(){
        $data['getRecords'] = User::getAdmin();
        $data['header_title'] = 'Admin List';
        return view('admin.admin.list', $data);
    }

    public function add(){
        
        $data['header_title'] = 'Add Admin';
        return view('admin.admin.add', $data);
    }

    public function insert(Request $request){
        $user = new User;
        $user->name = trim($request->name);
        $user->email = trim($request->email);
        $user->password = Hash::make($request->password);
        $user->user_type = 1;
        $user->save();

        return redirect('admin/admin/list')->with('success', 'Admin Successfully Created...');
    }

    public function edit($id){
        $data['getRecord'] = User::getData($id);
        if(!empty($data['getRecord']))
        {
            $data['header_title'] = 'Edit Admin';
            return view('admin.admin.edit',$data);
        }
        else{
            abort(404);
        }
        
    }

    public function update($id, Request $request){
        $user = User::getData($id);
        $user->name = trim($request->name);
        $user->email = trim($request->email);
        if(!empty($request->password)){
            $user->password = Hash::make($request->password);
        }
        $user->save();

        return redirect('admin/admin/list')->with('success', 'Admin Updated Successfully...');
    }

    public function delete($id){
        $user = User::getData($id);
        $user->is_delete =1;
        $user->save();

        return redirect('admin/admin/list')->with('success', 'Admin Updated deleted...');
    }
}

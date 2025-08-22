<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;

use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    public function create(){
        return view('role/role-add');
    }

    public function store(Request $req){
        $role = new Role; 

        $role->roleName               = $req->rolename;
        $role->description            = $req->description;
        $role->subscriber_id          = Auth::user()->id;

        $role->save();

        return response() -> json([
            'status'=>200,
            'message' => 'Role created Successfully!'
        ]);
    }

    public function list(Request $request){

        // $vat = Vat::all();
        $role = Role::where('subscriber_id',  1)->get();

        if($request -> ajax()){
            return response()->json([
                'role'=>$role,
            ]);
        }

        return view('role/role-list', ['roles' => $role]);
    }

    public function edit($id){
        $role = Role::find($id);

        if($role){
            return response()->json([
                'status'=>200,
                'role'=>$role,        
            ]);
        }
    }

    public function update(Request $req, $id){
        $role = Role::find($id);

        $role->roleName       = $req->rolename;
        $role->description    = $req->description;
        
        $role->save();
        
        return response() -> json([
            'status'=>200,
            'message' => 'Role Data Updated Successfully'
        ]);
    }

    public function destroy($id){
        Role::find($id)->delete($id);

        return redirect('role-list')->with('status', 'Deleted successfully!');
    }


}

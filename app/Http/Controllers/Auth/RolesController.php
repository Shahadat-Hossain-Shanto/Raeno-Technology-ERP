<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        //$roles = Role::all();

        $roles = Role::where('subscriber_id', Auth::user()->subscriber_id)->get();
        return view('role.role-index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::all();

        // print_r($permissions);

        $permission_groups = DB::table('permissions')
            ->select('group_name as name')
            ->groupBy('group_name')
            ->get();

        // return json_encode($permission_groups);
        //dd($permissions);
        return view('role.role-create', compact('permissions', 'permission_groups'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $role =  Role::create(['name' => $request->rolename]);
        $messages = [
            'rolename.required'  =>    "Role name is required.",
        ];

        $validator = Validator::make($request->all(), [
            'rolename' => 'required',
        ], $messages);

        if ($validator->passes()) {
            $role = new Role();
            $role->name          = $request->rolename;
            $role->subscriber_id = Auth::user()->subscriber_id;
            $role->save();

            $permissions = $request->input('permissions');
            if (!empty($permissions)) {
                $role->syncPermissions($permissions);
            }
            return redirect()->route('admin.roles');
        }

        return response()->json(['error' => $validator->errors()]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::findById($id);
        $permissions = DB::table('permissions')
            ->select('group_name as name')
            ->groupBy('group_name')
            ->get();
        // $permissions = Permission::all();
        // return json_encode($permissions);
        return view('role.role-edit', compact('role', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $role = Role::find($id);
        $role->name = $request->rolename;
        $permissions = $request->input('permissions');
        if (!empty($permissions)) {
            $role->syncPermissions($permissions);
        }
        $role->save();

        session()->flash('success', 'Role has been updated !!');
        return redirect()->route('admin.roles');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // $roleid = Role::where('subscriber_id', Auth::user()->subscriber_id)->get();
        $roleid = Role::find($id);


        if ($roleid->name == 'admin') {
            return redirect('/role-list');
        } else {
            $roleid->delete();
            session()->flash('success', 'Role has been deleted !!');
            return back();
        }
    }
}

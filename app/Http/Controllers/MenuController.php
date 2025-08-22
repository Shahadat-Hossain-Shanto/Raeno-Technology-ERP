<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;

class MenuController extends Controller
{
    public function create()
    {
        return view('menu/menu-add');
    }
    public function store(Request $request)
    {
        $menu = new Menu();
        $menu->name    = $request->menuname;
        $menu->href = $request->menulink;

        $menu->save();
    }
    public function list(Request $request)
    {

        $menu = Menu::all();


        if ($request->ajax()) {
            return response()->json([
                'menu' => $menu,
            ]);
        }

        return view('menu/menu-list', ['menus' => $menu]);;
    }

    public function edit($id)
    {
        $menu = Menu::find($id);

        if ($menu) {
            return response()->json([
                'status' => 200,
                'menu' => $menu,

            ]);
        }
    }
    public function update(Request $req, $id)
    {
        $menu = Menu::find($id);

        $menu->name                    = $req->menuname;
        $menu->href                    = $req->menulink;



        $menu->save();

        return response()->json([
            'status' => 200,
            'message' => 'menu Data Updated Successfully'
        ]);
    }
    public function destroy($id)
    {
        Menu::find($id)->delete($id);

        return redirect('menu-list')->with('status', 'Deleted successfully!');
    }
}

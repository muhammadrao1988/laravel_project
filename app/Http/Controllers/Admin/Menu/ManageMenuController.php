<?php

namespace App\Http\Controllers\Admin\Menu;

use App\TraitLibraries\AlertMessages;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Yajra\Datatables\Datatables;
use App\Models\Menu;
use App\Models\Configuration;

class ManageMenuController extends BaseController
{
    use AlertMessages;

    public $module = "Menu";

    public $permissions = Array(
        "index" => "read",
        "store" => "update",
    );

    protected $mainViewFolder = 'admin.menu.manage-menu.';

    public function index(Request $request)
    {
        if($request->ajax()){
            return  Datatables::of(Menu::getList())
                    ->addColumn('action', function ($data){
                        return Menu::actionButtons($data);
                    })->rawColumns(['action'])->make(true);
        }
        return view($this->mainViewFolder . 'index');
    }

    public function store(Request $request)
    {
        $menu = Configuration::find($request->id);
        $menu->active = $request->active;
        $menu->value = $request->value;
        if($menu->save()){
            $request->session()->flash('success', $this->setAlertSuccess("Menu",'update', $request->id));
        }
        return redirect('menu');
    }
}

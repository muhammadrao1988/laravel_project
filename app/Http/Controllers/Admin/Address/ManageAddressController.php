<?php

namespace App\Http\Controllers\Admin\Address;

use App\Models\Address;
use App\Models\Service;
use App\TraitLibraries\AlertMessages;
use App\TraitLibraries\ResponseWithHttpStatus;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use App\Models\Flag;
use App\Models\FlagType;

class ManageAddressController extends BaseController
{
    use AlertMessages, ResponseWithHttpStatus;

    public $module = "Address";

    public $permissions = Array(
        "index" => "read",
        "show" => "read",
        "create" => "create",
        "edit" => "update",
        "destroy" => "delete",
    );

    protected $mainViewFolder = 'admin.addresses.manage-address.';

    public function index(Request $request)
    {
        if($request->ajax()){
            return  Datatables::of(Address::getList())
                    ->addColumn('action', function ($data){
                        return Address::actionButtons($data);
                    })->rawColumns(['action'])->make(true);
        }

        return view($this->mainViewFolder . 'index');
    }

    public function create(Request $request)
    {
        $response = Address::syncAddresses();
        if($response['success']==true){
            $request->session()->flash('success', $response['msg']);
        }else{
            $request->session()->flash('error', $response['msg']);
        }
        return redirect(route('addresses.index'));
    }

    public function edit($id, Request $request)
    {
        return redirect(route('addresses.index'));
    }

    public function show($id, Request $request)
    {
        $model = Address::find($id);
        if(empty($model)){
            $request->session()->flash('error', 'Record does not exist in system.');
            return redirect(route('addresses.index'));
        }

        return view($this->mainViewFolder.'show', compact('model'));
    }

    public function destroy(Request $request, $id)
    {
        return redirect(route('addresses.index'));
    }

    public function store(Request $request)
    {
        return redirect(route('addresses.index'));
    }

}

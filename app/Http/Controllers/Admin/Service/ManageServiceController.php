<?php

namespace App\Http\Controllers\Admin\Service;

use App\Models\Service;
use App\TraitLibraries\AlertMessages;
use App\TraitLibraries\ResponseWithHttpStatus;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use App\Models\Flag;
use App\Models\FlagType;

class ManageServiceController extends BaseController
{
    use AlertMessages, ResponseWithHttpStatus;

    public $module = "Service";

    public $permissions = Array(
        "index" => "read",
        "show" => "read",
        "create" => "create",
        "edit" => "update",
        "destroy" => "delete",
    );

    protected $mainViewFolder = 'admin.services.manage-service.';

    public function index(Request $request)
    {
        if($request->ajax()){
            return  Datatables::of(Service::getList())
                    ->addColumn('action', function ($data){
                        return Service::actionButtons($data);
                    })->editColumn('billing_frequency', function ($data){
                        return $data->billing_frequency." month";
                    })->rawColumns(['action'])->make(true);
        }

        return view($this->mainViewFolder . 'index');
    }

    public function create(Request $request)
    {
        $response = Service::syncServices();
        if($response['success']==true){
            $request->session()->flash('success', $response['msg']);
        }else{
            $request->session()->flash('error', $response['msg']);
        }
        return redirect(route('services.index'));
    }

    public function edit($id, Request $request)
    {
        return redirect(route('services.index'));
    }

    public function show($id, Request $request)
    {
        $model = Service::find($id);
        if(empty($model)){
            $request->session()->flash('error', 'Record does not exist in system.');
            return redirect(route('services.index'));
        }

        return view($this->mainViewFolder.'show', compact('model'));
    }

    public function destroy(Request $request, $id)
    {
        return redirect(route('services.index'));
    }

    public function store(Request $request)
    {
        return redirect(route('services.index'));
    }

}

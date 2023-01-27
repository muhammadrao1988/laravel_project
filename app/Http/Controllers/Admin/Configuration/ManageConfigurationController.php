<?php

namespace App\Http\Controllers\Admin\Configuration;

use App\TraitLibraries\AlertMessages;
use App\Models\Configuration;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;

class ManageConfigurationController extends BaseController
{
    use AlertMessages;

    public $module = "Configuration";

    public $permissions = Array(
        "index" => "read",
        "show" => "read",
        "edit" => "update",
    );

    protected $mainViewFolder = 'admin.configuration.manage-configuration.';

    public function index(Request $request)
    {
        if($request->ajax()){
            return  Datatables::of(Configuration::getList())
                    ->editColumn('value', function ($data){
                        return Configuration::filterValue($data->value);
                    })->addColumn('action', function ($data){
                        return Configuration::actionButtons($data);
                    })->rawColumns(['action'])->make(true);
        }
        return view($this->mainViewFolder . 'index');
    }

    public function edit($id)
    {
        $model = Configuration::find($id);
        return view($this->mainViewFolder.'form', compact('model'));
    }

    public function show($id)
    {
        $model = Configuration::find($id);
        return view($this->mainViewFolder.'show', compact('model'));
    }

    public function store(Request $request)
    {
        $request->validate(Configuration::validationRules(@$request->id));

        $model = new Configuration($request->all());
        $action = "create";
        $redirect = 'configuration.create';

        if(!empty(@$request->input('id'))){
            $model = Configuration::find($request->input('id'));
            $model->loadModel($request->all());
            $action = "update";
            $redirect = 'configuration.index';
        }

        if (!$model->save())
            throw new \Exception($this->setAlertError('Configuration', $action));

        $request->session()->flash('success', $this->setAlertSuccess('Configuration', $action, $model->id));
        return redirect()->route($redirect);
    }
}

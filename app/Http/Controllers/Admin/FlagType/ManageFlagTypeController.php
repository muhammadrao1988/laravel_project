<?php

namespace App\Http\Controllers\Admin\FlagType;

use App\TraitLibraries\AlertMessages;
use App\Models\FlagType;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;

class ManageFlagTypeController extends BaseController
{
    use AlertMessages;

    public $module = "FlagType";

    public $permissions = Array(
        "index" => "read",
        "show" => "read",
        "edit" => "update",
    );

    protected $mainViewFolder = 'admin.flagtype.manage-flagtype.';

    public function index(Request $request)
    {
        if($request->ajax()){
            return  Datatables::of(FlagType::getList())
                    ->addColumn('action', function ($data){
                        return FlagType::actionButtons($data);
                    })->rawColumns(['action'])->make(true);
        }
        return view($this->mainViewFolder . 'index');
    }

    public function edit($id)
    {
        $model = FlagType::find($id);
        return view($this->mainViewFolder.'form', compact('model'));
    }

    public function show($id)
    {
        $model = FlagType::find($id);
        return view($this->mainViewFolder.'show', compact('model'));
    }

    public function store(Request $request)
    {
        $request->validate(FlagType::validationRules(@$request->id));

        $model = new FlagType($request->all());
        $action = "create";
        $redirect = 'flagtype.create';

        $model->code = strtolower(preg_replace('/\s+/', '_', $request->name));

        if(!empty(@$request->input('id'))){
            $model = FlagType::find($request->input('id'));
            $model->loadModel($request->all());
            $action = "update";
            $redirect = 'flagtype.index';
        }

        if (!$model->save())
            throw new \Exception($this->setAlertError('FlagType', $action));

        $request->session()->flash('success', $this->setAlertSuccess('Flag Type', $action, $model->id));
        return redirect()->route($redirect);
    }
}

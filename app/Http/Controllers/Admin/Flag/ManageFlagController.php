<?php

namespace App\Http\Controllers\Admin\Flag;

use App\TraitLibraries\AlertMessages;
use App\TraitLibraries\ResponseWithHttpStatus;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use App\Models\Flag;
use App\Models\FlagType;

class ManageFlagController extends BaseController
{
    use AlertMessages, ResponseWithHttpStatus;

    public $module = "Flag";

    public $permissions = Array(
        "index" => "read",
        "show" => "read",
        "create" => "create",
        "edit" => "update",
        "destroy" => "delete",
    );

    protected $mainViewFolder = 'admin.flag.manage-flag.';

    public function index(Request $request)
    {
        if($request->ajax()){
            return  Datatables::of(Flag::getList())
                    ->addColumn('action', function ($data){
                        return Flag::actionButtons($data);
                    })->rawColumns(['action'])->make(true);
        }

        $flagtype = array();
        if(!empty($request->flagtype)){
            $flagtype = FlagType::where('code', $request->flagtype)->get()->first();
        }
        return view($this->mainViewFolder . 'index', compact('flagtype'));
    }

    public function create(Request $request)
    {
        $model = new Flag();
        $flagtype = array();
        if(!empty($request->flagtype)){
            $flagtype = FlagType::where('code', $request->flagtype)->get()->first();
        }
        return view($this->mainViewFolder . 'form', compact('model', 'flagtype'));
    }

    public function edit($id, Request $request)
    {
        $model = Flag::find($id);
        $flagtype = array();
        if(!empty($request->flagtype)){
            $flagtype = FlagType::where('code', $request->flagtype)->get()->first();
        }
        return view($this->mainViewFolder.'form', compact('model', 'flagtype'));
    }

    public function show($id, Request $request)
    {
        $model = Flag::find($id);
        $flagtype = array();
        if(!empty($request->flagtype)){
            $flagtype = FlagType::where('code', $request->flagtype)->get()->first();
        }
        return view($this->mainViewFolder.'show', compact('model', 'flagtype'));
    }

    public function destroy(Request $request, $id)
    {
        $flagName = "Flag";
        $redirect = 'flag';

        if(!empty($request->flagtype)){
            $flagName = $request->flagtype;
            $redirect .= "?flagtype=".$request->flagtype;
        }

        if(Flag::destroy($id)){
            $alertMessage = $this->setAlertSuccess($flagName,'delete', $id);
            if(\Common::isAPIRequest()){
                return $this->responseSuccess($alertMessage, null, 200);
            }
            $request->session()->flash('success', $alertMessage);
        }
        return redirect($redirect);
    }

    public function store(Request $request)
    {
        $request->validate(Flag::validationRules(@$request->id));

        $model = new Flag($request->all());
        $action = "create";
        $redirect = 'flag';

        if(!empty(@$request->input('id'))){
            $model = Flag::find($request->input('id'));
            $model->loadModel($request->all());
            $action = "update";
            $redirect = 'flag';
        }

        $flagName = "Flag";
        if(!empty($request->flagtype)){
            $flagName = $request->flagtype;
            $redirect .= "?flagtype=".$request->flagtype;
        }

        if (!$model->save())
            throw new \Exception($this->setAlertError($flagName, $action));

        $alertMessage = $this->setAlertSuccess($flagName, $action, $model->id);

        if(\Common::isAPIRequest()){
            return $this->responseSuccess($alertMessage, $model, 200);
        }

        $request->session()->flash('success', $alertMessage);

        if($request->save_btn && $request->save_btn == config('constants.SAVE_ADD_MORE')){
            $redirect = 'flag/create';
        }

        return redirect($redirect);
    }

    public function getFlagChild(Request $request)
    {
        if(!empty($request->flagName)){
            $flag = Flag::where('name', $request->flagName)->where('flagType', $request->parentFlagType)->first();
            if(!empty($flag->id)){
                return Flag::where('parentId', $flag->id)->where('flagType', $request->childFlagType)->get();
            }
        }
        return [];
    }
}

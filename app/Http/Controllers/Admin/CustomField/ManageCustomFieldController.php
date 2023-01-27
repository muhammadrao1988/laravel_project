<?php

namespace App\Http\Controllers\Admin\CustomField;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\TraitLibraries\AlertMessages;
use Yajra\Datatables\Datatables;
use App\Models\CustomField;

class ManageCustomFieldController extends BaseController
{
    use AlertMessages;

    public $module = "CustomField";

    public $permissions = Array(
        "index" => "read",
        "show" => "read",
        "create" => "create",
        "edit" => "update",
        "destroy" => "delete",
    );

    protected $mainViewFolder = 'admin.customfield.manage-customfield.';

    public $fieldTypes = array("text", "number", "select", "textarea", "checkbox", "radio");

    public function index(Request $request)
    {
        if($request->ajax()){
            return  Datatables::of(CustomField::getList())
                    ->addColumn('action', function ($data){
                        return CustomField::actionButtons($data);
                    })->rawColumns(['action'])->make(true);
        }
        return view($this->mainViewFolder . 'index');
    }

    public function create()
    {
        $model = new CustomField();
        $len = 1;
        if (!empty(session()->getOldInput())) {
            $len = count(session()->getOldInput()['field']);
        }
        $fieldTypes = $this->fieldTypes;
        return view($this->mainViewFolder . 'form', compact('model', 'len', 'fieldTypes'));
    }

    public function edit($model)
    {
        $model = CustomField::where('model', $model)->get()->toArray();

        $len = 1;
        if (!empty($model)) {
            $len = count($model);
        }
        if (!empty(session()->getOldInput())) {
            $len = count(session()->getOldInput()['field']);
        }
        $fieldTypes = $this->fieldTypes;
        return view($this->mainViewFolder . 'form', compact('model', 'len', 'fieldTypes'));
    }

    public function show($model)
    {
        $model = CustomField::where('model', $model)->get()->toArray();
        $len = 1;
        if (!empty($model)) {
            $len = count($model);
        }
        if (!empty(session()->getOldInput())) {
            $len = count(session()->getOldInput()['field']);
        }
        return view($this->mainViewFolder . 'show', compact('model', 'len'));
    }

    public function store(Request $request)
    {
        $request->validate(CustomField::validationRules());
        CustomField::validate($request);

        foreach ($request->field as $sr => $row) {
            $row['model'] = $request->model;
            if (@$row['delete'] == 1) {
                $model = CustomField::find($row['id']);
                $model->forceDelete();
            }else{
                $row['name'] = \Common::clean($row['title']);
                $row['rules'] = CustomField::makeRules($row);
                $row['acceptable'] = CustomField::serializeAcceptable($row['acceptable']);

                $model = new CustomField($row);

                if(!empty(@$row['id'])){
                    $model = CustomField::find($row['id']);
                    $model->loadModel($row);
                }

                if (!$model->save())
                    throw new \Exception($this->setAlertError('CustomField', 'update'));
            }
        }

        $request->session()->flash('success', $this->setAlertSuccess('CustomField', 'update', $request->model));
        return redirect(route('customfield.index'));
    }
}

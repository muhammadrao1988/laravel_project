<?php

namespace App\Http\Controllers\Admin\States;

use App\Helpers\Common;
use App\Models\GiftIdea;
use App\Http\Controllers\Controller;
use App\Models\States;
use Illuminate\Http\Request;
use App\TraitLibraries\AlertMessages;
use DataTables;
use App\Models\User;
use App\Models\Website;
use App\Http\Requests\StoreGiftIdeasRequest;
use App\Http\Requests\UpdateGiftIdeasRequest;
use App\Models\Category;
use App\Http\Requests\StoreCategoriesRequest;
use Validator;
use Storage;


class ManageStateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     *
     */
    use AlertMessages;

    public $module = "States";

    protected $mainViewFolder = 'admin.states.manage-states.';


    public $permissions = Array(
        "index" => "read",
        "show" => "read",
        "create" => "create",
        "update" => "update",
        "edit" => "update",
        "destroy" => "delete",
    );


    public function __construct()
    {
        if(isset($this->module)){
          \View::share('module', $this->module);
        }
    }
    public function index(Request $request)
    {
        if($request->ajax()){
            return  Datatables::of(States::orderBy('name','asc')->get())
                    ->addColumn('action', function ($data){
                        return States::actionButtons($data);
                    })->editColumn('tax_rate', function ($data){
                        return $data->tax_rate."%";
                    })->editColumn('active', function ($data){
                        if($data->active==1){
                            return '<span class="btn btn-success btn-sm">Active</span>';
                        }else{
                            return '<span class="btn btn-danger btn-sm">InActive</span>';
                        }
                    })->rawColumns(['action','active'])->make(true);
        }
        return view($this->mainViewFolder . 'index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = new States();

        return view($this->mainViewFolder . 'form', compact('model'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(States::validationRules(@$request->id));

        $model = new States($request->all());
        $action = "create";
        $redirect = 'states';

        if(!empty(@$request->input('id'))){
            $model = States::find($request->input('id'));
            $model->loadModel($request->all());
            $action = "update";
            $redirect = 'states';
        }

        $flagName = "State Tax";
        $model->tax_rate = Common::decimalPlace($model->tax_rate,2);

        if (!$model->save())
            throw new \Exception($this->setAlertError($flagName, $action));

        $alertMessage = $this->setAlertSuccess($flagName, $action, $model->id);

        $request->session()->flash('success', $alertMessage);

        return redirect(route('states.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
        $model = States::find($id);
        if(!empty($model)){
            return view($this->mainViewFolder.'show', compact('model'));
        }
        else{
            $request->session()->flash('warning', $this->setAlertError('Cattegory', 'none'));
            return redirect(route('Category.index'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = States::find($id);
        return view($this->mainViewFolder . 'form', compact('model'));
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $record = States::find($id);
        if(empty($record->id)){
            $request->session()->flash('success', 'State not found');
            return redirect()->route('states.index');
        }
        if(Website::where('state','=',$record->name)->count() > 0){
            $request->session()->flash('warning', 'Unable to delete record, it is using in Giftee account.');
            return redirect()->route('states.index');
        }
        if ($record->delete()) {
            $request->session()->flash('warning', $this->setAlertSuccess('States', 'delete'));
        }
        return redirect()->route('states.index');
    }
}

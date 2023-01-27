<?php

namespace App\Http\Controllers\Admin\Giftee;

use App\Http\Controllers\BaseController;
use App\Models\Admin;
use App\Models\Customer;
use App\Models\Website;
use App\TraitLibraries\AlertMessages;
use DemeterChain\C;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use App\Models\Role;
use App\Models\User;
use App\Models\Flag;
use App\Models\Parties;
use Common;

class ManageGifteeController extends BaseController
{
    use AlertMessages;

    public $module = "Giftee";

    protected $mainViewFolder = 'admin.giftee.manage-giftee.';

    protected $_user;

    public $permissions = Array(
        "index" => "read",
        "show" => "read",
        "create" => "create",
        "update" => "update",
        "edit" => "update",
        "destroy" => "delete",
    );

    public function index(Request $request)
    {
        if($request->ajax()){
            return  Datatables::of(Website::getList())
                    ->addColumn('action', function ($data){
                        return Website::actionButtons($data);
                    })->editColumn('created_at', function ($data){
                        return \App\Helpers\Common::CTL($data->created_at);
                    })->addColumn('active', function ($data){
                        if($data->active==1) {
                            return '<span class="btn btn-success btn-sm">Active</span>';
                        }else if($data->active==-1){
                            return '<span class="btn btn-danger btn-sm">Deactivated</span>';
                        }else{
                            return '<span class="btn btn-danger btn-sm">Inactive</span>';
                        }
                    })->rawColumns(['action','active'])->make(true);
        }
        return view($this->mainViewFolder . 'index');
    }

    public function create()
    {
        $model = new Website();
        return view($this->mainViewFolder . 'form', compact('model'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate(Website::validationRulesAdmin(!empty($request->id) ? $request->id : 0),Website::validationMsgs(),['name'=>'Full name','zip'=>'Zipcode']);
        $action = "create";
        $created_updated_by = auth()->guard('admin')->user()->username;

        if(!empty(@$request->input('id'))){
            $request->merge(['alphaRole'=>'USERS','updated_by'=>$created_updated_by]);
            //$model = Website::userCreateOrUpdate(@$request->input('id'),$request);
            $website = new Website();
            $model =$website->userCreateOrUpdate(@$request->input('id'),$request);
            $action = "update";
        }else{
            $request->merge(['alphaRole'=>'USERS','created_by'=>$created_updated_by]);
            $website = new Website();
            $model =$website->userCreateOrUpdate(-1,$request);
        }

        $request->session()->flash('success', $this->setAlertSuccess('Giftee', $action));

        $redirect = 'giftee.index';
        return redirect()->route($redirect);

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
        $model = Website::find($id);
        $notification_setting = $model->notificationSettings()->get()->pluck('setting_name')->toArray();
        $email_notification_setting = $model->emailNotificationSettings()->get()->pluck('setting_name')->toArray();
        if(!empty($model)){

            return view($this->mainViewFolder.'show', compact('model','notification_setting','email_notification_setting'));
        }
        else{
            $request->session()->flash('warning', $this->setAlertError('Giftee', 'none'));
            return redirect(route('giftee.index'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = Website::find($id);
        $model->password = null;
        return view($this->mainViewFolder . 'form', compact('model'));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $user = Website::find($id);
        if ($user->delete()) {
            $request->session()->flash('success', $this->setAlertSuccess('Giftee', 'delete'));
        }
        return redirect(route('giftee.index'));
    }
}

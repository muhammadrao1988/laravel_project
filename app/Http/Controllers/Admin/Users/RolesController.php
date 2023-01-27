<?php

namespace App\Http\Controllers\Admin\Users;

use App\TraitLibraries\AlertMessages;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use App\Models\Role;
use App\TraitLibraries\ResponseWithHttpStatus;

class RolesController extends Controller
{
    public $module = "Role";

    protected $mainViewFolder = 'admin.users.roles.';

    public $permissions = array(
        "index" => "read",
        "view" => "read",
        "show" => "read",
        "create" => "create",
        "update" => "update",
        "edit" => "update",
        "destroy" => "delete",
        "assignPermission" => "create",
        "addsPermissions" => "create",
        "removesPermissions" => "create"
    );

    use RegistersUsers, AlertMessages, ResponseWithHttpStatus;

    /**
     * The Controller Model repository instance.
     */
    protected $_role;
    protected $_validator;


    /**
     * Create a new controller instance.
     *
     * @param Client $user
     * @return void
     */
    public function __construct(Role $role)
    {
        $this->_role = $role;
        if(isset($this->module)){
            \View::share('module', $this->module);
        }
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return Datatables::of(Role::getList())
                ->addColumn('action', function ($data) {
                    return Role::actionButtons($data);
                })->editColumn('created_at', function ($data) {
                    return $data->created_at;
                })->editColumn('updated_at', function ($data) {
                    return $data->updated_at;
                })->rawColumns(['action'])->make(true);
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
        $model = $this->_role;
        $model->permissions = json_encode([], 1);
        return view($this->mainViewFolder . 'form', compact('model'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = $this->_role->rule;
        $rules['roleName'] = ['required', 'unique:roles,roleName'];
        $this->validate($request, $rules);

        try {
            // Start Transaction
            DB::beginTransaction();
            $this->_role = new Role($request->all());
            $permissions = $request->input('permissions');
            $this->_role->permissions = json_encode($permissions);

            if (!$this->_role->save())
                throw new \Exception($this->setAlertError('Role'));

            DB::commit();

            $alertMessage = $this->setAlertSuccess('Role', 'create');

            if (\Common::isAPIRequest()) {
                return $this->responseSuccess($alertMessage, $this->_role, 200);
            }

            $request->session()->flash('success', $alertMessage);

            $redirect = 'roles.index';

            if ($request->save_btn && $request->save_btn == config('constants.SAVE_ADD_MORE')) {
                $redirect = 'roles.create';
            }

            return redirect()->route($redirect);

        } catch (\Exception $exception) {
            DB::rollBack();
            if (\Common::isAPIRequest()) {
                return $this->responseFailure($exception->getMessage(), 500);
            }
            $request->session()->flash('error', $exception->getMessage());
        }

        return back()->withInput($request->all());
    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = $this->_role->findOrFail($id);
        return view($this->mainViewFolder . 'view', ['model' => $model]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = $this->_role->findOrFail($id);
        return view($this->mainViewFolder . 'form', compact('model'));
    }


    /**
     * Update the specified resource in storage.
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {
        //returns if validation false...
        $this->validate($request, $this->_role->rule);

        try {
            // Start Transaction
            DB::beginTransaction();
            $model = $this->_role->findOrFail($id);
            //set attributes ..
            $model->roleName = $request->roleName;

            $permissions = json_decode($model->permissions, 1);

            $removes = array_diff($permissions, $request->get('permissions'));
            $adds = array_diff($request->get('permissions'), $permissions);

            $permissions = $this->addsPermissions($adds, $permissions);
            $permissions = $this->removesPermissions($removes, $permissions);


            $model->permissions = json_encode($request->get('permissions'));

            if (!$model->save())
                throw new \Exception($this->setAlertError('Role', 'update'));

            DB::commit();

            $alertMessage = $this->setAlertSuccess('Role', 'update', $model->id);
            if (\Common::isAPIRequest()) {
                return $this->responseSuccess($alertMessage, $model, 200);
            }

            $request->session()->flash('success', $alertMessage);
            return redirect(route('roles.index'));

        } catch (\Exception $exception) {
            DB::rollBack();
            if (\Common::isAPIRequest()) {
                return $this->responseFailure($exception->getMessage(), 500);
            }
            $request->session()->flash('error', $exception->getMessage());
        }

        return back()->withInput($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        try {
            $model = $this->_role->findOrFail($id);
            if ($model->delete()) {
                $alertMessage = "Role #".$model->id." deleted successfully.";
                if (\Common::isAPIRequest()) {
                    return $this->responseSuccess($alertMessage, $model, 200);
                }
                $request->session()->flash('success', $alertMessage);
            }
        } catch (\Exception $e) {
            if (\Common::isAPIRequest()) {
                return $this->responseFailure($e->getMessage(), 500);
            }
            $request->session()->flash('error', $e->getMessage());
        }
        return back();

    }
    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
//    public function permissions($id)
//    {
//        $model = $this->_role->findOrFail($id);
//
//        return view($this->mainViewFolder . 'permissions', ['model' => $model]);
//
//    }

    /**
     * Toggle Permissions with the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function assignPermission(Request $request)
    {
        try {
            // Start Transaction
            DB::beginTransaction();

            if (!$request->ajax() && !$request->isMethod('PUT'))
                abort(403, 'Unauthorized action.');

            $model = $this->_role->findOrFail((int)$request->input('roleId'));

            if (empty($request->input('permission')))
                throw new \Exception("Unauthorized Post Data.");

            $permissions = json_decode($model->permissions, 1);
            $selectedPermission = $request->input('permission');

            if (in_array($selectedPermission, $permissions, true)) {
                $permissions = $this->removesPermissions([$selectedPermission], $permissions);
            } else {
                $permissions = $this->addsPermissions([$selectedPermission], $permissions);
            }

            $model->permissions = json_encode($permissions, 1);

            if (!$model->save())
                throw new \Exception($this->setAlertError('Role', 'update'));

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return json_encode(['status' => false, 'message' => $e->getMessage()]);
        }

        return json_encode(['status' => true, 'message' => $this->setAlertSuccess($selectedPermission, 'update')]);
    }

    protected function addsPermissions($adds, $permissions)
    {
        // assign permission to user
        foreach ($adds as $permission):
            $permissions[] = $permission;
        endforeach;

        return $permissions;
    }

    protected function removesPermissions($removes, $permissions)
    {
        // assign permission to user
        foreach ($removes as $permission):
            if (in_array($permission, $permissions, true)) {
                foreach ($permissions as $key => $value):
                    if ($value == $permission)
                        unset($permissions[$key]);
                endforeach;
            }
        endforeach;
        return $permissions;
    }

}

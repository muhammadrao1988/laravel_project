<?php

namespace App\Http\Controllers\Admin\CronJob;

use App\Http\Controllers\BaseController;
use App\Models\CronJob;
use App\TraitLibraries\AlertMessages;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use ReflectionClass;

class ManageCronJobController extends BaseController
{
    use AlertMessages;

    public $module = "CronJob";

    public $permissions = array(
        "index" => "read",
        "show" => "read",
        "create" => "create",
        "edit" => "update",
        "destroy" => "delete",
    );

    protected $mainViewFolder = 'admin.cron-job.manage-cron-job.';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return Datatables::of(CronJob::getList())
                ->addColumn('action', function ($data) {
                    return CronJob::actionButtons($data);
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
        $functions = collect((new ReflectionClass('\App\Helpers\Cron'))->getMethods())->pluck('name');
        $model = new CronJob();
        $compact = compact( 'model','functions');

        return view($this->mainViewFolder . 'form', $compact);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(CronJob::validationRules());

        $model = new CronJob($request->all());
        $action = "create";
        $redirect = 'cronJob.create';

        if (!$model->save())
            throw new \Exception($this->setAlertError('Courier', $action));

        $alertMessage = $this->setAlertSuccess('Courier', $action, $model->id);

        $request->session()->flash('success', $alertMessage);

        return redirect()->route($redirect);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $functions = collect((new ReflectionClass('\App\Helpers\Cron'))->getMethods())->pluck('name');
        $model = CronJob::findOrFail($id);
        $compact = compact('model', 'functions');
        return view($this->mainViewFolder . 'form', $compact);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate(CronJob::validationRules($id));

        $model = CronJob::findOrFail($id);

        if(!$request->active){
            $model->active = false;
            $model->save();
        }

        if($request->active){
            $model->active = true;
            $model->save();
        }

        $model->update($request->all());
        $action = "update";
        $redirect = 'cronJob.index';

        if (!$model->save())
            throw new \Exception($this->setAlertError('Courier', $action));

        $alertMessage = $this->setAlertSuccess('Courier', $action, $model->id);

        $request->session()->flash('success', $alertMessage);

        return redirect()->route($redirect);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Flag;
use App\Models\TransporterLead;
use App\Models\VehicleType;
use App\TraitLibraries\ResponseWithHttpStatus;
use Illuminate\Http\Request;
use App\Http\Controllers\TransporterLead\ManageTransporterLeadController as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ManageTransporterLeadController extends BaseController
{
    use ResponseWithHttpStatus;

    public function __construct(TransporterLead $salesLead)
    {
        $this->model = $salesLead;
    }

    public function index(Request $request)
    {
        $result['vehicleTypes'] = VehicleType::where('active',1)->select('id','name')->get();
        return $this->responseSuccess(config('api.response.messages.200'), $result, 200);
    }

    public function show( $id)
    {
        return false;
    }

    public function store(Request $request)
    {
        return parent::store($request);

    }
}

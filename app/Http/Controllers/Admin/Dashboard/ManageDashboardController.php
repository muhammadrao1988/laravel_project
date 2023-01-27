<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Helpers\Common;
use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Models\Dashboard;

class ManageDashboardController extends BaseController
{
    public $module = "Dashboard";

    protected $mainViewFolder = 'admin.dashboard.manage-dashboard.';

    public function index(Request $request)

    {

        return view($this->mainViewFolder . 'index');
    }


}

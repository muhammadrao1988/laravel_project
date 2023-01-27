<?php

namespace App\Models;

use App\Helpers\Common;
use App\Models\Report;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\IbShipmentLpn;

class Dashboard extends Model
{
    public static function RWO()
    {
        $return = array();
        $result = Report::RWPP((object)array());
        if (!empty($result)) {
            foreach ($result as $row) {
                $return['labels'][] = $row->room;

                $OPercentage = 0;
                if ($row->UP > 0 && $row->FP > 0) {
                    $OPercentage = Common::decimalPlace((($row->FP / $row->UP) * 100), 2);
                }

                $EPercentage = 0;
                if ($row->UP > 0 && $row->EP > 0) {
                    $EPercentage = \Common::decimalPlace((($row->EP / $row->UP) * 100), 2);
                }

                $return['values']['occupancy'][] = $OPercentage;
                $return['values']['empty'][] = $EPercentage;
            }
        }
        return json_encode($return);
    }

    public static function STSR()
    {
        $return = array();
        $request['fromDate'] = date('Y-m-01');
        $request['toDate'] = date('Y-m-d');
        $result = Report::STSR((object)$request);

        $receivedQty = 0;
        $dispatchedQty = 0;
        $closingQty = 0;

        $receivedWeight = 0;
        $dispatchedWeight = 0;
        $closingWeight = 0;

        if (!empty($result)) {
            foreach ($result as $row) {
                $receivedQty += $row->receivedQty;
                $dispatchedQty += $row->dispatchedQty;
                $closingQty += $row->closingQty;

                $receivedWeight += $row->receivedWeight;
                $dispatchedWeight += $row->dispatchedWeight;
                $closingWeight += $row->closingWeight;
            }
        }

        $return['labels'][] = 'Received';
        $return['labels'][] = 'Dispatched';
        $return['labels'][] = 'Closing';

        $return['values']['quantity'][] = Common::decimalPlace($receivedQty, 2);
        $return['values']['quantity'][] = Common::decimalPlace($dispatchedQty, 2);
        $return['values']['quantity'][] = Common::decimalPlace($closingQty, 2);

        $return['values']['weight'][] = Common::decimalPlace($receivedWeight, 2);
        $return['values']['weight'][] = Common::decimalPlace($dispatchedWeight, 2);
        $return['values']['weight'][] = Common::decimalPlace($closingWeight, 2);
        return json_encode($return);
    }

    public static function HVCR($request)
    {
        $return = array();
        if (!empty($request->reportType)) {
            if ($request->reportType == "YTD") {
                $requestReturn['fromDate'] = date('Y') . '-01-01';
                $requestReturn['toDate'] = date('Y-m-d');
                $request->session()->put('HVCR_TYPE', "YTD");
            } else {
                $requestReturn['fromDate'] = date('Y-m') . '-01';
                $requestReturn['toDate'] = date('Y-m-d');
                $request->session()->put('HVCR_TYPE', "MTD");
            }

        } else {
            $requestReturn['fromDate'] = ($request->session()->has('HVCR_TYPE') && $request->session()->get('HVCR_TYPE') == "YTD") ? date('Y') . '-01-01' : date('Y-m') . "-01";
            $requestReturn['toDate'] = date('Y-m-d');
        }
        $requestReturn['orderBy'] = 'DESC';
        $requestReturn['limitRecord'] = 10;

        $result = Report::HVCR((object)$requestReturn);
        if (!empty($result)) {
            foreach ($result as $row) {
                if(@$row->totalWeight > 0){
                    $return['labels'][] = $row->partyName;
                    $return['values'][] = $row->totalWeight;
                    $return['color'][] = Common::getRandomColor();
                }
            }
        }
        return json_encode($return);

    }

    public static function SABPC($request)
    {
        $return = array();
        if (!empty($request->reportRoom)) {
            $requestReturn['aisle'] = $request->reportRoom;
        }
        $requestReturn['dashboardGraph'] = 1;


        $result = Report::SAPC((object)$requestReturn, 1);
        if (!empty($result)) {
            foreach ($result as $row) {
                if(@$row->closingWeight > 0){
                    $return['labels'][] = $row->graphParent;
                    $return['values'][] = $row->closingWeight;
                    $return['color'][] = Common::getRandomColor();
                }
            }
        }
        return json_encode($return);
    }

    public static function CVO()
    {
        $result = Report::CVO();
        return json_encode($result);
    }

    public static function PRF()
    {
        $result = Report::PRF();
        return json_encode($result);
    }

    public static function productPendingPutawayCount()
    {
        request()->merge(['fromCreateDate' => date("Y-01-01", strtotime("-2 year")), 'toCreateDate' => date('Y-m-d')]);
        request()->merge(['lpnStatus' => ['Received']]);
        $sql = IbShipmentLpn::search(request());
        return collect(DB::select($sql))->sum('receivedQty');
    }

    public static function productExpiringCount()
    {
        request()->merge(['dayBucket' => "30"]);
        return collect(Report::SRBE(request()))->unique('itemCode')->count();
    }

    public static function expiredProductCount()
    {
        request()->merge(['dayBucket' => "0"]);
        return collect(Report::SRBE(request()))->unique('itemCode')->count();
    }

    public static function productWithoutExpiryCount()
    {
        request()->merge(['dayBucket' => "no-expiry"]);
        return collect(Report::SRBE(request()))->unique('itemCode')->count();
    }

    public static function orderInProgressCount()
    {
        $result = DispatchOrder::getList(request());
        return $result->pluck('status')->filter(function ($p) {
            return in_array($p, ['Partly Allocated', 'Allocated', 'Partly Picked', 'Picked', 'Partly Shipped']);
        })->count();
    }

}

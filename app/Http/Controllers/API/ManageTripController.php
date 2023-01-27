<?php

namespace App\Http\Controllers\API;

use App\Helpers\Common;
use App\Models\OrderAddresses;
use App\Models\SmsLogs;
use App\TraitLibraries\AlertMessages;
use App\TraitLibraries\ResponseWithHttpStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\Trip;
use App\Models\Driver;
use App\Http\Controllers\Trip\ManageTripController AS ParentControlller;

class ManageTripController extends ParentControlller
{
    use AlertMessages,ResponseWithHttpStatus;


    public function __construct(Trip $trip)
    {
        $this->model = $trip;
    }

    public function getAssignTripListing(Request $request)
    {
        try
        {
            $driver=Driver::where('userId',Auth::id())->where('active',1)->first();
            if(!empty($driver->id))
            {
                $param['driverId'] = $driver->id;
                $param['assignedTrip'] = 1;
                $trip = Trip::getTrips($param);
                return $this->responseSuccess(config('api.response.messages.200'), $trip, 200);
            }
            else
                return $this->responseFailure(config('api.response.messages.401'), 401);
        }
        catch (\Exception $exception) {
            return $this->responseFailure($exception->getMessage(), 500);
        }

    }

    public function getAllTripListing(Request $request)
    {
        try
        {
            $driver=Driver::where('userId',Auth::id())->where('active',1)->first();
            if(!empty($driver->id))
            {
                $param['driverId'] = $driver->id;
                $trip = Trip::getTrips($param);
                return $this->responseSuccess(config('api.response.messages.200'), $trip, 200);
            }
            else
                return $this->responseFailure(config('api.response.messages.401'), 401);
        }
        catch (\Exception $exception) {
            return $this->responseFailure($exception->getMessage(), 500);
        }

    }
    public function changeTripStatus(Request $request)
    {
        try
        {
            $validator = \Validator::make($request->all(), [
                'tripId' =>  'required|integer',
                'shipmentId' =>  'required',
                'status' =>  'required|string',
                'otp_code' => 'required_if:status,Picked Up,Delivered'
            ]);
            if ($validator->fails()) {
                return $this->responseFailure($validator->errors()->first(), 422);
            }

            $driver=Driver::with('user')->where('userId',Auth::id())->where('active',1)->first();

            if(!empty($driver->id))
            {
                $msg="";
                $param['driverId'] = $driver->id;
                $param['id'] = $request->tripId;
                switch ($request->status) {
                    case "Planned":
                      $trip = Trip::tripStarted($request->tripId);
                      if(!empty($trip['success']))
                      {
                            $trip=Trip::getTrips($param);
                            if(count($trip)>0 &&  !empty($trip[0]['shipmentSource']->phone) )
                            {
                                $message ="Dear User, Your trip # ".$trip[0]['documentId']." source pick up code is ";
                                SmsLogs::saveSMS($trip[0]['shipmentSource']->phone,$driver->userId,'pick_up_otp_code',$message,$request->tripId,$trip[0]['shipmentId']);
                            }
                          return $this->responseSuccess(config('api.response.messages.200'), $trip, 200);
                      }
                      else
                          $msg=$trip['msg'];
                    break;
                    case "Cancelled":
                      $trip = Trip::tripCancelled($request->tripId);
                      if(!empty($trip['success']))
                      {
                         return $this->responseSuccess(config('api.response.messages.200'), $trip, 200);
                      }
                      else
                          $msg=$trip['msg'];
                    break;
                    case "Picked Up":
                        //$trip=Trip::getTripInfo($request->tripId);
                        $trip=Trip::getTrips($param);
                        if(count($trip)>0 &&  !empty($trip[0]['shipmentSource']->phone) ) {
                            $verify = SmsLogs::verifyOtp($trip[0]['shipmentSource']->phone, $request->otp_code, 'pick_up_otp_code', $request->tripId, $request->shipmentId);

                            if ($verify['success']) {
                                $request->merge(['shipmentId'=>explode(',',$request->shipmentId)]);
                                $trip_info = Trip::tripPickedUp($request->tripId,$request);
                                if (!empty($trip_info['success'])) {
                                    if (count($trip) > 0 && !empty($trip[0]['shipmentDestination']->phone)) {
                                        $message = "Dear User, Your trip # " . $trip[0]['documentId'] . " destination delivery code is ";
                                        SmsLogs::saveSMS($trip[0]['shipmentDestination']->phone, $driver->userId, 'delivery_otp_code', $message, $request->tripId, $trip[0]['shipmentId']);
                                    }
                                    return $this->responseSuccess(config('api.response.messages.200'), $trip, 200);
                                } else
                                    $msg = $trip_info['msg'];
                            } else
                                return $this->responseFailure(config('api.response.messages.423'), 423);
                        }
                        else
                            return $this->responseFailure(config('api.response.messages.423'), 423);
                    break;
                    case "Delivered":
                        //$trip=Trip::getTripInfo($request->tripId);
                        $trip=Trip::getTrips($param);
                        if(count($trip)>0 &&  !empty($trip[0]['shipmentDestination']->phone) ) {

                            $verify = SmsLogs::verifyOtp($trip[0]['shipmentDestination']->phone, $request->otp_code, 'delivery_otp_code', $request->tripId, $request->shipmentId);
                            if ($verify['success']) {
                                $request->merge(['shipmentId'=>explode(',',$request->shipmentId)]);
                                $trip_info = Trip::tripDelivered($request->tripId,$request);
                                if (!empty($trip_info['success'])) {
                                    return $this->responseSuccess(config('api.response.messages.200'), $trip, 200);
                                } else
                                    $msg = $trip_info['msg'];
                            } else
                                return $this->responseFailure(config('api.response.messages.423'), 423);
                        }
                        else
                        return $this->responseFailure(config('api.response.messages.423'), 423);
                    break;
                }

                return $this->responseFailure($msg, 403);

            }
            else
                return $this->responseFailure(config('api.response.messages.401'), 401);
        }
        catch (\Exception $exception) {
            return $this->responseFailure($exception->getMessage(), 500);
        }

    }

}

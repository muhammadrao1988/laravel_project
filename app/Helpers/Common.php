<?php

namespace App\Helpers;

use App;
use Carbon\Carbon;
use Config;
use App\managers\UserManager;
use App\Models\CycleCount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use App\TraitLibraries\ResponseWithHttpStatus;
use App\Models\Role;
use App\Models\UserRole;
use App\Models\Parties;
use App\Models\Configuration;
use App\Models\EmailLog;
use App\Models\CustomField;
use App\Models\CustomFieldResponse;
use Illuminate\Support\Facades\Log;
use JamesMills\LaravelTimezone\Timezone;
use mysql_xdevapi\Exception;

class Common
{
    use ResponseWithHttpStatus;

    public static function getConfig($module, $key)
    {
        $model = Configuration::where('module', $module)->where('key', $key)->get()->first();

        if (!empty($model)) {
            return $model->value;
        } else {
            return "";
        }
    }

    public static function getSetting($module, $name, $key, $value = "false", $description = "", $isCreate = 1)
    {
        $model = Configuration::where('module', $module)->where('name', $name)->where('key', $key)->get()->first();
        if (empty($model) && $isCreate == 1) {
            $model = new Configuration();
            $model->module = $module;
            $model->name = $name;
            $model->key = $key;
            $model->description = $description;
            $model->value = $value;
            $model->forAdmin = 1;
            $model->save();
        }

        if (!empty($model)) {
            return $model->value;
        } else {
            return "";
        }
    }

    public static function getCustomFields($model)
    {
        $return = array();
        $result = CustomField::where('model', $model)->orderBy('sort')->get();

        if ($result->count() > 0) {
            $return = $result;
        }
        return $return;
    }

    public static function getCustomFieldRules($model)
    {
        $rules = array();
        foreach (SELF::getCustomFields($model) as $row) {
            if (!empty($row->rules)) {
                $rules[$row->name] = $row->rules;
            }
        }
        return $rules;
    }

    public static function getCustomFieldValue($model, $fieldName, $modelId = 0)
    {
        $return = "";
        $fieldId = CustomField::where("model", $model)->where("name", $fieldName)->value('id');
        if (!empty($fieldId)) {
            $result = CustomFieldResponse::where('modelId', $modelId)->value('response');
            if (!empty($result)) {
                $return = @$result[$fieldName];
            }
        }
        return $return;
    }

    public static function select2Ajax()
    {
        $request = (object)$_POST;
        $return = [];
        if (!empty(@$request->term)) {
            if (@$request->source == "customer") {
                $query = "SELECT
                                id,
                                fullName as text
                            FROM parties
                            WHERE 1 AND type = 'customer'";

                if (@$request->isDefault == 1) {
                    $query .= " AND id = '" . $request->term . "'";
                } else {
                    $query .= " AND (fullName LIKE '%" . $request->term . "%')";
                }
            }

            if (@$request->source == "flag") {
                $query = "SELECT
                                id,
                                name as text
                            FROM flags
                            WHERE 1 AND active = 1";

                if (!empty(@$request->filter)) {
                    $query .= " AND flagType = '" . @$request->filter . "'";
                }

                if (@$request->isDefault == 1) {
                    $query .= " AND id = '" . $request->term . "'";
                } else {
                    $query .= " AND (name LIKE '%" . $request->term . "%')";
                }
            }

            if (@$request->source == "flag-for-parent") {
                $query = "SELECT
                                flags.id,
                                Concat(flags.name, ' - ', FT.name) as text
                            FROM flags
                            LEFT JOIN flag_types FT ON FT.code = flags.flagType
                            WHERE 1 AND flags.active = 1";

                if (!empty(@$request->filter)) {
                    $query .= " AND flags.flagType = '" . @$request->filter . "'";
                }

                if (@$request->isDefault == 1) {
                    $query .= " AND flags.id = '" . $request->term . "'";
                } else {
                    $query .= " AND (flags.name LIKE '%" . $request->term . "%')";
                }
            }

            if (@$request->source == "flag-name") {
                $query = "SELECT
                                name as id,
                                name as text
                            FROM flags
                            WHERE 1 AND active = 1";

                if (!empty(@$request->filter)) {
                    $query .= " AND flagType = '" . @$request->filter . "'";
                }

                if (@$request->isDefault == 1) {
                    $query .= " AND name = '" . $request->term . "'";
                } else {
                    $query .= " AND (name LIKE '%" . $request->term . "%')";
                }
            }

            if (@$request->source == "flagType") {
                $query = "SELECT
                                id,
                                name as text
                            FROM flag_types
                            WHERE 1 AND active = 1";

                if (@$request->isDefault == 1) {
                    $query .= " AND id = '" . $request->term . "'";
                } else {
                    $query .= " AND (name LIKE '%" . $request->term . "%')";
                }
            }

            if (@$request->source == "flagType-code") {
                $query = "SELECT
                                code as id,
                                name as text
                            FROM flag_types
                            WHERE 1 AND active = 1";

                if (@$request->isDefault == 1) {
                    $query .= " AND code = '" . $request->term . "'";
                } else {
                    $query .= " AND (name LIKE '%" . $request->term . "%')";
                }
            }
        }
    }

    public static function getCompany()
    {
        return explode('.', @$_SERVER['HTTP_HOST'])[0];
    }

    public static function getCustomerRolePartyId()
    {
        if (!empty(auth()->user()->id)) {
            $roleId = UserRole::where('user_id', auth()->user()->id)->pluck('role_id')->first();
        }
        return 0;
    }

    public static function getRoleName()
    {
        if (!empty(auth()->user())) {
            $roleId = UserRole::where('user_id', auth()->user()->id)->pluck('role_id')->first();
            if (!empty($roleId)) {
                $roleName = Role::where('id', $roleId)->pluck('roleName')->first();
                return strtolower($roleName);
            }
        }
        return "";
    }

    private static function like($str, $searchTerm)
    {
        $searchTerm = strtolower($searchTerm);
        $str = strtolower($str);
        $pos = strpos($str, $searchTerm);
        if ($pos === false)
            return false;
        else
            return true;
    }

    public static function importDateFormatter($dateTime)
    {
        $date = $dateTime;
        $type = gettype($dateTime);

        if ($type == "string") {

            $date = null;
            if (!empty($dateTime)) {
                $strtotime = strtotime($dateTime);
                if ($strtotime == false) {
                    $strtotime = strtotime(str_replace('-', '/', $dateTime));
                }

                if ($strtotime == false) {
                    $strtotime = strtotime(str_replace('/', '-', $dateTime));
                }

                $date = date('Y-m-d', $strtotime);
            }
        }

        if ($type == "double" || $type == "integer") {
            $dateTime = ($dateTime - 25569) * 86400;
            $date = gmdate("Y-m-d", $dateTime);
        }
        return $date;
    }

    public static function returnZero($value)
    {
        if (empty($value) || !isset($value) || is_nan($value)) {
            $value = 0;
        }
        return $value;
    }

    public static function throwValidationError($field, $message)
    {
        if (self::isAPIRequest()):
            return self::responseFailure($message, 422);
        endif;

        throw ValidationException::withMessages([$field => $message]);
    }

    public static function throwValidationErrorAjax($message)
    {
        if (self::isAPIRequest()):
            return self::responseFailure($message, 422);
        endif;

        echo json_encode($message);
        exit();
    }

    public static function decimalPlace($number, $place)
    {
        return number_format((float)$number, $place, '.', '');
    }

    public static function getWeekDay($weekDay)
    {
        $days = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
        return $days[$weekDay];
    }

    public static function numberFormat($number, $decimal = 2)
    {
        return number_format(SELF::returnZero($number), $decimal);
    }

    public static function convertToBoolean($val)
    {
        $boolean_values = ["yes" => 1, "y" => 1, "no" => 0, "n" => 0, "1" => 1, "0" => 0, "true" => 1, "false" => 0, "active" => 1, "inactive" => 0];
        $val = strtolower($val);
        if ($val == "") {
            return "0";

        } else if (array_key_exists($val, $boolean_values)) {
            return $boolean_values[$val];
        } else {
            return -1;
        }

    }

    public static function sendEmail($recipients, $subject, $body, $attachment = "", $attachmentName = "")
    {
        try {
            foreach ($recipients as $recipient) {
                \Mail::send([], [], function ($m) use ($recipient, $subject, $body, $attachment, $attachmentName) {
                    $m->to($recipient);
                    $m->subject($subject);
                    $m->setBody($body, 'text/html');
                    if ($attachment) {
                        $m->attach($attachment);
                    }
                });

                $response = "success";
                if (\Mail::failures()) {
                    $response = Mail::failures();
                        EmailLog::create([
                         'from' => env("MAIL_FROM_ADDRESS"),
                         'to' => $recipient,
                         'subject' => $subject,
                         'body' => $body,
                         'response' => $response,
                    ]);
                    return['success'=>false];
                }
                return['success'=>true];


            }
        }catch (\Exception $exception ){

            EmailLog::create([
                'from' => env("MAIL_FROM_ADDRESS"),
                'to' => $recipient,
                'subject' => $subject,
                'body' => $body,
                'response' => $exception->getMessage(),
            ]);
            return['success'=>false];

        }
    }

    public static function CTL($dateTime, $is_date = false, $dateFormat = "",$guard="admin")
    {

        if (empty($dateTime)) {
            return null;
        }
        if($tz = !empty(auth()->guard($guard)->user()->timezone)){
            $tz = auth()->guard($guard)->user()->timezone;
        }else{
            $tz ="US/Central";
        }

        if (empty($dateFormat)) {
            //$dateFormat = SELF::getConfig('company', 'dateFormat');
            $dateFormat = "m/d/Y";
        }

        if ($is_date) {
            return \Carbon\Carbon::parse($dateTime, 'UTC')->setTimezone($tz)->format($dateFormat);
            //return \Timezone::convertToLocal(\Carbon\Carbon::parse($dateTime), $dateFormat);
        } else if (!empty(explode(' ', $dateTime)[1])) {
            return \Carbon\Carbon::parse($dateTime, 'UTC')->setTimezone($tz)->format($dateFormat. ' g:i:s a');
            //return \Timezone::convertToLocal(\Carbon\Carbon::parse($dateTime), $dateFormat . ' g:i:s a');
        } else {
            return \Carbon\Carbon::parse($dateTime)->format($dateFormat);
        }
    }

    public static function humanReadTime($time,$timeZone)
    {
        if($timeZone==""){
            $timeZone ="US/Central";
        }
        return \Carbon\Carbon::parse(strtotime($time))->setTimezone($timeZone)->diffForHumans();
    }

    public static function CFL($dateTime)
    {
        if (empty($dateTime)) {
            return null;
        }
        $carbon = \Carbon\Carbon::parse($dateTime, auth()->guard('admin')->user()->timezone)->setTimezone('UTC');
        return $carbon->toDateTimeString();
    }

    public static function CAMPM($time)
    {
        if (!empty(($time))) {
            return date("h:i A", strtotime($time));
        }
    }

    public static function isDateTime($string = "")
    {
        if (!empty($string)) {
            if (\DateTime::createFromFormat('Y-m-d H:i:s', $string) !== false) {
                return SELF::CTL($string);
            }
        }
        return $string;
    }

    public static function userRole()
    {
        if (!empty(session('user_role'))) {
            return session('user_role');
        } else {
            $user_alpha_role = auth()->user()->alphaRole;
            session(['user_role' => $user_alpha_role]);
            return $user_alpha_role;
        }
    }

    public static function checkPermission($module, $key)
    {
        $user_role = SELF::userRole();
        if (strtolower($user_role) == strtolower(UserManager::AlphaRoleSuper)) {
            return true;
        } else {
            $permissions = session('permissions');
            $permissionName = strtolower($module . $key);
            if (!empty($permissions) && in_array($permissionName, $permissions)) {
                return true;
            } else {
                return false;
            }
        }
    }

    public static function canCreate($module)
    {
        return SELF::checkPermission($module, "Create");
    }

    public static function canRead($module)
    {
        return SELF::checkPermission($module, "Read");
    }

    public static function canUpdate($module)
    {
        return SELF::checkPermission($module, "Update");
    }

    public static function canDelete($module)
    {
        return SELF::checkPermission($module, "Delete");
    }

    public static function isAPIRequest()
    {
        if (request()->has('entryStatus') && request()->input('entryStatus') == config('api.entry_status')) {
            return true;
        }
        return false;
    }

    public static function getModels($path = false, $all = false)
    {
        if (!$path)
            $path = app_path() . "/Models";

        $ignore = array("BillingRules", "Carrier", "CarrierType", "Category", "IbShipment", "IbShipmentGateEntry", "IbShipmentItem", "IbShipmentLpn", "IbShipmentPutaway", "Item", "Location", "Order", "OrderItem", "Parties");
        $out = [];
        $results = scandir($path);
        foreach ($results as $result) {
            if ($result === '.' or $result === '..') continue;

            if ($all) {
                $filename = $path . '/' . $result;
                if (is_dir($filename)) {
                    $out = array_merge($out, self::getModels($filename, true));
                } else {
                    $out[] = substr($result, 0, -4);
                }
            } else {
                $filename = $result;
                if (in_array(substr($filename, 0, -4), $ignore)) {
                    if (is_dir($filename)) {
                        $out = array_merge($out, self::getModels($filename));
                    } else {
                        $model = 'App\Models\\' . substr($filename, 0, -4);
                        $out[] = $model::$module;
                    }
                }
            }
        }
        return $out;
    }

    public static function getEnvHost()
    {
        $path = base_path() . "/.env";

        $results = scandir($path);
        foreach ($results as $result) {
            if ($result === '.' or $result === '..') continue;
            $out[] = str_replace('..env.', '', $result);
        }
        return $out;
    }

    public static function getModelsCF()
    {
        $modelsCF = CustomField::distinct()->get(['model'])->pluck('model')->toArray();
        $models = SELF::getModels();
        $models = array_diff($models, $modelsCF);
        return $models;
    }

    public static function clean($string)
    {
        $string = preg_replace("/[^A-Za-z0-9 ]/", '', $string);
        $string = trim($string);
        $string = preg_replace('/\s+/', '_', $string);
        return strtolower($string);
    }

    public static function generateSpaceSeparatedCode($value)
    {
        if ($value != "") {
            $explode_val = explode(" ", $value);
            if (count($explode_val) == 1) {
                return strtoupper(substr($value, 0, 2));
            } else {
                $prefix = "";
                foreach ($explode_val as $i) {
                    $prefix .= substr($i, 0, 1);
                }
                return strtoupper($prefix);
            }
        } else {
            return false;
        }
    }

    public static function getRandomColor()
    {
        foreach (array('r', 'g', 'b') as $color) {
            $rgbColor[$color] = mt_rand(0, 255);
        }
        return "rgb(" . implode(",", $rgbColor) . ")";
    }

    public static function excelToDateTimeObject($value)
    {
        if (empty($value)) {
            return null;
        }
        return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
    }


    public static function getMonths($start = 1, $end = 12)
    {
        return array_reduce(range(1, 12), function ($rslt, $m) {
            $rslt[$m] = date('F', mktime(0, 0, 0, $m, 10));
            return $rslt;
        });
    }

    public static function getTotalMonthsBwDates($from, $to)
    {
        $fromYear = date("Y", strtotime($from));
        $fromMonth = date("m", strtotime($from));
        $toYear = date("Y", strtotime($to));
        $toMonth = date("m", strtotime($to));
        if ($fromYear == $toYear) {
            return ($toMonth - $fromMonth) + 1;
        } else {
            return (12 - $fromMonth) + 1 + $toMonth;
        }
    }

    public static function convertHMSToHours($time)
    {
        $hms = explode(":", $time);

        if (!isset($hms[0])) {
            $hms[0] = 1;
        }

        if (!isset($hms[1])) {
            $hms[1] = 0;
        }

        if (!isset($hms[2])) {
            $hms[2] = 0;
        }
        return ($hms[0] + ($hms[1] / 60) + ($hms[2] / 3600));
    }

    public static function hoursToMinutes($hours)
    {
        $minutes = 0;
        if (strpos($hours, ':') !== false) {
            // Split hours and minutes.
            list($hours, $minutes) = explode(':', $hours);
        }
        return $hours * 60 + $minutes;
    }

    public static function getScheduleType($scheduleId)
    {
        $result = \App\Models\ReportSchedule::where('id', $scheduleId)->first();
        $cronJob = \App\Models\CronJob::where('model_type', 'ReportSchedule')->where('model_id', $scheduleId)->first();

        if (@$cronJob->frequency_func == "everyMinute") {
            @$cronJob->frequency_func = "One Time At";
        }

        $day = " ";
        if (@$cronJob->frequency_func == "weeklyOn") {
            $day = " " . SELF::getWeekDay(@$cronJob->frequency_func_arg_1) . " At ";
        }

        if (@$cronJob->frequency_func == "monthlyOn") {
            $day = " " . @$cronJob->frequency_func_arg_1 . " At ";
        }

        return SELF::removeCamelCase(@$cronJob->frequency_func) . $day . SELF::getTimeOnly(SELF::CTL($result->startDateTime));
    }

    public static function removeCamelCase($string)
    {
        return ucwords(preg_replace('/([a-z])([A-Z])/s', '$1 $2', $string));
    }

    public static function getTimeOnly($dateTime)
    {
        return @explode(" ", $dateTime)[1] . " " . @explode(" ", $dateTime)[2];
    }

    public static function uploadFile($filePath, $file, $fileName = "")
    {
        $filePath = strtolower(env('APP_ENV')) . "/" . strtolower(env('APP_NAME')) . "/" . $filePath;
        if (empty($fileName)) {
            if (\Storage::put($filePath, $file, 'public')) {
                return $filePath;
            }
        } else {
            return \Storage::putFileAs($filePath, $file, $fileName, 'public');
        }
    }

    public static function isJSON($string)
    {
        return is_string($string) && is_array(json_decode($string, true)) ? true : false;
    }

    public static function getAddressLatitudeLongitude($address){
        $address =urlencode($address); // Google HQ
        $geocode=file_get_contents('https://maps.google.com/maps/api/geocode/json?address='.$address.'&key=AIzaSyCqzK1c3YOz2lyqHMzakPSgShHMOkTP-EM');
        $output= json_decode($geocode);
        $latitude = $output->results[0]->geometry->location->lat;
        $longitude = $output->results[0]->geometry->location->lng;
        return ['latitude'=>$latitude,'longitude'=>$longitude];

    }
    public static function encrypt_decrypt($string, $action = 'encrypt')
    {
        $encrypt_method = "AES-256-CBC";
        $secret_key = 'AA74CDCC2BBRT935D36HH7B63C27'; // user define private key
        $secret_iv = '5fgf5HJ5g23'; // user define secret key
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16); // sha256 is hash_hmac_algo
        if ($action == 'encrypt') {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        } else if ($action == 'decrypt') {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }
        return $output;
    }

    public static function from_camel_case($input) {
        $pattern = '!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!';
        preg_match_all($pattern, $input, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ?
                strtolower($match) :
                lcfirst($match);
        }
        return implode(' ', $ret);
    }

    public static function allCountry(){

// All countries
// length 252
        return $countries_list = array(
            "US" => "United States",

            "AS" => "American Samoa",

            "AR" => "Argentina",

            "AU" => "Australia",
            "AT" => "Austria",

            "BE" => "Belgium",

            "BR" => "Brazil",

            "CA" => "Canada",

            "CN" => "China",
            "CZ" => "Czech Republic",

            "FI" => "Finland",
            "FR" => "France",


            "DE" => "Germany",

            "GR" => "Greece",
            "GL" => "Greenland",

            "IS" => "Iceland",

            "IT" => "Italy",

            "JE" => "Jersey",
            "JO" => "Jordan",


            "MX" => "Mexico",

            "NL" => "Netherlands",

            "NZ" => "New Zealand",

            "NO" => "Norway",


            "SG" => "Singapore",

            "ZA" => "South Africa",

            "ES" => "Spain",

            "SE" => "Sweden",
            "CH" => "Switzerland",

            "TR" => "Turkey",

            "GB" => "United Kingdom",

            "UM" => "United States Minor Outlying Islands",
        );

    }

    public static function countries(){
        return [
          "United States"
        ];
    }
    public static function statesWithTax(){
        $states = [
            'Alabama'=>4.00,
            'Alaska'=>0.00,
            'Arizona'=>5.60,
            'Arkansas'=>6.50,
            'California'=>7.25,
            'Colorado'=>2.90,
            'Connecticut'=>6.35,
            'Delaware'=>0.00,
            'Florida'=>6.00,
            'Georgia'=>4.00,
            'Hawaii'=>4.00,
            'Idaho'=>6.00,
            'Illinois'=>6.25,
            'Indiana'=>7.00,
            'Iowa'=>6.00,
            'Kansas'=>6.50,
            'Kentucky'=>6.00,
            'Louisiana'=>4.45,
            'Maine'=>5.50,
            'Maryland'=>6.00,
            'Massachusetts'=>6.25,
            'Michigan'=>6.00,
            'Minnesota'=>6.88,
            'Mississippi'=>7.00,
            'Missouri'=>4.22,
            'Montana'=>0.00,
            'Nebraska'=>5.50,
            'Nevada'=>6.85,
            'New Hampshire'=>0.00,
            'New Jersey'=>6.62,
            'New Mexico'=>5.00,
            'New York'=>4.00,
            'North Carolina'=>4.75,
            'North Dakota'=>5.00,
            'Oklahoma'=>4.50,
            'Oregon'=>0.00,
            'Pennsylvania'=>6.00,
            'Rhode Island'=>7.00,
            'South Carolina'=>6.00,
            'Ohio'=>5.75,
            'South Dakota'=>4.50,
            'Tennessee'=>7.00,
            'Texas'=>6.25,
            'Utah'=>4.85,
            'Vermont'=>6.00,
            'Virginia'=>4.30,
            'Washington'=>6.50,
            'West Virginia'=>6.00,
            'Wisconsin'=>5.00,
            'Wyoming'=>4.00,

        ];
        return $states;
    }

    public static function states(){
        return App\Models\States::where('active','=',1)->select('name')->pluck('name');
        $states = [
            'Alabama',
            'Alaska',
            'Arizona',
            'Arkansas',
            'California',
            'Colorado',
            'Connecticut',
            'Delaware',
            'Florida',
            'Georgia',
            'Hawai',
            'Idaho',
            'Illinois',
            'Indiana',
            'Iowa',
            'Kansas',
            'Kentucky',
            'Louisiana',
            'Maine',
            'Maryland',
            'Massachusetts',
            'Michigun',
            'Minnesota',
            'Mississippi',
            'Missouri',
            'Montana',
            'Nebraska',
            'Nevada',
            'New Hampshire',
            'New Jersey',
            'New Mexico',
            'New York',
            'North Carolina',
            'North Dakota',
            'Oklahoma',
            'Oregon',
            'Pennsylvania',
            'Rhode Island',
            'South Carolina',
            'Ohio',
            'South Dakota',
            'Tennessee',
            'Texas',
            'Utah',
            'Vermont',
            'Virginia',
            'Washington',
            'West Virginia',
            'Wisconsin',
            'Wyoming',

        ];
        return $states;
    }
    public static function orderStatus(){
        return [
          "1"=>"Order Processing",
          "2"=>"Order Placed",
          "3"=>"Order Shipped",
          "4"=>"Order Delivered"
        ];
    }

    public static function notification_settings($return_type,$key=false){
        $setting = [
            'receive_gift'=>'Notify me when I receive a prezzie',
            'follow_profile'=>'Notify me when people follow me',
            'tips_and_updates'=>'Notify me about Prezziez tips and updates',
            'add_gift_wishlist'=>'Notify me when someone I follow adds a new gift to their wishlist',
        ];
        if($return_type=="array"){
            return $setting;
        }else if($return_type=="value"){
            if(array_key_exists($key,$setting)){
                return $setting[$key];
            }else{
                return false;
            }
        }else if($return_type=="key"){


        }
        return false;
    }
    public static function subdivision($search_by=false,$search_value= false)
    {
       $subdivision = ['United Kingdom' =>
            'GB_UKM',
            'Great Britain' =>
                'GB_GBN',
            'Scotland' =>
                'GB_SCT',
            'North Lanarkshire' =>
                'GB_NLK',
            'Renfrewshire' =>
                'GB_RFW',
            'Perth and Kinross' =>
                'GB_PKN',
            'Angus' =>
                'GB_ANS',
            'Falkirk' =>
                'GB_FAL',
            'Glasgow City' =>
                'GB_GLG',
            'Moray' =>
                'GB_MRY',
            'Dumfries and Galloway' =>
                'GB_DGY',
            'East Lothian' =>
                'GB_ELN',
            'South Ayrshire' =>
                'GB_SAY',
            'Aberdeenshire' =>
                'GB_ABD',
            'Eilean Siar' =>
                'GB_ELS',
            'North Ayrshire' =>
                'GB_NAY',
            'West Lothian' =>
                'GB_WLN',
            'Argyll and Bute' =>
                'GB_AGB',
            'Clackmannanshire' =>
                'GB_CLK',
            'East Dunbartonshire' =>
                'GB_EDU',
            'Fife' =>
                'GB_FIF',
            'Midlothian' =>
                'GB_MLN',
            'Orkney Islands' =>
                'GB_ORK',
            'Scottish Borders, The' =>
                'GB_SCB',
            'South Lanarkshire' =>
                'GB_SLK',
            'Stirling' =>
                'GB_STG',
            'Shetland Islands' =>
                'GB_ZET',
            'West Dunbartonshire' =>
                'GB_WDU',
            'Aberdeen City' =>
                'GB_ABE',
            'Dundee City' =>
                'GB_DND',
            'East Ayrshire' =>
                'GB_EAY',
            'East Renfrewshire' =>
                'GB_ERW',
            'Highland' =>
                'GB_HLD',
            'Inverclyde' =>
                'GB_IVC',
            'Edinburgh, City of' =>
                'GB_EDH',
            'England and Wales' =>
                'GB_EAW',
            'England' =>
                'GB_ENG',
            'Greenwich' =>
                'GB_GRE',
            'Halton' =>
                'GB_HAL',
            'Haringey' =>
                'GB_HRY',
            'Kingston upon Hull' =>
                'GB_KHL',
            'North East Lincolnshire' =>
                'GB_NEL',
            'Norfolk' =>
                'GB_NFK',
            'Portsmouth' =>
                'GB_POR',
            'South Gloucestershire' =>
                'GB_SGC',
            'West Berkshire' =>
                'GB_WBK',
            'Wandsworth' =>
                'GB_WND',
            'Warrington' =>
                'GB_WRT',
            'Isles of Scilly' =>
                'GB_IOS',
            'Bedford' =>
                'GB_BDF',
            'Barking and Dagenham' =>
                'GB_BDG',
            'Buckinghamshire' =>
                'GB_BKM',
            'Bristol, City of' =>
                'GB_BST',
            'Cambridgeshire' =>
                'GB_CAM',
            'Doncaster' =>
                'GB_DNC',
            'Kirklees' =>
                'GB_KIR',
            'London, City of' =>
                'GB_LND',
            'Luton' =>
                'GB_LUT',
            'Manchester' =>
                'GB_MAN',
            'Milton Keynes' =>
                'GB_MIK',
            'North Lincolnshire' =>
                'GB_NLN',
            'North Somerset' =>
                'GB_NSM',
            'North Tyneside' =>
                'GB_NTY',
            'Plymouth' =>
                'GB_PLY',
            'Reading' =>
                'GB_RDG',
            'Shropshire' =>
                'GB_SHR',
            'Sunderland' =>
                'GB_SND',
            'South Tyneside' =>
                'GB_STY',
            'Southwark' =>
                'GB_SWK',
            'Wirral' =>
                'GB_WRL',
            'Westminster' =>
                'GB_WSM',
            'Bath and North East Somerset' =>
                'GB_BAS',
            'Birmingham' =>
                'GB_BIR',
            'Blackpool' =>
                'GB_BPL',
            'Cheshire West and Chester' =>
                'GB_CHW',
            'Hounslow' =>
                'GB_HNS',
            'Hertfordshire' =>
                'GB_HRT',
            'Kingston upon Thames' =>
                'GB_KTT',
            'Leicestershire' =>
                'GB_LEC',
            'Rochdale' =>
                'GB_RCH',
            'Rotherham' =>
                'GB_ROT',
            'Suffolk' =>
                'GB_SFK',
            'Surrey' =>
                'GB_SRY',
            'Southampton' =>
                'GB_STH',
            'Stockton-on-Tees' =>
                'GB_STT',
            'Swindon' =>
                'GB_SWD',
            'Telford and Wrekin' =>
                'GB_TFW',
            'Windsor and Maidenhead' =>
                'GB_WNM',
            'Worcestershire' =>
                'GB_WOR',
            'West Sussex' =>
                'GB_WSX',
            'Bexley' =>
                'GB_BEX',
            'Bournemouth' =>
                'GB_BMH',
            'Bradford' =>
                'GB_BRD',
            'Bury' =>
                'GB_BUR',
            'Cheshire East' =>
                'GB_CHE',
            'Cornwall' =>
                'GB_CON',
            'Hampshire' =>
                'GB_HAM',
            'Liverpool' =>
                'GB_LIV',
            'North Yorkshire' =>
                'GB_NYK',
            'Oxfordshire' =>
                'GB_OXF',
            'Richmond upon Thames' =>
                'GB_RIC',
            'Rutland' =>
                'GB_RUT',
            'Sandwell' =>
                'GB_SAW',
            'Sheffield' =>
                'GB_SHF',
            'Slough' =>
                'GB_SLG',
            'Thurrock' =>
                'GB_THR',
            'Torbay' =>
                'GB_TOB',
            'Waltham Forest' =>
                'GB_WFT',
            'Wiltshire' =>
                'GB_WIL',
            'Wakefield' =>
                'GB_WKF',
            'York' =>
                'GB_YOR',
            'Calderdale' =>
                'GB_CLD',
            'Coventry' =>
                'GB_COV',
            'Derby' =>
                'GB_DER',
            'Devon' =>
                'GB_DEV',
            'Dudley' =>
                'GB_DUD',
            'Gloucestershire' =>
                'GB_GLS',
            'Hackney' =>
                'GB_HCK',
            'Harrow' =>
                'GB_HRW',
            'Isle of Wight' =>
                'GB_IOW',
            'Islington' =>
                'GB_ISL',
            'Kensington and Chelsea' =>
                'GB_KEC',
            'Lambeth' =>
                'GB_LBH',
            'Leeds' =>
                'GB_LDS',
            'Newcastle upon Tyne' =>
                'GB_NET',
            'Nottingham' =>
                'GB_NGM',
            'Salford' =>
                'GB_SLF',
            'Solihull' =>
                'GB_SOL',
            'Stoke-on-Trent' =>
                'GB_STE',
            'Wolverhampton' =>
                'GB_WLV',
            'Wokingham' =>
                'GB_WOK',
            'Barnet' =>
                'GB_BNE',
            'Brighton and Hove' =>
                'GB_BNH',
            'Barnsley' =>
                'GB_BNS',
            'Darlington' =>
                'GB_DAL',
            'East Riding of Yorkshire' =>
                'GB_ERY',
            'Essex' =>
                'GB_ESS',
            'Havering' =>
                'GB_HAV',
            'Hillingdon' =>
                'GB_HIL',
            'Hartlepool' =>
                'GB_HPL',
            'Kent' =>
                'GB_KEN',
            'Leicester' =>
                'GB_LCE',
            'Middlesbrough' =>
                'GB_MDB',
            'Merton' =>
                'GB_MRT',
            'Redbridge' =>
                'GB_RDB',
            'Sefton' =>
                'GB_SFT',
            'St. Helens' =>
                'GB_SHN',
            'Trafford' =>
                'GB_TRF',
            'Tower Hamlets' =>
                'GB_TWH',
            'Warwickshire' =>
                'GB_WAR',
            'Walsall' =>
                'GB_WLL',
            'Derbyshire' =>
                'GB_DBY',
            'Dorset' =>
                'GB_DOR',
            'Enfield' =>
                'GB_ENF',
            'Gateshead' =>
                'GB_GAT',
            'Hammersmith and Fulham' =>
                'GB_HMF',
            'Knowsley' =>
                'GB_KWL',
            'Lancashire' =>
                'GB_LAN',
            'Lewisham' =>
                'GB_LEW',
            'Lincolnshire' =>
                'GB_LIN',
            'Northumberland' =>
                'GB_NBL',
            'Poole' =>
                'GB_POL',
            'Redcar and Cleveland' =>
                'GB_RCC',
            'Staffordshire' =>
                'GB_STS',
            'Tameside' =>
                'GB_TAM',
            'Wigan' =>
                'GB_WGN',
            'Blackburn with Darwen' =>
                'GB_BBD',
            'Brent' =>
                'GB_BEN',
            'Bolton' =>
                'GB_BOL',
            'Bracknell Forest' =>
                'GB_BRC',
            'Central Bedfordshire' =>
                'GB_CBF',
            'Cumbria' =>
                'GB_CMA',
            'Camden' =>
                'GB_CMD',
            'Croydon' =>
                'GB_CRY',
            'Durham County' =>
                'GB_DUR',
            'East Sussex' =>
                'GB_ESX',
            'Herefordshire' =>
                'GB_HEF',
            'Medway' =>
                'GB_MDW',
            'Northamptonshire' =>
                'GB_NTH',
            'Nottinghamshire' =>
                'GB_NTT',
            'Newham' =>
                'GB_NWM',
            'Oldham' =>
                'GB_OLD',
            'Peterborough' =>
                'GB_PTE',
            'Stockport' =>
                'GB_SKP',
            'Somerset' =>
                'GB_SOM',
            'Southend-on-Sea' =>
                'GB_SOS',
            'Sutton' =>
                'GB_STN',
            'Bromley' =>
                'GB_BRY',
            'Ealing' =>
                'GB_EAL',
            'Wales' =>
                'GB_WLS',
            'Neath Port Talbot' =>
                'GB_NTL',
            'Rhondda, Cynon, Taff' =>
                'GB_RCT',
            'Bridgend' =>
                'GB_BGE',
            'Newport' =>
                'GB_NWP',
            'Blaenau Gwent' =>
                'GB_BGW',
            'Carmarthenshire' =>
                'GB_CMN',
            'Denbighshire' =>
                'GB_DEN',
            'Flintshire' =>
                'GB_FLN',
            'Merthyr Tydfil' =>
                'GB_MTY',
            'Powys' =>
                'GB_POW',
            'Vale of Glamorgan, The' =>
                'GB_VGL',
            'Isle of Anglesey' =>
                'GB_AGY',
            'Caerphilly' =>
                'GB_CAY',
            'Gwynedd' =>
                'GB_GWN',
            'Wrexham' =>
                'GB_WRX',
            'Ceredigion' =>
                'GB_CGN',
            'Monmouthshire' =>
                'GB_MON',
            'Pembrokeshire' =>
                'GB_PEM',
            'Torfaen' =>
                'GB_TOF',
            'Cardiff' =>
                'GB_CRF',
            'Swansea' =>
                'GB_SWA',
            'Conwy' =>
                'GB_CWY',
            'Northern Ireland' =>
                'GB_NIR',
            'North Down' =>
                'GB_NDN',
            'Newry and Mourne' =>
                'GB_NYM',
            'Antrim' =>
                'GB_ANT',
            'Down' =>
                'GB_DOW',
            'Derry' =>
                'GB_DRY',
            'Fermanagh' =>
                'GB_FER',
            'Newtownabbey' =>
                'GB_NTA',
            'Ards' =>
                'GB_ARD',
            'Craigavon' =>
                'GB_CGV',
            'Lisburn' =>
                'GB_LSB',
            'Banbridge' =>
                'GB_BNB',
            'Castlereagh' =>
                'GB_CSR',
            'Moyle' =>
                'GB_MYL',
            'Omagh' =>
                'GB_OMH',
            'Carrickfergus' =>
                'GB_CKF',
            'Magherafelt' =>
                'GB_MFT',
            'Armagh' =>
                'GB_ARM',
            'Ballymena' =>
                'GB_BLA',
            'Limavady' =>
                'GB_LMV',
            'Strabane' =>
                'GB_STB',
            'Cookstown' =>
                'GB_CKT',
            'Dungannon and South Tyrone' =>
                'GB_DGN',
            'Larne' =>
                'GB_LRN',
            'Belfast' =>
                'GB_BFS',
            'Ballymoney' =>
                'GB_BLY',
            'Coleraine' =>
                'GB_CLR'
        ];
       if($search_by!=false && $search_value!=false){
           if($search_by=="key"){
               if(array_key_exists($search_value,$subdivision)){
                   return $subdivision[$search_value];
               }
           }else{
               return array_search($search_value,$subdivision,true);
           }
       }else{
           return $subdivision;
       }
    }

    public static function merchants(){

        $merchants = [

                'Walmart' => [
                    'name' => 'walmart.com',
                    'shipping_price' => '6'
                ],
                'Shein' => [
                    'name' => 'shein.com',
                    'shipping_price' => '4'
                ],
                'Anthropologie' => [
                    'name' => 'anthropologie.com',
                    'shipping_price' => '7'
                ],
                'H&M' => [
                    'name' => 'www2.hm.com',
                    'shipping_price' => '4'
                ],
                'Zara' => [
                    'name' => 'zara.com',
                    'shipping_price' => '5'
                ],
                'FashionNova' => [
                    'name' => 'fashionnova.com',
                    'shipping_price' => '5'
                ],
                'Target' => [
                    'name' => 'target.com',
                    'shipping_price' => '6'
                ],
                'Homegoods' => [
                    'name' => 'homegoods.com',
                    'shipping_price' => '15'
                ],
                'Best Buy' => [
                    'name' => 'bestbuy.com',
                    'shipping_price' => '6'
                ],
                'Macys' => [
                    'name' => 'macys.com',
                    'shipping_price' => ''
                ],
                'Home Depot' => [
                    'name' => 'homedepot.com',
                    'shipping_price' => '6'
                ],
                'Wish' => [
                    'name' => 'wish.com',
                    'shipping_price' => '12'
                ],
                'Gamestop' => [
                    'name' => 'gamestop.com',
                    'shipping_price' => '6'
                ],
            'Glossier' => [
                'name' => 'glossier.com',
                'shipping_price' => '5'
            ],
            'Urban Outfitters' => [
                'name' => 'urbanoutfitters.com',
                'shipping_price' => '5'
            ],
            'Forever21' => [
                'name' => 'orever21.com',
                'shipping_price' => '6'
            ],
            'Buy Buy Baby' => [
                'name' => 'buybuybaby.com',
                'shipping_price' => ''
            ],
            'Free People' => [
                'name' => 'freepeople.com',
                'shipping_price' => '5'
            ],
            'Fitbit' => [
                'name' => 'fitbit.com',
                'shipping_price' => '4'
            ],
            'Bath and body works' => [
                'name' => 'bathandbodyworks.com',
                'shipping_price' => '7'
            ],
            'Bed Bath and Beyond' => [
                'name' => 'bedbathandbeyond.com',
                'shipping_price' => '6'
            ],
            'Yankee Candle' => [
                'name' => 'yankeecandle.com',
                'shipping_price' => '6'
            ],
            'Under Armour' => [
                'name' => 'underarmour.com',
                'shipping_price' => '5'
            ],
            'Adidas' => [
                'name' => 'adidas.com',
                'shipping_price' => '5'
            ],
            'Fossil' => [
                'name' => 'fossil.com',
                'shipping_price' => '5'
            ],
            'Dolls Kill' => [
                'name' => 'dollskill.com',
                'shipping_price' => '6'
            ],
            'Rebdolls' => [
                'name' => 'rebdolls.com',
                'shipping_price' => '5'
            ],
            'Boohoo' => [
                'name' => 'Boohoo.com',
                'shipping_price' => '10'
            ],
            'Playboy' => [
                'name' => 'playboy.com',
                'shipping_price' => '7'
            ],
            'Nasty Gal' => [
                'name' => 'nastygal.com',
                'shipping_price' => '10'
            ],
            'Lush' => [
                'name' => 'lush.com',
                'shipping_price' => '6'
            ],
            'Asos' => [
                'name' => 'asos.com',
                'shipping_price' => '5'
            ],
            'Crate and Barrel' => [
                'name' => 'crateandbarrel.com',
                'shipping_price' => ''
            ],
            'Barnes and Noble' => [
                'name' => 'barnesandnoble.com',
                'shipping_price' => '5'
            ],
            'HSN' => [
                'name' => 'hsn.com',
                'shipping_price' => ''
            ],
            'QVC' => [
                'name' => 'qvc.com',
                'shipping_price' => ''
            ],
            'Ulta' => [
                'name' => 'ulta.com',
                'shipping_price' => '6'
            ],
            'Sephora' => [
                'name' => 'sephora.com',
                'shipping_price' => '6.5'
            ],
            'Mac Cosmetics' => [
                'name' => 'maccosmetics.com',
                'shipping_price' => '5'
            ],
            'Hobby Lobby' => [
                'name' => 'hobbylobby.com',
                'shipping_price' => ''
            ],
            'Michaels Craft Store' => [
                'name' => 'michaels.com',
                'shipping_price' => '7'
            ],
            'Ikea' => [
                'name' => 'ikea.com',
                'shipping_price' => ''
            ],
            'Pier 1 Imports' => [
                'name' => 'pier1.com',
                'shipping_price' => '5'
            ],
            'Wayfair' => [
                'name' => 'wayfair.com',
                'shipping_price' => '5'
            ],
            'Lowe’s' => [
                'name' => 'lowes.com',
                'shipping_price' => '6'
            ],
            'Kohl’s' => [
                'name' => 'kohls.com',
                'shipping_price' => '9'
            ],
            'Gap' => [
                'name' => 'gap.com',
                'shipping_price' => '5'
            ],
            'JC Penny' => [
                'name' => 'gap.com',
                'shipping_price' => '5'
            ],
            'Gap' => [
                'name' => 'jcpenney.com',
                'shipping_price' => '9'
            ],
            'Walgreens' => [
                'name' => 'walgreens.com',
                'shipping_price' => '6'
            ],
            'Victoria’s Secret' => [
                'name' => 'victoriassecret.com',
                'shipping_price' => '8'
            ],
            'Savage X Fenty' => [
                'name' => 'savage.com',
                'shipping_price' => '5'
            ],
            'The Reformation' => [
                'name' => 'thereformation.com',
                'shipping_price' => '0'
            ],
            'Apple' => [
                'name' => 'apple.com',
                'shipping_price' => '0'
            ],
            'Dossier' => [
                'name' => 'dossier.co',
                'shipping_price' => '9'
            ],
            'Le Creuset' => [
                'name' => 'lecreuset.com',
                'shipping_price' => '6'
            ],
            'Missguided' => [
                'name' => 'misguided.com',
                'shipping_price' => '8'
            ],
            'Other' => [
                'name' => 'others',
                'shipping_price' => '0'
            ],

        ];
        return $merchants;
    }
    public static function estimated_tax($total, $user_id,$subtotal, $state_name = "", $credit_apply_subtotal=0 ){
        if($total > 0 || $credit_apply_subtotal > 0) {
            $tax = 0;
            $state_additional = 0;
            if($state_name==""){
                $user_state = App\Models\Website::find($user_id);
                if(!empty($user_state->state)){
                    $state_name = $user_state->state;
                   $state = App\Models\States::where('name','=',$state_name)->first();
                   if(!empty($state->id) && $state->tax_rate > 0){
                       $tax = $state->tax_rate/100;
                       if(strtolower($state->name)=="colorado"){
                           $state_additional = 0.27;
                       }
                   }
                }

            }else{
                $state = App\Models\States::where('name','=',$state_name)->first();
                if(!empty($state->id) && $state->tax_rate > 0){
                    $tax = $state->tax_rate/100;
                    if(strtolower($state->name)=="colorado"){
                        $state_additional = 0.27;
                    }
                }
            }
            //prezziez fee will not calculate if total <=0
            //$total * .1 => prezziez fee
            //tax and stripe fee will calculate base on subtotal
            if($total > 0) {
                if ($tax > 0) {
                    $calculate_estimated =  ($total * .1) + ($subtotal * $tax) + $state_additional;
                    $total = $total + $calculate_estimated;
                    $subtotal = $subtotal + $calculate_estimated;
                } else {
                    $total = $total + ($total * .1);
                    $subtotal = $subtotal + ($total * .1);
                }
            }else{
                $total = $credit_apply_subtotal;
                if ($tax > 0) {
                    $calculate_estimated =  ($subtotal * $tax) + $state_additional;
                    $total = $total +  $calculate_estimated;
                    $subtotal = $subtotal + ($subtotal * $tax) + $state_additional;
                } else {
                    $total = $total;
                }
            }
            //echo $total;
            //echo "<br>";
            return $total +  ($subtotal * .029 + .30);
        }else{
            return 0;
        }
    }
    public static function estimated_tax_contributed($total = 0,$tax = 0){
        if($total > 0) {
            $total = $total +  $tax;
            return $total +  ($total * .029 + .30);
        }else{
            return 0;
        }
    }
    public static function estimated_prezziez_fee($total = 0){
        if($total > 0) {
            return ($total * .1);
        }else{
            return 0;
        }
    }
    public static function estimated_stripe_fee($total = 0, $sys_fee = 0, $taxes=0){
        if($total > 0) {
            $total = $total + $sys_fee + $taxes;
            return ($total * .029 + .30);
        }else{
            return 0;
        }
    }
    public static function estimated_tax_fee($total = 0,$tax = 0,$state_name){
        if($total > 0 && $tax > 0){
            $tax = $tax/100;
            $state_additional = 0;
            if(strtolower($state_name)=="colorado"){
                $state_additional = 0.27;
            }

            return   ($total * $tax) + $state_additional ;
        }else{
            return 0;
        }

    }

}

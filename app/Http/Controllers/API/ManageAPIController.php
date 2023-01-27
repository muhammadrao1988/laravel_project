<?php

namespace App\Http\Controllers\API;

use App;
use App\TraitLibraries\ResponseWithHttpStatus;
use Config;
use Artisan;
use Response;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Category;
use App\Models\Parties;
use Illuminate\Support\Facades\Validator;


class ManageAPIController extends Controller
{
    public $logId = 0;
    use ResponseWithHttpStatus;

    public function __construct(Request $request){
        if(!empty($_POST)){
            $this->makeLog($_POST, $request->path());
        }
    }
    public function englishtoUrduTranslator(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'text' => 'required|string'
        ]);

        if ($validator->fails()) {
            return $this->responseFailure($validator->errors()->first(), 422);
        }

        try{
            return $this->responseSuccess(config('api.response.messages.200'),  ['text' => App\Helpers\Common::GoogleTranslate($request->text)], 200);
        }
        catch (\Exception $exception) {
            return $this->responseFailure($exception->getMessage(), 500);
        }

    }
    public function forceUpdate(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'device_type' => 'required',
            'version' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->responseFailure($validator->errors()->first(), 422);
        }

        try {
            $flag = false;
            $message = '';
            $where=array(
                'flagType'=>'mobile_app_versions',
                'field1'=>$request->get('device_type'),
            );
            $params = App\Models\Flag::where($where)->first()->toArray();

            $version_int = 0;
            if ($params) {
                $db_version = $params['field2'];
                $user_version = $request->get('version');
                $db_version_arr = explode('.', $db_version);
                $user_version_arr = explode('.', $user_version);

                if (isset($db_version_arr[0]) && isset($user_version_arr[0]) && $db_version_arr[0] > $user_version_arr[0]) {
                    $version_int = 2;
                    $message = "we've upgraded Truck Walay please update your app and enjoy added functionality";
                } elseif (isset($db_version_arr[1]) && isset($user_version_arr[1]) && $db_version_arr[1] > $user_version_arr[1]) {
                    $version_int = 1;
                    $message = "we've upgraded Truck Walay please update your app and enjoy added functionality";
                } elseif (isset($db_version_arr[2]) && isset($user_version_arr[2]) && $db_version_arr[2] > $user_version_arr[2]) {
                    $version_int = 0;
                    $message = 'You have minor updates';
                } else {
                    $message = 'You have already on updated version';
                }
            }
            $apiKey = env("google_api_key");
            return $this->responseSuccess($message,  ['version_int' => $version_int, 'google_api_key' => $apiKey], 200);

        } catch (\Exception $exception) {
            return $this->responseFailure($exception->getMessage(), 500);
        }
    }

    private function makeResponse($message, $httpcode){
        $this->makeLog($message);
        return response()->json(['message' => $message], $httpcode);
    }

    private function makeLog($message, $logName = ''){
            if(empty($this->logId)){
                $query = "INSERT INTO activity_log(
                                log_name,
                                description,
                                created_at
                                )VALUES(
                                '".$logName."',
                                'Request: [".str_replace("'", "", json_encode($message))."]',
                                '".date("Y:m:d H:i:s")."'
                            )";
            DB::statement($query);
            $this->logId = DB::getPdo()->lastInsertId();
        }else{
            $query = "UPDATE activity_log
                        SET description = CONCAT(IFNULL(description, ''), ', Response: [".str_replace("'", "", json_encode($message))."]')
                        WHERE id = ".$this->logId."";
            DB::statement($query);
        }
    }

    public function index(Request $request){
        $_token = $request->_token;
        if($_token != "08a34e2f877a5624dfaa225fab00dcd2"){
            return "Invalid API Request!";
        }
        $name = $request->name;
        $url = $request->url;
        $mongoDB = $request->mongoDB;
        $dbName = $request->dbName;
        $dbUser = $request->dbUser;
        $dbUserPassword = $request->dbUserPassword;

        if(!empty($name) && !empty($url) && !empty($mongoDB)  && !empty($dbName) && !empty($dbName) && !empty($dbName)){
            return $this->createDatabase($name, $url, $mongoDB, $dbName, $dbUser, $dbUserPassword);
        }else{
            return "All fields are required!";
        }
    }

    private function createDatabase($name, $url, $mongoDB, $dbName, $dbUser, $dbUserPassword){
        try{
            $isDBCreated = DB::statement("CREATE DATABASE IF NOT EXISTS {$dbName};");
            if($isDBCreated){
                DB::statement("DROP USER IF EXISTS '".$dbUser."'@'%'");
                DB::statement("CREATE USER '".$dbUser."'@'%' IDENTIFIED BY '".$dbUserPassword."'");
                DB::statement("GRANT ALL ON ".$dbName.".* TO '".$dbUser."'@'%'");
                DB::statement("FLUSH PRIVILEGES");

                \Common::makeDBConnection($dbName, $dbUser, $dbUserPassword);

                $migrate = Artisan::call( 'migrate', ['--database' => $dbName, '--force' => true]);
                $seed = Artisan::call( 'db:seed', ['--database' => $dbName, '--force' => true]);
                if($migrate === 0 && $seed === 0){
                    $envFile = base_path('.env/..env.'.$url);
                    if(\File::copy(base_path('/..env.example'), $envFile)){
                        if($this->setMongoDB($mongoDB, $dbName, $dbUser, $dbUserPassword)){
                            $data['APP_NAME'] = strtoupper($name);
                            $data['APP_ENV'] = 'PRODUCTION';
                            $data['APP_KEY'] = "base64:".base64_encode(\Illuminate\Support\Str::random(32));
                            $data['APP_DEBUG'] = 'false';
                            $data['APP_URL'] = "http://".$url;

                            $data['DB_DATABASE'] = $dbName;
                            $data['DB_USERNAME'] = $dbUser;
                            $data['DB_PASSWORD'] = $dbUserPassword;
                            $data['MONGO_DB_DATABASE'] = $dbName;
                            $data['MONGO_DB_USERNAME'] = $dbUser;
                            $data['MONGO_DB_PASSWORD'] = $dbUserPassword;
                            $data['MAIL_FROM_ADDRESS'] = "noreply@".$url;

                            if($this->setEnvironmentValue($envFile, $data)){
                                $newEnvFile='..env.'.$url;
                                if(file_exists("/var/www/html/fms/copy-.env-file.sh")){
                                    if(!empty(shell_exec("/var/www/html/fms/copy-.env-file.sh ". $newEnvFile))){
                                        return 1;
                                    }
                                }else{
                                    return 1;
                                }
                            }
                        }else{
                            return "Error in MongoDB (".$mongoDB.") Connection!";
                        }
                    }
                }
            }else{
                return "Database '".$dbName."' already exists!";
            }
        }catch(Exception $e) {
            return $e->getMessage();
        }
    }

    private function setEnvironmentValue($envFile, array $values){
        $str = file_get_contents($envFile);
        if (count($values) > 0) {
            foreach ($values as $envKey => $envValue) {
                $str .= "\n";
                $keyPosition = strpos($str, "{$envKey}=");
                $endOfLinePosition = strpos($str, "\n", $keyPosition);
                $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);
                $str = str_replace($oldLine, "{$envKey}={$envValue}", $str);
            }
        }
        $str = substr($str, 0, -1);
        if (!file_put_contents($envFile, $str)) return false;
        return 1;
    }

    private function setMongoDB($mongoDB, $dbName, $dbUser, $dbUserPassword){
        $mongoDB = new \MongoDB\Driver\Manager($mongoDB);

        $cmd = array ("usersInfo" => array('user' => $dbUser, "db" => $dbName));
        $user = $mongoDB->executeCommand($dbName, new \MongoDB\Driver\Command($cmd));
        if(count($user->toArray()[0]->users) > 0){
            $cmd = array ("dropUser" => $dbUser);
            $result = $mongoDB->executeCommand($dbName, new \MongoDB\Driver\Command($cmd));
        }

        $cmd = array (
                        "createUser" => $dbUser,
                        "pwd" => $dbUserPassword,
                        "roles" => array(
                            array("role" => "readWrite", "db" => $dbName)
                        )
                    );
        $result = $mongoDB->executeCommand($dbName, new \MongoDB\Driver\Command($cmd));
        if($result){
            return 1;
        }
    }
}

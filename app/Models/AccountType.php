<?php

namespace App\Models;

use App\Helpers\Common;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class AccountType extends BaseModel
{
    public static $module = "AccountType";

    protected $fillable = [
        'id',
        'sonar_id',
        'sonar_unique_id',
        'name',
        'type',
        'active',
        'entryStatus',
    ];

    public static function validationRules($id = 0){
        $rules['name'] = ['required'];
        $rules['flagType'] = ['required'];
        return $rules;
    }

    public static function getList(){
        $return = self::get();
        return $return;
    }

    public static function actionButtons($data){
        $return = '<div class="btn-group" role="group">
                        <a class="btn" href="'.route('accountTypes.show', $data->id).'" title="View">
                            <i class="fas fa-eye"></i>
                        </a>';

      /*  if(\Common::canUpdate(static::$module)) {
            $return .= '<a class="btn" href="'.(!empty(@$_GET['flagtype']) ? \URL::to('/flag/'.$data->id.'/edit?flagtype='.@$_GET['flagtype']) : \URL::to('/flag/'.$data->id.'/edit')).'" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>';
        }

        if(\Common::canDelete(static::$module)) {
            $return .= '<form action="'.route('flag.destroy', $data->id).'" method="post" id="delete_form_'.$data->id.'">
                            <input type="hidden" name="_method" value="DELETE">
                            <input type="hidden" name="_token" value="'.csrf_token().'">
                            <input type="hidden" name="flagtype" value="'.@$_GET['flagtype'].'">
                            <a class="btn delete-confirm-id" title="Delete" data-id="'.$data->id.'">
                                <i class="fas fa-trash"></i>
                            </a>
                        </form>';
        }*/

        $return .=  '</div>';
        return $return;
    }

    public static function syncAccountType(){
        $token = Common::getConfig('company','sonarAPIToken');
        $url = Common::getConfig('company','sonarAPIURL');

        if(empty($token) || empty($url)){
            return ['success'=>false,'msg'=>'Please first set sonar api url and token'];
        }
        try {
            $paginationQuery = <<<GQL
            query MyCoolQuery {
             account_types {
              page_info{
               page,
               total_pages,
               total_count,
               records_per_page
              }
             }
            }
            GQL;
            $client = new Client();
            $response = $client->post($url, [
                'headers' => [
                    'Authorization' => "Bearer $token",
                    'Accept' => 'application/json',
                ],
                'json' => [
                    'query' => $paginationQuery
                ]
            ]);
            $decodedResponse = json_decode($response->getBody()->getContents(), false, JSON_PRETTY_PRINT);
            if(!empty($decodedResponse->data) && (!empty($decodedResponse->data->account_types->page_info))) {
                $pageInfo = $decodedResponse->data->account_types->page_info;
                $totalPages = $pageInfo->total_pages;
                $page = $pageInfo->page;
                $total_count = $pageInfo->total_count;
                if($total_count > 0){
                    for($i=1;$i<=$totalPages;$i++){
                        $query = <<<GQL
                        query MyCoolQuery(\$paginator: Paginator) {
                         account_types(paginator: \$paginator) {
                          entities {
                           id
                           sonar_unique_id
                           name
                           type
                          }
                         }
                        }
                        GQL;
                        $variables = [
                            'paginator' =>[
                                'page'=> $i,
                                'records_per_page'=>100,
                            ],
                        ];
                        $client = new Client();
                        $response = $client->post($url, [
                            'headers' => [
                                'Authorization' => "Bearer $token",
                                'Accept' => 'application/json',
                            ],
                            'json' => [
                                'query' => $query,
                                'variables' => $variables
                            ]
                        ]);
                        $decodedResponse = json_decode($response->getBody()->getContents(), false, JSON_PRETTY_PRINT);
                        if(!empty($decodedResponse->data) && (!empty($decodedResponse->data->account_types->entities))){

                            foreach ($decodedResponse->data->account_types->entities as $data){
                                self::updateOrCreate(
                                    ['sonar_id' => $data->id],
                                    [
                                        'sonar_id' => $data->id,
                                        'sonar_unique_id' => $data->sonar_unique_id,
                                        'name' => $data->name,
                                        'type' => $data->type,
                                        'active' => 1,
                                    ]
                                );
                            }


                        }else{
                            $msg = "";
                            foreach($decodedResponse->errors as $error){
                                $msg.= $error->message;
                            }
                            return ['success'=>false,'msg'=>$msg];
                        }

                    }
                    return ['success'=>true,'msg'=>"Account Types synced successfully.."];
                }else{
                    return['success'=>false,'msg'=>'No record exists in SONAR Portal'];
                }

            }
            else{
                $msg = "";
                foreach($decodedResponse->errors as $error){
                    $msg.= $error->message;
                }
                return ['success'=>false,'msg'=>$msg];
            }

        }catch (\Exception $ex){
            return ['success'=>false,'msg'=>$ex->getMessage()];
        }

    }

}

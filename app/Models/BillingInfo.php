<?php

namespace App\Models;
use App\Helpers\Common;
use App\Models\Category;
use App\Models\User;
use App\ModelsOrder;

use Illuminate\Database\Eloquent\Model;

class BillingInfo extends Model
{
    protected $table='billing_info';

    public static $module = "Billing";

    protected $fillable = [
        'id',  'user_id','first_name', 'last_name', 'email','country','city','state','postal_code','address','active'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public static function actionButtons($data){
        $return = '<div class="btn-group" role="group">
                      <a class="btn" href="'.route('giftideas.show', $data->id).'" title="View">
                            <i class="fas fa-eye"></i>
                        </a>';

        if(\Common::canUpdate(static::$module)) {
            $return .= '<a class="btn" href="'.route('giftideas.edit', $data->id).'" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>';
        }

        if(\Common::canDelete(static::$module)) {
            $return .= '<form action="'.route('giftideas.destroy', $data->id).'" method="post" id="delete_form_'.$data->id.'">
                            <input type="hidden" name="_method" value="DELETE">
                            <input type="hidden" name="_token" value="'.csrf_token().'">
                            <a class="btn delete-confirm-id" title="Delete" data-id="'.$data->id.'">
                                <i class="fas fa-trash"></i>
                            </a>
                        </form>';
        }
        $return .= '</div>';
        return $return;
    }

}

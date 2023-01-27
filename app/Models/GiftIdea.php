<?php

namespace App\Models;
use App\Helpers\Common;
use App\Models\Category;

use Illuminate\Database\Eloquent\Model;

class GiftIdea extends Model
{
    protected $table='giftideas';

    public static $module = "GiftIdea";

    protected $fillable = [
        'id',  'category_id','item_name', 'item_url', 'price','shipping_fee','merchant','image_path','merchant_name','expedited_shipping_fee','active'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public static function actionButtons($data){
        $return = '<div class="btn-group" role="group">
                      <a class="btn" href="'.route('giftguide.show', $data->id).'" title="View">
                            <i class="fas fa-eye"></i>
                        </a>';

        if(\Common::canUpdate(static::$module)) {
          $return .= '<a class="btn" href="'.route('giftguide.edit', $data->id).'" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>';
        }

        if(\Common::canDelete(static::$module)) {
           $return .= '<form action="'.route('giftguide.destroy', $data->id).'" method="post" id="delete_form_'.$data->id.'">
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

    public function categories(){

        return $this->belongsTo(Category::class,'category_id');
    }


}

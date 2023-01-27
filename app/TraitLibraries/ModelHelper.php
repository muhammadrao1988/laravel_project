<?php
/**
 * Created by PhpStorm.
 * User: warispopal
 * Date: 4/3/19
 * Time: 3:54 PM
 */

namespace App\TraitLibraries;

use App\helpers\DateTimeHelper;

trait ModelHelper
{
    /**
     * @param array $attributes
     */
    public function loadModel(Array $attributes): void
    {
        foreach ($attributes as $attribute => $value) {
            if (in_array($attribute, $this->fillable, true))
                $this->setAttribute($attribute, $value);
        }
    }

}

<?php
/**
 * Created by PhpStorm.
 * User: warispopal
 * Date: 1/25/19
 * Time: 3:14 PM
 */

namespace App\TraitLibraries;


trait AlertMessages
{
    protected $message = array(
        "error" => array(
            'none' => 'Something Went Wrong In {modelName}.',
            'create' => 'An Error Occur While Creating {modelName}.',
            'update' => 'An Error Occur While Updating {modelName}.',
            'notfound' => '{modelName} Not found.',
            'notdeleted' => '{modelName} already has entries. It cannot be deleted.',
        ),
        "success" => array(
            'none' => 'Done.',
            'create' => '{modelName} {id} Created Successfully.',
            'update' => '{modelName} {id} Updated Successfully.',
            'delete' => '{modelName} {id} Deleted Successfully.',
            'cancel' => '{modelName} {id} Cancelled Successfully.',
        ),
    );


    public function setAlertError($modelName, $type = 'none')
    {
        return str_replace('{modelName}', $modelName, @$this->message['error'][strtolower($type)]);
    }

    public function setAlertSuccess($modelName, $type = 'none', $id="")
    {
        $message = @$this->message['success'][strtolower($type)];
        $message =  str_replace('{modelName}', $modelName, $message);
        if(!empty($id)){
            $id = "#".$id;
        }
        $message =  str_replace('{id}', $id, $message);
        return $message;
    }
}

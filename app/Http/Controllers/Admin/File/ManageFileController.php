<?php

namespace App\Http\Controllers\Admin\File;

use Response;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Collection;

class ManageFileController extends Controller
{
    public function sample($model, Request $request){
        $filename = $model."-Sample.xlsx";
        $model = 'App\Models\\'.$model;
        if(property_exists($model, 'importable')){
            $model = new $model();
            $importable = $model->get_importable();
            $record = $model::latest()->first();
            $data = [];

            if(!empty($record)) {
                foreach ($importable as $field) {
                    $fetching_field = isset($field['title']) ? $field['field'] : $field;

                    if ($record->$fetching_field === 0) {
                        $data[] = "0";
                    }else {
                        $data[] = $record->$fetching_field;
                    }
                }
            }

            return (new Collection([isset($importable['title']) ? $importable['title'] : $importable,$data]))->downloadExcel(
                $filename,
                $writerType = null,
                $headings = false
            );
        }
    }

    public function import($model, Request $request){
        if($request->hasFile('file')){
            $this->validate($request, [
                'file' => 'required|file',
            ]);

            $extensions = array("xlsx");
            $result = array($request->file('file')->getClientOriginalExtension());
            if(in_array($result[0], $extensions)){
                $path = $request->file('file')->getRealPath();

                $modelName = $model;
            	$model = "App\Imports\\".$model."Import";
                $model = new $model();
                if(Excel::import($model, request()->file('file'))){
                    $request->session()->flash('success', 'Data imported successfully.');
                }
            }else{
                $request->session()->flash('error', 'Please select valid file. The following extensions are allowed: '.implode(",",$extensions).".");
            }
        }
        return back();
    }

    public function export($model, Request $request){
        $fileName = $model;
    	$model = "App\Exports\\".$model."Export";
    	$model = new $model();
        return Excel::download($model, $fileName.'.xlsx');
    }

    public function download($path, Request $request){
        $path = base_path(str_replace("|","/",$path));
        return response()->download($path);
    }
}

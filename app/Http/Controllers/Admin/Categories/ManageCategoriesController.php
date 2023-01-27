<?php

namespace App\Http\Controllers\Admin\Categories;

use App\Models\GiftIdea;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\TraitLibraries\AlertMessages;
use DataTables;
use App\Models\User;
use App\Models\Website;
use App\Http\Requests\StoreGiftIdeasRequest;
use App\Http\Requests\UpdateGiftIdeasRequest;
use App\Models\Category;
use App\Http\Requests\StoreCategoriesRequest;
use Validator;
use Storage;


class ManageCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     *
     */
    use AlertMessages;

    public $module = "Categories";

    protected $mainViewFolder = 'admin.categories.manage-categories.';


    public $permissions = Array(
        "index" => "read",
        "show" => "read",
        "create" => "create",
        "update" => "update",
        "edit" => "update",
        "destroy" => "delete",
    );


    public function __construct()
    {
        if(isset($this->module)){
          \View::share('module', $this->module);
        }
    }
    public function index(Request $request)
    {
        if($request->ajax()){
            return  Datatables::of(Category::orderBy('created_at','desc'))
                    ->addColumn('action', function ($data){
                        return Category::actionButtons($data);
                    })->editColumn('created_at', function ($data){
                        return \App\Helpers\Common::CTL($data->created_at);
                    })->editColumn('active',function($data){
                    if($data->active==1) {
                        return '<span class="btn btn-success btn-sm">Active</span>';
                    }else {
                        return '<span class="btn btn-danger btn-sm">Inactive</span>';
                    }
                })->editColumn('image_path', function ($data){
                        $url = asset("storage/uploads/category/".$data->image_path);
                        return ' <a href="/storage/uploads/category/'.$data->image_path.'" target="_blank">
                        <img src="'.$url.'" border="0" width="40" class="img-rounded" align="center" id="image"/></a>';
                    })->rawColumns(['action','image_path','active'])->make(true);
        }
        return view($this->mainViewFolder . 'index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = new Category();

        return view($this->mainViewFolder . 'form', compact('model'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategoriesRequest $request)
    {
        $action = "create";

        try{

            $imagePath= $request->file('image');
            $ext = $imagePath->getClientOriginalName();
            $imageName = $imagePath->getClientOriginalName();
            $imageName = date('YmdHis').".".$ext;
            $request->file('image')->storeAs('uploads/category', $imageName, 'public');
            $request->merge([
                'image_path'=>$imageName
            ]);
            Category::create($request->except(['image']));
            $request->session()->flash('success', $this->setAlertSuccess('Category', $action));
            return redirect()->route('categories.index');
        }
        catch(\Exception $e){
            return back()->withErrors(['error'=>$e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
        $model = Category::find($id);
        if(!empty($model)){
            return view($this->mainViewFolder.'show', compact('model'));
        }
        else{
            $request->session()->flash('warning', $this->setAlertError('Cattegory', 'none'));
            return redirect(route('Category.index'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = Category::find($id);
        $model->password = null;
        return view($this->mainViewFolder . 'form', compact('model'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)

    {
        if($request->has_image==0 && !$request->hasfile('image')){
            return redirect()->back()->withErrors(['error'=>'Image filed is required.']);
        }

        if($request->hasfile('image')){
            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'image' => 'required|mimes:jpeg,jpg,png|max:2048|dimensions:min_width=1920,min_height=600'
              ]);
             if($validator->fails()){
                return redirect()->back()->withErrors($validator)->withInput();
             }
            $imagePath= $request->file('image');
            $ext = $imagePath->getClientOriginalName();
            $imageName = $imagePath->getClientOriginalName();
            $imageName = date('YmdHis').".".$ext;
            $request->file('image')->storeAs('uploads/category', $imageName, 'public');
            Category::where('id',$id)->update(['image_path'=>$imageName]);

        }
        Category::where('id',$id)->update(['title'=>$request->title,'active'=>$request->active]);
        return redirect()->route('categories.index')->with(['success'=>'Category Updated Successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $record = Category::find($id);
        if ($record->delete()) {
            $request->session()->flash('success', $this->setAlertSuccess('Category', 'delete'));
        }
        return redirect()->route('categories.index')->with(['success'=>'Category Deleted Successfully.']);
    }
}

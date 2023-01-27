<?php

namespace App\Http\Controllers\Admin\GiftIdeas;

use App\Http\Controllers\BaseController;
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

class ManageGiftIdeasController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     *
     */
    use AlertMessages;

    public $module = "GiftIdea";

    protected $mainViewFolder = 'admin.giftideas.manage-giftideas.';


    public $permissions = Array(
        "index" => "read",
        "show" => "read",
        "create" => "create",
        "update" => "update",
        "edit" => "update",
        "destroy" => "delete",
    );
    public function index(Request $request)
    {

        if($request->ajax()){
            return  Datatables::of(GiftIdea::with('categories')->orderBy('created_at','desc')->get())
                    ->addColumn('action', function ($data){
                        return GiftIdea::actionButtons($data);
                    })->addColumn('category', function ($data){
                        return $data->categories->title;
                    })->editColumn('price', function ($data){
                        return '$' . number_format($data->price);
                    })->editColumn('item_url', function ($data){
                        return '<a href=" '.$data->item_url.' ">Link</a>';
                    })->editColumn('created_at', function ($data){
                        return \App\Helpers\Common::CTL($data->created_at);
                    })->editColumn('image_path', function ($data){
                        $url = asset("storage/uploads/wishlist_item/".$data->image_path);
                        return ' <a href="/storage/uploads/wishlist_item/'.$data->image_path.'" target="_blank">
                        <img src="'.$url.'" border="0" width="40" class="img-rounded" align="center" id="image"/></a>';
                    })->editColumn('active',function($data){
                    if($data->active==1) {
                        return '<span class="btn btn-success btn-sm">Active</span>';
                    }else {
                        return '<span class="btn btn-danger btn-sm">Inactive</span>';
                    }
                })->rawColumns(['action','category','item_url','image_path','active'])->make(true);

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
        $model = new GiftIdea();
        $categories = Category::all();
        return view($this->mainViewFolder . 'form', compact('model','categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreGiftIdeasRequest $request)
    {
        $action = "create";

        try{
            $imagePath= $request->file('image');
            $ext = $imagePath->getClientOriginalName();
            $imageName = $imagePath->getClientOriginalName();
            $imageName = date('YmdHis').".".$ext;
            $request->file('image')->storeAs('uploads/wishlist_item', $imageName, 'public');
            $request->merge([
                'image_path'=>$imageName,
                'merchant_name'=>$request->merchant
            ]);
            GiftIdea::create($request->except(['image']));
            $request->session()->flash('success', $this->setAlertSuccess('Gift Guide', $action));
            return redirect()->route('giftguide.index');

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
        $model = GiftIdea::find($id);
        $categories=Category::all();
        if(!empty($model)){
            return view($this->mainViewFolder.'show', compact('model','categories'));
        }
        else{
            $request->session()->flash('warning', $this->setAlertError('GiftIdea', 'none'));
            return redirect(route('giftguide.index'));
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
        $model = GiftIdea::find($id);
        $model->password = null;
        $categories=Category::all();
        return view($this->mainViewFolder . 'form', compact('model','categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateGiftIdeasRequest $request, $id)

    {
        if($request->has_image==0 && !$request->hasfile('image')){
            return redirect()->back()->withErrors(['error'=>'Image filed is required.']);
        }

        if($request->hasfile('image')){
            $imagePath= $request->file('image');
            $ext = $imagePath->getClientOriginalName();
            $imageName = $imagePath->getClientOriginalName();
            $imageName = date('YmdHis').".".$ext;
            $request->file('image')->storeAs('uploads/wishlist_item', $imageName, 'public');
            GiftIdea::where('id',$id)->update(['image_path'=>$imageName]);
        }
        GiftIdea::where('id',$id)->update([
            'category_id'=>$request->category_id,
            'item_name'=>$request->item_name,
            'item_url'=>$request->item_url,
            'price'=>$request->price,
            'merchant'=>$request->merchant,
            'shipping_fee'=>$request->shipping_fee,
            'expedited_shipping_fee'=>$request->expedited_shipping_fee,
            'merchant_name'=>$request->merchant,
            'active'=>$request->active,
        ]);
        return redirect()->route('giftguide.index')->with(['success'=>'Item Updated Successfully.']);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $record = GiftIdea::find($id);
        if ($record->delete()) {
            $request->session()->flash('success', $this->setAlertSuccess('GiftIdea', 'delete'));
        }
        return redirect()->route('giftguide.index')->with(['success'=>'Item Deleted Successfully.']);
    }
}

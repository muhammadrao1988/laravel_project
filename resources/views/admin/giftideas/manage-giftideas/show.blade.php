@extends('adminlte::page')
@section('title', (($model->exists)?'View':'New').' Gift Guide '.(($model->exists)?'#'.$model->id:''))
@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h1>@yield('title')</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('giftguide.index') }}">Gift Guide</a></li>
                <li class="breadcrumb-item active">{{ ($model->exists)?'View #'.$model->id:'New' }}</li>
            </ol>
        </div>
    </div>
@stop
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <form action="{{ isset($model->id) ? route('giftguide.update',$model->id) : route('giftguide.store') }}" method="POST" class="registerForm" enctype="multipart/form-data">
                        @if(isset($model->id))
                            @method('put')
                        @endif
                        <input type="hidden" name="giftidea" value="GiftIdea">
                        @csrf
                        @if ($model->exists)
                            <input type="hidden" name="id" id="id" value="{{ $model->id }}">
                        @endif
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 col-sm-4 col-12">
                                    <div class="label-text">
                                        <label for="">Category *</label>
                                        <select name="category_id"  class="form-control{{ $errors->has('category') ? ' is-invalid' : '' }} select2" style="width: 100%" data-name="Category" required>
                                            <option value="">Select</option>
                                            @foreach ($categories as $category )
                                                <option value="{{$category->id}}" {{ (old('category_id') == $category->id) ? 'selected=""':'' }} {{ ($model->category_id == $category->id) ? 'selected=""':''}} >
                                                    {{ $category->title }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('category'))
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('category') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label for="">Merchant *</label>
                                        <select id="merchant" name="merchant"  class="form-control{{ $errors->has('merchant') ? ' is-invalid' : '' }} select2" style="width: 100%" data-name="Merchant" required>
                                            <option value="">Select</option>
                                            @foreach(\App\Helpers\Common::merchants() as  $key=>$merchant)
                                                <option data-merchant-name="{{$key}}" data-shipping-price="{{$merchant['shipping_price']}}" value="{{$merchant['name']}}" {{ (old('merchant') == $merchant['name']) ? 'selected':'' }} {{ $model->merchant == $merchant['name'] ? 'selected':''}} >
                                                    {{$merchant['name']}}</option>
                                            @endForeach
                                        </select>
                                        @if ($errors->has('merchant'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('merchant') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <input type="hidden" id="merchant_name" name="merchant_name" value="">
                                    <div class="form-group">
                                        <label for="price">Price (USD)*</label>
                                        <input type="number" id="price" required data-name="Price"
                                               class="form-control{{ $errors->has('price') ? ' is-invalid' : '' }}" value="{{ (old('price'))? old('price'): $model->price }}" name="price">
                                        @if ($errors->has('item_url'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('item_url') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label for="expedited_shipping_fee">Expedited Shipping Fee </label>
                                        <input type="number"   id="expedited_shipping_fee"  data-name="Shipping Fee"
                                               class="form-control{{ $errors->has('expedited_shipping_fee') ? ' is-invalid' : '' }}" value="{{ (old('expedited_shipping_fee'))? old('expedited_shipping_fee'): $model->expedited_shipping_fee }}" name="expedited_shipping_fee">
                                        @if ($errors->has('expedited_shipping_fee'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('expedited_shipping_fee') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="item_name">Item Name *</label>
                                        <input type="text" id="item_name" required data-name="Item Name"
                                               class="form-control{{ $errors->has('item_name') ? ' is-invalid' : '' }}" value="{{ (old('item_name'))? old('item_name'): $model->item_name }}"  name="item_name">
                                        @if ($errors->has('item_name'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('item_name') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label for="item_url">Item URL *</label>
                                        <input type="text"  id="item_url" data-name="Item Url" required
                                               class="form-control{{ $errors->has('item_url') ? ' is-invalid' : '' }}" value="{{ (old('item_url'))? old('item_url'): $model->item_url }}" name="item_url">
                                        @if ($errors->has('item_url'))
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('item_url') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label for="price">Shipping Fee *</label>
                                        <input type="number" required  id="shipping_fee"  data-name="Shipping Fee"
                                               class="form-control{{ $errors->has('shipping_fee') ? ' is-invalid' : '' }}" value="{{ (old('shipping_fee'))? old('shipping_fee'): $model->shipping_fee }}" name="shipping_fee">
                                        @if ($errors->has('shipping_fee'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('shipping_fee') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label for="active">Status</label>
                                        <select id="active" name="active" class="form-control select2" style="width: 100%">
                                            @foreach([1=>'Active', 0=>'Inactive'] as  $key=>$value)
                                                <option value="{{$key}}"  {{ ($model->active == $key && !($model->active == "")) ? 'selected':''}} >
                                                    {{ $value }}
                                                </option>
                                            @endForeach
                                        </select>
                                    </div>

                                </div>
                                <div class="col-md-4 col-sm-4 col-12">
                                    @if(!isset($model->image_path))
                                        <div class="upload__box">
                                            <div class="upload__btn-box">
                                                <label class="upload__btn">
                                                    <p>Upload Image</p>
                                                    <input type="file" class="upload__inputfile" name="image">
                                                </label>
                                            </div>
                                            <div class="upload__img-wrap"></div>
                                        </div>
                                    @else
                                        <div id="img">
                                            <img src="{{ asset("storage/uploads/wishlist_item/".$model->image_path)}}" alt="" name="image" width="150" height="150">
                                        </div>
                                        <input type="hidden" name="has_image" id="has-image" value="1">
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('giftguide.index') }}"
                               class="btn btn-raised btn-warning mr-1">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection
@section('css')
@stop
@section('plugins.Select2', true)
@section('js')
<script>
    $(document).ready(function () {
            $(".registerForm input, .registerForm select").prop('disabled',true);
        })
</script>

@stop


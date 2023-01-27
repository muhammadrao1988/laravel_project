@extends('adminlte::page')
@section('title', (($model->exists)?'Edit':'Add ').' Gift Guide '.(($model->exists)?'#'.$model->id:''))
@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h1>@yield('title')</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('giftguide.index') }}">Gift Guide</a></li>
                <li class="breadcrumb-item active">{{ ($model->exists)?'Edit #'.$model->id:'Add' }}</li>
            </ol>
        </div>
    </div>
@stop
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <form id="giftGuideForm" action="{{ isset($model->id) ? route('giftguide.update',$model->id) : route('giftguide.store') }}" method="POST" class="registerForm" enctype="multipart/form-data">
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
                                    <p style="color: #fff">df d </p>
                                </div>
                                    <div class="form-group">
                                        <label for="">Merchant *</label>
                                       {{-- <select id="merchant" name="merchant"  class="form-control{{ $errors->has('merchant') ? ' is-invalid' : '' }} select2" style="width: 100%" data-name="Merchant" required>
                                            <option value="">Select</option>
                                            @foreach(\App\Helpers\Common::merchants() as  $key=>$merchant)
                                                <option data-merchant-name="{{$key}}" data-shipping-price="{{$merchant['shipping_price']}}" value="{{$merchant['name']}}" {{ (old('merchant') == $merchant['name']) ? 'selected':'' }} {{ $model->merchant == $merchant['name'] ? 'selected':''}} >
                                                    {{$merchant['name']}}</option>
                                            @endForeach
                                        </select>--}}
                                        <input type="text" id="merchant" required data-name="Merchant"
                                               class="form-control{{ $errors->has('merchant') ? ' is-invalid' : '' }}" value="{{ (old('merchant'))? old('merchant'): $model->merchant }}"  name="merchant">
                                        @if ($errors->has('merchant'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('merchant') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <input type="hidden" id="merchant_name" name="merchant_name" value="">
                                    <div class="form-group">
                                        <label for="price">Price (USD) *</label>
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
                                        <p>Character Count: <span id="item_name_count">0</span></p>
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
                                    <div class="upload__box">
                                        <div class="upload__btn-box">
                                            <div class="error_image text-danger"></div>
                                            <label class="upload__btn">
                                                <p>Upload Image</p>

                                                <input type="file" accept="image/*" class="upload__inputfile" name="image">
                                            </label>
                                        </div>
                                        <div class="upload__img-wrap">
                                            <div class="upload__img-box">
                                            @if(!empty($model->image_path))
                                                <div id="img">
                                                <img src="{{ asset("storage/uploads/wishlist_item/".$model->image_path)}}" alt="" name="image" width="150" height="150">
                                                    <button class="close" id="edit-btn">
                                                        <span><i class="fas fa-times-circle"></i> </span>
                                                    </button>
                                                </div>
                                            @endif
                                            </div>

                                        </div>
                                    </div>
                                    @if(!isset($model->image_path))

                                    @else
                                    {{--<div id="img">
                                    <button class="close" id="edit-btn">
                                        <span>&times;</span>
                                    </button>
                                    <img src="{{ asset("storage/uploads/wishlist_item/".$model->image_path)}}" alt="" name="image" width="150" height="150">
                                    </div>--}}
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
                            @if(!isset($model->id))
                                <button type="button" class="btn btn-raised btn-primary" id="save-btn"
                                        name="save_btn" value="{{ config('constants.SAVE') }}">
                                    <i class="fa fa-save"></i> Save
                                </button>
                            @else
                                <button type="button" class="btn btn-raised btn-primary" id="save-btn"
                                        name="save_btn" value="{{ config('constants.UPDATE') }}">
                                    <i class="fa fa-save"></i> Save
                                </button>
                            @endif
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
        function merchantChange(){

           $("#merchant_name").val($('option:selected', "#merchant").attr('data-merchant-name'));

        }
        $(document).ready(function () {
            $("#item_name_count").text($("#item_name").val().length);
            $('#item_name').unbind('keyup change input paste').bind('keyup change input paste',function(e){
                var $this = $(this);
                var val = $this.val();
                var valLength = val.length;
                var maxCount = 25;
                if(valLength>maxCount){
                    $this.val($this.val().substring(0,maxCount));
                }
            });
            $('#item_name').on('keyup', function() {
                $("#item_name_count").text($(this).val().length)
                //console.log(this.value.length);
            });
            @if(isset($model->id))
                merchantChange();
            @endif

            $("#merchant").change(function () {
                if($(this).val()!="") {
                    $("#merchant_name").val($('option:selected', $(this)).attr('data-merchant-name'));
                    $("#shipping_fee").val($('option:selected', $(this)).attr('data-shipping-price'));
                }
            })

            jQuery.validator.addMethod("noSpace", function (value, element) {
                if(value!="") {
                    return value.indexOf(" ") < 0 && value != "";
                }else{
                    return true;
                }
            }, "Space is not allowed");


            jQuery.validator.addMethod("passwordValidation", function (value, element) {
                var regexp =  /^(?=.*[A-Za-z])(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{8,}$/;
                var re = new RegExp(regexp);
                console.log(this.optional(element) || re.test(value));
                // alert("ddd");
                return this.optional(element) || re.test(value);
            }, "Invalid password");

            jQuery.validator.addMethod("usernameValidation", function (value, element) {
                var regexp =  /^(?=.*[A-Za-z])(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{8,}$/;
                var re = new RegExp(regexp);
                console.log(this.optional(element) || re.test(value));
                // alert("ddd");
                return this.optional(element) || re.test(value);
            }, "Invalid password");
            jQuery.validator.addMethod("urlValidation", function (value, element) {
                var regexp =  {{config('constants.VALID_URL')}};
                var re = new RegExp(regexp);
                return this.optional(element) || re.test(value);
            }, "Invalid Item URL");
            jQuery.validator.addMethod("urlMerchantValidation", function (value, element) {
                var merchant_name_selected =  $("#merchant").val();
                if(merchant_name_selected=="" || merchant_name_selected=="others"){
                    return true;
                }else{
                    var url_val = $("#item_url").val();
                    return url_val.includes(merchant_name_selected);
                }
            }, "Item URL must contain selected merchant value");

            jQuery.validator.addMethod("priceValidation", function (value, element) {
                var price =  $("#price").val();
                if(price <= 0){
                    return false;
                }
                return true;
            }, "Item price must be greater than zero");



            $(".registerForm").validate({
                errorClass: 'is-invalid text-danger',
                errorElement: "div",
                errorPlacement: function (error, element) {
                    error.insertAfter(element.closest('div'));
                    error.addClass("error_text");
                    //console.log(element);
                },
                rules: {
                    password: {
                        minlength: 8,
                        noSpace: true,
                        passwordValidation: true
                    },
                    username :{
                        noSpace: true,
                        minlength: 4,
                    },
                    item_url : {
                        urlValidation : true,
                        //urlMerchantValidation : true
                    },
                    price : {
                        priceValidation : true
                    }
                }
            });
            $("#save-btn").click(function () {
                if($(".registerForm").valid()){
                    $("#save-btn").prop("disabled",true);
                    $("#giftGuideForm").submit();
                }
            })
        });

        $('#edit-btn').click(function(e){
        e.preventDefault();
           $('#has-image').val(0);
            $(this).parent().html('');
            /*$('#img').append('<div class="upload__box">'+
                                '<div class="upload__btn-box">'+
                                    '<label class="upload__btn">'+
                                        '<p>Upload images</p>'+
                                        '<input type="file" class="upload__inputfile" name="image">'+
                                    '</label>'+
                                '</div>'+
                                    '<div class="upload__img-wrap"></div>'+
                            '</div>');*/
                    ImgUpload();
            });

    </script>
    <script>
        $(document).ready(function () {
    ImgUpload();
});

function ImgUpload() {
  var imgWrap = "";
  var imgArray = [];

  //$('.upload__inputfile').each(function () {

    $('.upload__inputfile').on('change', function (e) {
      imgWrap = $(this).closest('.upload__box').find('.upload__img-wrap');
        $(".error_image").html("");

      var maxLength = $(this).attr('data-max_length');
      var files = e.target.files;
      var filesArr = Array.prototype.slice.call(files);
      var iterator = 0;
      filesArr.forEach(function (f, index) {

        if (!f.type.match('image.*')) {
            $(".error_image").html("Only jpg,jpeg,png and gif images are allowed. ")
          return;
        }

        if (imgArray.length > maxLength) {
          return false
        } else {
          var len = 0;
          for (var i = 0; i < imgArray.length; i++) {
            if (imgArray[i] !== undefined) {
              len++;
            }
          }
          if (len > maxLength) {
            return false;
          } else {
            imgArray.push(f);

            var reader = new FileReader();
            reader.onload = function (e) {
              var html = "<div class='upload__img-box'><div style='background-image: url(" + e.target.result + ")' data-number='" + $(".upload__img-close").length + "' data-file='" + f.name + "' class='img-bg'><div class='upload__img-close'><i class='fas fa-faw fa-times'></i> </div></div></div>";
              imgWrap.html(html);
              iterator++;
            }
            reader.readAsDataURL(f);
          }
        }
      });
    });
  //});

  $('body').on('click', ".upload__img-close", function (e) {
    $('.upload__inputfile').val('');
    var file = $(this).parent().data("file");
    for (var i = 0; i < imgArray.length; i++) {
      if (imgArray[i].name === file) {
        imgArray.splice(i, 1);
        break;
      }
    }
    $(this).parent().parent().remove();
  });
}


    </script>
@stop

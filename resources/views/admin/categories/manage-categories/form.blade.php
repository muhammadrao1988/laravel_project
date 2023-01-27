@extends('adminlte::page')
@section('title', (($model->exists)?'Edit':'New').' Categories '.(($model->exists)?'#'.$model->id:''))
@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h1>@yield('title')</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Category</a></li>
                <li class="breadcrumb-item active">{{ ($model->exists)?'Edit #'.$model->id:'New' }}</li>
            </ol>
        </div>
    </div>
@stop
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <form action="{{ isset($model->id) ? route('categories.update',$model->id) : route('categories.store') }}" method="POST" class="registerForm" enctype="multipart/form-data">
                        @if(isset($model->id))
                            @method('put')
                        @endif
                        <input type="hidden" name="category" value="Category">
                        @csrf
                        @if ($model->exists)
                            <input type="hidden" name="id" id="id" value="{{ $model->id }}">
                        @endif
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="category">Title *</label>
                                        <input type="text" id="category"  data-name="Title" required
                                        class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" value="{{ (old('title'))? old('title'): $model->title }}"  name="title">
                                        @if ($errors->has('title'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('title') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-12">
                                    @if(!isset($model->image_path))
                                    <div class="upload__box">
                                            <div class="upload__btn-box">
                                                <label class="upload__btn">
                                                <p>Upload Image</p>
                                                <input type="file" class="upload__inputfile" name="image" accept="image/*">
                                                </label>
                                            </div>
                                        <div class="upload__img-wrap"></div>
                                        The image dimension must be at-least 1920 x 600
                                    </div>
                                    @else
                                    <div id="img">
                                    <button class="close" id="edit-btn">
                                        <span>&times;</span>
                                    </button>
                                    <img src="{{ asset("storage/uploads/category/".$model->image_path)}}" alt="" name="image" width="150" height="150">
                                    </div>
                                    <input type="hidden" name="has_image" id="has-image" value="1">
                                    @endif
                                </div>
                                <div class="col-md-6 col-sm-6 col-12">
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
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('categories.index') }}"
                               class="btn btn-raised btn-warning mr-1">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                            @if(!isset($model->id))
                                <button type="submit" class="btn btn-raised btn-primary" id="save-btn"
                                        name="save_btn" value="{{ config('constants.SAVE') }}">
                                    <i class="fa fa-save"></i> Save
                                </button>
                            @else
                                <button type="submit" class="btn btn-raised btn-primary" id="save-btn"
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

        $(document).ready(function () {

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
                    }
                }
            });
        });

        $('#edit-btn').click(function(e){
        e.preventDefault();
           $('#has-image').val(0);
                $(this).parent().html('');
            $('#img').append('<div class="upload__box">'+
                                '<div class="upload__btn-box">'+
                                    '<label class="upload__btn">'+
                                        '<p>Upload Image</p>'+
                                        '<input type="file" class="upload__inputfile" name="image">'+
                                    '</label>'+
                                '</div>'+
                                    '<div class="upload__img-wrap"></div>'+
                            '</div>');
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

  $('.upload__inputfile').each(function () {

    $(this).on('change', function (e) {

      imgWrap = $(this).closest('.upload__box').find('.upload__img-wrap');
      var maxLength = $(this).attr('data-max_length');
      var files = e.target.files;
      var filesArr = Array.prototype.slice.call(files);
      var iterator = 0;
      filesArr.forEach(function (f, index) {

        if (!f.type.match('image.*')) {
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
              var html = "<div class='upload__img-box'><div style='background-image: url(" + e.target.result + ")' data-number='" + $(".upload__img-close").length + "' data-file='" + f.name + "' class='img-bg'><div class='upload__img-close'></div></div></div>";
              imgWrap.append(html);
              iterator++;
            }
            reader.readAsDataURL(f);
          }
        }
      });
    });
  });

  $('body').on('click', ".upload__img-close", function (e) {
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

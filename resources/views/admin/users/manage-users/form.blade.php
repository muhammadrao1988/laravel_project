@extends('adminlte::page')
@section('title', (($model->exists)?'Edit':'New').' User '.(($model->exists)?'#'.$model->id:''))
@section('content_header')
<div class="row">
    <div class="col-sm-6">
        <h1>@yield('title')</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('users.index') }}">User</a></li>
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
                <form method="post" class="form"
                enctype="multipart/form-data"
                action="{{ ($model->exists)? route('users.update', [$model->id]): route('users.store') }}">
                    <input type="hidden" name="userType" value="Admin">
                @if ($model->exists)
                @method('PUT')
                @endif
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="user_name">Full Name</label>
                                <input type="text" id="user_name"
                                class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                value="{{ (old('name'))? old('name'): $model->name }}"
                                placeholder="Full Name"
                                name="name" autofocus>
                                @if ($errors->has('name'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="user_email">Email</label>
                                <input type="email" id="user_email"
                                class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                value="{{ (old('email'))? old('email'): $model->email}}"
                                placeholder="Email"
                                name="email">
                                @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="user_contactNumber">Contact Number</label>
                                <input type="text" step="0.01" id="user_contactNumber"
                                class="form-control{{ $errors->has('contactNumber') ? ' is-invalid' : '' }}"
                                value="{{ (old('contactNumber'))? old('contactNumber'): $model->contactNumber }}"
                                placeholder="Contact Number"
                                name="contactNumber" autofocus>
                                @if ($errors->has('contactNumber'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('contactNumber') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="customer_password">Password</label>
                                <input type="text" id="customer_password"
                                class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                value="{{ (old('password'))? old('password'): $model->password }}"
                                placeholder="Password"
                                name="password" autofocus>
                                @if ($errors->has('password'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="users_alphaRole">Alpha Role</label>
                            <select id="users_alphaRole" name="alphaRole"
                            class="form-control{{ $errors->has('alphaRole') ? ' is-invalid' : '' }} select2" style="width: 100%">
                            <option value="">Select</option>
                            @foreach(['SUPER', 'USERS'] as  $title)
                            <option value="{{$title}}" {{ (old('alphaRole') == $title) ? 'selected=""':'' }} {{ ($model->alphaRole == $title) ? 'selected=""':''}} >
                            {{ str_replace('_',' ',$title) }}</option>
                            @endForeach
                        </select>
                        @if ($errors->has('alphaRole'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('alphaRole') }}</strong>
                        </span>
                        @endif
                    </div>
                    <div class="form-group col-md-4 req-by-user">
                        <label for="role_id">Roles</label>
                        <select id="role_id" name="role_id[]" class="form-control{{ $errors->has('role_id') ? ' is-invalid' : '' }} select2" multiple="" style="width: 100%;">
                            @foreach($roles as  $key => $title)
                            <option value="{{$key}}" {{ (old('role_id') == null) ? '' : (in_array($key, old('role_id')) ? "selected" : "") }} {{in_array($key,$assigned_role_array) ? "selected" : "" }}>{{ $title }}</option>
                            @endForeach
                        </select>
                        @if ($errors->has('role_id'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('role_id') }}</strong>
                        </span>
                        @endif
                    </div>

                        @if($model->id != $user_data['id'])
                            <div class="col-md-4">
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
                         @else
                            <input type="hidden" name="active" value="{{$model->active}}">
                         @endif
        </div>
    </div>

    <div class="card-footer">
        <a href="{{ route('users.index') }}"
        class="btn btn-raised btn-warning mr-1">
        <i class="fas fa-arrow-left"></i> Back
    </a>
    @if(!isset($model->id))
    <button type="submit" class="btn btn-raised btn-primary disableOnSubmit" id="save-btn" name="save_btn" value="{{ config('constants.SAVE') }}">
        <i class="fa fa-save"></i> Save
    </button>
    <button type="submit" class="btn btn-raised btn-primary disableOnSubmit" id="save-add-more-btn" name="save_btn" value="{{ config('constants.SAVE_ADD_MORE') }}">
        <i class="fa fa-save"></i> Save & Add More
    </button>
    @else
    <button type="submit" class="btn btn-raised btn-primary disableOnSubmit" id="save-btn" name="save_btn" value="{{ config('constants.UPDATE') }}">
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


    $('#users_alphaRole').change(function(){
        if($(this).val() == "SUPER"){
            $('.req-by-user').addClass('hidden');
            emptyUserField();
        }else{
            $('.req-by-user').removeClass('hidden');
        }
    });

    if($('#users_alphaRole').val() == "USERS"){
        $('.req-by-user').removeClass("hidden");
    }else{
        $('.req-by-user').addClass("hidden");
        emptyUserField();
    }

    function emptyUserField(){
        $('#role_id').val('').trigger('change');
        $('#customerId').val('').trigger('change');
    }

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
            }, "Must have at least 8 characters and contain a number and special character");

            jQuery.validator.addMethod("usernameValidation", function (value, element) {
                var regexp =  /^(?=.*[A-Za-z])(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{8,}$/;
                var re = new RegExp(regexp);
                console.log(this.optional(element) || re.test(value));
                // alert("ddd");
                return this.optional(element) || re.test(value);
            }, "Invalid password");

            $(".form").validate({
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

</script>
@stop

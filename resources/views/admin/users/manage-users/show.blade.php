@extends('adminlte::page')
@section('title', 'View User #'.$model->id)
@section('content_header')
<div class="row">
    <div class="col-sm-6">
        <h1>@yield('title')</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
          <li class="breadcrumb-item active">@yield('title')</li>
        </ol>
    </div>
</div>
@stop
@section('content')
<div class="container-fluid">
    <div class="row">

          <div class="col-sm-12">
            <div class="card card-primary card-outline">
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label>Name</label>
                            <input type="text" disabled value="{{ $model->name }}" name="name"
                            class="form-control">
                        </div>

                        <div style="display: none" class="form-group col-md-4">
                            <label>Phone:</label>
                            <input type="text" disabled value="{{ $model->contactNumber }}"
                                   class="form-control">
                        </div>

                        <div class="form-group col-md-4">
                            <label>Email</label>
                            <input type="text" disabled value="{{ $model->email }}" name="email"
                            class="form-control">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Contact Number</label>
                            <input type="text" disabled value="{{ $model->contactNumber }}" name="contactNumber"
                            class="form-control">
                        </div>
                        <div class="form-group col-md-4">
                            <label>Alpha Role</label>
                            <input type="text" disabled value="{{ $model->alphaRole }}" name="alphaRole"
                            class="form-control">
                        </div>

                        @if($model->alphaRole == "USERS")
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Roles</label>
                                <select disabled="" class="form-control select2" multiple="" style="width: 100%;">
                                    @foreach($roles as  $key => $title)
                                        <option value="{{$key}}" {{ in_array($key, $assigned_role_array) ? 'selected' : '' }}>
                                            {{ $title }}
                                        </option>
                                    @endForeach
                                </select>
                            </div>
                        </div>
                        @endif

                        <div class="col-md-4">
                            <div class="form-group {{ ($errors->has('active'))?"error" :'' }}">
                                <label for="users_active">Status</label>
                                <select id="users_active" name="active" disabled=""
                                class="form-control"  style="width: 100%">
                                    @foreach(['1'=>'Active', '0'=>'Inactive'] as  $key=>$value)
                                    <option value="{{$key}}" {{ ($model->active == $key) ? 'selected':''}} >{{ $value }}</option>
                                    @endForeach
                                </select>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="card-footer">
                    <a href="{{ route('users.index') }}"
                    class="btn btn-raised btn-warning mr-1">
                    <i class="fas fa-arrow-left"></i> Back
                    </a>
                    @if(\Common::canUpdate($module))
                    <a class="btn btn-raised btn-success mr-1" href="{{ route('users.edit', $model->id) }}"
                       data-original-title="" title="">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    @endif
                </div>
            </div>
        </div>
</div>
</div>
@stop

@section('css')
@stop
@section('plugins.Select2', true)
@section('js')
@stop

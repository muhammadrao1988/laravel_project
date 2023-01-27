@extends('adminlte::page')
@section('title', ('View Configuration ('.$model->description.')'))
@section('content_header')
<div class="row">
    <div class="col-sm-10">
        <h1 style="font-size: 27px;">@yield('title')</h1>
    </div>
    <div class="col-sm-2">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('configuration.index') }}">Configuration</a></li>
          <li class="breadcrumb-item active">{{ 'View #'.$model->id }}</li>
        </ol>
    </div>
</div>
@stop

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      
      <div class="card card-primary card-outline">
        <!-- <div class="card-header">
          <h3 class="card-title">Quick Example</h3>
        </div> -->
          <div class="card-body">
            <div class="row">
              <div class="form-group col-md-4">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" disabled>{{ $model->description }}</textarea>
              </div>

              <div class="form-group col-md-4">
                <label for="value">Value</label>
                @if(in_array($model->value, array("true", "false")))
                <select disabled="" class="form-control" style="width: 100%;">
                  <option value="true" {{ $model->value == "true" ? "selected" : "" }}>Yes</option>
                  <option value="false" {{ $model->value == "false" ? "selected" : "" }}>No</option>
                </select>
                @else
                <input disabled type="text" class="form-control" value="{{ $model->value }}">
                @endif
              </div>
            </div>
          </div>
          <div class="card-footer">
            <a href="{{ route('configuration.index') }}"
               class="btn btn-raised btn-warning mr-1">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            @if(\Common::canUpdate($module))
            <a class="btn btn-raised btn-success mr-1" href="{{ route('configuration.edit', $model->id) }}">
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

@section('js')
@stop
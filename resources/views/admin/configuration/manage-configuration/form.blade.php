@extends('adminlte::page')
@section('title', ((($model->exists)?'Edit':'New').' Configuration '.(($model->exists)?'('.$model->description.')':''))))
@section('content_header')
<div class="row">
  <div class="col-sm-10">
    <h1 style="font-size: 27px;">@yield('title')</h1>
  </div>
  <div class="col-sm-2">
    <ol class="breadcrumb float-sm-right">
      <li class="breadcrumb-item"><a href="{{ route('configuration.index') }}">Configuration</a></li>
      <li class="breadcrumb-item active">{{ ($model->exists)?'Edit #'.$model->id:'New' }}</li>
    </ol>
  </div>
</div>
@stop

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      
        <form action="{{ route('configuration.store') }}" method="POST">
      <div class="card card-primary card-outline">
        <!-- <div class="card-header">
          <h3 class="card-title">Quick Example</h3>
        </div> -->
          @csrf
          @if ($model->exists)
          <input type="hidden" name="id" id="id" value="{{ $model->id }}">
          @endif
          <div class="card-body">
            <div class="row">
              <div class="form-group col-md-4">
                <label for="value">Value</label>
                
                @if(in_array($model->value, array("true", "false")))
                <select class="form-control{{ $errors->has('value') ? ' is-invalid' : '' }}" id="value" name="value" style="width: 100%;">
                  <option value="true" {{ $model->value == "true" ? "selected" : "" }}>Yes</option>
                  <option value="false" {{ $model->value == "false" ? "selected" : "" }}>No</option>
                </select>
                @else
                <input type="text" class="form-control{{ $errors->has('value') ? ' is-invalid' : '' }}" id="value" name="value" value="{{ $model->value }}">
                @endif

                @if ($errors->has('value'))
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $errors->first('value') }}</strong>
                </span>
                @endif
              </div>
            </div>
          </div>
          <div class="card-footer">
            <a href="{{ route('configuration.index') }}"
            class="btn btn-raised btn-warning mr-1">
            <i class="fas fa-arrow-left"></i> Back
          </a>
          <button type="submit" class="btn btn-raised btn-primary disableOnSubmit" id="save-btn">
            <i class="fa fa-save"></i> Save
          </button>
        </div>
      </form>
    </div>

  </div>
</div>
</div>
@stop

@section('css')
@stop

@section('js')
@stop
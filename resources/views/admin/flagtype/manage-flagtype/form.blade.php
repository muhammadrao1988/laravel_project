@extends('adminlte::page')
@section('title', (($model->exists)?'Edit':'New').' Flag Type '.(($model->exists)?'#'.$model->id:''))
@section('content_header')
  <div class="row">
      <div class="col-sm-6">
          <h1>@yield('title')</h1>
      </div>
      <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('flagtype.index') }}">Flag Type</a></li>
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
        <!-- <div class="card-header">
          <h3 class="card-title">Quick Example</h3>
        </div> -->
        <form action="{{ route('flagtype.store') }}" method="POST">
          @csrf
          @if ($model->exists)
              <input type="hidden" name="id" id="id" value="{{ $model->id }}">
          @endif
          <div class="card-body">
            <div class="row">
              
              <div class="form-group col-md-4">
                <label for="name">Name</label>
                <input type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" id="name" name="name" value="{{ (old('name'))? old('name'): $model->name }}">
                @if ($errors->has('name'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                @endif
              </div>
            </div>
          </div>
          <div class="card-footer">
            <a href="{{ route('flagtype.index') }}"
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
@section('plugins.Select2', true)
@section('js')
@stop
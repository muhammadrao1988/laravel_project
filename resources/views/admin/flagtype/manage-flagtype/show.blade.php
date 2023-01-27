@extends('adminlte::page')
@section('title', 'View Flag Type #'.$model->id)
@section('content_header')
<div class="row">
    <div class="col-sm-6">
        <h1>@yield('title')</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('flagtype.index') }}">Flag Type</a></li>
          <li class="breadcrumb-item active">@yield('title')</li>
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
                <label for="name">Name</label>
                <input type="text" disabled class="form-control" id="name" name="name" value="{{ $model->name }}">
              </div>
            </div>
          </div>
          <div class="card-footer">
            <a href="{{ route('flagtype.index') }}"
               class="btn btn-raised btn-warning mr-1">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            @if(\Common::canUpdate($module))
            <a class="btn btn-raised btn-success mr-1" href="{{ route('flagtype.edit', $model->id) }}">
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
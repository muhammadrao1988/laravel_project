@extends('adminlte::page')
@section('title', 'View '.(!empty($flagtype) ? $flagtype->name : 'Flag').' #'.$model->id)
@section('content_header')
<div class="row">
    <div class="col-sm-6">
        <h1>@yield('title')</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ (!empty($flagtype) ? URL::to('/flag?flagtype='.$flagtype->code) : URL::to('/flag/')) }}">{{ !empty($flagtype) ? $flagtype->name : 'Flag' }}</a></li>
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

              <div class="form-group col-md-4" style="{{ !empty($flagtype) ? 'display: none' : '' }}">
                <label for="flagType">Flag Type</label>
                <select id="flagType" name="flagType" class="form-control select2-ajax" style="width: 100%;" data-select2-source="flagType-code" data-select2-value="{{ $model->flagType }}" disabled>
                </select>
              </div>

              <div class="form-group col-md-4">
                <label for="parentId">Parent</label>
                <select id="parentId" name="parentId" class="form-control select2-ajax" style="width: 100%;" data-select2-source="flag" data-select2-value="{{ $model->parentId }}" disabled>
                </select>
              </div>
            </div>

            <div class="row">
              <div class="form-group col-md-4">
                <label for="description">Description</label>
                <input type="text" disabled class="form-control" id="description" name="description" value="{{ $model->description }}">
              </div>

              <div class="form-group col-md-4">
                <label for="field1">Field 1</label>
                <input type="text" disabled class="form-control" id="field1" name="field1" value="{{ $model->field1 }}">
              </div>

              <div class="form-group col-md-4">
                <label for="field2">Field 2</label>
                <input type="text" disabled class="form-control" id="field2" name="field2" value="{{ $model->field2 }}">
              </div>
            </div>
          </div>
          <div class="card-footer">
            <a href="{{ (!empty($flagtype) ? URL::to('/flag?flagtype='.$flagtype->code) : URL::to('/flag/')) }}"
               class="btn btn-raised btn-warning mr-1">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            @if(\Common::canUpdate($module))
            <a class="btn btn-raised btn-success mr-1" href="{{ (!empty(@$_GET['flagtype']) ? \URL::to('/flag/'.$model->id.'/edit?flagtype='.@$_GET['flagtype']) : \URL::to('/flag/'.$model->id.'/edit')) }}">
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
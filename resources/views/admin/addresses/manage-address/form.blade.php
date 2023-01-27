@extends('adminlte::page')
@section('title', (($model->exists)?'Edit ':'New ').(!empty($flagtype) ? $flagtype->name : 'Flags').(($model->exists)?' #'.$model->id:''))
@section('content_header')
  <div class="row">
      <div class="col-sm-6">
          <h1>@yield('title')</h1>
      </div>
      <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ (!empty($flagtype) ? URL::to('/flag?flagtype='.$flagtype->code) : URL::to('/flag/')) }}">{{ !empty($flagtype) ? $flagtype->name : 'Flag' }}</a></li>
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
        <form action="{{ route('flag.store') }}" method="POST">
          @csrf
          @if($model->exists)
              <input type="hidden" name="id" id="id" value="{{ $model->id }}">
          @endif
          @if(!empty($flagtype))
              <input type="hidden" name="flagtype" id="flagtype" value="{{ $flagtype->code }}">
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

              <div class="form-group col-md-4" style="{{ !empty($flagtype) ? 'display: none' : '' }}">
                <label for="flagType">Flag Type</label>
                <select id="flagType" name="flagType" class="form-control{{ $errors->has('flagType') ? ' is-invalid' : '' }} select2-ajax" style="width: 100%;" data-select2-source="flagType-code" data-select2-value="{{ old('flagType') ? old('flagType') : ($model->flagType ? $model->flagType : @$flagtype->code) }}">
                </select>
                @if ($errors->has('flagType'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('flagType') }}</strong>
                    </span>
                @endif
              </div>

              <div class="form-group col-md-4">
                <label for="parentId">Parent</label>
                <select id="parentId" name="parentId" class="form-control{{ $errors->has('parentId') ? ' is-invalid' : '' }} select2-ajax" style="width: 100%;" data-select2-source="flag-for-parent" data-select2-value="{{ old('parentId') ? old('parentId') : $model->parentId }}">
                </select>
                @if ($errors->has('parentId'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('parentId') }}</strong>
                    </span>
                @endif
              </div>
            </div>

            <div class="row">
              <div class="form-group col-md-4">
                <label for="description">Decsription</label>
                <input type="text" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" id="description" name="description" value="{{ (old('description'))? old('description'): $model->description }}">
                @if ($errors->has('description'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('description') }}</strong>
                    </span>
                @endif
              </div>

              <div class="form-group col-md-4">
                <label for="field1">Field 1</label>
                <input type="text" class="form-control{{ $errors->has('field1') ? ' is-invalid' : '' }}" id="field1" name="field1" value="{{ (old('field1'))? old('field1'): $model->field1 }}">
                @if ($errors->has('field1'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('field1') }}</strong>
                    </span>
                @endif
              </div>

              <div class="form-group col-md-4">
                <label for="field2">Field 2</label>
                <input type="text" class="form-control{{ $errors->has('field2') ? ' is-invalid' : '' }}" id="field2" name="field2" value="{{ (old('field2'))? old('field2'): $model->field2 }}">
                @if ($errors->has('field2'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('field2') }}</strong>
                    </span>
                @endif
              </div>
            </div>

          </div>
          <div class="card-footer">
            <a href="{{ (!empty($flagtype) ? URL::to('/flag?flagtype='.$flagtype->code) : URL::to('/flag/')) }}"
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
            @if(($model->exists) && empty($flagtype))
              <button type="button" class="btn btn-raised btn-success mr-1 float-right disableOnSubmit" id="copy-btn">
                <i class="fa fa-copy"></i> Copy
              </button>
            @endif
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

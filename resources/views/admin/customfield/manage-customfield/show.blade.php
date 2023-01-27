@extends('adminlte::page')
@section('title', 'View'. (!empty(@$model[0]['model']) ? "" : "Add").' Custom Fields'.(!empty(@$model[0]['model']) ? " (".@$model[0]['model'].")" : ""))
@section('content_header')
<div class="row">
    <div class="col-sm-6">
        <h1>@yield('title')</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('customfield.index') }}">Custom Fields</a></li>
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
            <div class="row" id="div-main">
              @for($i = 1; $i<=$len; $i++)
                <div class="row col-md-12">
                  <div class="col-md-12">
                    <hr><h3 class="sr" name="field[{{$i}}][sr]" id="field-{{$i}}-sr">Field # {{ $i }}</h3><hr>
                  </div>
                  
                  <div class="form-group col-md-4">
                    <label>Title</label>
                    <input type="text" disabled class="form-control" value="{{ @$model[$i-1]['title'] }}"/>
                  </div>

                  <div class="form-group col-md-4">
                    <label>Name</label>
                    <input type="text" disabled class="form-control" value="{{ @$model[$i-1]['name'] }}" />
                  </div>

                  <div class="form-group col-md-4">
                    <label>Description</label>
                    <input type="text" disabled class="form-control" value="{{ @$model[$i-1]['description'] }}"/>
                  </div>

                  <div class="form-group col-md-4">
                    <label>Type</label>
                    <select disabled class="form-control">
                      <option value="" disabled selected="">{{ @$model[$i-1]['type'] }}</option>
                    </select>
                  </div>

                  <div class="form-group col-md-4">
                    <label>Acceptable / Options</label>
                    <input type="text" disabled class="form-control" value="{{ @$model[$i-1]['acceptable'] }}"/>
                  </div>

                  <div class="form-group col-md-4">
                    <label>Default Value</label>
                    <input type="text" disabled class="form-control" value="{{ @$model[$i-1]['defaultValue'] }}"/>
                  </div>

                  <div class="form-group col-md-4">
                    <label>Sorting #</label>
                    <input type="number" disabled class="form-control" value="{{ @$model[$i-1]['sort'] }}"/>
                  </div>

                  <div class="form-group col-md-4">
                    <label>Required?</label>
                    <select disabled class="form-control">
                      <option value="" disabled selected="">{{ explode('|', @$model[$i-1]['rules'])[0] == "required" ? "Yes" : "No" }}</option>
                    </select>
                  </div>

                  <div class="form-group col-md-4">
                    <label>Status</label>
                    <select disabled class="form-control">
                      <option value="" disabled selected="">{{ @$model[$i-1]['active'] == "1" ? "Active" : "Inactive" }}</option>
                    </select>
                  </div>
                </div>
              @endfor
            </div>
          </div>
          <div class="card-footer">
            <a href="{{ route('customfield.index') }}" class="btn btn-raised btn-warning mr-1">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            @if(\Common::canUpdate($module))
            <a class="btn btn-raised btn-success mr-1" href="{{ route('customfield.edit', $model[0]['model']) }}">
                <i class="fas fa-edit"></i> Edit
            </a>
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
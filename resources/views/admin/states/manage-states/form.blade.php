@extends('adminlte::page')
@section('title', (($model->exists)?'Edit':'New').' State Tax '.(($model->exists)?'#'.$model->id:''))
@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h1>@yield('title')</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('states.index') }}">State Tax</a></li>
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
                    <form action="{{  route('states.store') }}" method="POST" class="registerForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" id="id" value="{{ $model->id }}">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="category">Name *</label>
                                        <input type="text" id="name"  data-name="Name" required
                                        class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" value="{{ (old('name'))? old('name'): $model->name }}"  name="name">
                                        @if ($errors->has('name'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('name') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tax_rate">Tax Rate %</label>
                                        <input type="number" step="0.01" id="tax_rate"
                                               class="form-control{{ $errors->has('tax_rate') ? ' is-invalid' : '' }}" max="100"
                                               value="{{ (old('tax_rate'))? old('tax_rate'): $model->tax_rate }}"
                                               placeholder="Tax Rate"
                                               name="tax_rate" >
                                        @if ($errors->has('tax_rate'))
                                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('tax_rate') }}</strong>
                                </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 req-by-user">
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






        });


    </script>
@stop

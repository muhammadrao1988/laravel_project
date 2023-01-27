@extends('adminlte::page')
@section('title', 'View Account Type #'.$model->id)
@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h1>@yield('title')</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('accountTypes.index') }}"> Account Types</a></li>
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

                    <div class="card-body">

                        <div class="row">

                            <div class="form-group col-md-4">
                                <label>Sonar id</label>
                                <input type="text" disabled class="form-control" value="{{ $model->sonar_id }}">
                            </div>

                            <div class="form-group col-md-4">
                                <label>Sonar Unique Id</label>
                                <input type="text" disabled class="form-control"  value="{{ $model->sonar_unique_id }}">
                            </div>

                            <div class="form-group col-md-4">
                                <label>Name</label>
                                <input type="text" disabled class="form-control" value="{{ $model->name }}">
                            </div>

                            <div class="form-group col-md-4">
                                <label>Name</label>
                                <input type="text" disabled class="form-control" value="{{ $model->type }}">
                            </div>

                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{route('accountTypes.index') }}" class="btn btn-raised btn-warning mr-1">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
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
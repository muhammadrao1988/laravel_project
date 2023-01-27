@extends('adminlte::page')
@section('title', 'View Address #'.$model->id)
@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h1>@yield('title')</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('services.index') }}"> Services</a></li>
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
                                <label>Addressable id</label>
                                <input type="text" disabled class="form-control" value="{{ $model->addressable_id }}">
                            </div>

                            <div class="form-group col-md-4">
                                <label>Addressable type</label>
                                <input type="text" disabled class="form-control" value="{{ $model->addressable_type }}">
                            </div>

                            <div class="form-group col-md-4">
                                <label>Address Line 1</label>
                                <textarea class="form-control" disabled>{{$model->line1}}</textarea>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Address Line 2</label>
                                <textarea class="form-control" disabled>{{$model->line2}}</textarea>
                            </div>

                            <div class="form-group col-md-4">
                                <label>City</label>
                                <input type="text" disabled class="form-control" value="{{ $model->city }}">
                            </div>

                            <div class="form-group col-md-4">
                                <label>Country</label>
                                <input type="text" disabled class="form-control" value="{{ $model->country }}">
                            </div>

                            <div class="form-group col-md-4">
                                <label>County</label>
                                <input type="text" disabled class="form-control" value="{{ $model->county }}">
                            </div>

                            <div class="form-group col-md-4">
                                <label>Subdivision</label>
                                <input type="text" disabled class="form-control" value="{{ $model->subdivision }}">
                            </div>

                            <div class="form-group col-md-4">
                                <label>Zip</label>
                                <input type="text" disabled class="form-control" value="{{ $model->zip }}">
                            </div>

                            <div class="form-group col-md-4">
                                <label>Fips</label>
                                <input type="text" disabled class="form-control" value="{{ $model->fips }}">
                            </div>

                            <div class="form-group col-md-4">
                                <label>Latitude</label>
                                <input type="text" disabled class="form-control" value="{{ $model->latitude }}">
                            </div>

                            <div class="form-group col-md-4">
                                <label>Longitude</label>
                                <input type="text" disabled class="form-control" value="{{ $model->longitude }}">
                            </div>

                            <div class="form-group col-md-4">
                                <label>Serviceable</label>
                                <input type="text" disabled class="form-control" value="{{ $model->serviceable }}">
                            </div>

                            <div class="form-group col-md-4">
                                <label>Type</label>
                                <input type="text" disabled class="form-control" value="{{ $model->type }}">
                            </div>

                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{route('services.index') }}" class="btn btn-raised btn-warning mr-1">
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
@extends('adminlte::page')
@section('title', 'View Service #'.$model->id)
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
                                <label>Name</label>
                                <input type="text" disabled class="form-control" value="{{ $model->name }}">
                            </div>

                            <div class="form-group col-md-4">
                                <label>Amount</label>
                                <input type="text" disabled class="form-control" value="{{ $model->amount }}">
                            </div>

                            <div class="form-group col-md-4">
                                <label>Type</label>
                                <input type="text" disabled class="form-control" value="{{ $model->type }}">
                            </div>

                            <div class="form-group col-md-4">
                                <label>Application</label>
                                <input type="text" disabled class="form-control" value="{{ $model->application }}">
                            </div>

                            <div class="form-group col-md-4">
                                <label>Upload speed kilobits per second</label>
                                <input type="text" disabled class="form-control" value="{{ $model->upload_speed_kilobits_per_second }}">
                            </div>

                            <div class="form-group col-md-4">
                                <label>Download speed kilobits per second</label>
                                <input type="text" disabled class="form-control" value="{{ $model->download_speed_kilobits_per_second }}">
                            </div>

                            <div class="form-group col-md-4">
                                <label>Billing frequency</label>
                                <input type="text" disabled class="form-control" value="{{ $model->billing_frequency }} month">
                            </div>

                            <div class="form-group col-md-4">
                                <label>Technology code</label>
                                <input type="text" disabled class="form-control" value="{{ $model->technology_code }}">
                            </div>

                            <div class="form-group col-md-4">
                                <label>Company Id</label>
                                <input type="text" disabled class="form-control" value="{{ $model->company_id }}">
                            </div>

                            <div class="form-group col-md-4">
                                <label>Active</label>
                                <input type="text" disabled class="form-control" value="{{ $model->active }}">
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
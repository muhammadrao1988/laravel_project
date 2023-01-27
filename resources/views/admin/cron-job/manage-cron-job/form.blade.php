@extends('adminlte::page')
@section('title', (($model->exists)?'Edit':'New').' Cron Job'.(($model->exists)?' # '.$model->id:''))
@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h1>@yield('title')</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('cronJob.index') }}">Cron Jobs</a></li>
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
                    <form method="post" class="form"
                          enctype="multipart/form-data"
                          action="{{ ($model->exists)? route('cronJob.update', [$model->id]): route('cronJob.store') }}">
                        @if ($model->exists)
                            @method('PUT')
                        @endif
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="function">{{ __('Function:') }}</label>
                                        <select id="function" name="function" class="form-control" style="width: 100%">
                                            <option value="">-- Select --</option>
                                            @foreach($functions as $function)
                                                <option value="{{ $function }}" {{ $function == old('function', $model->function) ? 'selected': '' }}>{{ $function }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="function_arg_1">{{ __('Argument #1:') }}</label>
                                        <input class="form-control" id="function_arg_1" type="text"
                                               name="function_arg_1"
                                               value="{{ old('function_arg_1', $model->function_arg_1) }}"/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="function_arg_2">{{ __('Argument #2:') }}</label>
                                        <input class="form-control" id="function_arg_2" type="text"
                                               name="function_arg_2"
                                               value="{{ old('function_arg_2', $model->function_arg_2) }}"/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="frequency_func">{{ __('Frequency:') }}</label>
                                        <select id="frequency_func" name="frequency_func" class="form-control" style="width: 100%">
                                            <option value="">-- Select --</option>
                                            <option value="{{ \App\Models\CronJob::FREQUENCY_EVERY_MINUTE }}" {{ \App\Models\CronJob::FREQUENCY_EVERY_MINUTE == old('frequency_func', $model->frequency_func) ? 'selected' : '' }}>{{ \App\Models\CronJob::FREQUENCY_EVERY_MINUTE }}</option>
                                            <option value="{{ \App\Models\CronJob::FREQUENCY_EVERY_TWO_MINUTES }}" {{ \App\Models\CronJob::FREQUENCY_EVERY_TWO_MINUTES == old('frequency_func', $model->frequency_func) ? 'selected' : '' }}>{{ \App\Models\CronJob::FREQUENCY_EVERY_TWO_MINUTES }}</option>
                                            <option value="{{ \App\Models\CronJob::FREQUENCY_EVERY_THIRTY_MINUTES }}" {{ \App\Models\CronJob::FREQUENCY_EVERY_THIRTY_MINUTES == old('frequency_func', $model->frequency_func) ? 'selected' : '' }}>{{ \App\Models\CronJob::FREQUENCY_EVERY_THIRTY_MINUTES }}</option>
                                            <option value="{{ \App\Models\CronJob::FREQUENCY_HOURLY }}" {{ \App\Models\CronJob::FREQUENCY_HOURLY == old('frequency_func', $model->frequency_func) ? 'selected' : '' }}>{{ \App\Models\CronJob::FREQUENCY_HOURLY }}</option>
                                            <option value="{{ \App\Models\CronJob::FREQUENCY_DAILY }}" {{ \App\Models\CronJob::FREQUENCY_DAILY == old('frequency_func', $model->frequency_func) ? 'selected' : '' }}>{{ \App\Models\CronJob::FREQUENCY_DAILY }}</option>
                                            <option value="{{ \App\Models\CronJob::FREQUENCY_DAILY_AT }}" {{ \App\Models\CronJob::FREQUENCY_DAILY_AT == old('frequency_func', $model->frequency_func) ? 'selected' : '' }}>{{ \App\Models\CronJob::FREQUENCY_DAILY_AT }}</option>
                                            <option value="{{ \App\Models\CronJob::FREQUENCY_TWICE_DAILY }}" {{ \App\Models\CronJob::FREQUENCY_TWICE_DAILY == old('frequency_func', $model->frequency_func) ? 'selected' : '' }}>{{ \App\Models\CronJob::FREQUENCY_TWICE_DAILY }}</option>
                                            <option value="{{ \App\Models\CronJob::FREQUENCY_WEEKLY }}" {{ \App\Models\CronJob::FREQUENCY_WEEKLY == old('frequency_func', $model->frequency_func) ? 'selected' : '' }}>{{ \App\Models\CronJob::FREQUENCY_WEEKLY }}</option>
                                            <option value="{{ \App\Models\CronJob::FREQUENCY_MONTHLY }}" {{ \App\Models\CronJob::FREQUENCY_MONTHLY == old('frequency_func', $model->frequency_func) ? 'selected' : '' }}>{{ \App\Models\CronJob::FREQUENCY_MONTHLY }}</option>
                                            <option value="{{ \App\Models\CronJob::FREQUENCY_YEARLY }}" {{ \App\Models\CronJob::FREQUENCY_YEARLY == old('frequency_func', $model->frequency_func) ? 'selected' : '' }}>{{ \App\Models\CronJob::FREQUENCY_YEARLY }}</option>
                                            <option value="{{ \App\Models\CronJob::FREQUENCY_QUARTERLY }}" {{ \App\Models\CronJob::FREQUENCY_QUARTERLY == old('frequency_func', $model->frequency_func) ? 'selected' : '' }}>{{ \App\Models\CronJob::FREQUENCY_QUARTERLY }}</option>
                                            <option value="{{ \App\Models\CronJob::FREQUENCY_EVERY_SIX_HOURS }}" {{ \App\Models\CronJob::FREQUENCY_EVERY_SIX_HOURS == old('frequency_func', $model->frequency_func) ? 'selected' : '' }}>{{ \App\Models\CronJob::FREQUENCY_EVERY_SIX_HOURS }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="frequency_func_arg_1">{{ __('Argument #1:') }}</label>
                                        <input class="form-control" id="frequency_func_arg_1" type="text"
                                               name="frequency_func_arg_1"
                                               value="{{ old('frequency_func_arg_1', $model->frequency_func_arg_1) }}"/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="frequency_func_arg_2">{{ __('Argument #2:') }}</label>
                                        <input class="form-control" id="frequency_func_arg_2" type="text"
                                               name="frequency_func_arg_2"
                                               value="{{ old('frequency_func_arg_2', $model->frequency_func_arg_2) }}"/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="model_type">{{ __('Model Type:') }}</label>
                                        <select id="model_type" name="model_type" class="form-control"
                                                style="width: 100%">
                                            <option value="">{{ __('-- Select --') }}</option>
                                            @foreach(\Common::getModels('', true) as $m)
                                                <option value="{{ $m }}" {{ $m == old('model_type', $model->model_type) ? 'selected' : '' }}>{{ $m }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="model_id">{{ __('Model ID:') }}</label>
                                        <input class="form-control" id="model_id" type="number" name="model_id"
                                               value="{{ old('model_id', $model->model_id) }}"/>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="active">Status</label>
                                        <select id="active" name="active" class="form-control" style="width: 100%">
                                            @foreach([1=>'Active', 0=>'Inactive'] as  $key=>$value)
                                                <option value="{{$key}}" {{ ($model->active == $key && !($model->active == "")) ? 'selected':''}} >
                                                    {{ $value }}
                                                </option>
                                            @endForeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <a href="{{ route('cronJob.index') }}"
                               class="btn btn-raised btn-warning mr-1">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                            @if(!isset($model->id))
                                <button type="submit" class="btn btn-raised btn-primary disableOnSubmit" id="save-btn"
                                        name="save_btn" value="{{ config('constants.SAVE') }}">
                                    <i class="fa fa-save"></i> Save
                                </button>
                            @else
                                <button type="submit" class="btn btn-raised btn-primary disableOnSubmit" id="save-btn"
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
@section('plugins.Select2', true)
@section('scripts')
@stop
@section('styles')
@stop

@extends('adminlte::page')
@section('title', (($model->exists)?'View':'New').' Giftee '.(($model->exists)?'#'.$model->id:''))
@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h1>@yield('title')</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('giftee.index') }}">Giftee</a></li>
                <li class="breadcrumb-item active">{{ ($model->exists)?'View #'.$model->id:'New' }}</li>
            </ol>
        </div>
    </div>
@stop
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <form  class="registerForm">

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="name">Full Name</label>
                                        <input required type="text" id="name" data-name="Full Name"
                                               class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                               value="{{ (old('name'))? old('name'): $model->name }}"
                                               placeholder="Full Name"
                                               name="name">

                                        @if ($errors->has('name'))
                                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="displayName">Display Name</label>
                                        <input type="text" id="displayName" data-name="Display Name" required
                                               class="form-control{{ $errors->has('displayName') ? ' is-invalid' : '' }}"
                                               value="{{ (old('displayName'))? old('displayName'): $model->displayName }}"
                                               placeholder="Display Name"
                                               name="displayName">

                                        @if ($errors->has('displayName'))
                                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('displayName') }}</strong>
                                </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" id="email" required
                                               class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                               value="{{ (old('email'))? old('email'): $model->email}}"
                                               placeholder="Email"
                                               name="email">
                                        @if ($errors->has('email'))
                                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="username">Username</label>
                                        <input type="text" id="username" required
                                               class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}"
                                               value="{{ (old('username'))? old('username'): $model->username}}"
                                               placeholder="Username"
                                               name="username">
                                        @if ($errors->has('username'))
                                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('username') }}</strong>
                                </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="username">Contact Number</label>
                                        <input type="text" id="username" required
                                               class="form-control{{ $errors->has('contactNumber') ? ' is-invalid' : '' }}"
                                               value="{{ (old('contactNumber'))? old('contactNumber'): $model->contactNumber}}"
                                               placeholder="Contact Number"
                                               name="contactNumber">
                                        @if ($errors->has('contactNumber'))
                                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('contactNumber') }}</strong>
                                </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="address">Address *</label>
                                        <input type="text" id="address" required
                                               class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }}"
                                               value="{{ (old('address'))? old('address'): $model->address }}"
                                               placeholder="Address"
                                               name="address">

                                        @if ($errors->has('address'))
                                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('address') }}</strong>
                                </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="country">Country</label>
                                        <input type="text" id="address" required
                                               class="form-control"
                                               value="{{  $model->country }}"
                                               placeholder="Address"
                                               name="address">
                                        @if ($errors->has('country'))
                                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('country') }}</strong>
                                </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="state">State *</label>
                                        <input type="text" id="state" required
                                               class="form-control{{ $errors->has('state') ? ' is-invalid' : '' }}"
                                               value="{{ (old('state'))? old('state'): $model->state }}"
                                               placeholder="State"
                                               name="state">

                                        @if ($errors->has('state'))
                                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('state') }}</strong>
                                </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="city">City *</label>
                                        <input type="text" id="city" required
                                               class="form-control{{ $errors->has('city') ? ' is-invalid' : '' }}"
                                               value="{{ (old('city'))? old('city'): $model->state }}"
                                               placeholder="City"
                                               name="city">

                                        @if ($errors->has('city'))
                                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('city') }}</strong>
                                </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="city">Zipcode *</label>
                                        <input type="text" id="zip" required
                                               class="form-control{{ $errors->has('zip') ? ' is-invalid' : '' }}"
                                               value="{{ (old('city'))? old('zip'): $model->zip }}"
                                               placeholder="Zipcode"
                                               name="zip">

                                        @if ($errors->has('zip'))
                                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('zip') }}</strong>
                                </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="active">Status</label>
                                        <select id="active" name="active" class="form-control select2"
                                                style="width: 100%">
                                            @foreach([1=>'Active', 0=>'Inactive',-1=>'Deactivated'] as  $key=>$value)
                                                <option value="{{$key}}" {{ ($model->active == $key && !($model->active == "")) ? 'selected':''}} >
                                                    {{ $value }}
                                                </option>
                                            @endForeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="city">Short Description </label>
                                       <p>{{$model->short_description}}</p>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="city">Profile URL </label>
                                        <p><a href="{{route('profileUrl',$model->username)}}" target="_blank">{{route('profileUrl',$model->username)}}</a></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="offer_gift">Offer a gift</label>
                                        <select id="offer_gift" name="offer_gift" class="form-control select2"
                                                style="width: 100%">
                                            @foreach([1=>'Show this option on profile', 0=>'Do not show this option on profile'] as  $key=>$value)
                                                <option value="{{$key}}" {{ ($model->offer_gift == $key && !($model->offer_gift == "")) ? 'selected':''}} >
                                                    {{ $value }}
                                                </option>
                                            @endForeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="fulfill_orders">Fulfill orders for items that are outpriced</label>
                                        <select id="fulfill_orders" name="fulfill_orders" class="form-control select2"
                                                style="width: 100%">
                                            @foreach([1=>'Yes', 0=>'No'] as  $key=>$value)
                                                <option value="{{$key}}" {{ ($model->fulfill_orders == $key && !($model->fulfill_orders == "")) ? 'selected':''}} >
                                                    {{ $value }}
                                                </option>
                                            @endForeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="fulfill_orders">Account Private</label>
                                        <select  class="form-control select2"
                                                style="width: 100%">
                                            @foreach([1=>'Yes', 0=>'No'] as  $key=>$value)
                                                <option value="{{$key}}" {{ ($model->privacy_setting == $key && !($model->privacy_setting == "")) ? 'selected':''}} >
                                                    {{ $value }}
                                                </option>
                                            @endForeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-12">
                                    <div class="notify-text">
                                        <h4 class="notify-set-text">Notification Settings</h4>

                                            @foreach(\App\Helpers\Common::notification_settings("array") as $setting_key=>$setting_value)
                                                    <p class="font-weight-bold">
                                                        <input name="notification_setting[]" value="{{$setting_key}}" type="checkbox" class="notify-set-field" {{in_array($setting_key,$notification_setting) ? "checked" :""}}>
                                                        {{$setting_value}}
                                                    </p>
                                            @endforeach

                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-sm-12">
                                    <div class="notify-text">
                                        <h4 class="notify-set-text">Email Notifications</h4>

                                            @foreach(\App\Helpers\Common::notification_settings("array") as $setting_key=>$setting_value)
                                                <p class="font-weight-bold">
                                                        <input name="email_notification_setting[]" value="{{$setting_key}}" type="checkbox" class="notify-set-field" {{in_array($setting_key,$email_notification_setting) ? "checked" :""}}>
                                                    {{$setting_value}}
                                                </p>
                                            @endforeach

                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="card-footer">
                            <a href="{{ route('giftee.index') }}"
                               class="btn btn-raised btn-warning mr-1">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
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
            $(".registerForm input, .registerForm select").prop('disabled',true);
        })

    </script>
@stop

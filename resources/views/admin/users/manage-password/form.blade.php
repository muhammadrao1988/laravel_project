@extends('adminlte::page')
@section('title','Change Password')
@section('content_header')
<div class="row bgcolors m-0">
    <div class="col-sm-6">
        <div class="divText">
            <h1>@yield('title')</h1>
        </div>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item">
                <a href="@php echo url('users/'.auth('admin')->user()->id); @endphp">@php echo auth('admin')->user()->name; @endphp</a>
            </li>
            <li class="breadcrumb-item active">Change password</li>
        </ol>
    </div>
</div>
@stop
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-outline">
                <form method="POST" action="{{ route('update-password') }}">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="current_password">Current Password</label>
                                <input id="password" type="password" class="form-control" name="current_password" placeholder="Enter Current Password" autofocus>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="new_password">New Password</label>
                                <input id="new_password" type="password" class="form-control" name="new_password" placeholder="Enter New Password" autofocus>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="confirm_new_password">Confirm New Password</label>
                                <input id="confirm_new_password" type="password" class="form-control" name="confirm_new_password" placeholder="Confirm New Password" autofocus>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('admin.dashboard') }}"
                           class="btn btn-raised btn-warning mr-1">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class='fas fa-save'></i> Save
                        </button>
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

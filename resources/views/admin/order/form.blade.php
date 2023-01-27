@extends('adminlte::page')
@section('title', 'Edit Order')
@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h1>@yield('title')</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">Order</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </div>
    </div>
@stop
@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <form action="{{  route('orders.update',$model->id) }}" method="POST" class="registerForm" enctype="multipart/form-data">
                        @method('put')
                        <input type="hidden" name="order" value="Order">
                        @csrf
                        <input type="hidden" name="id" id="id" value="{{ $model->id }}">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 col-sm-4 col-12">
                                <div class="label-text">
                                    <label for="">Cutomer *</label>
                                    <input type="text" id="customer" required data-name="Customer Name"
                                    class="form-control{{ $errors->has('customer') ? ' is-invalid' : '' }} disable" value="{{ (old('customer'))? old('item_name'): $model->customer->name }}"  name="customer">
                                    @if ($errors->has('customer'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('customer') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="giftee_username">Giftee</label>
                                    <input type="text" id="giftee_username" required data-name="Giftee Name"
                                    class="form-control{{ $errors->has('giftee_username') ? ' is-invalid' : '' }} disable" value="{{ (old('giftee'))? old('giftee'): $model->giftee_username }}"  name="giftee_username">
                                    @if ($errors->has('giftee'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('giftee_username') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="total_amount">Total Amount</label>
                                    <input type="text"  id="total_amount" data-name="Total Amount"
                                    class="form-control{{ $errors->has('total_amount') ? ' is-invalid' : '' }} disable" value="{{ (old('total_amount'))? old('total_amount'): $model->total_amount }}" name="item url">
                                    @if ($errors->has('total_amount'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('total_amount') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                </div>
                                <div class="col-md-4 col-sm-4 col-12">
                                    <div class="form-group">
                                        <label for="status">Status *</label>
                                        <select id="status" name="status" required
                                                   class="form-control{{ $errors->has('status') ? ' is-invalid' : '' }} select2"
                                                   style="width: 100%">
                                               <option value="">Select</option>
                                               @foreach(\App\Helpers\Common::orderStatus() as  $status)
                                               {{$status}};
                                                   <option value="{{$status}}" {{ (old('status') == $status) ? 'selected=""':'' }} {{ ($model->status == $status) ? 'selected=""':''}} >
                                                       {{ $status }}</option>
                                               @endForeach
                                           </select>
                                        @if ($errors->has('item_url'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('status') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label for="created_at">Created At *</label>
                                        <input type="text" required data-name="Created At"
                                        class="form-control{{ $errors->has('created_at') ? ' is-invalid' : '' }} disable" value="{{ (old('created_at'))? old('created_at'): \Carbon\Carbon::parse($model->created_at)->format('d/M/Y H:i:s') }}" name="created_at">
                                        @if ($errors->has('created_at'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('created_at') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('orders.index') }}"
                               class="btn btn-raised btn-warning mr-1">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                                <button type="submit" class="btn btn-raised btn-primary" id="save-btn"
                                        name="save_btn" value="{{ config('constants.UPDATE') }}">
                                    <i class="fa fa-save"></i> Save
                                </button>
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
        $(".disable").prop('disabled',true);
    })
</script>
@stop

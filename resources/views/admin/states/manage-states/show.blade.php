@extends('adminlte::page')
@section('title', (($model->exists)?'Edit':'New').' Categories '.(($model->exists)?'#'.$model->id:''))
@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h1>@yield('title')</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Category</a></li>
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
                    <form action="{{ isset($model->id) ? route('categories.update',$model->id) : route('categories.store') }}" method="POST" class="registerForm" enctype="multipart/form-data">
                        @if(isset($model->id))
                            @method('put')
                        @endif
                        <input type="hidden" name="category" value="Category">
                        @csrf
                        @if ($model->exists)
                            <input type="hidden" name="id" id="id" value="{{ $model->id }}">
                        @endif
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="category">Item Name *</label>
                                        <input type="text" id="category" required data-name="Item Name"
                                        class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" value="{{ (old('title'))? old('title'): $model->title }}"  name="title">
                                        @if ($errors->has('title'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('item_name') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-12">
                                    @if(!isset($model->image_path))
                                    <div class="upload__box">
                                            <div class="upload__btn-box">
                                                <label class="upload__btn">
                                                <p>Upload images</p>
                                                <input type="file" class="upload__inputfile" name="image">
                                                </label>
                                            </div>
                                        <div class="upload__img-wrap"></div>
                                    </div>
                                    @else
                                    <div id="img">
                                    <img src="{{asset($model->image_path)}}" alt="" name="image" width="150" height="150">
                                    </div>
                                    <input type="hidden" name="has_image" id="has-image" value="1">
                                    @endif
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
            $(".registerForm input, .registerForm select").prop('disabled',true);
        })
</script>
@stop

@extends('adminlte::page')
@section('title', (!empty(@$model[0]['model']) ? "Edit" : "Add").' Custom Fields'.(!empty(@$model[0]['model']) ? " (".@$model[0]['model'].")" : ""))
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
        <form action="{{ route('customfield.store') }}" method="POST">
          @csrf
          <div class="card-body">
            <div class="row {{ !empty(@$model[0]['model']) ? "hidden" : "" }}">
              <div class="form-group col-md-4">
                <label for="model">Model Name</label>
                <select class="form-control{{ $errors->has('model') ? ' is-invalid' : '' }} select2" id="model" name="model">
                  @if(!empty(@$model[0]['model']))
                    <option value="{{ @$model[0]['model'] }}" selected="">{{ @$model[0]['model'] }}</option>
                  @else
                    <option value="" disabled selected="">Please Select</option>
                    @foreach(\Common::getModelsCF() as $key => $value)
                        <option value="{{ $value }}" {{ @$model[0]['model'] == $value ? 'selected=selected' : (old('model') == $value ? 'selected=selected' : '')}} >
                            {{ $value }}
                        </option>
                    @endforeach
                  @endif
                </select>
                @if ($errors->has('model'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('model') }}</strong>
                    </span>
                @endif
              </div>
            </div>
            <div class="row" id="div-main">
              @for($i = 1; $i<=$len; $i++)
                <div id="div-clone-{{$i}}" class="row col-md-12 div-clone">
                  <div class="col-md-12">
                    <hr><h3 class="sr" name="field[{{$i}}][sr]" id="field-{{$i}}-sr">Field # {{ $i }}</h3><hr>
                  </div>
                  
                  <div class="hidden">
                    <input type="hidden" name="field[{{$i}}][id]" id="field-{{$i}}-id" value="{{ (old('field.'.($i).'.id'))? old('field.'.($i).'.id') : @$model[$i-1]['id'] }}">
                  </div>
                  
                  <div class="hidden">
                    <input type="hidden" name="field[{{$i}}][delete]" id="field-{{$i}}-delete" value="0">
                  </div>

                  <div class="form-group col-md-4">
                    <label>Title</label>
                    <input type="text" 
                      name="field[{{$i}}][title]" 
                      id="field-{{$i}}-title" 
                      class="form-control{{ $errors->has('field.'.$i.'.title') ? ' is-invalid' : '' }}" 
                      value="{{ old('field.'.($i).'.title', @$model[$i-1]['title']) }}"
                      onchange="makeName(this.id);" 
                    />
                    @if ($errors->has('field.'.$i.'.title'))
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $errors->first('field.'.$i.'.title') }}</strong>
                      </span>
                    @endif
                  </div>

                  <div class="form-group col-md-4">
                    <label>Name</label>
                    <input type="text" readonly="" 
                      name="field[{{$i}}][name]" 
                      id="field-{{$i}}-name" 
                      class="form-control{{ $errors->has('field.'.$i.'.name') ? ' is-invalid' : '' }}" 
                      value="{{ old('field.'.($i).'.name', @$model[$i-1]['name']) }}"
                    />
                    @if ($errors->has('field.'.$i.'.name'))
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $errors->first('field.'.$i.'.name') }}</strong>
                      </span>
                    @endif
                  </div>

                  <div class="form-group col-md-4">
                    <label>Description</label>
                    <input type="text" 
                      name="field[{{$i}}][description]" 
                      id="field-{{$i}}-description" 
                      class="form-control{{ $errors->has('field.'.$i.'.description') ? ' is-invalid' : '' }}" 
                      value="{{ old('field.'.($i).'.description', @$model[$i-1]['description']) }}"
                    />
                    @if ($errors->has('field.'.$i.'.description'))
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $errors->first('field.'.$i.'.description') }}</strong>
                      </span>
                    @endif
                  </div>

                  <div class="form-group col-md-4">
                    <label>Type</label>
                    <select name="field[{{$i}}][type]" id="field-{{$i}}-type" class="form-control{{ $errors->has('field.'.$i.'.type') ? ' is-invalid' : '' }} select2" style="width: 100%;">
                      <option value="" disabled selected="">Please Select</option>
                      @foreach($fieldTypes as $key => $value)
                          <option value="{{ $value }}" {{ @$model[$i-1]['type'] == $value ? 'selected=selected' : (old('field.'.($i).'.type') == $value ? 'selected=selected' : '')}} >
                              {{ $value }}
                          </option>
                      @endforeach
                    </select>
                    @if ($errors->has('field.'.$i.'.type'))
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $errors->first('field.'.$i.'.type') }}</strong>
                      </span>
                    @endif
                  </div>

                  <div class="form-group col-md-4">
                    <label>Acceptable / Options</label>
                    <input type="text" 
                      name="field[{{$i}}][acceptable]" 
                      id="field-{{$i}}-acceptable" 
                      class="form-control{{ $errors->has('field.'.$i.'.acceptable') ? ' is-invalid' : '' }}" 
                      value="{{ old('field.'.($i).'.acceptable', @$model[$i-1]['acceptable']) }}"
                    />
                    @if ($errors->has('field.'.$i.'.acceptable'))
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $errors->first('field.'.$i.'.acceptable') }}</strong>
                      </span>
                    @endif
                  </div>

                  <div class="form-group col-md-4">
                    <label>Default Value</label>
                    <input type="text" 
                      name="field[{{$i}}][defaultValue]" 
                      id="field-{{$i}}-defaultValue" 
                      class="form-control{{ $errors->has('field.'.$i.'.defaultValue') ? ' is-invalid' : '' }}" 
                      value="{{ old('field.'.($i).'.defaultValue', @$model[$i-1]['defaultValue']) }}"
                    />
                    @if ($errors->has('field.'.$i.'.defaultValue'))
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $errors->first('field.'.$i.'.defaultValue') }}</strong>
                      </span>
                    @endif
                  </div>

                  <div class="form-group col-md-4">
                    <label>Sort #</label>
                    <input type="number" 
                      name="field[{{$i}}][sort]" 
                      id="field-{{$i}}-sort" 
                      class="form-control{{ $errors->has('field.'.$i.'.sort') ? ' is-invalid' : '' }}" 
                      value="{{ old('field.'.($i).'.sort', @$model[$i-1]['sort']) }}"
                    />
                    @if ($errors->has('field.'.$i.'.sort'))
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $errors->first('field.'.$i.'.sort') }}</strong>
                      </span>
                    @endif
                  </div>

                  <div class="form-group col-md-4">
                    <label>Required?</label>
                    <select name="field[{{$i}}][isRequired]" 
                      id="field-{{$i}}-isRequired" class="form-control{{ $errors->has('field.'.$i.'.isRequired') ? ' is-invalid' : '' }} select2" style="width: 100%">
                      @foreach(["1"=>'Yes', "0"=>'No'] as $key=>$value)
                        <option value="{{$key}}" {{ (explode('|', @$model[$i-1]['rules'])[0] == "required" && $key == "1") ? "selected" : '' }} {{ (explode('|', @$model[$i-1]['rules'])[0] == "nullable" && $key == "0") ? "selected" : '' }} >
                          {{ $value }}
                        </option>
                      @endForeach
                    </select>
                  </div>

                  <div class="form-group col-md-4">
                    <label>Status</label>
                    <select name="field[{{$i}}][active]" 
                      id="field-{{$i}}-active" class="form-control{{ $errors->has('field.'.$i.'.active') ? ' is-invalid' : '' }} select2" style="width: 100%">
                      @foreach([1=>'Active', 0=>'Inactive'] as $key=>$value)
                        <option value="{{$key}}" {{ (@$model[$i-1]['active'] == $key && !(@$model[$i-1]['active'] == "")) ? 'selected':''}} >
                          {{ $value }}
                        </option>
                      @endForeach
                    </select>
                  </div>

                  <div class="form-group col-md-12">
                    <button  type="button" class="btn btn-raised btn-danger btn-sm remove" id="field-{{$i}}-remove" onclick="removeDiv(this.id);">
                      <i class="fa fa-minus"> Remove</i>
                    </button>
                  </div>

                </div>
              @endfor
            </div>
          </div>
          <div class="card-footer">
            <a href="{{ route('customfield.index') }}" class="btn btn-raised btn-warning mr-1">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <button type="submit" class="btn btn-raised btn-primary disableOnSubmit" id="save-btn">
                <i class="fa fa-save"></i> Save
            </button>
            <button  type="button" class="btn btn-raised btn-primary" id="cloneDiv">
              <i class="fa fa-plus"> Add</i>
            </button>
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
<script type="text/javascript">
	function makeName(id){
		var i = id.split('-')[1];
		var name = $('#field-'+i+'-title').val();
		name = name.replace(/[^A-Za-z0-9 ]/g, "").trim().replace(/ /g,"_");
		$('#field-'+i+'-name').val(name);
	}
</script>
@stop
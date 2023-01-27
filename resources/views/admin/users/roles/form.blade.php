@extends('adminlte::page')
@section('title', (($model->exists)?'Edit':'New').' Role '.(($model->exists)?'#'.$model->id:''))
@section('content_header')
<div class="row">
  <div class="col-sm-6">
    <h1>@yield('title')</h1>
  </div>
  <div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
      <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Role</a></li>
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
        <form method="post" enctype="multipart/form-data" action="{{ ($model->exists)? route('roles.update', [$model->id]): route('roles.store') }}">
        @if ($model->exists)
        @method('PUT')
        @endif
        @csrf
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="roles_name">Role Name</label>
                <input type="text" id="roleName" class="form-control{{ $errors->has('roleName') ? ' is-invalid' : '' }}" value="{{ (old('roleName'))? old('roleName'): $model->roleName }}" name="roleName" autofocus>
                @if ($errors->has('roleName'))
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $errors->first('roleName') }}</strong>
                </span>
                @endif
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-12 text-center"><h3>Permissions</h3></div>
            <div class="col-md-12">
              <table class="table table-bordered table-hover table-condensed">
                <thead>
                  <tr>
                    <th>&nbsp;</th>
                    <th>
                      <input type="checkbox" id="check-all"/>
                    </th>
                  </tr>
                </thead>
                <tbody>
                  @foreach(config('permissions') as $permission)
                    <?php
                      $checked =false;
                      if(!empty($model->permissions)){
                        $permissions = json_decode($model->permissions);
                        if (in_array(strtolower($permission['module'].$permission['key']), $permissions)) {
                          $checked = true;
                        }else{
                          $checked = false;
                        }
                      }
                    ?>
                    @if($permission['module'] != @$prevModule)
                      <tr>
                        <th class="text-left">
                          <h3>{{ ucwords($permission['module']) }}</h3>
                        </th>
                        <th>
                          <input type="checkbox" value="<?php echo $permission['module']; ?>"
                            onclick="moduleCheck(this);"/>
                        </th>
                      </tr>
                    @endif
                    <tr>
                      <td>{{ $permission['name'] }}</td>
                      <td>
                        <input type="checkbox" class="check-single check-single-<?php echo $permission['module']; ?>" name="permissions[]" value="{{ strtolower($permission['module'].$permission['key']) }}" <?php if($checked){ echo 'checked'; }else{ echo ''; } ?> />
                      </td>
                    </tr>
                    @php
                      $prevModule = $permission['module'];
                    @endphp
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="card-footer">
          <a href="{{ route('roles.index') }}" class="btn btn-raised btn-warning mr-1">
            <i class="fas fa-arrow-left"></i> Back
          </a>
            <button type="submit" class="btn btn-raised btn-primary disableOnSubmit" id="save-btn" name="save_btn" value="{{ config('constants.SAVE') }}">
                <i class="fa fa-save"></i> Save
            </button>
            <button type="submit" class="btn btn-raised btn-primary disableOnSubmit" id="save-add-more-btn" name="save_btn" value="{{ config('constants.SAVE_ADD_MORE') }}">
                <i class="fa fa-save"></i> Save & Add More
            </button>
        </div>
      </form>
    </div>
  </div>
</div>
</div>
</div>
@endsection
@section('css')
@stop
@section('js')
<script type="text/javascript">
  function moduleCheck(obj){
    var moduleName = $(obj).val();
    $('.check-single-'+moduleName).each(function(){
        if ($(obj).is(':checked')){
            $(this).prop('checked', true);
        }else{
            $(this).prop('checked', false);
        }
    });
  }
</script>
@stop

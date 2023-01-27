@extends('adminlte::page')
@section('title', 'Configurations')
@section('content_header')
<div class="row">
  <div class="col-md-6">
    <h1>@yield('title')</h1>
  </div>
  <div class="col-md-6">
    <div class="btn-group float-right">
      <a href="#" class="btn btn-primary">Import/Export</a>
      <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="sr-only">Toggle Dropdown</span>
      </button>
      <div class="dropdown-menu dropdown-menu-right">
        <form class="hidden" id="import-form" action="{{ route('import', 'Configuration') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <input type="file" name="file" id="import-file" class="form-control">
        </form>
        @if(\Common::canCreate($module))
          <a id="import-btn-confirm" class="dropdown-item" href="#">Import</a>
          <a id="import-btn" class="dropdown-item hidden" href="#"></a>
        @endif
        <a class="dropdown-item" href="{{ route('export', 'Configuration') }}">Export</a>
      </div>
    </div>
  </div>
</div>
@stop

@section('content')
<div class="container-fluid">
  <section id="multi-column">
    <div class="row">
      <div class="col-12">
        <div class="card card-primary card-outline">
          <div class="card-body collapse show">
            <div class="card-block table-responsive">
              <table class="table table-bordered table-striped table-hover datatable" width="100%">
                <thead>
                  <tr>
                    <th>Id</th>
                    <th>Description</th>
                    <th>Value</th>
                    <th>Action</th>
                  </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
@stop

@section('css')
@stop

@section('js')
<script>
  $('#import-btn-confirm').on('click', function(){
    swal({
        title: 'Are you sure?',
        text: "This will remove any existing configuration!",
        type: 'warning',
        buttons: true,
        dangerMode: true,
        icon: "warning",
    }).then(function (isConfirm) {
        if (isConfirm) {
          $('#import-btn').trigger('click');
        }
    }).catch(swal.noop);
  });

  $(function() {
    $('.datatable').DataTable({
      serverSide: true,
      iDisplayLength: 100,
      aaSorting: [ [0, "desc"] ],
      ajax: "{{ route('configuration.index') }}",
      columns: [
      { data: 'id'},
      { data: 'description'},
      { data: 'value'},
      { data: 'action', searchable: false, sortable: false},
      ]
    });
  });
</script>
@stop

@section('plugins.Datatables', true)
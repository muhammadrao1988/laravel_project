@extends('adminlte::page')
@section('title', !empty($flagtype) ? $flagtype->name : 'Flags')
@section('content_header')
<div class="row">
  <div class="col-md-6">
    <h1>@yield('title')</h1>
  </div>
  <div class="col-md-6">
    <div class="btn-group float-right">
      @if(\Common::canCreate($module))
      <a href="{{ (!empty($flagtype) ? URL::to('/flag/create?flagtype='.$flagtype->code) : URL::to('/flag/create')) }}" class="btn btn-primary">Add New</a>
      @endif
      @if(empty($flagtype))
      <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="sr-only">Toggle Dropdown</span>
      </button>
      <div class="dropdown-menu dropdown-menu-right">
        <form class="hidden" id="import-form" action="{{ route('import', 'Flag') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <input type="file" name="file" id="import-file" class="form-control">
        </form>
        @if(\Common::canCreate($module))
        <a id="import-btn" class="dropdown-item" href="#">Import</a>
        @endif
        <a class="dropdown-item" href="{{ route('export', 'Flag') }}">Export</a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="{{ route('sample', 'Flag') }}">Download Sample</a>
      </div>
      @endif
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
                    <th>Name</th>
                    <th>Parent</th>
                    <th>Type</th>
                    <th>Active</th>
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
  $(function() {
    $('.datatable').DataTable({
      serverSide: true,
      iDisplayLength: 100,
      aaSorting: [ [0, "desc"] ],
      ajax: {
        url: "{{ route('flag.index') }}",
        data: function(s){
          s.flagtype = "{{ @$flagtype->code }}";
        }
      },
      columns: [
      { data: 'id'},
      { data: 'name'},
      { data: 'parentName', name: 'parent.name'},
      { data: 'flagTypeName', name: 'flag_types.name'},
      { data: 'active'},
      { data: 'action', searchable: false, sortable: false},
      ]
    });
  });
</script>
@stop

@section('plugins.Datatables', true)
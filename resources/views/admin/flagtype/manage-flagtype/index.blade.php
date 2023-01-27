@extends('adminlte::page')
@section('title', 'Flag Types')
@section('content_header')
<div class="row">
  <div class="col-md-6">
    <h1>@yield('title')</h1>
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
                    <th>Code</th>
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
      ajax: "{{ route('flagtype.index') }}",
      columns: [
      { data: 'id'},
      { data: 'name'},
      { data: 'code'},
      { data: 'active'},
      { data: 'action', searchable: false, sortable: false},
      ]
    });
  });
</script>
@stop
@section('plugins.Datatables', true)
@extends('adminlte::page')
@section('title', 'Users')
@section('content_header')
<div class="row">
  <div class="col-md-6">
    <h1>@yield('title')</h1>
  </div>
  @if(\Common::canCreate($module))
  <div class="col-md-6">
    <div class="float-right">
      <a href="{{ route('users.create') }}" class="btn btn-raised btn-primary round btn-min-width mr-1 mb-1">Add
      New</a>
    </div>
  </div>
  @endif
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
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Created</th>
                    @if(\Common::userRole() == "SUPER")
                    <th>Last Login</th>
                    @endif
                    <th>Status</th>
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
      ajax: "{{ route('users.index') }}",
      columns: [
      { data: 'id'},
      { data: 'name'},
      { data: 'email'},
      { data: 'created_at'},
      @if(\Common::userRole() == "SUPER")
        { data: 'last_login_at'},
      @endif
      { data: 'active'},
      { data: 'action', searchable: false, sortable: false},
      ]
    });
  });
</script>
@stop

@section('plugins.Datatables', true)
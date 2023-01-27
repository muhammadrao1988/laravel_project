@extends('adminlte::page')
@section('title', 'Orders')
@section('content_header')
<div style="margin-bottom: 10px;" class="row">
</div>
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
                    <th>Old Status</th>
                    <th>New Status</th>
                    <th>Updated At</th>
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
      ajax: "{{ route('order-logs',$id) }}",
      columns: [
      { data: 'id'},
      { data: 'old_status'},
      { data: 'new_status'},
      { data: 'updated_at'},
      ]
    });

  });
</script>
@stop

@section('plugins.Datatables', true)

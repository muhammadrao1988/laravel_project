@extends('adminlte::page')
@section('title', 'Gift Guide')
@section('content_header')
<div class="row">
  <div class="col-md-6">
    <h1>@yield('title')</h1>
  </div>
  @if(\Common::canCreate($module))
  <div class="col-md-6">
    <div class="float-right">
      <a href="{{ route('giftguide.create') }}" class="btn btn-raised btn-primary round btn-min-width mr-1 mb-1">Add
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
                    <th>Category</th>
                    <th>Item Name</th>
                    <th>Item Url</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Merchant</th>
                    <th>Image</th>
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
      ajax: "{{ route('giftguide.index') }}",
      columns: [
      { data: 'id'},
      { data: 'category',name:'categories.title'},
      { data: 'item_name'},
      { data: 'item_url'},
      { data: 'price'},
      { data: 'active'},
      { data: 'merchant'},
      { data: 'image_path'},
      { data: 'action', searchable: false, sortable: false},
      ]
    });
  });
</script>
@stop

@section('plugins.Datatables', true)

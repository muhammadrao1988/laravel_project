@extends('adminlte::page')
@section('title', 'Contribution Orders')
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
      <div class="col-md-12">
        <div class="card card-primary card-outline collapsed-card">
          <div class="card-header">
            <h3 class="card-title">Search</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                        class="fas fa-plus"></i>
              </button>
            </div>
          </div>
          <div class="card-body" style="display: none;">
            <form action="{{ route('contribution_orders.index') }}" method="POST">
              @csrf
              <div class="row">
                <div class="form-group col-md-4">
                  <label for="fromDate">Wishlist Item Id</label>
                  <input type="text" class="form-control" id="orderId" name="orderId" value="{{ @old('orderId') }}">
                </div>
                <div class="form-group col-md-4">
                  <label for="fromDate">Order Num</label>
                  <input type="text" class="form-control" id="orderNum" name="orderNum" value="{{ @old('orderNum') }}">
                </div>
                <div class="form-group col-md-4">
                  <label for="orderStatus">Status</label>
                  <select id="orderStatus" class="form-control" style="width: 100%;" >
                    <option value="">Select Status</option>
                    <option value="Created">Collecting</option>
                    <option value="Collected">Collected</option>
                    <option value="Order Placed">Order Placed</option>
                    <option value="Shipped">Shipped</option>
                    <option value="Delivered">Delivered</option>
                    <option value="Received">Received</option>
                    <option value="Returned">Returned</option>
                  </select>
                </div>
                <div class="form-group col-md-4">
                  <label for="returnRequest">Return Request</label>
                  <select id="returnRequest" class="form-control" style="width: 100%;" >
                    <option value="">Select</option>
                    <option value="yes">Yes</option>
                    <option value="no">No</option>
                  </select>
                </div>
              </div>
            </form>
          </div>
          <div class="card-footer">
            <button type="submit" class="btn btn-raised btn-primary" id="searchBtn">
              <i class="fa fa-search"></i> Search
            </button>
            <button type="button" class="btn btn-raised btn-default ml-3" id="resetBtn">
              <i class="fa fa-archive"></i> Reset
            </button>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-12">
        <div class="card card-primary card-outline">
          <div class="card-body collapse show">
            <div class="card-block table-responsive">
              <table class="table table-bordered table-striped table-hover datatable" width="100%">
                <thead>
                  <tr>
                    <th>Wishlist Item ID</th>
                    <th>Order #</th>
                    <th>Giftee</th>
                    <th>Total amount</th>
                    <th>Status</th>
                    <th>Return Order Request</th>
                    <th>Updated At</th>
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
@section('plugins.Pace', true)
@section('js')
<script>
  $(function() {
    $('.datatable').DataTable({
      serverSide: true,
      iDisplayLength: 25,
      aaSorting: [ [0, "desc"] ],
      ajax: {
        url: "{{ route('contribution_orders.index') }}",
        data: function (s) {
          s.orderStatus = $('#orderStatus').val();
          s.orderId = $('#orderId').val();
          s.orderNum = $('#orderNum').val();
          s.returnRequest = $('#returnRequest').val();
        }
      },
      columns: [
      { data: 'id'},
      { data: 'order_num'},
      { data: 'giftee'},
      { data: 'total_price'},
      { data: 'status'},
      { data: 'return_status'},
      { data: 'updated_at'},
      { data: 'action', searchable: false, sortable: false}
      ]
    });
    $('#searchBtn').click(function () {
      Pace.restart();
      $('.datatable').DataTable().draw(true);
    });
    $('#resetBtn').click(function () {
      $("#orderId").val("");
      $("#orderStatus").val("");
      $("#orderNum").val("");
      $("#returnRequest").val("");
      $("#searchBtn").trigger("click");
    });


  });
</script>
@stop

@section('plugins.Datatables', true)

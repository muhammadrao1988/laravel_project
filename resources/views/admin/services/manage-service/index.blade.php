@extends('adminlte::page')
@section('title', 'Services')
@section('content_header')
    <div class="row">
        <div class="col-md-6">
            <h1>@yield('title')</h1>
        </div>
        <div class="col-md-6">
            <div class="btn-group float-right">
                @if(\Common::canCreate($module))
                    <a href="{{  route('services.create') }}" class="btn btn-primary">Sync Services from SONAR</a>
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
                                        <th>Sonar Id</th>
                                        <th>Name</th>
                                        <th>Amount</th>
                                        <th>Type</th>
                                        <th>Billing Frequency</th>
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
        $(function () {
            $('.datatable').DataTable({
                serverSide: true,
                iDisplayLength: 100,
                aaSorting: [[0, "desc"]],
                ajax: {
                    url: "{{ route('services.index') }}",
                    data: function (s) {
                        s.flagtype = "{{ @$flagtype->code }}";
                    }
                },
                columns: [
                    {data: 'id'},
                    {data: 'sonar_id'},
                    {data: 'name'},
                    {data: 'amount'},
                    {data: 'type'},
                    {data: 'billing_frequency'},
                    {data: 'active'},
                    {data: 'action', searchable: false, sortable: false},
                ]
            });
        });
    </script>
@stop

@section('plugins.Datatables', true)
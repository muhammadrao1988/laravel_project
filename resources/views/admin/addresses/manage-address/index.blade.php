@extends('adminlte::page')
@section('title', 'Addresses')
@section('content_header')
    <div class="row">
        <div class="col-md-6">
            <h1>@yield('title')</h1>
        </div>
        <div class="col-md-6">
            <div class="btn-group float-right">
                @if(\Common::canCreate($module))
                    <a href="{{  route('addresses.create') }}" class="btn btn-primary">Sync Addresses from SONAR</a>
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
                                        <th>Type</th>
                                        <th>Serviceable</th>
                                        <th>Addressable Type</th>
                                        <th>Country</th>
                                        <th>City</th>
                                        <th>Subdivision</th>
                                        <th>Zip</th>
                                        <th>Line 1</th>
                                        <th>Line 2</th>
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
                aaSorting: [[4, "desc"]],
                ajax: {
                    url: "{{ route('addresses.index') }}",
                },
                columns: [
                    {data: 'id'},
                    {data: 'sonar_id'},
                    {data: 'type'},
                    {data: 'serviceable'},
                    {data: 'addressable_type'},
                    {data: 'country'},
                    {data: 'city'},
                    {data: 'subdivision'},
                    {data: 'zip'},
                    {data: 'line1'},
                    {data: 'line2'},
                    {data: 'action', searchable: false, sortable: false},
                ]
            });
        });
    </script>
@stop

@section('plugins.Datatables', true)
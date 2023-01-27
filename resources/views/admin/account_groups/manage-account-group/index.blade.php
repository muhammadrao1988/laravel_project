@extends('adminlte::page')
@section('title', 'Account Group')
@section('content_header')
    <div class="row">
        <div class="col-md-6">
            <h1>@yield('title')</h1>
        </div>
        <div class="col-md-6">
            <div class="btn-group float-right">
                @if(\Common::canCreate($module))
                    <a href="{{  route('accountGroups.create') }}" class="btn btn-primary">Sync Account Groups from SONAR</a>
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
                    url: "{{ route('accountGroups.index') }}",
                },
                columns: [
                    {data: 'id'},
                    {data: 'sonar_id'},
                    {data: 'name'},
                    {data: 'action', searchable: false, sortable: false},
                ]
            });
        });
    </script>
@stop

@section('plugins.Datatables', true)
@extends('adminlte::page')
@section('title', 'Cron Jobs')
@section('content_header')
<div class="row">
    <div class="col-md-6">
        <h1>@yield('title')</h1>
    </div>
    @if(\Common::canCreate($module))
    <!-- <div class="col-md-6">
        <div class="float-right">
            <a href="{{ route('cronJob.create') }}" class="btn btn-raised btn-primary round btn-min-width mr-1 mb-1">{{ _('Add New') }}</a>
        </div>
    </div> -->
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
                                        <th>Function</th>
                                        <th>Function Arg # 1</th>
                                        <th>Function Arg # 2</th>
                                        <th>Frequency</th>
                                        <th>Frequency Arg # 1</th>
                                        <th>Frequency Arg # 2</th>
                                        <th>Model Type</th>
                                        <th>Model Id</th>
                                        <th>Total Execution</th>
                                        <th>Active</th>
                                        <!-- <th>Created</th> -->
                                        <!-- <th>Action</th> -->
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
            ajax: "{{ route('cronJob.index') }}",
            columns: [
            { data: 'id'},
            { data: 'function'},
            { data: 'function_arg_1'},
            { data: 'function_arg_2'},
            { data: 'frequency_func'},
            { data: 'frequency_func_arg_1'},
            { data: 'frequency_func_arg_2'},
            { data: 'model_type'},
            { data: 'model_id'},
            { data: 'total_execution'},
            { data: 'active'},
            // { data: 'created_at'},
            // { data: 'action', searchable: false, sortable: false},
            ]
        });
    });
</script>
@stop
@section('plugins.Datatables', true)
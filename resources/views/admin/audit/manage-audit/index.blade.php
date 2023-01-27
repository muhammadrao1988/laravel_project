@extends('adminlte::page')
@section('title', 'Audits')
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
                                        <th>Model</th>
                                        <th>Action</th>
                                        <th>User</th>
                                        <th>Old Values</th>
                                        <th>New Values</th>
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
            iDisplayLength: 10,
            ordering: false,
            ajax: "{{ route('audit') }}",
            columns: [
                { data: 'model'},
                { data: 'event'},
                { data: 'user'},
                { data: 'oldValues'},
                { data: 'newValues'},
            ]
        });
    });
</script>
@stop
@section('plugins.Datatables', true)
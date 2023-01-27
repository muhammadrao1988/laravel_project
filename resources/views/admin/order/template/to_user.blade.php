<div class="card">
    <div class="card-header">
        <h3 class="card-title">To</h3>
    </div>
    <div class="card-body row">
        <div class="col-md-12">
            <div class="table-responsive">
                @if(!empty($model->toUser->id))
                    <table class="table">
                        <tbody>
                        <tr>
                            <th style="width:50%">Profile URL:</th>
                            <td><a href="{{route('profileUrl',$model->toUser->username)}}"
                                   target="_blank">{{$model->toUser->displayName}}</a>
                            </td>
                        </tr>
                        <tr>
                            <th>Address:</th>
                            <td>{{$model->toUser->address}}</td>
                        </tr>
                        <tr>
                            <th>City:</th>
                            <td>{{$model->toUser->city}}</td>
                        </tr>
                        <tr>
                            <th>State:</th>
                            <td>{{$model->toUser->state}}</td>
                        </tr>
                        <tr>
                            <th>Country:</th>
                            <td>{{$model->toUser->country}}</td>
                        </tr>
                        <tr>
                            <th>Zip:</th>
                            <td>{{$model->toUser->zip}}</td>
                        </tr>
                        <tr>
                            <th>Phone:</th>
                            <td>{{$model->toUser->contactNumber}}</td>
                        </tr>
                        <tr>
                            <th>Email Address:</th>
                            <td>{{$model->toUser->email}}</td>
                        </tr>

                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</div>
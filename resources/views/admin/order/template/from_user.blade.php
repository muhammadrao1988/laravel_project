<div class="card">
    <div class="card-header">
        <h3 class="card-title">From</h3>
    </div>
    <div class="card-body row">
        <div class="col-md-12">
            <div class="table-responsive">
                @if(!empty($model->fromUser->id))
                    <table class="table">
                        <tbody>
                        <tr>
                            <th style="width:50%">Profile URL:</th>
                            <td><a href="{{route('profileUrl',$model->fromUser->username)}}"
                                   target="_blank">{{$model->fromUser->displayName}}</a></td>
                        </tr>
                        <tr>
                            <th>Address:</th>
                            <td>{{$model->fromUser->address}}</td>
                        </tr>
                        <tr>
                            <th>City:</th>
                            <td>{{$model->fromUser->city}}</td>
                        </tr>
                        <tr>
                            <th>State:</th>
                            <td>{{$model->fromUser->state}}</td>
                        </tr>
                        <tr>
                            <th>Country:</th>
                            <td>{{$model->fromUser->country}}</td>
                        </tr>
                        <tr>
                            <th>Zip:</th>
                            <td>{{$model->fromUser->zip}}</td>
                        </tr>
                        <tr>
                            <th>Phone:</th>
                            <td>{{$model->fromUser->contactNumber}}</td>
                        </tr>
                        <tr>
                            <th>Email Address:</th>
                            <td>{{$model->fromUser->email}}</td>
                        </tr>

                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</div>
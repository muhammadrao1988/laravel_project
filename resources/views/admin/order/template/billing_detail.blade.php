<div class="card">
    <div class="card-header">
        <h3 class="card-title">Billing Info</h3>
    </div>
    <div class="card-body row">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table">
                    <tbody>
                    <tr>
                        <th style="width:50%">Name:</th>
                        <td>{{$model->billingInfo->first_name}} {{$model->billingInfo->last_name}}</td>
                    </tr>
                    <tr>
                        <th>Email Address:</th>
                        <td>{{$model->billingInfo->email}}</td>
                    </tr>
                    <tr>
                        <th>Billing Address:</th>
                        <td>{{$model->billingInfo->address}}</td>
                    </tr>
                    <tr>
                        <th>Country:</th>
                        <td>{{$model->billingInfo->country}}</td>
                    </tr>
                    <tr>
                        <th>City:</th>
                        <td>{{$model->billingInfo->city}}</td>
                    </tr>
                    <tr>
                        <th>State:</th>
                        <td>{{$model->billingInfo->state}}</td>
                    </tr>
                    <tr>
                        <th>Postal Code:</th>
                        <td>{{$model->billingInfo->postal_code}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
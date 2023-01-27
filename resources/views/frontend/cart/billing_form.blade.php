<form id="billing_info_form">
    @csrf
    <h6 class="customer-info-text">Billing Information</h6>
    <div class="row">
        <div class="col-md-6 col-sm-6 col-12">
            <div class="tabs-field">
                <label class="customer-tabs-label">First name*</label>
                <input type="text" data-name="first name" required  value="{{$billing_detail->first_name}}"
                       name="first_name" class="customer-tabs-fields"  id="first_name">
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-12">
            <div class="tabs-field">
                <label class="customer-tabs-label">Last name*</label>
                <input type="text"  data-name="last name" required value="{{$billing_detail->last_name}}" name="last_name"
                       id="last_name" class="customer-tabs-fields">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-12">
            <div class="tabs-field">
                <label class="customer-tabs-label">Email Address*</label>
                <input  class="customer-tabs-fields" data-name="email" required type="email" value="{{$billing_detail->email}}" name="email"
                        id="email">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-12">
            <div class="tabs-field">
                <label class="customer-tabs-label">Billing Address</label>
                <textarea  class="customer-tabs-fields"data-name="address" required name="address"
                           id="address">{{$billing_detail->address}}</textarea></textarea>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-sm-6 col-12">
            <div class="tabs-field">
                <label class="customer-tabs-label">Country</label>
                <select class="customer-tabs-select" name="country" required data-name="Country">
                    <option value="">Select Country</option>
                    @foreach(\App\Helpers\Common::allCountry() as $val)
                        <option {{strtolower($billing_detail->country) == strtolower($val) ? "selected" : ""}} value="{{$val}}">{{$val}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-12">
            <div class="tabs-field">
                <label class="customer-tabs-label">City</label>
                <input type="text" placeholder="Vilniu" class="customer-tabs-fields" data-name="city" required name="city" id="city"
                       value="{{$billing_detail->city}}">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-sm-6 col-12">
            <div class="tabs-field">
                <label class="customer-tabs-label">State</label>
                <input type="text" class="customer-tabs-fields"  data-name="state" required name="state" id="billing_state"
                       value="{{$billing_detail->state}}">
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-12">
            <div class="tabs-field">
                <label class="customer-tabs-label">POSTAL CODE</label>
                <input type="text"  data-name="Postal Code" required type="text" class="customer-tabs-fields" name="postal_code" id="postal_code"
                       value="{{$billing_detail->postal_code}}">
            </div>
        </div>
    </div>
    <div class="row shopping-row">
        <div class="col-md-6 col-sm-6 col-12">

        </div>
        <div class="col-md-6 col-sm-6 col-12">
            <a href="javascript:;" class="pull-right continue-shopping continue_payment_button">CONTINUE TO PAYMENT</a>
        </div>
    </div>
    <input type="hidden" name="{{$field_name_checkout}}" value="1">
    <input type="hidden" name="current_credit" value="{{$current_credit}}">

</form>
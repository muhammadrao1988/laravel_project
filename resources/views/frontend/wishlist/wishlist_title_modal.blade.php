<div class="modal modal4 fade" id="wish_list_title_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-slideout" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <a href="javascriptvoid:(0)" class="back-icon-link"><i class="fa fa-angle-left left-tri"
                                                                       aria-hidden="true"></i> Back</a>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="wishlist_form" method="POST">
                    {{csrf_field()}}
                    <input type="hidden" id="gift_type" value="" name="type">
                    <input type="hidden" name="id" value="" id="id">

                    <h5 class="modal-title add-prezziez-heading align-right" id="exampleModalLabel"><span
                                class="prezziez-setting">New Wishlist</span> List Details</h5>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-12">
                            <div class="selection-popup-boxes">
                                <input name="title" id="title" type="text" class="weekly-field" placeholder="Enter wishlist title">
                            </div>
                        </div>
                    </div>
                    <div class="row date_type">
                        <div class="col-md-12 col-sm-12 col-12">
                            <div class="selection-popup-boxes date-picker-box">
                                <input name="date" id="type_date" placeholder="Select Date" type="date" required>
                            </div>
                        </div>
                    </div>
                    <a href="javascript:;" class="add-next submit_wishlist">CREATE LIST</a>
                </form>
            </div>
        </div>
    </div>
</div>


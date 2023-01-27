<div class="modal popup-modal fade" id="acceptRejectModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" class="profile_url" value="">
                <input type="hidden" class="operation_type" value="">
                <div class="modal-body-content">
                    <img src="{{asset('image/web/placeholder.png')}}" alt="image" class="img-fluid dynamic_img">
                    <h4 class="unfol-joe dynamic_name"></h4>
                    <div class="modal-unfollow-buttn">
                        <button type="button" class="unfol-joe-trans acceptRejectBtn" data-value="accepted_request">ACCEPT</button>
                        <br>
                        <button type="button" class="unfol-joe-col acceptRejectBtn" data-value="rejected_request">DECLINE</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal modal3 fade" id="wish_list_add_edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-slideout" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <h5 class="modal-title add-prezziez-heading align-right" id="exampleModalLabel"><span
                            class="prezziez-setting">List Settings</span> Select Type of List</h5>
                <div class="error_wishlist_type text-danger text-center"></div>
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-12">
                        <div class="selection-popup-boxes">
                            <ul>
                                <li>
                                    <input type="checkbox" id="cb1" value="general" name="gift_type"/>
                                    <label for="cb1">
                                        <div class="popup-inner">
                                            <img src="{{asset('image/web/pop1.png')}}" alt="image"
                                                 class="img-fluid">
                                            <div class="popup-inner-content">
                                                <h6 class="popup-inner-heading">General</h6>
                                                <p class="popup-inner-text">General desired items i.e. clothes,
                                                    toys, electronics etc. </p>
                                            </div>
                                        </div>
                                    </label>
                                </li>
                                <li><input type="checkbox" id="cb2" value="registry" name="gift_type"/>
                                    <label for="cb2">
                                        <div class="popup-inner">
                                            <img src="{{asset('image/web/pop2.png')}}" alt="image"
                                                 class="img-fluid">
                                            <div class="popup-inner-content">
                                                <h6 class="popup-inner-heading">Registry</h6>
                                                <p class="popup-inner-text">i.e Wedding, housewarming, baby shower,
                                                    etc.</p>
                                            </div>
                                        </div>
                                    </label>
                                </li>
                                <li>
                                    <input type="checkbox" id="cb3" value="experiences" name="gift_type"/>
                                    <label for="cb3">
                                        <div class="popup-inner">
                                            <img src="{{asset('image/web/pop3.png')}}" alt="image"
                                                 class="img-fluid">
                                            <div class="popup-inner-content">
                                                <h6 class="popup-inner-heading">Experiences</h6>
                                                <p class="popup-inner-text">i.e Vacations, outings, activities, etc.</p>
                                            </div>
                                        </div>
                                    </label>
                                </li>
                                <li>
                                    <input type="checkbox" id="cb4" value="events" name="gift_type"/>
                                    <label for="cb4">
                                        <div class="popup-inner">
                                            <img src="{{asset('image/web/pop5.png')}}" alt="image"
                                                 class="img-fluid">
                                            <div class="popup-inner-content">
                                                <h6 class="popup-inner-heading">Events</h6>
                                                <p class="popup-inner-text">i.e Birthday, christmas, graduation,
                                                    etc.</p>
                                            </div>
                                        </div>
                                    </label>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <a href="javascript:;" class="add-next next_wishlist">NEXT</a>
            </div>
        </div>
    </div>
</div>

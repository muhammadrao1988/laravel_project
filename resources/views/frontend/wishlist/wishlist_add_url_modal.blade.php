<div class="modal modal1 fade" id="ModalSlide" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-slideout" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="wishlist_form" class="wishlist_url_form" method="POST">
                    {{csrf_field()}}
                    <h5 class="modal-title add-prezziez-heading align-right" id="exampleModalLabel">Add a
                        Prezzie</h5>
                    <p class="add-prezziez-text">Paste a link from anywhere on the web</p>
                    <input id="url_web" required name="url" value="{{ (old('url'))? old('url'): ""}}" type="text"
                           class="prezziez-field {{ $errors->has('url') ? ' is-invalid' : '' }}"
                           placeholder="https: //">
                    <a href="javascript:;" class="add-next next_sc">NEXT</a>
                </form>
                {{--<h5 class="modal-title add-prezziez-heading align-right" id="exampleModalLabel">Add a Prezziez</h5>
                <p class="add-prezziez-text">Paste a link from anywhere on the web</p>
                <input type="text" class="prezziez-field" placeholder="https: //">
                <a href="javascriptvoid:(0)" class="add-next">NEXT</a>--}}
            </div>
            <div class="modal-footer">
                <p class="modal-footer-text">Don’t have a link?
                    <button type="button" class="btn btn-secondary manual-add no-link">Add Manually.</button>
                </p>
            </div>
        </div>
    </div>
</div>

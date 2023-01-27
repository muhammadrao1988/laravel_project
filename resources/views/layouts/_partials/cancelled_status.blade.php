<div class="modal fade" id="cancellationForm" tabindex="-1" role="dialog" aria-labelledby="cancellationModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Cancel Record</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="recordId" value="" id="recordId">
                <div class="form-group">
                    <label for="cancellationReason" class="control-label">Cancel Reason:</label>
                    <textarea id="cancellationReason" class="form-control" name="cancellationReason"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveStatus">Confirm Cancelled</button>
            </div>
        </div>
    </div>
</div>
@section('js')
@parent
<script>
  $(function() {
      $("body").on("click", ".cancelLead", function (e) {
          $("#recordId").val($(this).attr('data-id'));
      });
      $("#saveStatus").click(function () {
          var recordId = $("#recordId").val();
          var cancellationReason = $("#cancellationReason").val();
          if (cancellationReason == "") {
              showInfo("Please add reason for cancellation.");
              return false;
          }
          if (confirm("Do you really want to cancel this record?")) {
              var data = {
                  'recordId': recordId,
                  'id': recordId,
                  'cancellationReason': cancellationReason,
                  'status': 'Cancelled',
                  '_token': "{{csrf_token()}}"
              };

              $("#saveStatus").attr('disabled', 'disabled');
              $.ajax({
                  url: "{{ route($route) }}",
                  dataType: "json",
                  type: "POST",
                  async: false,
                  data: data
              }).then(function (data) {
                  $("#saveStatus").removeAttr('disabled');
                  location.reload();
              }).fail(function (error) {
                  $("#saveStatus").removeAttr('disabled');
                  if (error.responseJSON.hasOwnProperty('errors')) {
                      for (var prop in error.responseJSON.errors) {
                          $(error.responseJSON.errors[prop]).each(function (val, msg) {
                              showError(msg)
                          });
                      }
                  } else {
                      showInfo("There is an error in cancellation of record, please refresh the page and try again!");
                  }
              });
          }
      })
  });


</script>
@stop

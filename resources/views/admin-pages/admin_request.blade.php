@extends('layout.master')

@section('title')
    <h1>Request Overview</h1>
@endsection

@section('content')
<div class="content-container">
    <div class="table-responsive">
        <table class='table table-bordered' id="request_table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Request Note</th>
                    <th>Date Sent</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="request_body">
                
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="approvedModal" tabindex="-1" aria-labelledby="approvedModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="approvedModalLabel">APPRAISAL FORM CONTROL</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Toggle buttons -->
        <div class="btn-group" role="group">
            <div class="form-check form-switch p-0">
                <label class="form-check-label">KRA Encoding Phase</label>
                <label class="form-check-label d-flex justify-content-between align-items-center" for="switchCheckLabel_0">
                    <span>On</span>
                    <input class="form-check-input ms-0" type="checkbox" role="switch" id="switchCheckLabel_KRA">
                    <span>Off</span>
                </label>
            </div>
            
            <div class="form-check form-switch p-0">
                <label class="form-check-label">Performance Review Phase</label>
                <label class="form-check-label d-flex justify-content-between align-items-center" for="switchCheckLabel_0">
                    <span>On</span>
                    <input class="form-check-input ms-0" type="checkbox" role="switch" id="switchCheckLabel_PR">
                    <span>Off</span>
                </label>
            </div>

            <div class="form-check form-switch p-0">
                <label class="form-check-label">Evaluation Phase</label>
                <label class="form-check-label d-flex justify-content-between align-items-center" for="switchCheckLabel_0">
                    <span>On</span>
                    <input class="form-check-input ms-0" type="checkbox" role="switch" id="switchCheckLabel_EVAL">
                    <span>Off</span>
                </label>
            </div>

            <div class="form-check form-switch p-0">
                <div class="d-flex justify-content-center align-items-center">
                    <label class="form-check-label">LOCK</label>
                </div>   
                <label class="form-check-label d-flex justify-content-between align-items-center" for="switchCheckLabel_0">
                    <span>On</span>
                    <input class="form-check-input ms-0" type="checkbox" role="switch" id="switchCheckLabel_LOCK">
                    <span>Off</span>
                </label>
            </div>
        </div>

        <!-- Text area -->
        <div class="form-group mt-3">
          <label for="approveTextarea">Message</label>
          <textarea class="form-control" id="approveTextarea" rows="3"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="disapprovedModal" tabindex="-1" aria-labelledby="disapprovedModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="disapprovedModalLabel">FEEDBACK</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Text area -->
        <div class="form-group">
          <label for="approveTextarea">Message</label>
          <textarea class="form-control" id="disapproveTextarea" rows="3" required></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>


<script>
    $(document).ready(function() {
        $.ajax({
            url: '{{ route('getUserRequests') }}',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                // Clear the table body
                $('#request_body').empty();
                console.log(data);
                // Loop through the retrieved data and populate the table
                $.each(data, function(index, request) {
                    var row = $('<tr>');

                    row.append($('<td>').text(request.name)); // Use the evaluator's name from the data
                    row.append($('<td>').text(request.request));
                    row.append($('<td>').text(request.date_sent));
                    row.append($('<td>').text(request.status));

                    // // Create a Bootstrap-styled switch for the "action"
                    // var switchCell = $('<td>');

                    // var switchDiv = $('<div>').addClass('form-check form-switch p-0');

                    // var switchInput = $('<input>').addClass('form-check-input ms-0').attr({
                    //     type: 'checkbox',
                    //     role: 'switch',
                    //     id: 'switchCheckLabel_' + index // You can use a unique ID for each switch
                    // });

                    // // Create a custom label structure to display "Approve" and "Disapprove" simultaneously
                    // var switchLabel = $('<label>').addClass('form-check-label d-flex justify-content-between align-items-center').attr('for', 'switchCheckLabel_' + index);
                    // switchLabel.append('<span>Approve</span>', switchInput, '<span>Disapprove</span>');

                    // // Append the switch components to the table cell
                    // switchDiv.append(switchLabel);
                    // switchCell.append(switchDiv);
                    // row.append(switchCell);

                    // Create buttons for "Approve" and "Disapprove"
                    var approveButton = $('<button>').addClass('btn btn-primary').attr('id', 'approveButton').text('Approve');
                    var disapproveButton = $('<button>').addClass('btn btn-danger').attr('id', 'disapproveButton').text('Disapprove');

                    // Set the initial state
                    var isApproved = false;

                    // Set the click event handlers for the buttons
                    approveButton.on('click', function () {
                        // Handle the "Approve" action
                        isApproved = true;
                        console.log('Approved');

                        // Trigger the modal
                        $('#approvedModal').modal('show');
                    });

                    disapproveButton.on('click', function () {
                        // Handle the "Disapprove" action
                        isApproved = false;
                        console.log('Disapproved');

                        // Trigger the modal
                        $('#disapprovedModal').modal('show');
                    });

                    // Create a table cell and append the buttons to it
                    var actionCell = $('<td>');
                    actionCell.append(approveButton, disapproveButton);

                    // Append the table cell to the row
                    row.append(actionCell);

                    // Append the row to the table
                    $('#request_body').append(row);
                });
            },
            error: function(error) {
                console.error('Error:', error);
            }
        });
    });

</script>
@endsection
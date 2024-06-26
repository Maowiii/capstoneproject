@extends('layout.master')

@section('title')
    <h1>Request Overview</h1>
@endsection

@section('content')
    <div class="row g-3 align-items-start mb-3">
        <div class="col-auto">
            <h4>School Year:</h4>
        </div>
        <div class="col">
            <select class="form-select align-middle" id="evaluation-year-select">
                @if (!$activeEvalYear)
                    <option value="">Select an Evaluation Year (no ongoing evaluation)</option>
                @endif
                @foreach ($evaluationYears as $year)
                    <option value="{{ $year->sy_start }}_{{ $year->sy_end }}"
                        @if ($activeEvalYear && $year->eval_id === $activeEvalYear->eval_id) selected @endif>
                        {{ $year->sy_start }} - {{ $year->sy_end }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="content-container">
        <div class="table-responsive">
            <div class="input-group mb-2 search-box">
                <input type="text" class="form-control" placeholder="Search" id="search">
                <button class="btn btn-outline-secondary" type="button">
                    <i class='bx bx-search'></i>
                </button>
            </div>

            <table class='table table-bordered' id="request_table">
                <thead>
                    <tr>
                        <th>Requester</th>
                        <th>Appraisee</th>
                        <th>Evaluation Type</th>
                        <th>Request Note</th>
                        <th>Date Sent</th>
                        <th>Approver</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="request_body">

                </tbody>
            </table>
        </div>
        <nav id="appraisal_pagination_container">
            <ul class="pagination pagination-sm justify-content-end" id="request_pagination"></ul>
        </nav>
    </div>

    <div class="position-relative">
        <div id="toastHolder" aria-live="polite" aria-atomic="true" class="position-fixed bottom-0 end-0 p-3"
            style="z-index: 99;">
            <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-delay="1000">
                <div class="toast-header bg-primary">
                    <strong class="me-auto">Pop-up Message</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    <!-- Message content goes here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade modal-lg" id="approvedModal" tabindex="-1" aria-labelledby="approvedModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="approvedModalLabel">APPROVAL CONTROL</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5>Instructions:</h5>
                    <p class='text-justify'>This approval control form is intended for unlocking the appraisal form in
                        response to an employee's request.
                        To initiate the process, toggle the switch to 'ON' and subsequently click <i>'Save Changes.'</i> The
                        system will promptly apply the adjustments
                        to the requester's account.<br>If you require additional guidance or wish to provide specific
                        instructions, kindly leave a note before proceeding
                        with the <i>'Save Changes.'</i> action. Thank you.
                    </p>
                    <!-- Toggle buttons -->
                    <div class="btn-group" role="group">
                        <div class="form-check form-switch p-0" style="margin-left: 30px" id="kraLockHolder">
                            <label class="form-check-label">
                                <h6>KRA Encoding</h6>
                            </label>
                            <label class="form-check-label d-flex justify-content-between align-items-center"
                                for="switchCheckLabel_KRA">
                                <span> Unlock </span>
                                <input class="form-check-input ms-0" type="checkbox" role="switch"
                                    id="switchCheckLabel_KRA" name="kra_Lock">
                                <span> Lock </span>
                            </label>
                        </div>

                        <div class="form-check form-switch p-0"style="margin-left: 50px" id="prLockHolder">
                            <label class="form-check-label">
                                <h6>Performance Review</h6>
                            </label>
                            <label class="form-check-label d-flex justify-content-between align-items-center"
                                for="switchCheckLabel_PR">
                                <span> Unlock </span>
                                <input class="form-check-input ms-0" type="checkbox" role="switch" id="switchCheckLabel_PR"
                                    name="pr_Lock">
                                <span> Lock </span>
                            </label>
                        </div>

                        <div class="form-check form-switch p-0" style="margin-left: 50px" id="evalLockHolder">
                            <label class="form-check-label">
                                <h6>Evaluation Phase</h6>
                            </label>
                            <label class="form-check-label d-flex justify-content-between align-items-center"
                                for="switchCheckLabel_EVAL">
                                <span> Unlock </span>
                                <input class="form-check-input ms-0" type="checkbox" role="switch"
                                    id="switchCheckLabel_EVAL" name="eval_Lock">
                                <span> Lock </span>
                            </label>
                        </div>

                        <div class="form-check form-switch p-0" style="margin-left: 50px" id="evalLockHolder">
                            <label class="form-check-label">
                                <h6>Deadline (optional)</h6>
                            </label>
                            <label class="form-check-label d-flex justify-content-between align-items-center"
                                for="switchCheckLabel_EVAL">
                                <input class='form-control' type='date' name="custom_deadline" id="custom_deadline"
                                    placeholder="optional">
                            </label>
                        </div>

                        <!-- <div class="form-check form-switch p-0" style="margin-left: 50px" id="lockLockHolder">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <label class="form-check-label"><h6>Appraisal Form</h6></label>
                                            </div>
                                            <label class="form-check-label d-flex justify-content-between align-items-center" for="switchCheckLabel_LOCK">
                                                <span> Off </span>
                                                <input class="form-check-input ms-0" type="checkbox" role="switch" id="switchCheckLabel_LOCK" name="form_Lock">
                                                <span> On </span>
                                            </label>
                                        </div> -->
                    </div>

                    <!-- Text area -->
                    <div class="form-group mt-3">
                        <label for="approveTextarea">
                            <h5>Note:</h5>
                        </label>
                        <textarea class="form-control" id="approveTextarea" placeholder="Enter any further instructions after approval..."
                            rows="3"></textarea>
                    </div>

                    <!-- Alert -->
                    <div id="approveAlertHolder"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="approveSubmitBtn" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade modal-lg" id="disapprovedModal" tabindex="-1" aria-labelledby="disapprovedModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="disapprovedModalLabel">FEEDBACK</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5>Instructions:</h5>
                    <p class='text-justify'>This feedback form system is designed to provide a brief explanation to why the
                        request was disapproved.
                        Kindly fill up the field provided below. Thank you
                    </p>
                    <!-- Text area -->
                    <div class="form-group">
                        <label for="disapproveTextarea">
                            <h5>Message</h5>
                        </label>
                        <textarea class="form-control" id="disapproveTextarea"
                            placeholder="Enter the reason why the request was disapproved..." rows="3" required></textarea>
                    </div>

                    <!-- Alert -->
                    <div id="disapproveAlertHolder"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="disapprvSubmitBtn" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        $(document).ready(function() {
            var globalSelectedYear = null;
            var activeYear = $('#evaluation-year-select').val();

            $('#evaluation-year-select').change(function() {
                var selectedYear = $(this).val();
                globalSelectedYear = selectedYear;

                loadUserRequestsTable(selectedYear, null, activeYear);
                // console.log('Selected Year: ' + selectedYear);
            });

            $('#search').on('input', function() {
                var search = $(this).val();
                loadUserRequestsTable(globalSelectedYear, search, activeYear)
            });

            loadUserRequestsTable();

            // Click event for the "Submit" button
            $('#approveSubmitBtn').click(function() {
                // Perform validation
                // Valid switches, create a confirmation alert
                const confirmationAlert = $('<div>')
                    .addClass('alert alert-warning alert-dismissible fade show mt-3').attr('role',
                        'alert')
                    .append($('<strong>').text(
                        'Confirmation: Are you sure you want to proceed with the approval?'))
                    .append($('<button>').addClass('btn-close').attr('data-bs-dismiss', 'alert').attr(
                        'aria-label', 'Close'));

                // Display the confirmation alert
                $('#approveAlertHolder').html(confirmationAlert);

                // Disable input elements and text area
                $('input[type="checkbox"]').prop('disabled', true);
                $('#approveTextarea').prop('disabled', true);

                $(this).hide();
                $('#confirmApprovalSubmitBtn').show();

            });

            // OLD SUBMIT BUTTON 
            // $('#approveSubmitBtn').click(function() {
            //     // Perform validation
            //     if (isAtLeastOneCheckboxChecked()) {
            //         // Valid switches, create a confirmation alert
            //         const confirmationAlert = $('<div>')
            //             .addClass('alert alert-warning alert-dismissible fade show mt-3').attr('role',
            //                 'alert')
            //             .append($('<strong>').text(
            //                 'Confirmation: Are you sure you want to proceed with the approval?'))
            //             .append($('<button>').addClass('btn-close').attr('data-bs-dismiss', 'alert').attr(
            //                 'aria-label', 'Close'));

            //         // Display the confirmation alert
            //         $('#approveAlertHolder').html(confirmationAlert);

            //         // Disable input elements and text area
            //         $('input[type="checkbox"]').prop('disabled', true);
            //         $('#approveTextarea').prop('disabled', true);

            //         $(this).hide();
            //         $('#confirmApprovalSubmitBtn').show();
            //     } else {
            //         // Invalid switches, show an error message
            //         const errorAlert = $('<div>')
            //             .addClass('alert alert-danger alert-dismissible fade show mt-3').attr('role',
            //                 'alert')
            //             .append($('<strong>').text(
            //                 'Warining: Please enable at least one(1) form lock switches before submitting.'
            //             ))
            //             .append($('<button>').addClass('btn-close').attr('data-bs-dismiss', 'alert').attr(
            //                 'aria-label', 'Close'));

            //         // Display the confirmation alert
            //         $('#approveAlertHolder').html(errorAlert);
            //     }
            // });

            $('#disapprvSubmitBtn').click(function() {
                // Get the text from the textarea
                var textareaValue = $('#disapproveTextarea').val();

                // Validate and sanitize the text
                var sanitizedText = validateAndSanitizeText(textareaValue);

                // Perform further actions with the sanitized text
                if (sanitizedText) {
                    // Valid switches, create a confirmation alert
                    const confirmationAlert = $('<div>')
                        .addClass('alert alert-warning alert-dismissible fade show mt-3').attr('role',
                            'alert')
                        .append($('<strong>').text(
                            'Confirmation: Are you sure you want to proceed with the disapproval?'))
                        .append($('<button>').addClass('btn-close').attr('data-bs-dismiss', 'alert').attr(
                            'aria-label', 'Close'));

                    // Display the confirmation alert
                    $('#disapproveAlertHolder').html(confirmationAlert);

                    // Disable input elements and text area
                    $('#disapproveTextarea').prop('disabled', true);

                    $(this).hide();
                    $('#confirmDisapprovalSubmitBtn').show();
                } else {
                    // Invalid switches, show an error message
                    const errorAlert = $('<div>')
                        .addClass('alert alert-danger alert-dismissible fade show mt-3').attr('role',
                            'alert')
                        .append($('<strong>').text(
                            'Warining: Please enter a reasonable reason for disapproval.'))
                        .append($('<button>').addClass('btn-close').attr('data-bs-dismiss', 'alert').attr(
                            'aria-label', 'Close'));

                    // Display the confirmation alert
                    $('#disapproveAlertHolder').html(errorAlert);
                }
            });
        });

        function loadUserRequestsTable(selectedYear = null, search = null, activeYear = null, page = 1) {
            $.ajax({
                url: '{{ route('getUserRequests') }}',
                method: 'GET',
                type: 'GET',
                data: {
                    selectedYear: selectedYear,
                    search: search,
                    page: page
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    // Clear the table body
                    $('#request_body').empty();
                    console.log(data.data);
                    if (data.data.length === 0) {
                        $('#request_body').html(
                            '<tr><td colspan="8" class="text-center">No appraisal requests available.</td></tr>'
                        );
                        return;
                    }
                    // Loop through the retrieved data and populate the table
                    $.each(data.data, function(index, request) {
                        var row = $('<tr>');

                        row.append($('<td>').text(request.requester));
                        row.append($('<td>').text(request.appraisee));
                        row.append($('<td>').text(request.appraisal_type));
                        row.append($('<td>').text(request.request));
                        row.append($('<td>').text(request.date_sent));
                        row.append($('<td>').text(request.approver));
                        row.append($('<td>').text(request.status));

                        // Create buttons for "Approve" and "Disapprove"
                        var approveButton = $('<button>').addClass('btn btn-primary approveButton')
                            .attr('id', 'approveButton')
                            .html('<i class="bi bi-check-circle"></i> Approve').attr(
                                'data-appraisal-id', request.appraisal_id)
                            .attr('data-request-id', request.request_id).attr('data-eval-year',
                                selectedYear);

                        var disapproveButton = $('<button>').addClass('btn btn-danger disapproveButton')
                            .attr('id', 'disapproveButton')
                            .html('<i class="bi bi-x-circle"></i> Disapprove').attr('data-appraisal-id',
                                request.appraisal_id)
                            .attr('data-request-id', request.request_id).attr('data-eval-year',
                                selectedYear);

                        // Set the initial state
                        var isApproved = false;

                        // Set the click event handlers for the buttons
                        approveButton.on('click', function() {
                            // Handle the "Approve" action
                            isApproved = true;
                            console.log('Approved');

                            var appraisalId = $(this).data('appraisal-id');
                            var requestId = $(this).data('request-id');

                            var evaluationType = request.appraisal_type.toLowerCase();

                            if (evaluationType === 'self evaluation') {
                                $('#kraLockHolder').hide();
                                $('#prLockHolder').show();
                                $('#evalLockHolder').show();
                                $('#lockLockHolder').show();
                            } else if (evaluationType === 'is evaluation') {
                                $('#kraLockHolder').show();
                                $('#prLockHolder').show();
                                $('#evalLockHolder').show();
                                $('#lockLockHolder').show();
                            } else if (evaluationType === 'internal customer 1' ||
                                evaluationType === 'internal customer 2') {
                                $('#kraLockHolder').hide();
                                $('#prLockHolder').hide();
                                $('#evalLockHolder').show();
                                $('#lockLockHolder').show();
                            }

                            // Create the confirmation button
                            var confirmButton = $('<button>').attr('type', 'submit').attr('id',
                                    'confirmApprovalSubmitBtn')
                                .attr('data-appraisal-id', appraisalId)
                                .attr('data-request-id', requestId)
                                .addClass('btn btn-primary').text('Send Approval').hide();

                            // Append the confirmation button to the modal footer
                            $('.modal-footer').append(confirmButton);

                            $('#switchCheckLabel_KRA').prop('checked', request.locks.kra ===
                                true ? true : false);
                            $('#switchCheckLabel_PR').prop('checked', request.locks.pr ===
                                true ? true : false);
                            $('#switchCheckLabel_EVAL').prop('checked', request.locks.eval ===
                                true ? true : false);
                            // $('#switchCheckLabel_LOCK').prop('checked', request.locks.lock === true ? true : false);

                            if (request.feedback) {
                                $('#approveTextarea').val(request.feedback);
                            }

                            if (selectedYear < activeYear) {
                                $('#approveSubmitBtn, #disapprvSubmitBtn').hide();
                                $('input[type="checkbox"]').prop('disabled', true);
                            }

                            // Trigger the modal
                            $('#approvedModal').modal('show');
                        });

                        disapproveButton.on('click', function() {
                            // Handle the "Disapprove" action
                            isApproved = false;
                            console.log('Disapproved');

                            var appraisalId = $(this).data('appraisal-id');
                            var requestId = $(this).data('request-id');

                            // Create the confirmation button
                            var confirmButton = $('<button>').attr('type', 'submit').attr('id',
                                    'confirmDisapprovalSubmitBtn')
                                .attr('data-appraisal-id', appraisalId)
                                .attr('data-request-id', requestId)
                                .addClass('btn btn-primary').text('Send Disapproval').hide();

                            // Append the confirmation button to the modal footer
                            $('#disapprovedModal .modal-footer').append(confirmButton);

                            if (request.feedback) {
                                $('#disapproveTextarea').val(request.feedback);
                            }

                            if (selectedYear < activeYear) {
                                $('#approveSubmitBtn, #disapprvSubmitBtn').hide();
                                $('input[type="checkbox"]').prop('disabled', true);
                            }
                            // Trigger the modal
                            $('#disapprovedModal').modal('show');
                        });

                        // Determine the action based on the request status
                        if (request.status.toLowerCase() === 'pending') {
                            // Enable both "Approve" and "Disapprove" buttons
                            approveButton.prop('disabled', false);
                            disapproveButton.prop('disabled', false);
                        } else if (request.action) {
                            // Enable only the "Approve" button
                            approveButton.prop('disabled', false);
                            disapproveButton.prop('disabled', true);
                        } else if (!request.action) {
                            // Enable only the "Disapprove" button
                            approveButton.prop('disabled', true);
                            disapproveButton.prop('disabled', false);
                        }

                        // Create a table cell and append the buttons to it
                        var actionCell = $('<td>');
                        actionCell.append(approveButton, disapproveButton);

                        // Append the table cell to the row
                        row.append(actionCell);

                        // Append the row to the table
                        $('#request_body').append(row);
                    });

                    var totalPage = data.pagination.last_page;
                    var currentPage = data.pagination.current_page;
                    var paginationLinks = data.pagination.links;

                    $('#request_pagination').empty();

                    for (var totalPageCounter = 1; totalPageCounter <=
                        totalPage; totalPageCounter++) {
                        (function(pageCounter) {
                            var pageItem = $('<li>').addClass('page-item');
                            if (pageCounter === currentPage) {
                                pageItem.addClass('active');
                            }

                            var pageButton = $('<button>').addClass('page-link').text(
                                pageCounter);
                            pageButton.click(function() {
                                // Redirect to the selected page
                                loadUserRequestsTable(selectedYear, search, activeYear,
                                    pageCounter);
                            });

                            pageItem.append(pageButton);
                            $('#request_pagination').append(pageItem);
                        })(totalPageCounter);
                    }
                },
                error: function(error) {
                    console.error('Error:', error);
                },
            });
        }

        $(document).on('click', '#confirmApprovalSubmitBtn', function() {
            var appraisalId = $(this).data('appraisal-id');
            var requestId = $(this).data('request-id');

            // Create a new FormData object
            var formData = new FormData();

            // Append your data to the FormData object
            formData.append('appraisalId', appraisalId);
            formData.append('requestId', requestId);
            formData.append('kra', $('#switchCheckLabel_KRA').is(':checked') ? 1 : 0);
            formData.append('pr', $('#switchCheckLabel_PR').is(':checked') ? 1: 0);
            formData.append('eval', $('#switchCheckLabel_EVAL').is(':checked') ? 1 : 0);
            formData.append('lock', $('#switchCheckLabel_LOCK').is(':checked') ? 1 : 0);
            formData.append('note', $('#approveTextarea').val());
            formData.append('deadline', $('#custom_deadline').val());
            formData.forEach(function(value, key) {
                console.log(key, value);
            });

            // Make the AJAX request
            $.ajax({
                url: '{{ route('submitRequestApproval') }}',
                method: 'POST',
                data: formData,
                processData: false, // Set to false when using FormData
                contentType: false, // Set to false when using FormData  
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(data) {
                    // Handle the server response (if needed)
                    if (data.success) {
                        loadUserRequestsTable();
                        showToast(data.message);
                    } else {
                        showToast("Error: " + data.message, true);
                    }

                    $('#approvedModal').modal('hide');
                    console.log(data);
                },
                error: function(error) {
                    showToast("Error: " + error.message, true);

                    console.error('Error:', error);
                }
            });

            // Close the modal or perform other actions
            console.log('confirm was clicked');
        });

        $(document).on('click', '#confirmDisapprovalSubmitBtn', function() {
            var appraisalId = $(this).data('appraisal-id');
            var requestId = $(this).data('request-id');

            // Create a new FormData object
            var formData = new FormData();

            // Append your data to the FormData object
            formData.append('appraisalId', appraisalId);
            formData.append('requestId', requestId);
            formData.append('note', $('#disapproveTextarea').val());

            // Make the AJAX request
            $.ajax({
                url: '{{ route('submitRequestDisapproval') }}',
                method: 'POST',
                data: formData,
                processData: false, // Set to false when using FormData
                contentType: false, // Set to false when using FormData  
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(data) {
                    // Handle the server response (if needed)
                    if (data.success) {
                        loadUserRequestsTable();
                        showToast(data.message);
                    } else {
                        showToast("Error: " + data.message, true);
                    }

                    $('#disapprovedModal').modal('hide');
                    console.log(data);
                },
                error: function(error) {
                    showToast("Error: " + error.message, true);

                    console.error('Error:', error);
                }
            });

            // Close the modal or perform other actions
            console.log('confirm was clicked');
        });

        // Function to validate the toggle switches
        function isAtLeastOneCheckboxChecked() {
            let isAnyCheckboxChecked = false;

            // Check each toggle switch
            $('input[type="checkbox"]').each(function() {
                if ($(this).is(':checked')) {
                    isAnyCheckboxChecked = true;
                    return false; // Exit the loop if any switch is checked
                }
            });
            return isAnyCheckboxChecked;
        }

        function validateAndSanitizeText(text) {
            // Trim leading and trailing white spaces
            text = text.trim();

            // Sanitize text to prevent cross-site scripting (XSS) attacks
            text = $('<div>').text(text).html();

            return text;
        }

        $('input[type="checkbox"]').click(function() {
            // Hide or reset alerts when a toggle button is clicked
            $('#approveAlertHolder').html(''); // Clear the content
        });

        $('#disapproveTextarea').on('input', function() {
            // Hide or reset alerts when the input field is modified
            $('#disapproveAlertHolder').html(''); // Clear the content
        });

        // Handler for modal hidden event
        $('#approvedModal').on('hidden.bs.modal', function() {
            // Enable checkboxes and text area when the modal is closed
            $('input[type="checkbox"]').prop('checked', false).prop('disabled', false);
            $('#approveTextarea').prop('disabled', false).val('');
            $('#approveAlertHolder').html(''); // Clear the content
            $('#approveSubmitBtn').show();
            $('#confirmApprovalSubmitBtn').hide();
        });

        // Handler for modal hidden event
        $('#disapprovedModal').on('hidden.bs.modal', function() {
            // Enable checkboxes and text area when the modal is closed
            $('#disapproveTextarea').prop('disabled', false).val('');
            $('#disapproveAlertHolder').html(''); // Clear the content
            $('#disapprvSubmitBtn').show();
            $('#confirmDisapprovalSubmitBtn').hide();
        });

        // Function to show a Bootstrap toast
        function showToast(message, isError = false) {
            const toastHolder = $("#toastHolder");
            const toast = toastHolder.find(".toast");

            // Update the toast message content
            toast.find(".toast-body").text(message);

            // Optionally, you can change toast styles based on whether it's an error message
            if (isError) {
                toast.removeClass("text-primary");
                toast.addClass("text-danger");
            } else {
                toast.removeClass("text-danger");
                toast.addClass("text-primary");
            }

            // Show the toast using Bootstrap's toast method
            toast.toast("show");
        }

        function refreshPage() {
            location.reload();
        }
    </script>
@endsection

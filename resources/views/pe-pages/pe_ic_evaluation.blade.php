@extends('layout.master')

@section('title')
    <h1>Internal Customer Evaluation Form</h1>
@endsection

@section('content')
    <div class="content-container">

        <!-- <div id="progressBarHandler" class="progress" style="height: 10px;">
                    <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-label="Animated striped example" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
                </div> -->

        <h3>Dear Adamson Employee,</h3>
        <p>Each department (co-academic offices) wants to know how well we are serving our employees/internal customers. We
            appreciate if you would take the time to complete this evaluation. This is a feedback mechanism to improve our
            services. Your answers will be treated with utmost confidentiality.</p>
        <p>Your opinion matters. We welcome your comments and suggestions. Please answer the questions by selecting one
            option from the provided choices using the radio buttons. This will make us aware of a problem, complaints, or
            an opportunity to make a suggestion, and to recognize or commend for a job well done.</p>

        <div id="ic_form" class="row g-3 align-items-center">
            <div class="col-auto">
                <h5>Name of the staff to be evaluated:</h5>
            </div>
            <div class="col-auto">
                <input class="form-control" type="text" id="appraiseeName" disabled>
            </div>

            <p>Given the following behavioral competencies, you are to assess the incumbent's performance using the scale.
                Choose each number which corresponds to your answer for each item. Please answer each item truthfully.<br>
                5 - Almost Always 4 - Frequently 3 - Sometimes 2 - Occasionally 1 - Hardly Ever
            </p>

            <!-- <div id="progressBarContainer" class="card sticky-top border-0 d-flex flex-column align-items-center">
                        <h5 class="fs-6">Progress Bar</h5>
                        <div id="progressBarHandler" class="progress w-75" style="height: 15px;">
                            <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-label="Animated striped example" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
                        </div>
                    </div> -->

            <div class="d-grid gap-3">
                <button type="button" class="btn btn-primary col-3" id="sendrequest"> <i class="bi bi-envelope-paper"></i>
                    Send Request</button>
                <div id="feedback-container" class="alert alert-info d-none" role="alert">
                    <button type="button" class="btn-close float-end" data-bs-dismiss="alert" aria-label="Close"></button>
                    <strong>Feedback:</strong> <span id="feedback-text"></span>
                    <hr>
                    <div id="additional-info" class="font-italic"></div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered" id="IC_table">
                    <thead>
                        <tr>
                            <th class="xxs-column">#</th>
                            <th>Question</th>
                            <th class="large-column">Score</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td></td>
                            <td class='text-right'>Total Weighted Score:</td>
                            <td>
                                <div class="d-flex justify-content-center gap-3">
                                    <input id="total-weighted-score"
                                        class="small-column form-control total-weighted text-center" type="text"
                                        readonly>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <p>What did you like best about his/her customer service? <span class="text-danger">*</span></p>
            <textarea class="form-control" id="service_area"></textarea>

            <p class="mt-3">Other comments and suggestions: <span class="text-danger">*</span></p>
            <textarea class="form-control" id="comments_area"></textarea>
        </div>
        <div class="d-flex justify-content-center gap-3  p-3">
            <button type="submit" class="btn btn-primary medium-column" id="submit-btn">Submit</button>
        </div>

        <div class="modal fade" id="signatory_modal" data-bs-backdrop="static">
            <div class="modal-dialog modal-lg">
                <div class="modal-content" id="signatory">
                    <div class="modal-header">
                        <h5 class="modal-title fs-5">Signatories</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-responsive" id="signtable">
                            <thead>
                                <tr>
                                    <th scope="col" style="width:20%" id="partieshead">PARTIES</th>
                                    <th scope="col" style="width:20%" id="fullnamehead">FULL NAME</th>
                                    <th scope="col" style="width:25%" id="signhead">SIGNATURE</th>
                                    <th scope="col" style="width:15%" id="datehead">DATE</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                        <div class="alert alert-danger d-none" role="alert" id="error-alert">
                        </div>
                        <div class="alert alert-warning" role="alert" id="confirmation-alert">
                                By submitting your signature, you automatically agree to the terms and conditions. And once you have submitted the form, you cannot alter any values.

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="cancel-btn" class="btn btn-secondary"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="button" id="esig-submit-btn" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal" id="imageModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="imageModalLabel">Signature Preview</h5>
                        <button type="button" class="btn-close" id="esig-close-btn"></button>
                    </div>
                    <div class="modal-body text-center">
                        <img id="modalImage" src="" alt="Signature" style="max-width: 100%;">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade modal-lg" id="request-popup-modal" data-bs-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fs-5">REQUEST FORM</h5>
                    <button type="button" class="btn-close common-close-button" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="{{ route('submitRequest') }}" method="POST" id="sendReqForm">
                    @csrf
                    <div class="modal-body">
                        <div id="validation-results" class="alert alert-danger" style="display: none;">
                            <ul id="validation-list"></ul>
                        </div>
                        <h5>Instructions:</h5>
                        <p class='text-justify'>This request form is designed to allow send request to have another attempt
                            in accomplishing the appraisal form.
                            Kindly provide the details of your request and any additional notes in the field provided.Thank
                            you</p>
                        <label for="requestText">
                            <h5>Request:</h5>
                        </label>
                        <textarea name="request" id="requestText" class="form-control" placeholder="Enter your request here..." required></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#sendrequest').hide();

            $('#service_area').on('blur', function() {
                var newService = $(this).val();
                updateService(newService);
                //updateProgressBar();
            });

            $('#comments_area').on('blur', function() {
                var newSuggestion = $(this).val();
                updateSuggestion(newSuggestion);
                //updateProgressBar();
            });

            // Function to retrieve URL parameters by name
            function getUrlParameter(name) {
                name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
                var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
                var results = regex.exec(location.search);
                return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
            }

            // Get the employee's name from the URL
            var appraiseeName = getUrlParameter('appraisee_name');

            // Set the employee's name in the input field
            if (appraiseeName) {
                $('#appraiseeName').val(appraiseeName);
            } else {
                $('.modal').hide();
                $('.content-body').remove();
                $('.content-container').remove();

                // Create a new div element
                var errorDiv = $('<div></div>');

                // Set the attributes and content for the error div
                errorDiv.attr('id', 'error-message');
                errorDiv.addClass('alert alert-danger content-container');
                errorDiv.text('An error occurred: You do not have permission to view this form.');

                // Append the error div to a specific element (e.g., the body)
                errorDiv.appendTo('.content-section');
            }

            $('#esig-submit-btn').on('click', function() {
                var fileInput = $('#esig')[0];
                var urlParams = new URLSearchParams(window.location.search);
                var appraisalId = urlParams.get('appraisal_id');
                var totalWeightedScore = $('#total-weighted-score').val();
                // console.log('Total Weighted Score: ' + totalWeightedScore);

                if (fileInput.files.length === 0) {
                    $('#esig').addClass('is-invalid');
                    return;
                } else {
                    var selectedFile = fileInput.files[0];

                    if (!isImageFile(selectedFile)) {
                        $('#esig').addClass('is-invalid');

                        $('#error-alert').removeClass('d-none').text(
                            'Please select a valid image file. Supported formats: JPEG, PNG, GIF.');

                        setTimeout(function() {
                            $('#error-alert').addClass('d-none');
                        }, 5000);

                        return;
                    }

                    var reader = new FileReader();
                    reader.onload = function(event) {
                        var fileData = event.target.result;
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url: '{{ route('pe.submitICSignature') }}',
                            type: 'POST',
                            data: {
                                appraisalId: appraisalId,
                                esignature: fileData,
                                totalWeightedScore: totalWeightedScore
                            },
                            success: function(response) {
                                if (response.success) {
                                    loadSignature();
                                    // console.log('Esignature Updated.');
                                    formChecker();
                                } else {
                                    var errorMessage = response.message;

                                    $('#error-alert').removeClass('d-none').text(
                                        errorMessage);

                                    setTimeout(function() {
                                        $('#error-alert').addClass('d-none');
                                    }, 5000);
                                }
                            },
                            error: function(xhr, status, error) {}
                        });
                    };

                    reader.readAsDataURL(selectedFile);
                }
            });

            function isImageFile(file) {
                return file.type.startsWith('image/');
            }

            function updateSuggestion(value) {
                var urlParams = new URLSearchParams(window.location.search);
                var appraisalId = urlParams.get('appraisal_id');

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{ route('updateSuggestion') }}',
                    type: 'POST',
                    data: {
                        newSuggestion: value,
                        appraisalId: appraisalId
                    },
                    success: function(response) {
                        // console.log('Backend updated successfully.');
                        $('#comments_area').removeClass('is-invalid');
                    },
                    error: function(xhr) {
                        if (xhr.responseText) {
                            // console.log('Error: ' + xhr.responseText);
                        } else {
                            // console.log('An error occurred.');
                        }
                    }
                });
            }

            function updateService(value) {
                // console.log(value);
                var urlParams = new URLSearchParams(window.location.search);
                var appraisalId = urlParams.get('appraisal_id');

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{ route('updateService') }}',
                    type: 'POST',
                    data: {
                        newService: value,
                        appraisalId: appraisalId
                    },
                    success: function(response) {
                        // console.log('Backend updated successfully.');
                        $('#service_area').removeClass('is-invalid');
                    },
                    error: function(xhr) {
                        if (xhr.responseText) {
                            // console.log('Error: ' + xhr.responseText);
                        } else {
                            // console.log('An error occurred.');
                        }
                    }
                });
            }

            function totalScore() {
                var total = 0;

                $('#IC_table .form-check-input[type="radio"]:checked').each(function() {
                    var score = parseInt($(this).val());
                    total += score;
                });

                numQuestions = $('#IC_table tbody tr').length;
                var averageScore = total / numQuestions;
                $('#total-weighted-score').val(averageScore.toFixed(2));
            }

            function loadTextAreas() {
                var urlParams = new URLSearchParams(window.location.search);
                var appraisalId = urlParams.get('appraisal_id');
                var sy = urlParams.get('sy');
                sy = (sy !== null && sy.trim() !== '') ? sy : null;


                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{ route('ad.getICCommentsAndSuggestions') }}',
                    type: 'POST',
                    data: {
                        appraisalId: appraisalId,
                        sy: sy
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#service_area').val(response.customerService);
                            $('#comments_area').val(response.suggestion);
                        } else {
                            // console.log('Comments not found or an error occurred.');
                        }
                    },
                    error: function(xhr) {
                        if (xhr.responseText) {
                            // console.log('Error: ' + xhr.responseText);
                        } else {
                            // console.log('An error occurred.');
                        }
                    }
                });
            }

            $('#IC_table').on('click', '.form-check-input[type="radio"]', function() {
                var clickedRadio = $(this);

                var urlParams = new URLSearchParams(window.location.search);
                var appraisalId = urlParams.get('appraisal_id');

                var radioButtonId = clickedRadio.attr('id');
                var questionId = radioButtonId.split('_')[1];
                var score = clickedRadio.val();
                // console.log('Question ID: ', questionId);

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{ route('saveICScores') }}',
                    type: 'POST',
                    data: {
                        questionId: questionId,
                        score: score,
                        appraisalId: appraisalId
                    },
                    success: function(response) {
                        // console.log('Score saved for question ID:', questionId);
                        clickedRadio.closest('tr').removeClass(
                            'table-danger');
                        // calculateRadioProgress(questionId)
                        calculateRadioProgress(questionId);

                        totalScore();
                        //updateProgressBar();
                    },
                    error: function(xhr) {
                        if (xhr.responseText) {
                            // console.log('Error: ' + xhr.responseText);
                        } else {
                            // console.log('An error occurred.');
                        }
                    }
                });
            });

            function loadICTable() {
                var urlParams = new URLSearchParams(window.location.search);
                var sy = urlParams.get('sy');
                sy = (sy !== null && sy.trim() !== '') ? sy : null;

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{ route('ad.getICQuestions') }}',
                    type: 'GET',
                    data: {
                        sy: sy
                    },
                    success: function(response) {
                        if (response.success) {
                            var tbody = $('#IC_table tbody');
                            tbody.empty();

                            var questionCounter = 1;

                            $.each(response.ICques, function(index, formquestions) {
                                var questionId = formquestions.question_id;

                                var row = `<tr>
                                            <td class="align-middle">${ questionCounter }</td> <!-- Display the counter -->
                                            <td class="align-baseline text-start editable" data-questionid="${ questionId }">
                                                ${ formquestions.question }
                                            </td>
                                            <td class="align-middle likert-column">
                                                @for ($i = 5; $i >= 1; $i--)
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" id="score_${ questionId }" name="ic_${ questionId }" value="{{ $i }}">
                                                        <label class="form-check-label" for="score_${ questionId }">{{ $i }}</label>
                                                    </div>
                                                @endfor
                                            </td>
                                        </tr>`;
                                tbody.append(row);
                                loadSavedScore(questionId);
                                questionCounter++;
                            });

                        } else {
                            // console.log('Error:' + response.error);
                        }
                    },
                    error: function(xhr, status, error) {
                        // console.log(error);
                    }
                });
            }

            function loadSavedScore(questionId) {
                var urlParams = new URLSearchParams(window.location.search);
                var appraisalId = urlParams.get('appraisal_id');
                var sy = urlParams.get('sy');
                sy = (sy !== null && sy.trim() !== '') ? sy : null;
                
                // console.log(appraisalId);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{ route('ad.getICScores') }}',
                    type: 'GET',
                    data: {
                        appraisalId: appraisalId,
                        questionId: questionId,
                        sy: sy,
                    },
                    success: function(savedScoreResponse) {
                        if (savedScoreResponse.success) {
                            var savedScore = savedScoreResponse.score;
                            if (savedScore !== null) {
                                $(`input[name="ic_${ questionId }"][value="${ savedScore }"]`)
                                    .prop('checked', true);
                                // calculateRadioProgress(questionId);
                            }
                        }
                        totalScore();
                        //updateProgressBar();
                    },
                    error: function(xhr, status, error) {
                        // console.log(error);
                    }
                });
            }

            function loadSignature() {
                var sy = null;

                var urlParams = new URLSearchParams(window.location.search);
                var appraisalId = urlParams.get('appraisal_id');
                sy = urlParams.get('sy');

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{ route('ad.loadICSignatures') }}',
                    type: 'GET',
                    data: {
                        sy: sy,
                        appraisalId: appraisalId,
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#signtable tbody').empty();
                            var newRow = $('<tr>').addClass('align-middle');
                            newRow.append($('<td>').text('Internal Customer'));
                            newRow.append($('<td>').text(response.full_name));

                            $('#modalImage').attr('src', response.sign_data);

                            if (response.sign_data) {
                                // console.log('Response Data Received');
                                $('#cancel-btn').hide();
                                $('#esig-submit-btn').hide();
                                newRow.append($('<td>').addClass('align-middle').html(
                                    '<button class="btn btn-outline-primary" id="view-sig-btn">' +
                                    'View Signature' +
                                    '</button>'
                                ));
                            } else {
                                // console.log('Response data not received');
                                newRow.append($('<td>').addClass('align-middle').html(
                                    '<div>' +
                                    '<input type="file" id="esig" class="form-control" accept="image/jpeg, image/png, image/jpg">' +
                                    '<img src="" width="100" id="signatureImage">' +
                                    '</div>'
                                ));
                            }

                            if (response.date_submitted) {
                                newRow.append($('<td>').text(response.date_submitted));
                            } else {
                                newRow.append($('<td>').text('-'));
                            }

                            $('#signtable tbody').append(newRow);

                        } else {
                            // console.log('fail');
                        }
                    },
                    error: function(xhr, status, error) {
                        // console.log(error);
                    }
                });
            }

            function formChecker() {
                var urlParams = new URLSearchParams(window.location.search);
                var appraisalId = urlParams.get('appraisal_id');
                var appraiseeId = urlParams.get('appraisee_account_id');

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{ route('pe.ICFormChecker') }}',
                    type: 'POST',
                    data: {
                        appraisalId: appraisalId,
                        appraiseeId: appraiseeId,
                    },
                    success: function(response) {
                        console.log(response);
                        if (response.form_submitted) {
                            $('input[type="radio"]').prop('disabled', true);
                            $('textarea').prop('disabled', true);
                            $('#confirmation-alert').addClass('d-none');
                            $('#submit-btn').text('View');
                            $('#progressBarContainer').remove();
                            $('#sendrequest').show();

                            if (response.hideSignatory) {
                                $('#submit-btn').remove();
                            }

                            if (response.hasRequest) {
                                if (response.status === 'Pending') {
                                    $('#sendrequest').removeClass('btn-primary').text('');
                                    $('#sendrequest').addClass('btn-outline-primary').text(
                                            'Request Sent').prop('disabled', true).append('<i>')
                                        .addClass('bi bi-envelope-paper');
                                } else if (response.status === 'Approved' || response.status ===
                                    'Disapproved') {
                                    // Display feedback and appropriate UI for approved or disapproved requests
                                    $('#feedback-text').text(response.feedback);

                                    // Check if approver_name and approved_at are available
                                    if (response.approver_name && response.approved_at) {
                                        const approverInfo =
                                            `Approved by ${ response.approver_name } on ${ response.approved_at }`;
                                        $('#additional-info').text(approverInfo).addClass(
                                            'font-italic');
                                    }
                                    $('#sendrequest').show();
                                    $('#feedback-container').removeClass('d-none');
                                }
                            }

                            if (!response.canRequest) {
                                $('#sendrequest').hide();
                                $('#requestText').prop('disabled', true);
                                $('#feedback-container').addClass('d-none');
                            }
                        } else {
                            if (!response.hasPermission) {
                                $('.modal').hide();
                                $('.content-body').remove();
                                $('.content-container').remove();
                            }
                            $('#sendrequest').hide();
                            $('#requestText').prop('disabled', true);
                            return;
                        }
                    },
                    error: function(xhr, status, error) {
                        // console.log(error);
                    }
                });
            }

            $(document).on('click', '#view-sig-btn', function() {
                $('#signatory_modal').modal('hide');
                $('#imageModal').modal('show');
            });

            $(document).on('click', '#esig-close-btn', function() {
                $('#imageModal').modal('hide');
                $('#signatory_modal').modal('show');
            });

            function dataURItoBlob(dataURI) {
                var byteString = atob(dataURI.split(',')[1]);
                var mimeString = dataURI.split(',')[0].split(':')[1].split(';')[0];
                var ab = new ArrayBuffer(byteString.length);
                var ia = new Uint8Array(ab);
                for (var i = 0; i < byteString.length; i++) {
                    ia[i] = byteString.charCodeAt(i);
                }
                return new Blob([ab], {
                    type: mimeString
                });
            }

            $('#submit-btn').on('click', function() {
                var totalWeightedScore = $('#total-weighted-score').val();
                // console.log('Total Weighted Score: ' + totalWeightedScore);
                $('#IC_table td').removeClass('is-invalid');
                $('#service_area, #comments_area').removeClass(
                    'is-invalid');

                var allRadioChecked = true;
                $('#IC_table tbody tr').each(function() {
                    var questionId = $(this).find('.form-check-input').attr('id').split(
                        '_')[1];
                    var anyRadioChecked = false;

                    $(this).find('.form-check-input').each(function() {
                        if ($(this).prop('checked')) {
                            anyRadioChecked = true;
                        }
                    });

                    if (!anyRadioChecked) {
                        allRadioChecked = false;
                        $(this).find('.form-check-input').closest('tr').addClass(
                            'table-danger');
                    }
                });

                var serviceValue = $('#service_area').val();
                var suggestionValue = $('#comments_area').val();
                var allTextAreasFilled = (serviceValue.trim() !== '') && (suggestionValue
                    .trim() !== '');

                if (!allTextAreasFilled) {
                    $('#service_area, #comments_area').addClass(
                        'is-invalid');
                }

                if (allRadioChecked && allTextAreasFilled) {
                    loadSignature();
                    $('#signatory_modal').modal('show');
                } else {
                    // console.log('Please complete all fields and select all radio buttons.');
                }
            });

            ////////////////////////// SEND REQUEST ////////////////////////////////
            $('#sendrequest').click(function() {
                $('#requestText').prop('disabled', false);

                // Check validity and list the results
                const validationList = $('#validation-list');
                validationList.empty();

                // Check input elements for validity
                const inputElements = $('input[disabled]'); // Exclude the textarea
                const textarea = $('textarea[required]:not(#requestText)'); // Exclude the textarea
                let invalidFields = [];

                inputElements.each(function() {
                    if (!this.checkValidity()) {
                        invalidFields.push($(this).attr('name'));
                    }
                });

                if (textarea.length > 0 && !textarea[0].checkValidity()) {
                    invalidFields.push('Request field');
                }

                if (invalidFields.length > 0) {
                    $.each(invalidFields, function(index, fieldName) {
                        validationList.append('<li>' + fieldName +
                            ' is not answered or invalid.</li>');
                    });

                    $('#validation-results').show();
                } else {
                    $('#validation-results').hide();
                }

                $('#request-popup-modal').modal('show');
            });

            $('#sendReqForm').on('submit', function(event) {
                var urlParams = new URLSearchParams(window.location.search);
                var appraisalId = urlParams.get('appraisal_id');

                event.preventDefault(); // Prevent the default form submission

                // Collect the form data
                const formData = new FormData(this);
                formData.append('appraisal_id', appraisalId);

                // Send the data to the server using AJAX
                $.ajax({
                    url: "{{ route('submitRequest') }}",
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        // Handle the server response (if needed)
                        $('#request-popup-modal').modal('hide');
                        refreshPage();
                        console.log(data);
                    },
                    error: function(error) {
                        console.error('Error:', error);
                    }
                });
            });

            $('#request-popup-modal').on('hidden.bs.modal', function() {
                // Enable checkboxes and text area when the modal is closed
                $('#requestText').prop('disabled', false).val('');
            });

            function refreshPage() {
                location.reload();
            }

            loadICTable();
            loadTextAreas();
            
            formChecker();

            setTimeout(function() {
                formChecker();
            }, 5000);

            var totalProgress = 0;

            function calculateCustomerServiceProgress() {
                return $('#service_area').val().trim() !== '' ? 100 : 0;
            }

            function calculateCommentsProgress() {
                return $('#comments_area').val().trim() !== '' ? 100 : 0;
            }

            function calculateRadioProgress(questionId) {
                // Get the total count of radio buttons in the specified group
                var totalRadioCount = $(`input[name="ic_${questionId}"]`).length;
                // console.log("totalRadioCount" + totalRadioCount);

                // Get the count of checked radio buttons in the specified group
                var checkedRadioCount = $(`input[name="ic_${questionId}"]:checked`).length;
                // console.log("checkedRadioCount" + checkedRadioCount);

                var totalQuestions = $('.form-check-input[type="radio"]').length / 5; // Assuming 5 radio buttons per question
                var totalWeights = 100; // Total weight should add up to 100%
                var weight = totalQuestions > 0 ? totalWeights / totalQuestions : 0;

                // Calculate progress per checked radio button
                var progressPerRadio = totalRadioCount > 0 ? (checkedRadioCount / totalRadioCount) * weight : 0;

                return progressPerRadio;
            }

            function calculateTotalProgress() {
                var totalRadioProgress = 0;

                // Iterate over each question
                $('#ic_form .form-check-input[type="radio"]').each(function () {
                    var questionId = $(this).attr('id').split('_')[1];
                    totalRadioProgress += calculateRadioProgress(questionId);
                });

                var customerServiceProgress = calculateCustomerServiceProgress();
                var commentsProgress = calculateCommentsProgress();

                // Add more progress calculations if needed
                // Calculate the total progress based on your criteria
                var totalProgress = (totalRadioProgress + customerServiceProgress + commentsProgress)/3; // Adjust the formula as needed

                return totalProgress;
            }

            function updateProgressBar() {
                // Calculate the total progress
                var totalProgress = Math.round(calculateTotalProgress());

                // Update the width of the progress bar
                $('#progressBar').css('width', totalProgress + '%').text(totalProgress + "%");
            }

        });
    </script>
@endsection

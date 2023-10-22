@extends('layout.master')

@section('title')
    <h1>Internal Customer Evaluation Form</h1>
@endsection

@section('content')
    <div class="content-container">
        <h3>Dear Adamson Employee,</h3>
        <p>Each department (co-academic offices) wants to know how well we are serving our employees/internal customers. We
            appreciate if you would take the time to complete this evaluation. This is a feedback mechanism to improve our
            services. Your answers will be treated with utmost confidentiality.</p>
        <p>Your opinion matters. We welcome your comments and suggestions. Please answer the questions by selecting one
            option from the provided choices using the radio buttons. This will make us aware of a problem, complaints, or
            an opportunity to make a suggestion, and to recognize or commend for a job well done.</p>

        <div class="row g-3 align-items-center">
            <div class="col-auto">
                <h5>Name of the staff to be evaluated:</h5>
            </div>
            <div class="col-auto">
                <input class="form-control" type="text" placeholder="{{ urldecode(request('appraisee_name')) }}"
                    name="name" disabled>
            </div>
        </div>

        <p>Given the following behavioral competencies, you are to assess the incumbent's performance using the scale.
            Choose each number which corresponds to your answer for each item. Please answer each item truthfully.<br>
           <b><i>5 - Almost Always 4 - Frequently 3 - Sometimes 2 - Occasionally 1 - Hardly Ever </b></i></p>
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
                            <input id="total-weighted-score" class="small-column form-control total-weighted text-center"
                                type="text" readonly>
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
           </div>

        <p>What did you like best about his/her customer service?</p>
        <textarea class="form-control" id="service_area" disabled></textarea>

        <p class="mt-3">Other comments and suggestions:</p>
        <textarea class="form-control" id="comments_area" disabled></textarea>
    </div>
    <div class="d-flex justify-content-center gap-3">
        <button type="button" class="btn btn-primary medium-column" id='view-btn'>View</button>
    </div>

    <div class="modal fade" id="signatory_modal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fs-5">Signatories</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                    <table class="table" id="signtable">
                        <thead>
                            <tr>
                                <th scope="col" style="width:20%" id="partieshead">Parties</th>
                                <th scope="col" style="width:20%" id="fullnamehead">Name</th>
                                <th scope="col" style="width:25%" id="signhead">Signature</th>
                                <th scope="col" style="width:15%" id="datehead">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="imageModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Signature Preview</h5>
                    <button type="button" class="btn-close" aria-label="Close" id="esig-close-btn"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" alt="Signature" style="max-width: 100%;">
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $(document).on('click', '#view-btn', function() {
                loadSignature();
                $('#signatory_modal').modal('show');
            });

            $(document).on('click', '#view-sig-btn', function() {
                $('#signatory_modal').modal('hide');
                $('#imageModal').modal('show');
            });

            $(document).on('click', '#esig-close-btn', function() {
                $('#imageModal').modal('hide');
                $('#signatory_modal').modal('show');
            });

            function loadSignature() {
                var urlParams = new URLSearchParams(window.location.search);
                var appraisalId = urlParams.get('appraisal_id');
                var sy = urlParams.get('sy');

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
                                newRow.append($('<td>').addClass('align-middle').html(
                                    '<button type="button" class="btn btn-outline-primary" id="view-sig-btn">' +
                                    'View Signature' +
                                    '</button>'
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

            function loadICTable() {
                var urlParams = new URLSearchParams(window.location.search);
                var sy = urlParams.get('sy');

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

                                var row = $('<tr>');
                                row.append($('<td>').addClass('align-middle').text(
                                    questionCounter));
                                row.append($('<td>').addClass(
                                    'align-baseline text-start editable').attr(
                                    'data-questionid', questionId).text(formquestions
                                    .question));

                                var likertColumn = $('<td>').addClass(
                                    'align-middle likert-column');
                                for (let i = 5; i >= 1; i--) {
                                    var formCheckDiv = $('<div>').addClass(
                                        'form-check form-check-inline');
                                    var input = $('<input>').addClass('form-check-input').attr({
                                        type: 'radio',
                                        id: `score_${questionId}_${i}`,
                                        name: `ic_${questionId}`,
                                        value: i
                                    }).prop('disabled', true);
                                    var label = $('<label>').addClass('form-check-label').attr(
                                        'for', `score_${questionId}_${i}`).text(i);

                                    formCheckDiv.append(input).append(label);
                                    likertColumn.append(formCheckDiv);
                                }
                                row.append(likertColumn);

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
                                $(`input[name="ic_${questionId}"][value="${savedScore}"]`).prop(
                                    'checked', true);
                            }
                        } else {
                            // console.log('Failed');
                        }
                        totalScore();
                    },
                    error: function(xhr, status, error) {
                        // console.log(error);
                    }
                });
            }

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

            loadICTable();
            loadTextAreas();

        });
    </script>
@endsection

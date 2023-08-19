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

        <h5>Name of the staff to be evaluated: <i><u>{{ urldecode(request('appraisee_name')) }}</u></i></h5>

        <p>Given the following behavioral competencies, you are to assess the incumbent's performance using the scale.
            Choose each number which corresponds to your answer for each item. Please answer each item truthfully.<br>
            5 - Almost Always 4 - Frequently 3 - Sometimes 2 - Occasionally 1 - Hardly Ever</p>

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

        <p>What did you like best about his/her customer service?</p>
        <textarea class="form-control" id="service_area"></textarea>

        <p class="mt-3">Other comments and suggestions:</p>
        <textarea class="form-control" id="comments_area"></textarea>
    </div>
    <div class="d-flex justify-content-center gap-3">
        <button type="submit" class="btn btn-primary medium-column" id="submit-btn">Submit</button>
    </div>

    <script>
        $(document).ready(function() {

            $('#service_area').on('blur', function() {
                var newService = $(this).val();
                updateService(newService);
            });

            $('#comments_area').on('blur', function() {
                var newSuggestion = $(this).val();

                updateSuggestion(newSuggestion);
            });

            function updateSuggestion(value) {
                var urlParams = new URLSearchParams(window.location.search);
                var appraisalId = urlParams.get('appraisal_id');

                $.ajax({
                    url: '{{ route('updateSuggestion') }}',
                    type: 'POST',
                    data: {
                        newSuggestion: value,
                        appraisalId: appraisalId
                    },
                    success: function(response) {
                        console.log('Backend updated successfully.');
                        $('#comments_area').removeClass('is-invalid');
                    },
                    error: function(xhr) {
                        if (xhr.responseText) {
                            console.log('Error: ' + xhr.responseText);
                        } else {
                            console.log('An error occurred.');
                        }
                    }
                });
            }

            function updateService(value) {
                console.log(value);
                var urlParams = new URLSearchParams(window.location.search);
                var appraisalId = urlParams.get('appraisal_id');

                $.ajax({
                    url: '{{ route('updateService') }}',
                    type: 'POST',
                    data: {
                        newService: value,
                        appraisalId: appraisalId
                    },
                    success: function(response) {
                        console.log('Backend updated successfully.');
                        $('#service_area').removeClass('is-invalid');
                    },
                    error: function(xhr) {
                        if (xhr.responseText) {
                            console.log('Error: ' + xhr.responseText);
                        } else {
                            console.log('An error occurred.');
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
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{ route('getCommentsAndSuggestions') }}',
                    type: 'POST',
                    data: {
                        appraisalId: appraisalId
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#service_area').val(response.customerService);
                            $('#comments_area').val(response.suggestion);
                        } else {
                            console.log('Comments not found or an error occurred.');
                        }
                    },
                    error: function(xhr) {
                        if (xhr.responseText) {
                            console.log('Error: ' + xhr.responseText);
                        } else {
                            console.log('An error occurred.');
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
                console.log('Question ID: ', questionId);

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
                        console.log('Score saved for question ID:', questionId);
                        clickedRadio.closest('tr').removeClass(
                            'table-danger');
                        totalScore();
                    },
                    error: function(xhr) {
                        if (xhr.responseText) {
                            console.log('Error: ' + xhr.responseText);
                        } else {
                            console.log('An error occurred.');
                        }
                    }
                });
            });


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            function loadICTable() {
                $.ajax({
                    url: '/editable-internal-customer-form/getICQuestions',
                    type: 'GET',
                    success: function(response) {
                        if (response.success) {
                            var tbody = $('#IC_table tbody');
                            tbody.empty();

                            var questionCounter = 1;

                            $.each(response.ICques, function(index, formquestions) {
                                var questionId = formquestions.question_id;

                                var row = `<tr>
                        <td class="align-middle">${questionCounter}</td> <!-- Display the counter -->
                        <td class="align-baseline text-start editable" data-questionid="${questionId}">
                            ${formquestions.question}
                        </td>
                        <td class="align-middle likert-column">
                            @for ($i = 5; $i >= 1; $i--)
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="score_${questionId}" name="ic_${questionId}" value="{{ $i }}">
                                    <label class="form-check-label" for="score_${questionId}">{{ $i }}</label>
                                </div>
                            @endfor
                        </td>
                    </tr>`;

                                tbody.append(row);
                                loadSavedScore(questionId);

                                questionCounter++;
                            });

                        } else {
                            console.log('Error:' + response.error);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                });
            }

            function loadSavedScore(questionId) {
                var urlParams = new URLSearchParams(window.location.search);
                var appraisalId = urlParams.get('appraisal_id');

                $.ajax({
                    url: '{{ route('getSavedICScores') }}',
                    type: 'GET',
                    data: {
                        appraisalId: appraisalId,
                        questionId: questionId
                    },
                    success: function(savedScoreResponse) {
                        if (savedScoreResponse.success) {
                            var savedScore = savedScoreResponse.score;
                            if (savedScore !== null) {
                                $(`input[name="ic_${questionId}"][value="${savedScore}"]`).prop(
                                    'checked', true);
                            }
                        }
                        totalScore();
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                });
            }

            $('#submit-btn').on('click', function() {
                $('#IC_table td').removeClass('is-invalid');
                $('#service_area, #comments_area').removeClass(
                'is-invalid');

                var allRadioChecked = true;
                $('#IC_table tbody tr').each(function() {
                    var questionId = $(this).find('.form-check-input').attr('id').split('_')[1];
                    var anyRadioChecked = false;

                    $(this).find('.form-check-input').each(function() {
                        if ($(this).prop('checked')) {
                            anyRadioChecked = true;
                        }
                    });

                    if (!anyRadioChecked) {
                        allRadioChecked = false;
                        $(this).find('.form-check-input').closest('tr').addClass('table-danger');
                    }
                });

                var serviceValue = $('#service_area').val();
                var suggestionValue = $('#comments_area').val();
                var allTextAreasFilled = (serviceValue.trim() !== '') && (suggestionValue.trim() !== '');

                if (!allTextAreasFilled) {
                    $('#service_area, #comments_area').addClass(
                    'is-invalid');
                }

                if (allRadioChecked && allTextAreasFilled) {
                  // DITO IOOPEN MODAL
                } else {
                    console.log('Please complete all fields and select all radio buttons.');
                }
            });

            loadICTable();
            loadTextAreas();
        });
    </script>
@endsection

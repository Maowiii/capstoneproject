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
                <!-- Data ng Editable Internal Customer Form -->
            </tbody>
        </table>
        <div class="d-flex justify-content-end">
            <button type="button" class="btn btn-primary" id="addQuestionBtn">Add Question</button>
        </div>

        <p>What did you like best about his/her customer service?</p>
        <textarea name="best_service" id="best_service"></textarea>

        <p>Other comments and suggestions:</p>
        <textarea name="comments_suggestions" id="comments_suggestions"></textarea>

    </div>
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            function loadICQuestionTable() {
                $.ajax({
                    url: '/editable-internal-customer-form/getICQuestions',
                    type: 'GET',
                    success: function(response) {
                        if (response.success) {
                            var tbody = $('#IC_table tbody');
                            tbody.empty();

                            $.each(response.ICques, function(index, formquestions) {
                                // Create table row using evalyear data
                                var row = `<tr>
                                    <td class="align-middle">${index + 1}</td>
                                    <td class="align-baseline text-start editable" contenteditable="true" data-questionid="${formquestions.question_id}">
                                        ${formquestions.question}
                                    </td>
                                    <td class="align-middle likert-column">
                                        

                                    </td>
                                </tr>`;

                                tbody.append(row); // Append new row to tbody
                            });
                        } else {
                            console.log(response.error); // Handle error response
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(error); // Handle Ajax error
                    }
                });
            }

            // Initial loading of the IC form table
            loadICQuestionTable();
        });
    </script>
@endsection

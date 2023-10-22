@extends('layout.master')

@section('title')
    <h1>Editable Internal Customer Form</h1>
@endsection

@section('content')
    <div class="content-container">
        <h2>Dear Adamson Employee,</h2>
        <p>Each department (co-academic offices) wants to know how well we are serving our employees/ internal customers. We
            appreciate if you would take the time to complete this evaluation. This is a feedback mechanism to improve our
            services. Your answers will be treated with utmost confidentiality.</p>
        <p>Your opinion matters. We welcome your comments and suggestions. Please answer the questions by selecting one
            option from the provided choices using the radio buttons. To select an option, click on the circle next to the
            answer that best fits your response. Only one option can be selected for each question. This will make us aware
            of a problem, complaints, or an opportunity to make a suggestion, and to recognize or commend for a job well
            done.</p>
        <p>Given the following behavioral competencies, you are to assess the incumbent's performance using the scale.
            Choose each number which corresponds to your answer for each item. Please answer each item truthfully.<br>
            5 - Almost Always 4 - Frequently 3 - Sometimes 2 - Occasionally 1 - Hardly Ever</p>
            <div class="table-responsive">
        <table class="table table-bordered" id="IC_table">
            <thead>
                <tr>
                    <th class="small-column">#</th>
                    <th>Question</th>
                    <th class="small-column">Action</th>
                </tr>
            </thead>
            <tbody>
                <!-- Data ng Editable Internal Customer Form -->
            </tbody>
        </table>
            </div>
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
            // Declare questionId variable globally
            let questionId;

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            function editableFormChecker() {
                $.ajax({
                    url: '{{ route('ad.formChecker') }}',
                    type: 'GET',
                    success: function(response) {
                        // console.log(response);
                        if (response.formLocked == true) {
                            $('.content-container button').prop('disabled', true);
                        }
                    },
                    error: function(xhr, status, error) {
                        var errorMessage = xhr.responseJSON && xhr.responseJSON.error ? xhr
                            .responseJSON.error : 'An error occurred.';
                        // console.log(errorMessage);
                    }
                });
            }

            // Event listener for add question button
            document.querySelector('#addQuestionBtn').addEventListener('click', addRow);

            // Function to load and update the evaluation year table
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
                                            <td class="align-middle">
                                                <button type="button" class="btn btn-outline-danger delete-button">Delete</button>
                                            </td>
                                            </tr>`;

                                tbody.append(row); // Append new row to tbody
                            });
                        } else {
                            // console.log(response.error); // Handle error response
                        }
                    },
                    error: function(xhr, status, error) {
                        // console.log(error); // Handle Ajax error
                    }
                });
            }

            // Initial loading of the IC form table
            loadICQuestionTable();
            editableFormChecker();


            // Event listener for focusout event
            $('#IC_table').on('focusout', '.editable:not(.new-row)', function() {
                const cell = $(this);
                const originalValue = cell.data('originalvalue');
                const newValue = cell.text().trim();
                const row = $(this).closest('tr');
                questionId = row.find('.editable').data('questionid'); // Assign the questionId globally

                if (newValue !== originalValue) {
                    const url = '/editable-internal-customer-form/updateICQuestions/' + questionId;

                    const data = {
                        question: newValue,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    };

                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: data,
                        success: function(response) {
                            console.log(response); // Handle success response
                        },
                        error: function(xhr, status, error) {
                            var errorMessage = xhr.responseJSON && xhr.responseJSON.error ? xhr
                                .responseJSON.error : 'An error occurred.';
                            console.log(errorMessage);
                        }
                    });
                }
            });

            $('#IC_table').on('click', '.delete-button', function(event) {
                event.stopPropagation();
                const row = $(this).closest('tr');
                questionId = row.find('.editable').data('questionid'); // Get the questionId from the row
                deleteRow(row, questionId);
            });

            function deleteRow(row, questionId) {
                if (confirm(
                        `Are you sure you want to delete this question?\n\n${row.find('.editable').text().trim()}`
                    )) {
                    var url = `/editable-internal-customer-form/deleteICQuestions/${questionId}`;

                    const token = $('meta[name="csrf-token"]').attr('content');
                    const data = {
                        _token: token,
                        status: 'inactive'
                    };

                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: data,
                        success: function(response) {
                            if (response.success) {
                                row.remove();
                                console.log(response.message);
                            } else {
                                console.error(response.error);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error deleting question:', error);
                        }
                    });
                }
            }

            function addRow() {
                const table = document.querySelector('table');
                const newRow = table.insertRow(-1);
                const numCells = table.rows[0].cells.length;

                const lastRowNumber = parseInt(table.rows[table.rows.length - 2].cells[0].textContent);
                const newRowNumber = lastRowNumber + 1;

                // Retrieve the CSRF token
                const token = $('meta[name="csrf-token"]').attr('content');

                for (let i = 0; i < numCells; i++) {
                    const newCell = newRow.insertCell(i);

                    if (i === 0) {
                        newCell.innerText = newRowNumber;
                        newCell.style.textAlign = 'center';
                    } else if (i === 1) {
                        newCell.contentEditable = true;
                        newCell.classList.add('editable');
                        newCell.style.textAlign = 'left';

                        newCell.addEventListener('focusout', () => {
                            const newValue = newCell.innerText.replace(/\s+/g, ' ');

                            // Send an AJAX request to add the new question
                            $.ajax({
                                url: '/editable-internal-customer-form/addICQuestions',
                                type: 'POST',
                                data: {
                                    question: newValue,
                                    _token: token
                                },
                                success: function(response) {
                                    if (response.success) {
                                        console.log(response.message);
                                        questionId = response
                                            .question_id; // Assign the questionId globally
                                        newCell.dataset.questionid = questionId;
                                    } else {
                                        console.error(response.error);
                                    }
                                },
                                error: function(xhr, status, error) {
                                    console.error('Error adding question:', error);
                                }
                            });
                        });
                    } else if (i === 2) {
                        const deleteBtn = document.createElement('button');
                        deleteBtn.type = 'button';
                        deleteBtn.classList.add('btn', 'btn-outline-danger', 'delete-button');
                        deleteBtn.innerText = 'Delete';
                        deleteBtn.dataset.questionid = ''; // Add the data-questionid attribute

                        newCell.appendChild(deleteBtn);
                        newCell.style.textAlign = 'center';
                    }
                }
            }
        });
    </script>
@endsection

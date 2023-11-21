@extends('layout.master')

@section('title')
    <h1>Editable Appraisal Form</h1>
@endsection

@section('content')
    <div class='content-container'>
        <h2>Instructions</h2>
        <p class='text-justify'>This performance appraisal is designed to improve organizational effectiveness and to assist
            the job incumbent in
            his/her job performance as well as his/her professional growth and development. Please take time to evaluate the
            job incumbent by completing this evaluation form. Please be reflective and candid in your responses. Kindly
            submit the accomplished form to HRMD on or before the deadline. Your cooperation is highly appreciated. Thank
            you.</p>
    </div>
    <div class="content-container">
        <h2>I. Behavioral Competencies</h2>
        <p>Given the following behavioral competencies, you are to assess the incumbent's performance using the scale. Put
            No. 1 on the number which corresponds to your answer for each item. Please answer each item truthfully.

            5 - Almost Always 4 - Frequently 3 - Sometimes 2 - Occasionally 1 - Hardly Ever</p>
        <h3>Core Values</h3>
        <h4>Search for Excellence</h4>
        <p>The highest standards of academic excellence and professionalism in service are the hallmarks of our educative
            endeavors. We regularly assess and transform our programs to make them effective for leaning, discovery of
            knowledge and community service. Our service ethics manifest strong sense of responsibility, competency,
            efficiency and professional conduct.</p>
        <h4>Sustained Integral Development</h4>
        <p>Education is a lifelong quest whose primary purpose is the full and integral development of the human person. We
            are committed to provide programs for holistic development and continuous learning. Networking with other
            educational institutions, government agencies, industries, business and other groups enhances our educational
            services.</p>
        <div class="table-responsive">
            <table class='table table-bordered' id="SID">
                <thead>
                    <tr>
                        <th class='small-column'>#</th>
                        <th>Question</th>
                        <th class='small-column'>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data ng First Table (Sustained Integral) -->

                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-end">
            <button type="button" class="btn btn-primary" id="addSIDQuestionBtn">Add Question</button>
        </div>

        <h4>Spirit of St. Vincent de Paul</h4>
        <p>The spirit of St. Vincent inspires and permeates our learning community, programs and services. This is shown in
            our sensitivity to the presence of God, compassionate service and the building of supportive relationships
            towards an effective service to persons in need.</p>
        <h4>Social Responsibility</h4>
        <p>Education at Adamson aims at developing a sense of social responsibility - a mark of an authentic Christian
            faith. Social responsibility leads us to empower the marginalized sectors of society through the creation of
            knowledge and human development. We are committed to work for the building of a society based on justice, peace,
            respect for human dignity and the integrity of creation.</p>

        <div class="table-responsive">
            <table class='table table-bordered' id="SR">
                <thead>
                    <tr>
                        <th class='small-column'>#</th>
                        <th>Question</th>
                        <th class='small-column'>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data ng Second Table (Social Responsibility) -->

                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-end">
            <button type="button" class="btn btn-primary" id="addSRQuestionBtn">Add Question</button>
        </div>

        <h4>Solidarity</h4>
        <p>Drawn together by a common vision and mission, we believe education is a shared responsibility and a
            collaborative effort where the gifts of persons are valued. Our learning community is a "family" where
            participation, team work, interdependence, communication and dialogue prevail. A culture of appreciation builds
            up our community, encouraging us towards excellence and professionalism.</p>

        <div class="table-responsive">
            <table class='table table-bordered' id="S">
                <thead>
                    <tr>
                        <th class='small-column'>#</th>
                        <th>Question</th>
                        <th class='small-column'>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data ng Second Table (SOLIDARITY) -->

                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-end">
            <button type="button" class="btn btn-primary" id="addSQuestionBtn">Add Question</button>
        </div>
    </div>
    <div class="content-container">
        <h2>II. Key Results Areas & Work Objectives</h2>
        <p>Please review each Key Results Area (KRA) and Work Objectives (WO) of job incumbent and compare such with his/her
            actual outputs. Finally, indicate the degree of output using the Likert-Scale below.</p>
        <div class="table-responsive">
            <table class='table table-bordered'>
                <thead>
                    <tr>
                        <th class='large-column'>Accomplishment Level</th>
                        <th colspan="2">Description</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>5</td>
                        <td>Oustanding Performance</td>
                        <td class='text-justify'>Performance far exceeds the standard expected of a job holder at this
                            level.
                            The review/assessment indicates that the job holder has achieved greater than fully effective
                            results against all of the performance criteria and indicators as specified in the Performance
                            Agreement and Work plan. Maintained this in all areas of responsibility throughout the
                            performance
                            cycle.</td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>Performance significantly above expectations</td>
                        <td class='text-justify'>Performance fully meets the standards expected for the job. The
                            review/assessment indicates that the job holder has achieved better than fully effective results
                            against more than half of the performance criteria and indicators as specified in the
                            Performance
                            Agreement and Work plan.</td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Performance fully effective (and slightly above expectations)</td>
                        <td class='text-justify'>Performance fully meets the standards expected in all areas of the job. The
                            review/assessment indicates that the job holder has achieved as a minimum effective results
                            against
                            all of the performance criteria and indicators as specified in the Performance Agreement and
                            Work
                            plan.</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Performance not fully effective</td>
                        <td class='text-justify'>Performance meets some of the standards expected for the job. The
                            review/assessment indicates that the job holder has achieved less than fully effective results
                            against more than half of the performance criteria and indicators as specified in the
                            Performance
                            Agreement and Work plan.</td>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>Unacceptable performance</td>
                        <td class='text-justify'>Performance does not meet the standard expected for the job. The
                            review/assessment indicates that the job holder has achieved less than fully effective results
                            against almost all of the performance criteria and indicators as specified in Performance
                            Agreement
                            and Work plan. </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="table-responsive">
            <table class='table table-bordered'>
                <thead>
                    <tr>
                        <th class='large-column'>KRA</th>
                        <th class='medium-column'>KRA Weight</th>
                        <th class='large-column'>Objectives</th>
                        <th class='large-column'>Performance Indicators</th>
                        <th class='large-column'>Actual Results</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div class="content-container">
        <h2>III. Future Performance Agenda</h2>
        <h3>Work Performance Plans</h3>
        <p>Identify work behaviors that the job incumbent needs to:</p>
        <div class="table-responsive">
            <table class='table table-bordered'>
                <thead>
                    <tr>
                        <th>Continue Doing</th>
                        <th>Stop Doing</th>
                        <th>Start Doing</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class='td-textarea'>
                            <textarea class='textarea'disabled></textarea>
                        </td>
                        <td class='td-textarea'>
                            <textarea class='textarea'disabled></textarea>
                        </td>
                        <td class='td-textarea'>
                            <textarea class='textarea'disabled></textarea>
                        </td>

                    </tr>
                </tbody>
            </table>
        </div>

        <h3>Learning Development Plans</h3>
        <p>Identify the learning needs of the job incumbent likewise recommend specific learning methodologies for each need
            that you have mentioned.</p>
        <div class="table-responsive">
            <table class='table table-bordered'>
                <thead>
                    <tr>
                        <th>Learning Need</th>
                        <th>Methodology</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class='td-textarea'>
                            <textarea class='textarea'disabled></textarea>
                        </td>
                        <td class='td-textarea'>
                            <textarea class='textarea'disabled></textarea>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="content-container">
        <h2>IV. Job Incumbent's Comments</h2>
        <div class="table-responsive">
            <table class='table table-bordered'>
                <thead>
                    <tr>
                        <th class='medium-column'>Question</th>
                        <th class='small-column'>Answer</th>
                        <th class='large-column'>Comments</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class='text-justify'>I agree with my performance rating.</td>
                        <td>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="RateinlineRadioOptions"
                                    id="inlineRadio1" value="option1">
                                <label class="form-check-label" for="inlineRadio1">Yes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="RateinlineRadioOptions"
                                    id="inlineRadio2" value="option2">
                                <label class="form-check-label" for="inlineRadio2">No</label>
                            </div>
                        </td>
                        <td class='td-textarea'>
                            <textarea class='textarea'disabled></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td class='text-justify'>My future work objectives and learning opportunities have been set for the
                            next
                            review period.</td>
                        <td>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="ObjectiveinlineRadioOptions"
                                    id="inlineRadio1" value="option1">
                                <label class="form-check-label" for="inlineRadio1">Yes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="ObjectiveinlineRadioOptions"
                                    id="inlineRadio2" value="option2">
                                <label class="form-check-label" for="inlineRadio2">No</label>
                            </div>
                        </td>
                        <td class='td-textarea'>
                            <textarea class='textarea'disabled></textarea>
                        </td>

                    </tr>
                    <tr>
                        <td class='text-justify'>I am satisfied with the performance review discussion.</td>
                        <td>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="RevDisinlineRadioOptions"
                                    id="inlineRadio1" value="option1">
                                <label class="form-check-label" for="inlineRadio1">Yes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="RevDisinlineRadioOptions"
                                    id="inlineRadio2" value="option2">
                                <label class="form-check-label" for="inlineRadio2">No</label>
                            </div>
                        </td>
                        <td class='td-textarea'>
                            <textarea class='textarea'disabled></textarea>
                        </td>

                    </tr>
                    <tr>
                        <td class='text-justify'>I am satisfied with the performance review process</td>
                        <td>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="RevProinlineRadioOptions"
                                    id="inlineRadio1" value="option1">
                                <label class="form-check-label" for="inlineRadio1">Yes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="RevProinlineRadioOptions"
                                    id="inlineRadio2" value="option2">
                                <label class="form-check-label" for="inlineRadio2">No</label>
                            </div>
                        </td>
                        <td class='td-textarea'>
                            <textarea class='textarea' disabled></textarea>
                        </td>

                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Declare questionId variable globally
            let questionId;

            // Function to load and update the evaluation year table
            function loadAppraisalQuestionTable() {
                $.ajax({
                    url: '/editable-appraisal-form/getAppraisalQuestions',
                    type: 'GET',
                    success: function(response) {
                        if (response.success) {
                            populateTable('SID', response.SID);
                            populateTable('SR', response.SR);
                            populateTable('S', response.S);
                        } else {
                            // console.log(response.error); // Handle error response
                        }
                    },
                    error: function(xhr, status, error) {
                        var errorMessage = xhr.responseJSON && xhr.responseJSON.error ? xhr
                            .responseJSON.error : 'An error occurred.';
                        // console.log(errorMessage);
                    }
                });
            }

            function editableFormChecker() {
                $.ajax({
                    url: '{{ route('ad.formChecker') }}',
                    type: 'GET',
                    success: function(response) {
                        console.log(response);

                        if (response.formLocked == true) {
                            const currentDate = new Date();
                            const kraStartDate = new Date(response.kra_start);
                            const newEvalYearStartDate = new Date(response.new_eval_year_start);

                            // Check if the current date is after new eval year start or before kra_start
                            if (currentDate > newEvalYearStartDate || currentDate < kraStartDate) {
                                // Allow editing
                                $('.editable').prop('contenteditable', true);
                                $('.content-container button').prop('disabled', false);
                                $('.content-container .delete-button').prop('disabled', false);
                            } else {
                                // Disable editing
                                $('.editable').prop('contenteditable', false);
                                $('.content-container button').prop('disabled', true);
                                $('.content-container .delete-button').prop('disabled', true);
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        var errorMessage = xhr.responseJSON && xhr.responseJSON.error ? xhr
                            .responseJSON.error : 'An error occurred.';
                        // console.log(errorMessage);
                    }
                });
            }

            function populateTable(tableId, questions) {
                var tbody = $('#' + tableId + ' tbody');
                tbody.empty();

                $.each(questions, function(index, formquestion) {
                    var row = `<tr>
                    <td class="align-middle">${index + 1}</td>
                    <td class="align-baseline text-start editable" contenteditable="true" data-questionid="${formquestion.question_id}" data-originalvalue="${formquestion.question}">
                        ${formquestion.question}
                    </td>
                    <td class="align-middle">
                        <button type="button" class="btn btn-outline-danger delete-button">Delete</button>
                    </td>
                </tr>`;

                    tbody.append(row);
                });
            }


            loadAppraisalQuestionTable();
            editableFormChecker();

            $('table').on('focusout', '.editable:not(.new-row)', function() {
                const cell = $(this);
                const originalValue = cell.data('originalvalue');
                const newValue = cell.text().trim();
                const row = $(this).closest('tr');
                const questionId = row.find('.editable').data('questionid');

                if (originalValue !== undefined && newValue !== originalValue) {
                    const url = '/editable-appraisal-form/updateAppraisalQuestions/' + questionId;

                    const data = {
                        question: newValue,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    };

                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: data,
                        success: function(response) {
                            // Handle success response
                        },
                        error: function(xhr, status, error) {
                            var errorMessage = xhr.responseJSON && xhr.responseJSON.error ? xhr
                                .responseJSON.error : 'An error occurred.';
                            // Handle error response
                        }
                    });
                }
            });


            $('table').on('click', '.delete-button', function(event) {
                event.stopPropagation();
                const row = $(this).closest('tr');
                questionId = row.find('.editable').data('questionid'); // Get the questionId from the row
                deleteRow(row, questionId);
            });

            function deleteRow(row, questionId) {
                if (confirm(
                        `Are you sure you want to delete this question?\n\n${row.find('.editable').text().trim()}`
                    )) {
                    var url = `/editable-appraisal-form/deleteAppraisalQuestions/${questionId}`;

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
                                // console.log(response.message);
                            } else {
                                // console.error(response.error);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error deleting question:', error);
                        }
                    });
                }
            }

            // Function to add a new row to the specified table
            function addRowToTable(tableId, tableInitials) {
                const table = document.getElementById(tableId);
                const newRow = table.insertRow(-1);
                const numCells = table.rows[0].cells.length;
                const newRowNumber = table.rows.length - 1;
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

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
                                url: '/editable-appraisal-form/addAppraisalQuestions',
                                type: 'POST',
                                data: {
                                    question: newValue,
                                    table_initials: tableInitials,
                                    _token: token
                                },
                                success: function(response) {
                                    if (response.success) {
                                        // console.log(response.message);
                                        const questionId = response.question_id;
                                        newCell.dataset.questionid = questionId;
                                        // console.log(response);
                                    } else {
                                        // console.error(response.error);
                                    }
                                },
                                error: function(xhr, status, error) {
                                    // console.error('Error adding question:', error);
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

            // Add event listeners to the "Add Question" buttons
            document.getElementById('addSIDQuestionBtn').addEventListener('click', () => {
                addRowToTable('SID', 'SID');
            });

            document.getElementById('addSRQuestionBtn').addEventListener('click', () => {
                addRowToTable('SR', 'SR');
            });

            document.getElementById('addSQuestionBtn').addEventListener('click', () => {
                addRowToTable('S', 'S');
            });

        });
    </script>
@endsection

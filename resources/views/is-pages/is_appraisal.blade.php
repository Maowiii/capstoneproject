@extends('layout.master')

@section('title')
    <h1>Appraisal Form</h1>
@endsection

@section('content')
    <!-- Modal -->
    <div class="modal fade" id="consentForm" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staicBackdropLabel">Consent Form</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Understood</button>
                </div>
            </div>
        </div>
    </div>

    <div class="content-container">
        <h2>Employee Information</h2>
        <div class="row g-3 align-items-center mb-3">
            <div class="col-auto">
                <label class="col-form-label">Last Name:</label>
            </div>
            <div class="col-auto">
                <input type="text" class="form-control" value="{{ $appraisee->last_name }}" readonly>
            </div>
            <div class="col-auto">
                <label class="col-form-label">First Name:</label>
            </div>
            <div class="col-auto">
                <input type="text" class="form-control" value="{{ $appraisee->first_name }}" readonly>
            </div>
        </div>
        <div class="row g-3 align-items-center mb-3">
            <div class="col-auto">
                <label class="col-form-label">Job Title:</label>
            </div>
            <div class="col-auto">
                <input type="text" class="form-control" value="{{ $appraisee->job_title }}" readonly>
            </div>
            <div class="col-auto">
                <label class="col-form-label">Department:</label>
            </div>
            <div class="col-auto">
                <input type="text" class="form-control" value="{{ $appraisee->department->department_name }}" readonly>
            </div>
        </div>
        <div class="row g-3 align-items-center mb-3">
            <div class="col-auto">
                <label class="col-form-label">Immediate Superior's Name:</label>
            </div>
            <div class="col-auto">
                <input type="text" class="form-control"
                    value="{{ $appraisee->immediateSuperior->first_name ?? 'N/A' }} {{ $appraisee->immediateSuperior->last_name ?? '' }}"
                    readonly>
            </div>
        </div>
        <div class="row g-3 align-items-center mb-3">
            <div class="col-auto">
                <label class="col-form-label">Immediate Superior's Position:</label>
            </div>
            <div class="col-auto">
                <input type="text" class="form-control" value="{{ $appraisee->immediateSuperior->position ?? 'N/A' }}"
                    readonly>
            </div>
        </div>
    </div>

    <form method="post" action="{{ route('saveISAppraisal') }}" enctype="multipart/form-data" class="needs-validation">
        <input type="hidden" value="{{ $appraisalId }}" name="appraisalID">

        <div class='content-container'>
            <h2>Instructions</h2>
            <p clas='text-justify'>This performance appraisal is designed to improve organizational effectiveness and to
                assist
                the job incumbent in
                hi/her job performance as well as his/her professional growth and development. Please take time to evaluate
                the
                job incumbent by completing this evaluation form. Please be reflective and candid in your responses. Kindly
                sumit the accomplished form to HRMD on or before the deadline. Your cooperation is highly appreciated.
                Thank
                you.</p>
        </div>
        <div class="content-container">
            <h2>I. Behavioral Competencies</h2>
            <p>Givn the following behavioral competencies, you are to assess the incumbent's performance using the scale.
                Put
                No. 1 on the number which corresponds to your answer for each item. Please answer each item truthfully.<br>

                5 - Almost Always 4 - Frequently 3 - Sometimes 2 - Occasionally 1 - Hardly Ever</p>
            <h3>Core Values</h3>
            <h4>Serch for Excellence</h4>
            <p>The highest standards of academic excellence and professionalism in service are the hallmarks of our
                educative
                eneavors. We regularly assess and transform our programs to make them effective for leaning, discovery of
                knowledge and community service. Our service ethics manifest strong sense of responsibility, competency,
                efficiency and professional conduct.</p>
            <h4>Sutained Integral Development</h4>
            <p>Education is a lifelong quest whose primary purpose is the full and integral development of the human person.
                We
                ar committed to provide programs for holistic development and continuous learning. Networking with other
                educational institutions, government agencies, industries, business and other groups enhances our
                educational
                sevices.</p>
            @csrf
            <table class='table table-bordered' id="SID_table">
                <thead>
                    <tr>
                        <th class='extra-small-column'>#</th>
                        <th>Question</th>
                        <th>Performance Level</th>
                    </tr>
                    </head>
                <tbody id='SID_table_body'>
                </tbody>
                <tfoot>
                    <tr>
                        <td></td>
                        <td class='text-right'>Frequency:</td>
                        <td>
                            <div class="d-flex justify-content-center gap-2">
                                <div class="col-auto">
                                    <input class="xxs-column form-control frequency-counter-5 text-center" type="text"
                                        readonly>
                                </div>
                                <div class="col-auto">
                                    <input class="xxs-column form-control frequency-counter-4 text-center" type="text"
                                        readonly>
                                </div>
                                <div class="col-auto">
                                    <input class="xxs-column form-control frequency-counter-3 text-center" type="text"
                                        readonly>
                                </div>
                                <div class="col-auto">
                                    <input class="xxs-column form-control frequency-counter-2 text-center" type="text"
                                        readonly>
                                </div>
                                <div class="col-auto">
                                    <input class="xxs-column form-control frequency-counter-1 text-center" type="text"
                                        readonly>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class='text-right'>Total:</td>
                        <td>
                            <div class="d-flex justify-content-center gap-3">
                                <input class="small-column form-control total-frequency text-center" type="text"
                                    readonly>
                            </div>
                        </td>
                    </tr>
                </tfoot>
            </table>

            <h4>Spirit of St. Vincent de Paul</h4>
            <p>The spirit of St. Vincent inspires and permeates our learning community, programs and services. This is shown
                in
                our sensitivity to the presence of God, compassionate service and the building of supportive relationships
                towards an effective service to persons in need.</p>
            <h4>Social Responsibility</h4>
            <p>Education at Adamson aims at developing a sense of social responsibility - a mark of an authentic Christian
                faith. Social responsibility leads us to empower the marginalized sectors of society through the creation of
                knowledge and human development. We are committed to work for the building of a society based on justice,
                peace,
                respect for human dignity and the integrity of creation.</p>

            <table class='table table-bordered' id="SR_table">
                <thead>
                    <tr>
                        <th class='extra-small-column'>#</th>
                        <th>Question</th>
                        <th class='small-column'>Action</th>
                    </tr>
                </thead>
                <tbody id="SR_table_body">
                    <!-- CONTENT -->

                </tbody>
                <tfoot>
                    <tr>
                        <td></td>
                        <td class='text-right'>Frequency:</td>
                        <td>
                            <div class="d-flex justify-content-center gap-3">
                                <div class="col-auto">
                                    <input class="xxs-column form-control frequency-counter-5 text-center" type="text"
                                        readonly>
                                </div>
                                <div class="col-auto">
                                    <input class="xxs-column form-control frequency-counter-4 text-center" type="text"
                                        readonly>
                                </div>
                                <div class="col-auto">
                                    <input class="xxs-column form-control frequency-counter-3 text-center" type="text"
                                        readonly>
                                </div>
                                <div class="col-auto">
                                    <input class="xxs-column form-control frequency-counter-2 text-center" type="text"
                                        readonly>
                                </div>
                                <div class="col-auto">
                                    <input class="xxs-column form-control frequency-counter-1 text-center" type="text"
                                        readonly>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class='text-right'>Total:</td>
                        <td>
                            <div class="d-flex justify-content-center gap-3">
                                <input class="small-column form-control total-frequency text-center" type="text"
                                    readonly>
                            </div>
                        </td>
                    </tr>
                </tfoot>
            </table>

            <h4>Solidarity</h4>
            <p>Drawn together by a common vision and mission, we believe education is a shared responsibility and a
                collaborative effort where the gifts of persons are valued. Our learning community is a "family" where
                participation, team work, interdependence, communication and dialogue prevail. A culture of appreciation
                builds
                up our community, encouraging us towards excellence and professionalism.</p>

            <table class='table table-bordered' id='S_table'>
                <thead>
                    <tr>
                        <th class='extra-small-column'>#</th>
                        <th>Question</th>
                        <th class='small-column'>Action</th>
                    </tr>
                </thead>
                <tbody id='S_table_body'>
                    <!-- CONTENT -->

                </tbody>
                <tfoot>
                    <tr>
                        <td></td>
                        <td class='text-right'>Frequency:</td>
                        <td>
                            <div class="d-flex justify-content-center gap-3">
                                <div class="col-auto">
                                    <input class="xxs-column form-control frequency-counter-5 text-center" type="text"
                                        readonly>
                                </div>
                                <div class="col-auto">
                                    <input class="xxs-column form-control frequency-counter-4 text-center" type="text"
                                        readonly>
                                </div>
                                <div class="col-auto">
                                    <input class="xxs-column form-control frequency-counter-3 text-center" type="text"
                                        readonly>
                                </div>
                                <div class="col-auto">
                                    <input class="xxs-column form-control frequency-counter-2 text-center" type="text"
                                        readonly>
                                </div>
                                <div class="col-auto">
                                    <input class="xxs-column form-control frequency-counter-1 text-center" type="text"
                                        readonly>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td class='text-right'>Total:</td>
                        <td>
                            <div class="d-flex justify-content-center gap-3">
                                <input class="small-column form-control total-frequency text-center" type="text"
                                    readonly>
                            </div>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="content-container">
            <h2>II. Key Results Areas & Work Objectives</h2>
            <p>Please review each Key Results Area (KRA) and Work Objectives (WO) of job incumbent and compare such with
                his/her
                actual outputs. Finally, indicate the degree of output using the Likert-Scale below.</p>
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
                        <td class='text-justify'>Performance fully meets the standards expected in all areas of the job.
                            The
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

            <table class='table table-bordered' id="kra_table">
                <thead>
                    <tr>
                        <th class='large-column'>KRA</th>
                        <th class='xxs-column'>Weight</th>
                        <th class='large-column'>Objectives</th>
                        <th class='large-column'>Performance Indicators</th>
                        <th class='large-column'>Actual Results</th>
                        <th class='medium-column'>Performance Level</th>
                        <th class="xxs-column">Weighted Total</th>
                        <th class="xxs-column">Action</th>
                    </tr>
                </thead>
                <tbody id="KRA_table_body">
                    <!-- CONTENT -->

                </tbody>
                <tfoot>
                    <tr>
                        <td class='text-right'>Weight Total:</td>
                        <td>
                            <div class="d-flex justify-content-center gap-3">
                                <input id="KRA_Weight_Total" class="small-column form-control total-weight"
                                    type="text" readonly>
                            </div>
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class='text-right'>Total:</td>
                        <td>
                            <div class="d-flex justify-content-center gap-3">
                                <input id="KRA_Total" class="small-column form-control total-weighted text-center"
                                    type="text" readonly>
                            </div>
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-primary" id="add-kra-btn">Add Row</button>
            </div>
        </div>
        <div class="content-container">
            <h2>III. Future Performance Agenda</h2>
            <h3>Work Performance Plans</h3>
            <p>Identify work behaviors that the job incumbent needs to:</p>
            <table class='table table-bordered' id='wpa-table'>
                <thead>
                    <tr>
                        <th>Continue Doing</th>
                        <th>Stop Doing</th>
                        <th>Start Doing</th>
                        <th class='small-column'>Action</th>
                    </tr>
                </thead>
                <tbody id='wpa_table_body'>

                </tbody>
            </table>
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-primary" id="add-wpa-btn">Add Row</button>
            </div>

            <h3>Learning Development Plans</h3>
            <p>Identify the learning needs of the job incumbent likewise recommend specific learning methodologies for each
                need
                that you have mentioned.</p>
            <table class='table table-bordered' id='ldp_table'>
                <thead>
                    <tr>
                        <th>Learning Need</th>
                        <th>Methodology</th>
                        <th class='small-column'>Action</th>
                    </tr>
                </thead>
                <tbody id='ldp_table_body'>

                    <tr id="ldp_1">
                        <td class='td-textarea'>
                            <textarea class='textarea' name="learning_need"></textarea>
                        </td>
                        <td class='td-textarea'>
                            <textarea class='textarea' name="methodology"></textarea>
                        </td>
                        <td class='td-action'></td>
                    </tr>
                </tbody>
            </table>
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-primary" id="add-ldp-btn">Add Row</button>
            </div>
        </div>

        <div class="content-container">
            <h2>IV. Job Incumbent's Comments</h2>
            <table class='table table-bordered' id='jic_table'>
                <thead>
                    <tr>
                        <th class='medium-column'>Question</th>
                        <th class='small-column'>Answer</th>
                        <th class='large-column'>Comments</th>
                    </tr>
                </thead>
                <tbody id='jic_table_body'>
                    <tr>
                        <td class='text-justify'>
                            <textarea class='textarea border-0' value="I agree with my performance rating."
                                name="feedback[1][{{ $appraisalId }}][question]" readonly></textarea>
                        </td>
                        <td>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input autosave-field" type="radio"
                                    name="feedback[1][{{ $appraisalId }}][answer]" id="inlineRadio1" value="1">
                                <label class="form-check-label" for="inlineRadio1">Yes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input autosave-field" type="radio"
                                    name="feedback[1][{{ $appraisalId }}][answer]" id="inlineRadio2" value="0">
                                <label class="form-check-label" for="inlineRadio2">No</label>
                            </div>
                        </td>
                        <td class="td-textarea">
                            <div class="position-relative">
                                <textarea class="textarea form-control border-0 autosave-field" name="feedback[1][{{ $appraisalId }}][comments]"></textarea>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class='text-justify'>
                            <textarea class='textarea border-0'
                                value="My future work objectives and learning opportunities have been set for the
                            next review period."
                                name="feedback[2][{{ $appraisalId }}][question]" readonly></textarea>
                        </td>
                        <td>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input autosave-field" type="radio"
                                    name="feedback[2][{{ $appraisalId }}][answer]" id="inlineRadio1" value="1">
                                <label class="form-check-label" for="inlineRadio1">Yes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input autosave-field" type="radio"
                                    name="feedback[2][{{ $appraisalId }}][answer]" id="inlineRadio2" value="0">
                                <label class="form-check-label" for="inlineRadio2">No</label>
                            </div>
                        </td>
                        <td class="td-textarea">
                            <div class="position-relative">
                                <textarea class="textarea form-control border-0 autosave-field" name="feedback[2][{{ $appraisalId }}][comments]"></textarea>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class='text-justify'>
                            <textarea class='textarea border-0' value="I am satisfied with the performance review discussion."
                                name="feedback[3][{{ $appraisalId }}][question]" readonly></textarea>
                        </td>
                        <td>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input autosave-field" type="radio"
                                    name="feedback[3][{{ $appraisalId }}][answer]" id="inlineRadio1" value="1">
                                <label class="form-check-label" for="inlineRadio1">Yes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input autosave-field" type="radio"
                                    name="feedback[3][{{ $appraisalId }}][answer]" id="inlineRadio2" value="0">
                                <label class="form-check-label" for="inlineRadio2">No</label>
                            </div>
                        </td>
                        <td class="td-textarea">
                            <div class="position-relative">
                                <textarea class="textarea form-control border-0 autosave-field" name="feedback[3][{{ $appraisalId }}][comments]"></textarea>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class='text-justify'>
                            <textarea class='textarea' value="I am satisfied with the performance review process."
                                name="feedback[4][{{ $appraisalId }}][question]" readonly></textarea>
                        </td>
                        <td>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input autosave-field" type="radio"
                                    name="feedback[4][{{ $appraisalId }}][answer]" id="inlineRadio1" value="1">
                                <label class="form-check-label" for="inlineRadio1">Yes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input autosave-field" type="radio"
                                    name="feedback[4][{{ $appraisalId }}][answer]" id="inlineRadio2" value="0">
                                <label class="form-check-label" for="inlineRadio2">No</label>
                            </div>
                        </td>
                        <td class="td-textarea">
                            <div class="position-relative">
                                <textarea class="textarea form-control border-0 autosave-field" name="feedback[4][{{ $appraisalId }}][comments]"></textarea>
                            </div>
                        </td>
                    </tr>
                </tbody>
        </div>
        </div>
    </form>

    <script>
        // Get the <textarea> elements by their names
        const textareaElement1 = document.querySelector('[name="feedback[1][{{ $appraisalId }}][question]"]');
        const textareaElement2 = document.querySelector('[name="feedback[2][{{ $appraisalId }}][question]"]');
        const textareaElement3 = document.querySelector('[name="feedback[3][{{ $appraisalId }}][question]"]');
        const textareaElement4 = document.querySelector('[name="feedback[4][{{ $appraisalId }}][question]"]');

        // Set the values you want to display
        const valueToDisplay1 =
            "I agree with my performance rating.";
        const valueToDisplay2 =
            "My future work objectives and learning opportunities have been set for the next review period.";
        const valueToDisplay3 =
            "I am satisfied with the performance review discussion.";
        const valueToDisplay4 =
            "I am satisfied with the performance review process."

        // Set the values as the innerText of the <textarea> elements
        textareaElement1.innerText = valueToDisplay1;
        textareaElement2.innerText = valueToDisplay2;
        textareaElement3.innerText = valueToDisplay3;
        textareaElement4.innerText = valueToDisplay4;

        $(document).ready(function() {
            var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            $('#add-wpa-btn').click(function() {
                addNewWPARow($('#wpa_table_body'));
            });

            $('#add-ldp-btn').click(function() {
                addNewLDPRow($('#ldp_table_body'));
            });

            $('#add-kra-btn').click(function() {
                var lastRow = $('#kra_table tbody tr:last-child');
                var clonedRow = lastRow.clone();

                // Clear the values of input fields in the cloned row
                clonedRow.find('textarea, select, input[type="text"]').val('');

                // Remove any selected attributes from radio buttons and select options
                clonedRow.find('input[type="radio"]').prop('checked', false);
                clonedRow.find('select option').prop('selected', false);

                // Use the number of rows as the index
                var newRowNumber = 0

                // Change the name attributes of input fields in the cloned row
                clonedRow.find('[name$="[KRA]"]').attr('name', 'KRA[' + newRowNumber +
                    '][{{ $appraisalId }}][KRA]').prop('readonly', false);

                clonedRow.find('input[name$="[kraID]"]').attr('name', 'KRA[' + newRowNumber +
                    '][{{ $appraisalId }}][kraID]').val(newRowNumber);

                clonedRow.find('select[name$="[KRA_weight]"]').attr('name', 'KRA[' + newRowNumber +
                    '][{{ $appraisalId }}][KRA_weight]').prop('readonly', false);

                clonedRow.find('[name$="[KRA_objective]"]').attr('name', 'KRA[' + newRowNumber +
                    '][{{ $appraisalId }}][KRA_objective]').prop('readonly', false);

                clonedRow.find('[name$="[KRA_performance_indicator]"]').attr('name', 'KRA[' +
                    newRowNumber + '][{{ $appraisalId }}][KRA_performance_indicator]').prop(
                    'readonly', false);

                clonedRow.find('input[name$="[KRA_answer]"]').attr('name', 'KRA[' + newRowNumber +
                    '][{{ $appraisalId }}][KRA_answer]').prop('disabled', true);

                clonedRow.find('.td-action').html(
                    '<button type="button" class="btn btn-danger kra-delete-btn align-middle">Delete</button>'
                );

                clonedRow.appendTo('#kra_table tbody');

                var krarowCount = $('#KRA_table_body tr').length;
                if (krarowCount > 1) {
                    $('#KRA_table_body tr .kra-delete-btn').prop('disabled', false);
                }
            });

            // For the KRA delete button
            $(document).on('click', '.kra-delete-btn', function() {
                var row = $(this).closest('tr');
                var kraID = row.find('input[name$="[kraID]"]').val();
                $.ajax({
                    type: 'POST',
                    url: '{{ route('deleteKRA') }}',
                    data: {
                        kraID: kraID
                    },
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(response) {
                        // If the database deletion is successful, remove the row from the table
                        row.remove();
                        updateWeightedTotal();

                        // Check the number of rows left
                        var rowCount = $('#KRA_table_body tr').length;
                        if (rowCount === 1) {
                            $('#KRA_table_body .kra-delete-btn').prop('disabled', true);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            });
            // For the WPA delete button
            $(document).on('click', '.wpa-delete-btn', function() {
                var row = $(this).closest('tr');
                var wpaID = row.data('wpa-id'); // Assuming you have a data attribute for WPA ID on the row

                // Send an AJAX request to delete the WPA record from the database
                $.ajax({
                    type: 'POST',
                    url: "{{ route('delete-wpa') }}", // Use the route() function to generate the URL
                    data: {
                        wpaID: wpaID // Pass the WPA record ID to be deleted
                    },
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(response) {
                        // If the database deletion is successful, remove the row from the table
                        row.remove();

                        var rowCount = $('#wpa_table_body tr').length;
                        if (rowCount === 1) {
                            $('#wpa_table_body tr .wpa-delete-btn').prop('disabled', true);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            });

            // For the LDP delete button
            $(document).on('click', '.ldp-delete-btn', function() {
                var row = $(this).closest('tr');
                var ldpID = row.data('ldp-id'); // Assuming you have a data attribute for LDP ID on the row

                // Send an AJAX request to delete the LDP record from the database
                $.ajax({
                    type: 'POST',
                    url: "{{ route('deleteLDP') }}", // Use the route() function to generate the URL
                    data: {
                        ldpID: ldpID // Pass the LDP record ID to be deleted
                    },
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(response) {
                        // If the database deletion is successful, remove the row from the table
                        row.remove();

                        var rowCount = $('#ldp_table tbody tr').length;
                        if (rowCount === 1) {
                            $('#ldp_table tbody tr .ldp-delete-btn').prop('disabled', true);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            });

            $(document).on('change', '#SID_table input[type="radio"]', function() {
                updateFrequencyCounter('SID_table');
            });

            $(document).on('change', '#SR_table input[type="radio"]', function() {
                updateFrequencyCounter('SR_table');
            });

            $(document).on('change', '#S_table input[type="radio"]', function() {
                updateFrequencyCounter('S_table');
            });

            $(document).on('change', '#KRA_table_body input[type="radio"]', function() {
                updateWeightedTotal();
            });

            $(document).on('change', '#KRA_table_body select', function() {
                updateWeightedTotal();
            });

            loadTableData();

            updateFrequencyCounter('SID_table');
            updateFrequencyCounter('SR_table');
            updateFrequencyCounter('S_table');

            updateWeightedTotal();

            formChecker();
            ////// VALIDATION /////////


            ////// AUTOSAVE //////////
            $('#KRA_table_body').on('change', '.autosave-field', function() {
                console.log('I was a KRA');
                var field = $(this);
                var kraID = field.attr('name').match(/\d+/)[0];
                var fieldName = field.attr('name').split('][')[2].replace(/\]/g, '');
                var fieldValue = field.val();

                // Send the updated field value to the server via Ajax
                $.ajax({
                    url: '{{ route('autosaveKRAField') }}', // Replace with your route URL
                    method: 'POST', // Use POST method to send data
                    data: {
                        kraID: kraID,
                        fieldName: fieldName,
                        fieldValue: fieldValue,
                        appraisalId: {{ $appraisalId }}
                    },
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(response) {
                        // Handle the success response if needed
                        console.log('Autosave successful.');
                        console.log('FieldName Acquired: ' + fieldName);
                    },
                    error: function(xhr, status, error) {
                        console.log('{{ route('autosaveKRAField') }}');

                        // Handle errors if any
                        console.error('Autosave failed:', error);
                        console.log('FieldName Acquired: ' + fieldName);
                    }
                });
            });

            $('#wpa_table_body').on('change', '.autosave-field', function() {
                var field = $(this);
                var wppID = field.attr('name').match(/\d+/)[0];
                var fieldName = field.attr('name').split('][')[2].replace(/\]/g, '');
                var fieldValue = field.val();

                // Send the updated field value to the server via Ajax
                $.ajax({
                    url: '{{ route('autosaveWPPField') }}', // Replace with your route URL
                    method: 'POST', // Use POST method to send data
                    data: {
                        wppID: wppID,
                        fieldName: fieldName,
                        fieldValue: fieldValue,
                        appraisalId: {{ $appraisalId }}
                    },
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(response) {
                        response.wpaData.forEach(function(wpa, index) {
                            var wpaID = wpa.performance_plan_id;
                            console.log(wpaID);
                            var closestRow = field.closest('tr');
                            console.log(closestRow);

                            closestRow.attr('data-wpa-id', wpaID);

                            // Change the name attribute of the textareas if needed
                            closestRow.find('textarea[name="WPA[0][' +
                                {{ $appraisalId }} + '][continue_doing]"]').attr(
                                'name', 'WPA[' + wpaID + '][' +
                                {{ $appraisalId }} + '][continue_doing]');
                            closestRow.find('textarea[name="WPA[0][' +
                                {{ $appraisalId }} + '][stop_doing]"]').attr(
                                'name', 'WPA[' + wpaID + '][' +
                                {{ $appraisalId }} + '][stop_doing]');
                            closestRow.find('textarea[name="WPA[0][' +
                                {{ $appraisalId }} + '][start_doing]"]').attr(
                                'name', 'WPA[' + wpaID + '][' +
                                {{ $appraisalId }} + '][start_doing]');

                            // Update the content of the closest row based on the response data
                            closestRow.find('textarea[name="WPA[' + wpaID + '][' +
                                {{ $appraisalId }} + '][continue_doing]"]').val(
                                wpa.continue_doing);
                            closestRow.find('textarea[name="WPA[' + wpaID + '][' +
                                {{ $appraisalId }} + '][stop_doing]"]').val(wpa
                                .stop_doing);
                            closestRow.find('textarea[name="WPA[' + wpaID + '][' +
                                {{ $appraisalId }} + '][start_doing]"]').val(wpa
                                .start_doing);
                        });

                        // Handle the success response if needed
                        console.log('Autosave successful.');
                        console.log('FieldName Acquired: ' + fieldName);
                    },
                    error: function(xhr, status, error) {
                        console.log('{{ route('autosaveWPPField') }}');

                        // Handle errors if any
                        console.error('Autosave failed:', error);
                        console.log('FieldName Acquired: ' + fieldName);
                    }
                });
            });

            $('#ldp_table_body').on('change', '.autosave-field', function() {
                var field = $(this);
                var ldpID = field.attr('name').match(/\d+/)[0];
                var fieldName = field.attr('name').split('][')[2].replace(/\]/g, '');
                var fieldValue = field.val();

                // Send the updated field value to the server via Ajax
                $.ajax({
                    url: '{{ route('autosaveLDPField') }}', // Replace with your route URL
                    method: 'POST', // Use POST method to send data
                    data: {
                        ldpID: ldpID,
                        fieldName: fieldName,
                        fieldValue: fieldValue,
                        appraisalId: {{ $appraisalId }}
                    },
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(response) {
                        response.ldpData.forEach(function(ldp, index) {
                            var ldpID = ldp.development_plan_id;
                            console.log(ldpID);
                            var closestRow = field.closest('tr');
                            console.log(closestRow);

                            closestRow.attr('data-ldp-id', ldpID);

                            // Change the name attribute of the textareas if needed
                            closestRow.find('textarea[name="LDP[0][' +
                                {{ $appraisalId }} + '][learning_need]"]').attr(
                                'name', 'LDP[' + ldpID + '][' +
                                {{ $appraisalId }} + '][learning_need]');
                            closestRow.find('textarea[name="LDP[0][' +
                                {{ $appraisalId }} + '][methodology]"]').attr(
                                'name', 'LDP[' + ldpID + '][' +
                                {{ $appraisalId }} + '][methodology]');

                            // Update the content of the closest row based on the response data
                            closestRow.find('textarea[name="LDP[' + ldpID + '][' +
                                {{ $appraisalId }} + '][learning_need]"]').val(ldp
                                .learning_need);
                            closestRow.find('textarea[name="LDP[' + ldpID + '][' +
                                {{ $appraisalId }} + '][methodology]"]').val(ldp
                                .methodology);
                        });

                        // Handle the success response if needed
                        console.log('Autosave successful.');
                        console.log('FieldName Acquired: ' + fieldName);
                    },
                    error: function(xhr, status, error) {
                        console.log('{{ route('autosaveLDPField') }}');

                        // Handle errors if any
                        console.error('Autosave failed:', error);
                        console.log('FieldName Acquired: ' + fieldName);
                    }
                });
            });

            $('#jic_table_body').on('change', '.autosave-field', function() {
                var field = $(this);
                var jicID = field.attr('name').match(/\d+/)[0];
                var fieldName = field.attr('name').split('][')[2].replace(/\]/g, '');
                var fieldValue = field.val();
                var fieldQuestion = field.closest("tr").find("textarea").html();

                // Send the updated field value to the server via Ajax
                $.ajax({
                    url: '{{ route('autosaveJICField') }}', // Replace with your route URL
                    method: 'POST', // Use POST method to send data
                    data: {
                        jicID: jicID,
                        fieldName: fieldName,
                        fieldValue: fieldValue,
                        fieldQuestion: fieldQuestion,
                        appraisalId: {{ $appraisalId }}
                    },
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(response) {
                        // Handle the success response if needed
                        console.log('Autosave successful.');
                        console.log('FieldName Acquired: ' + fieldName);
                    },
                    error: function(xhr, status, error) {
                        console.log('{{ route('autosaveLDPField') }}');

                        // Handle errors if any
                        console.error('Autosave failed:', error);
                        console.log('FieldName Acquired: ' + fieldName);
                    }
                });
            });
        });

        function loadTableData() {
            var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            $.ajax({
                url: '{{ route('pe.getAppraisalQuestions') }}',
                type: 'GET',
                data: {
                    appraisal_id: {{ $appraisalId }}
                },

                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    $('#SID_table_body').empty();
                    var SIDQuestions = response.SID;
                    var storedValues = response.storedValues;

                    SIDQuestions.forEach(function(question) {
                        var questionId = question.question_id;
                        var questionText = question.question;
                        var questionOrder = question.question_order;
                        var score = question.score;

                        var row = $('<tr>');
                        row.attr('data-question-id', (questionId.toString() + {{ $appraisalId }}));

                        var orderCell = $('<td>').text(questionOrder);
                        var questionCell = $('<td>').text(questionText).addClass('text-justify');
                        var performanceCell = $('<td>').addClass('large-column');
                        var performanceLevelDiv = $('<div>').addClass(
                            'd-flex justify-content-center gap-2');

                        for (var i = 5; i >= 1; i--) {
                            var label = $('<label>').addClass('form-check-label text-gray');
                            var input = $('<input>').attr({
                                type: 'radio',
                                name: 'SID[' + questionId.toString() + '][' +
                                    {{ $appraisalId }} + '][SIDanswer]',
                                class: 'form-check-input',
                                value: i
                            });

                            input[0].required = true;

                            // Use stored value if available
                            if (score !== null && Math.round(score * 100) === i * 100) {
                                input.prop('checked', true);
                            }

                            var span = $('<span>').addClass('ms-1').text(i);
                            label.append(input, span);
                            performanceLevelDiv.append($('<div>').addClass('col-auto').append(label));

                            input.on('invalid', function() {
                                $(this).addClass('is-invalid');
                                $(this).siblings('span').addClass('text-danger');
                            });

                            input.on('input', function() {
                                var row = $(this).closest('tr');
                                row.find('.is-invalid').removeClass('is-invalid');
                                row.find('.text-danger').removeClass(
                                    'text-danger fw-bold'); // Remove color change from span

                                $(this).closest('tr').removeClass(
                                    'text-danger fw-bold');

                                $(this).removeClass(
                                    'is-invalid'
                                ); // Also remove is-invalid class from the input
                            });
                        }
                        performanceCell.append(performanceLevelDiv);
                        row.append(orderCell, questionCell, performanceCell);

                        $('#SID_table_body').append(row);
                        $('#SID_table input[type="radio"]').trigger('change');
                    });

                    $('#SR_table_body').empty();
                    var SRQuestions = response.SR;

                    SRQuestions.forEach(function(question) {
                        var questionId = question.question_id;
                        var questionText = question.question;
                        var questionOrder = question.question_order;
                        var score = question.score;

                        var row = $('<tr>');
                        row.attr('data-question-id', (questionId.toString() + {{ $appraisalId }}));

                        var orderCell = $('<td>').text(questionOrder);
                        var questionCell = $('<td>').text(questionText).addClass('text-justify');
                        var performanceCell = $('<td>').addClass('large-column');
                        var performanceLevelDiv = $('<div>').addClass(
                            'd-flex justify-content-center gap-2');

                        for (var i = 5; i >= 1; i--) {
                            var label = $('<label>').addClass('form-check-label text-gray');
                            var input = $('<input>').attr({
                                type: 'radio',
                                name: 'SR[' + questionId.toString() + '][' +
                                    {{ $appraisalId }} + '][SRanswer]',
                                class: 'form-check-input',
                                value: i
                            });

                            input[0].required = true;

                            // Use stored value if available
                            if (score !== null && Math.round(score * 100) === i * 100) {
                                input.prop('checked', true);
                            }

                            var span = $('<span>').addClass('ms-1').text(i);
                            label.append(input, span);
                            performanceLevelDiv.append($('<div>').addClass('col-auto').append(label));

                            input.on('invalid', function() {
                                $(this).addClass('is-invalid');
                                $(this).siblings('span').addClass('text-danger');
                            });

                            input.on('input', function() {
                                var row = $(this).closest('tr');
                                row.find('.is-invalid').removeClass('is-invalid');
                                row.find('.text-danger').removeClass(
                                    'text-danger fw-bold'); // Remove color change from span

                                $(this).closest('tr').removeClass(
                                    'text-danger fw-bold');

                                $(this).removeClass(
                                    'is-invalid'
                                ); // Also remove is-invalid class from the input
                            });

                        }

                        performanceCell.append(performanceLevelDiv);
                        row.append(orderCell, questionCell, performanceCell);

                        $('#SR_table_body').append(row);
                        $('#SR_table input[type="radio"]').trigger('change');
                    });

                    $('#S_table_body').empty();
                    var SQuestions = response.S;

                    SQuestions.forEach(function(question) {
                        var questionId = question.question_id;
                        var questionText = question.question;
                        var questionOrder = question.question_order;
                        var score = question.score;

                        var row = $('<tr>');
                        row.attr('data-question-id', (questionId.toString() + {{ $appraisalId }}));

                        var orderCell = $('<td>').text(questionOrder);
                        var questionCell = $('<td>').text(questionText).addClass('text-justify');
                        var performanceCell = $('<td>').addClass('large-column');
                        var performanceLevelDiv = $('<div>').addClass(
                            'd-flex justify-content-center gap-2');

                        for (var i = 5; i >= 1; i--) {
                            var label = $('<label>').addClass('form-check-label text-gray');
                            var input = $('<input>').attr({
                                type: 'radio',
                                name: 'S[' + questionId.toString() + '][' +
                                    {{ $appraisalId }} + '][Sanswer]',
                                class: 'form-check-input',
                                value: i
                            });

                            input[0].required = true;

                            // Use stored value if available
                            if (score !== null && Math.round(score * 100) === i * 100) {
                                input.prop('checked', true);
                            }

                            var span = $('<span>').addClass('ms-1').text(i);
                            label.append(input, span);
                            performanceLevelDiv.append($('<div>').addClass('col-auto').append(label));

                            input.on('invalid', function() {
                                $(this).addClass('is-invalid');
                                $(this).siblings('span').addClass('text-danger');
                            });

                            input.on('input', function() {
                                var row = $(this).closest('tr');
                                row.find('.is-invalid').removeClass('is-invalid');
                                row.find('.text-danger').removeClass(
                                    'text-danger fw-bold'); // Remove color change from span

                                $(this).closest('tr').removeClass(
                                    'text-danger fw-bold');

                                $(this).removeClass(
                                    'is-invalid'
                                ); // Also remove is-invalid class from the input
                            });
                        }

                        performanceCell.append(performanceLevelDiv);
                        row.append(orderCell, questionCell, performanceCell);

                        $('#S_table_body').append(row);
                        $('#S_table input[type="radio"]').trigger('change');
                    });
                }
            });

            $.ajax({
                url: '{{ route('getKRA') }}',
                type: 'GET',
                data: {
                    appraisal_id: {{ $appraisalId }}
                },
                dataType: 'json',
                success: function(data) {
                    if (data.success) {
                        $('#KRA_table_body').empty();
                        var tbody = $('#KRA_table_body');

                        if (data.kraData.length === 0) {
                            // Add a new empty row if there are no rows
                            addNewKRARow(tbody);
                            var rowCount = $('#KRA_table_body tr').length;

                            if (rowCount === 1) {
                                $('#KRA_table_body tr .kra-delete-btn').prop('disabled', true);
                            }
                        } else {
                            data.kraData.forEach(function(kra, index) {
                                var kraID = kra.kra_id;

                                var row = $('<tr>').addClass('align-middle');

                                $('<input>').attr({
                                    type: 'hidden',
                                    name: 'KRA[' + kraID + '][' + {{ $appraisalId }} +
                                        '][kraID]',
                                    value: kraID
                                }).appendTo(row);

                                $('<td>').addClass('td-textarea').append(
                                    createTextArea(
                                        'KRA[' + kraID + '][' + {{ $appraisalId }} + '][KRA_kra]',
                                        kra.kra,
                                        false
                                    )
                                ).appendTo(row);

                                var weightSelect = $('<select>').addClass('form-select autosave-field')
                                    .attr('aria-label', 'Default select example')
                                    .attr('readonly', false)
                                    .attr('name', 'KRA[' + kraID + '][' + {{ $appraisalId }} +
                                        '][KRA_kra_weight]')
                                    .appendTo($('<td>')).appendTo(row);

                                $('<option>').appendTo(weightSelect); // Add an empty option

                                for (let i = 1; i <= 100; i++) {
                                    var option = $('<option>').val(i).text(i + '%').appendTo(
                                        weightSelect); // Corrected text
                                    var kraWeight = parseFloat(kra.kra_weight);

                                    if (Math.abs(kraWeight - i) < 0.01) {
                                        option.prop('selected', true);
                                    }
                                }

                                $('<td>').addClass('td-textarea').append(
                                    createTextArea(
                                        'KRA[' + kraID + '][' + {{ $appraisalId }} +
                                        '][KRA_objective]',
                                        kra.objective,
                                        false
                                    )
                                ).appendTo(row);

                                $('<td>').addClass('td-textarea').append(
                                    createTextArea(
                                        'KRA[' + kraID + '][' + {{ $appraisalId }} +
                                        '][KRA_performance_indicator]',
                                        kra.performance_indicator,
                                        false
                                    )
                                ).appendTo(row);

                                console.log(data.phaseData);
                                if (data.phaseData === "kra") {
                                    $('<td>').addClass('td-textarea').append(
                                        $('<textarea>').addClass('textarea').prop('readonly', true)
                                    ).appendTo(row);

                                    var performanceCell = $('<td>').appendTo(row);
                                    var performanceLevelDiv = $('<div>').addClass(
                                        'd-flex justify-content-center gap-2').appendTo(
                                        performanceCell);
                                    for (var i = 5; i >= 1; i--) {
                                        var label = $('<label>').addClass('form-check-label');
                                        var input = $('<input>').prop('readonly', true).attr({
                                            type: 'radio',
                                            name: 'KRA[' + kraID + '][' + {{ $appraisalId }} +
                                                '][KRA_answer]',
                                            class: 'form-check-input',
                                            value: i
                                        });

                                        input[0].disabled = true;

                                        label.append(input, i);
                                        $('<div>').addClass('col-auto').append(label).appendTo(
                                            performanceLevelDiv);
                                    }
                                } else if (data.phaseData === "pr") {
                                    $('<td>').addClass('td-textarea').append(
                                        createTextArea(
                                            'KRA[' + kraID + '][' + {{ $appraisalId }} +
                                            '][KRA_actual_result]',
                                            kra.actual_result,
                                            false
                                        )
                                    ).appendTo(row);

                                    var performanceCell = $('<td>').appendTo(row);
                                    var performanceLevelDiv = $('<div>').addClass(
                                        'd-flex justify-content-center gap-2'
                                    ).appendTo(performanceCell);

                                    for (var i = 5; i >= 1; i--) {
                                        var label = $('<label>').addClass('form-check-label');
                                        var input = $('<input>').prop('readonly', false).attr({
                                            type: 'radio',
                                            name: 'KRA[' + kraID + '][' + {{ $appraisalId }} +
                                                '][KRA_performance_level]',
                                            class: 'form-check-input autosave-field',
                                            value: i
                                        });

                                        input[0].required = true;

                                        var span = $('<span>').addClass('ms-1').text(i);
                                        label.append(input, span);
                                        performanceLevelDiv.append($('<div>').addClass('col-auto')
                                            .append(
                                                label));

                                        input.on('invalid', function() {
                                            $(this).addClass('is-invalid text-danger fw-bold');
                                            $(this).siblings('span').addClass('text-danger');
                                        });

                                        input.on('input', function() {
                                            var row = $(this).closest('tr');
                                            row.find('.is-invalid').removeClass('is-invalid');
                                            row.find('.text-danger').removeClass(
                                                'text-danger fw-bold'
                                            );

                                            $(this).closest('tr').removeClass(
                                                'text-danger fw-bold'
                                            );

                                            $(this).removeClass(
                                                'is-invalid'
                                            );
                                        });

                                        label.append(input, span);

                                        var kraPL = parseFloat(kra.performance_level);

                                        if (Math.abs(kraPL - i) < 0.01) {
                                            input.prop('checked', true);
                                        }

                                        $('<div>').addClass('col-auto').append(label).appendTo(
                                            performanceLevelDiv);
                                    }
                                } else if (data.phaseData === "eval") {
                                    $('<td>').addClass('td-textarea').append(
                                        createTextArea(
                                            'KRA[' + kraID + '][' + {{ $appraisalId }} +
                                            '][KRA_actual_result]',
                                            kra.actual_result,
                                            false
                                        )
                                    ).appendTo(row);

                                    var performanceCell = $('<td>').appendTo(row);
                                    var performanceLevelDiv = $('<div>').addClass(
                                        'd-flex justify-content-center gap-2'
                                    ).appendTo(performanceCell);

                                    for (var i = 5; i >= 1; i--) {
                                        var label = $('<label>').addClass('form-check-label');
                                        var input = $('<input>').prop('readonly', false).attr({
                                            type: 'radio',
                                            name: 'KRA[' + kraID + '][' + {{ $appraisalId }} +
                                                '][KRA_performance_level]',
                                            class: 'form-check-input autosave-field',
                                            value: i
                                        });

                                        input[0].required = true;

                                        var span = $('<span>').addClass('ms-1').text(i);
                                        label.append(input, span);
                                        performanceLevelDiv.append($('<div>').addClass('col-auto')
                                            .append(
                                                label));

                                        input.on('invalid', function() {
                                            $(this).addClass('is-invalid text-danger fw-bold');
                                            $(this).siblings('span').addClass('text-danger');
                                        });

                                        input.on('input', function() {
                                            var row = $(this).closest('tr');
                                            row.find('.is-invalid').removeClass('is-invalid');
                                            row.find('.text-danger').removeClass(
                                                'text-danger fw-bold'
                                            );

                                            $(this).closest('tr').removeClass(
                                                'text-danger fw-bold'
                                            );

                                            $(this).removeClass(
                                                'is-invalid'
                                            );
                                        });

                                        label.append(input, span);

                                        var kraPL = parseFloat(kra.performance_level);

                                        if (Math.abs(kraPL - i) < 0.01) {
                                            input.prop('checked', true);
                                        }

                                        $('<div>').addClass('col-auto').append(label).appendTo(
                                            performanceLevelDiv);
                                    }
                                } else {
                                    console.log('ELSE LOCK');
                                }

                                $('<td>').addClass('td-textarea').append(
                                    $('<textarea>').addClass(
                                        'textarea border-0 autosave-field').attr(
                                        'name', 'KRA[' + kraID + '][' + {{ $appraisalId }} +
                                        '][KRA_weighted_total]').prop(
                                        'readonly',
                                        true).val(kra.weighted_total)
                                ).appendTo(row);

                                $('<td>').addClass('td-action').append(
                                    $('<button>').addClass(
                                        'btn btn-danger kra-delete-btn align-middle KRA')
                                    .text('Delete')
                                    .attr('type', 'button')
                                ).appendTo(row);

                                tbody.append(row);

                                var krarowCount = $('#KRA_table_body tr').length;
                                if (krarowCount <= 1) {
                                    $('#KRA_table_body .kra-delete-btn').prop('disabled', true);
                                } else {
                                    $('#KRA_table_body .kra-delete-btn').prop('disabled', false);
                                }

                                row.find('input[type="radio"][name^="KRA[' + kraID + '][' +
                                        {{ $appraisalId }} + '][KRA_performance_level]"]')
                                    .trigger('change');
                            });
                            updateWeightedTotal();
                        }

                        $('#wpa_table_body').empty();
                        var wpatbody = $('#wpa_table_body');

                        if (data.wpaData.length === 0) {
                            // Add a new empty row if there are no rows
                            addNewWPARow(wpatbody);
                            var wparowCount = $('#wpa_table_body tr').length;

                            if (wparowCount === 1) {
                                $('#wpa_table_body tr .wpa-delete-btn').prop('disabled', true);
                            }
                            console.log(wparowCount);
                        } else {
                            data.wpaData.forEach(function(wpa, index) {
                                var wpaID = wpa.performance_plan_id;

                                var wparow = $('<tr>').addClass('align-middle');
                                $('<td>').addClass('td-textarea').append(
                                    createTextArea(
                                        'WPA[' + wpaID + '][' + {{ $appraisalId }} +
                                        '][continue_doing]',
                                        wpa.continue_doing,
                                        false
                                    )
                                ).appendTo(wparow);

                                $('<td>').addClass('td-textarea').append(
                                    createTextArea(
                                        'WPA[' + wpaID + '][' + {{ $appraisalId }} +
                                        '][stop_doing]',
                                        wpa.stop_doing,
                                        false
                                    )
                                ).appendTo(wparow);

                                $('<td>').addClass('td-textarea').append(
                                    createTextArea(
                                        'WPA[' + wpaID + '][' + {{ $appraisalId }} +
                                        '][start_doing]',
                                        wpa.start_doing,
                                        false
                                    )
                                ).appendTo(wparow);

                                $('<td>').addClass('td-action').append(
                                    $('<button>').addClass(
                                        'btn btn-danger wpa-delete-btn align-middle')
                                    .text('Delete')
                                ).appendTo(wparow);

                                wpatbody.append(wparow);

                                var wparowCount = $('#wpa_table_body tr').length;
                                if (wparowCount === 1) {
                                    $('#wpa_table_body .wpa-delete-btn').prop('disabled', true);
                                } else {
                                    $('#wpa_table_body .wpa-delete-btn').prop('disabled', false);
                                }
                            });
                        }

                        $('#ldp_table_body').empty();
                        var ldptbody = $('#ldp_table_body');

                        if (data.ldpData.length === 0) {
                            // Add a new empty row if there are no rows
                            addNewLDPRow(ldptbody);
                            var ldprowCount = $('#ldp_table_body tr').length;

                            if (ldprowCount === 1) {
                                $('#ldp_table_body tr .ldp-delete-btn').prop('disabled', true);
                            }
                        } else {
                            data.ldpData.forEach(function(ldp, index) {
                                var ldpID = ldp.development_plan_id;

                                var ldprow = $('<tr>').addClass('align-middle');
                                $('<td>').addClass('td-textarea').append(
                                    $('<textarea>').addClass('textarea').attr('name', 'LDP[' +
                                        ldpID +
                                        '][' + {{ $appraisalId }} + '][learning_need]').prop(
                                        'readonly',
                                        false)
                                    .val(ldp.learning_need)
                                ).appendTo(ldprow);

                                $('<td>').addClass('td-textarea').append(
                                    $('<textarea>').addClass('textarea').attr('name', 'LDP[' +
                                        ldpID +
                                        '][' + {{ $appraisalId }} + '][methodology]').prop(
                                        'readonly',
                                        false)
                                    .val(ldp.methodology)
                                ).appendTo(ldprow);

                                $('<td>').addClass('td-action').append(
                                    $('<button>').addClass(
                                        'btn btn-danger ldp-delete-btn align-middle')
                                    .attr('type', 'button')
                                    .text('Delete')
                                ).appendTo(ldprow);

                                ldptbody.append(ldprow);

                                var ldprowCount = $('#ldp_table_body tr').length;
                                if (ldprowCount <= 1) {
                                    $('#ldp_table_body .ldp-delete-btn').prop('disabled', true);
                                } else {
                                    $('#ldp_table_body .ldp-delete-btn').prop('disabled', false);
                                }
                            });
                        }
                    }

                    // Loop through the jicData and populate the table rows with data
                    data.jicData.forEach(function(jic, index) {
                        var row = document.querySelectorAll('#jic_table_body tr')[index];

                        var answerRadioYes = row.querySelector('input[name="feedback[' + (index + 1) +
                            '][{{ $appraisalId }}][answer]"][value="1"]');
                        var answerRadioNo = row.querySelector('input[name="feedback[' + (index + 1) +
                            '][{{ $appraisalId }}][answer]"][value="0"]');

                        if (jic.answer === 1) {
                            answerRadioYes.checked = true;
                        } else if (jic.answer === 0) {
                            answerRadioNo.checked = true;
                        }

                        $(answerRadioYes).on('invalid', function() {
                            $(this).addClass('is-invalid text-danger fw-bold');
                            $(this).siblings('span').addClass('text-danger');
                        });

                        $(answerRadioNo).on('invalid', function() {
                            $(this).addClass('is-invalid text-danger fw-bold');
                            $(this).siblings('span').addClass('text-danger');
                        });

                        $(answerRadioYes).on('input', function() {
                            var row = $(this).closest('tr');
                            row.find('.is-invalid').removeClass('is-invalid');
                            row.find('.text-danger').removeClass('text-danger fw-bold');

                            $(this).closest('tr').removeClass('text-danger fw-bold');
                        });

                        $(answerRadioNo).on('input', function() {
                            var row = $(this).closest('tr');
                            row.find('.is-invalid').removeClass('is-invalid');
                            row.find('.text-danger').removeClass('text-danger fw-bold');

                            $(this).closest('tr').removeClass('text-danger fw-bold');
                        });

                        var commentTextarea = row.querySelector('.textarea[name="feedback[' + (index +
                            1) + '][{{ $appraisalId }}][comments]"]');
                        commentTextarea.value = jic.comments;

                        // Attach input event handlers for validation
                        $(commentTextarea).on('input', function() {
                            $(this).removeClass('border border-danger');
                            $(this).removeClass('is-invalid');
                        }).on('invalid', function() {
                            $(this).addClass('is-invalid');
                            $(this).attr('placeholder', 'Please provide a valid input');
                        }).on('blur', function() {
                            if ($(this).val().trim() === '') {
                                $(this).addClass('is-invalid');
                            }
                        });

                        answerRadioYes.required = true;
                        answerRadioNo.required = true;
                        commentTextarea.required = true;
                    });
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }

        function createTextArea(name, value, isReadonly) {
            return $('<div>').addClass('position-relative').append(
                $('<textarea>').addClass('textarea form-control border-0 autosave-field').attr({
                    name: name,
                    readonly: isReadonly
                }).prop('required', true).val(value)
                .on('input', function() {
                    $(this).removeClass('border border-danger');
                    $(this).closest('td').removeClass(
                        'border border-danger');
                    $(this).removeClass('is-invalid');
                }).on('invalid', function() {
                    $(this).addClass('is-invalid');
                    $(this).closest('div').addClass('border border-danger');
                    $(this).attr('placeholder', 'Please provide a valid input');
                }).on('blur', function() {
                    if ($(this).val().trim() === '') {
                        $(this).addClass('is-invalid');
                        $(this).closest('td').addClass(
                            'border border-danger');
                    }
                })
            );
        }

        function addNewKRARow(tbody) {
            var kraID = 0;

            var row = $('<tr>').addClass('align-middle');

            $('<input>').attr({
                type: 'hidden',
                name: 'KRA[' +
                    kraID +
                    '][' + {{ $appraisalId }} + '][kraID]',
                value: kraID,
            }).appendTo(row);

            $('<td>').addClass('td-textarea').append(
                createTextArea(
                    'KRA[' + kraID + '][' + {{ $appraisalId }} + '][KRA]',
                    '',
                    false
                )
            ).appendTo(row);

            var weightSelect = $('<select>').addClass('form-select autosave-field').attr('aria-label',
                    'Default select example').attr('readonly', false).attr('name',
                    'KRA[' +
                    kraID +
                    '][' + {{ $appraisalId }} + '][KRA_kra_weight]')
                .appendTo($('<td>'))
                .appendTo(row);
            $('<option>').attr('selected', true).text('%').appendTo(weightSelect);

            for (let i = 1; i <= 100; i++) {
                var option = $('<option>').val(i).text(i).appendTo(weightSelect);
            }

            $('<td>').addClass('td-textarea').append(
                createTextArea(
                    'KRA[' + kraID + '][' + {{ $appraisalId }} +
                    '][KRA_objective]',
                    '',
                    false
                )
            ).appendTo(row);

            $('<td>').addClass('td-textarea').append(
                createTextArea(
                    'KRA[' + kraID + '][' + {{ $appraisalId }} +
                    '][KRA_performance_indicator]',
                    '',
                    false
                )
            ).appendTo(row);

            $('<td>').addClass('td-textarea').append(
                $('<textarea>').addClass('textarea').prop('readonly', true)
            ).appendTo(row);

            var performanceCell = $('<td>').appendTo(row);
            var performanceLevelDiv = $('<div>').addClass(
                'd-flex justify-content-center gap-2').appendTo(performanceCell);
            for (var i = 5; i >= 1; i--) {
                var label = $('<label>').addClass('form-check-label');
                var input = $('<input>').prop('readonly', true).attr({
                    type: 'radio',
                    name: 'KRA[' +
                        kraID +
                        '][' + {{ $appraisalId }} + '][KRA_answer]',
                    class: 'form-check-input',
                    value: i
                });
                label.append(input, i);
                $('<div>').addClass('col-auto').append(label).appendTo(
                    performanceLevelDiv);
            }

            $('<td>').addClass('td-textarea').append(
                createTextArea(
                    'KRA[' + kraID + '][' + {{ $appraisalId }} +
                    '][KRA_weighted_total]',
                    '',
                    true
                )
            ).appendTo(row);

            $('<td>').addClass('td-action').append(
                $('<button>').addClass('btn btn-danger kra-delete-btn align-middle').attr('type', 'button').text(
                    'Delete')
            ).appendTo(row);

            tbody.append(row);

            var krarowCount = $('#KRA_table_body tr').length;
            if (krarowCount > 1) {
                $('#KRA_table_body tr .kra-delete-btn').prop('disabled', false);
            }
        }

        function addNewWPARow(wpatbody) {
            var highestWpaID = 0;
            wpatbody.find('[name^="WPA["]').each(function() {
                var nameAttr = $(this).attr('name');
                var matches = nameAttr.match(/\[([0-9]+)\]/);
                if (matches && matches.length > 1) {
                    var wpaID = parseInt(matches[1]);
                    if (wpaID > highestWpaID) {
                        highestWpaID = wpaID;
                    }
                }
            });

            // Calculate the next available wpaID
            var nextWpaID = 0;

            var wparow = $('<tr>').addClass('align-middle');
            $('<td>').addClass('td-textarea').append(
                $('<textarea>').addClass('textarea form-control border-0 autosave-field').attr('name', 'WPA[' +
                    nextWpaID +
                    '][' + {{ $appraisalId }} + '][continue_doing]').prop('readonly',
                    false)
            ).appendTo(wparow);

            $('<td>').addClass('td-textarea').append(
                $('<textarea>').addClass('textarea form-control border-0 autosave-field').attr('name', 'WPA[' +
                    nextWpaID +
                    '][' + {{ $appraisalId }} + '][stop_doing]').prop('readonly',
                    false)
            ).appendTo(wparow);

            $('<td>').addClass('td-textarea').append(
                $('<textarea>').addClass('textarea form-control border-0 autosave-field').attr('name', 'WPA[' +
                    nextWpaID +
                    '][' + {{ $appraisalId }} + '][start_doing]').prop('readonly',
                    false)
            ).appendTo(wparow);

            $('<td>').addClass('td-action').append(
                $('<button>').addClass('btn btn-danger wpa-delete-btn align-middle')
                .attr('type', 'button')
                .text('Delete')
            ).appendTo(wparow);

            wpatbody.append(wparow);

            var wparowCount = $('#wpa_table_body tr').length;
            if (wparowCount > 1) {
                $('#wpa_table_body tr .wpa-delete-btn').prop('disabled', false);
            }
        }

        function addNewLDPRow(ldptbody) {
            var highestLDPID = 0;
            ldptbody.find('[name^="LDP["]').each(function() {
                var nameAttr = $(this).attr('name');
                var matches = nameAttr.match(/\[([0-9]+)\]/);
                if (matches && matches.length > 1) {
                    var ldpID = parseInt(matches[1]);
                    if (ldpID > highestLDPID) {
                        highestLDPID = ldpID;
                    }
                }
            });

            // Calculate the next available lpaID
            var nextLDPID = 0;

            var ldprow = $('<tr>').addClass('align-middle');
            $('<td>').addClass('td-textarea').append(
                $('<textarea>').addClass('textarea autosave-field').attr('name', 'LDP[' +
                    nextLDPID +
                    '][' + {{ $appraisalId }} + '][learning_need]').prop(
                    'readonly',
                    false)
            ).appendTo(ldprow);

            $('<td>').addClass('td-textarea').append(
                $('<textarea>').addClass('textarea autosave-field').attr('name', 'LDP[' +
                    nextLDPID +
                    '][' + {{ $appraisalId }} + '][methodology]').prop(
                    'readonly',
                    false)
            ).appendTo(ldprow);

            $('<td>').addClass('td-action').append(
                $('<button>').addClass('btn btn-danger ldp-delete-btn align-middle')
                .attr('type', 'button')
                .text('Delete')
            ).appendTo(ldprow);

            ldptbody.append(ldprow);

            var ldprowCount = $('#ldp_table_body tr').length;
            if (ldprowCount > 1) {
                $('#ldp_table_body tr .ldp-delete-btn').prop('disabled', false);
            }
        }

        function updateFrequencyCounter(tableId) {
            var frequencyCounters = [0, 0, 0, 0, 0];
            var total = 0;
            var questionCount = 0;

            $('#' + tableId + ' input[type="radio"]:checked').each(function() {
                var value = parseInt($(this).val());
                frequencyCounters[5 - value]++;
                total += value;
                questionCount++;

                var questionId = $(this).attr('name');
                var value = $(this).val();
                localStorage.setItem(questionId, value);
            });

            for (var i = 5; i >= 1; i--) {
                $('#' + tableId + ' .frequency-counter-' + i).val(frequencyCounters[5 - i]);
            }

            var weightedTotal = questionCount > 0 ? (total / questionCount).toFixed(2) : 0;
            $('#' + tableId + ' .total-frequency').val(weightedTotal);
        }

        function updateWeightedTotal() {
            console.log("updateWeightedTotal() called");

            var totalWeight = 0;
            var totalWeighted = 0;

            $('#KRA_table_body tr').each(function() {
                var row = $(this);
                var weightOption = row.find('select[name^="KRA"][name$="[KRA_kra_weight]"]');
                var selectedValue = weightOption.find("option:selected").val();
                var weight = parseFloat(selectedValue) / 100;

                var performanceLevel = parseInt(
                    row.find('input[type="radio"][name^="KRA"][name$="[KRA_performance_level]"]:checked')
                    .val()
                );

                console.log("weight");
                console.log(weight);

                if (!isNaN(weight) || !isNaN(performanceLevel)) {
                    var weightedValue = weight * performanceLevel;
                    totalWeight += weight;
                    totalWeighted += weightedValue;

                    console.log("weightedValue");
                    console.log(weightedValue);

                    if (isNaN(weightedValue)) {
                        weightedValue = 0;
                    }

                    // Update the weighted total input element
                    row.find('textarea[name^="KRA"][name$="[KRA_weighted_total]"]')
                        .val(weightedValue.toFixed(2));
                }
            });

            totalWeight = totalWeight * 100;
            console.log("totalWeight");
            console.log(totalWeight);

            if (totalWeight > 100) {
                isTotalWeightInvalid = true;
                $('#KRA_Weight_Total').addClass('is-invalid');
                $('select[name^="KRA"][name$="[KRA_kra_weight]"]').addClass('is-invalid');
            } else {
                $('#KRA_Weight_Total').removeClass('is-invalid');
                $('select[name^="KRA"][name$="[KRA_kra_weight]"]').removeClass('is-invalid');
            }

            if (isNaN(totalWeighted)) {
                totalWeighted = 0;
            }

            // Update the total weight input element
            $('#KRA_Weight_Total').val(totalWeight.toFixed(2));

            // Update the total weighted input element
            $('#KRA_Total').val(totalWeighted.toFixed(2));
        }

        function formChecker() {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('pe.SEFormChecker') }}',
                type: 'POST',
                data: {
                    appraisalId: {{ $appraisalId }}
                },
                success: function(response) {
                    console.log("PHASE");
                    console.log(response.phaseData);
                    if (response.phaseData === "kra") {
                        console.log("AQ1");

                        $('input[type="radio"]').prop('disabled', true);

                        $('textarea').prop('disabled', true);
                        $('#KRA_table_body textarea').prop('disabled', false);
                    } else if (response.phaseData === "pr") {
                        console.log("AQ2");
                    } else if (response.phaseData === "eval") {
                        console.log("AQ3");
                        $('#KRA_table_body textarea').prop('readonly', true);

                        // Disable select elements and add background color to nearest <td> elements
                        $('#KRA_table_body select').prop('disabled', true);

                        $('#KRA_table_body [name$="[KRA_actual_result]"]').prop('readonly', false);
                    } else if (response.locked === "lock") {
                        console.log("AQ4");

                        $('input[type="radio"]').prop('disabled', true);
                        $('textarea').prop('disabled', true);
                    }

                    console.log("LOCK");
                    console.log(response.locked);
                    if (response.locked === "kra") {
                        $('input[type="radio"]').prop('disabled', true);
                        $('textarea').prop('disabled', true);
                        $('#KRA_table_body textarea').prop('disabled', false);
                    } else if (response.locked === "pr") {

                    } else if (response.locked === "eval") {

                    } else if (response.locked === "lock") {
                        $('input[type="radio"]').prop('disabled', true);
                        $('textarea').prop('disabled', true);
                    }
                },
                error: function(xhr, status, error) {
                    if (xhr.responseText) {
                        console.log('Error: ' + xhr.responseText);
                    } else {
                        console.log('An error occurred.');
                    }
                }
            });
        }
    </script>
@endsection

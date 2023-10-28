@extends('layout.master')

@section('title')
    <h1>Appraisal Form</h1>
@endsection

@section('content')
    <!-- Modal -->
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

    <form method="post" action="{{ route('savePEAppraisal') }}" enctype="multipart/form-data" class="needs-validation"
        id="PEappraisalForm">
        <input type="hidden" value="{{ $appraisalId }}" name="appraisalID">

        <div class='content-container'>
            <h2>Instructions</h2>
            <p class='text-justify'>This performance appraisal is designed to improve organizational effectiveness and to
                assist
                the job incumbent in
                his/her job performance as well as his/her professional growth and development. Please take time to evaluate
                the
                job incumbent by completing this evaluation form. Please be reflective and candid in your responses. Kindly
                submit the accomplished form to HRMD on or before the deadline. Your cooperation is highly appreciated.
                Thank
                you.</p>
        </div>
        <div class="content-container">
            <h2>I. Behavioral Competencies</h2>
            <p>Given the following behavioral competencies, you are to assess the incumbent's performance using the scale.
                Put
                No. 1 on the number which corresponds to your answer for each item. Please answer each item truthfully.<br>

                5 - Almost Always 4 - Frequently 3 - Sometimes 2 - Occasionally 1 - Hardly Ever</p>
            <h3>Core Values</h3>
            <h4>Search for Excellence</h4>
            <p>The highest standards of academic excellence and professionalism in service are the hallmarks of our
                educative
                endeavors. We regularly assess and transform our programs to make them effective for leaning, discovery of
                knowledge and community service. Our service ethics manifest strong sense of responsibility, competency,
                efficiency and professional conduct.</p>
            <h4>Sustained Integral Development</h4>
            <p>Education is a lifelong quest whose primary purpose is the full and integral development of the human person.
                We
                are committed to provide programs for holistic development and continuous learning. Networking with other
                educational institutions, government agencies, industries, business and other groups enhances our
                educational
                services.</p>
            @csrf
            <div class="table-responsive">
                <table class='table table-bordered' id="SID_table">
                    <thead>
                        <tr>
                            <th class='extra-small-column'>#</th>
                            <th>Question</th>
                            <th>Performance Level</th>
                        </tr>
                    </thead>
                    <tbody id='SID_table_body'>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td></td>
                            <td class='text-right'>Frequency:</td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <div class="col-auto">
                                        <input class="xxs-column form-control frequency-counter-5 text-center"
                                            type="text" readonly>
                                    </div>
                                    <div class="col-auto">
                                        <input class="xxs-column form-control frequency-counter-4 text-center"
                                            type="text" readonly>
                                    </div>
                                    <div class="col-auto">
                                        <input class="xxs-column form-control frequency-counter-3 text-center"
                                            type="text" readonly>
                                    </div>
                                    <div class="col-auto">
                                        <input class="xxs-column form-control frequency-counter-2 text-center"
                                            type="text" readonly>
                                    </div>
                                    <div class="col-auto">
                                        <input class="xxs-column form-control frequency-counter-1 text-center"
                                            type="text" readonly>
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
        </div>

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
        <div class="table-responsive">
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
        </div>

        <h4>Solidarity</h4>
        <p>Drawn together by a common vision and mission, we believe education is a shared responsibility and a
            collaborative effort where the gifts of persons are valued. Our learning community is a "family" where
            participation, team work, interdependence, communication and dialogue prevail. A culture of appreciation
            builds
            up our community, encouraging us towards excellence and professionalism.</p>
        <div class="table-responsive">
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
        </div>

        <div class="table-responsive">
            <table class='table table-bordered' id='Overall_table'>
                <thead>
                    <tr>
                        <td></td>
                        <td class='text-right'>Overall Behavioral Competencies Total:</td>
                        <td>
                            <div class="d-flex justify-content-center gap-3">
                                <input id="BHTotal" class="small-column form-control total-frequency text-center"
                                    type="text" readonly>
                            </div>
                        </td>
                    </tr>
                </thead>
            </table>
        </div>

        <div class="content-container">
            <h2>II. Key Results Areas & Work Objectives</h2>
            <p>Please review each Key Results Area (KRA) and Work Objectives (WO) of job incumbent and compare such with
                his/her
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
                                The review/assessment indicates that the job holder has achieved greater than fully
                                effective
                                results against all of the performance criteria and indicators as specified in the
                                Performance
                                Agreement and Work plan. Maintained this in all areas of responsibility throughout the
                                performance
                                cycle.</td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>Performance significantly above expectations</td>
                            <td class='text-justify'>Performance fully meets the standards expected for the job. The
                                review/assessment indicates that the job holder has achieved better than fully effective
                                results
                                against more than half of the performance criteria and indicators as specified in the
                                Performance
                                Agreement and Work plan.</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Performance fully effective (and slightly above expectations)</td>
                            <td class='text-justify'>Performance fully meets the standards expected in all areas of the
                                job.
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
                                review/assessment indicates that the job holder has achieved less than fully effective
                                results
                                against more than half of the performance criteria and indicators as specified in the
                                Performance
                                Agreement and Work plan.</td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>Unacceptable performance</td>
                            <td class='text-justify'>Performance does not meet the standard expected for the job. The
                                review/assessment indicates that the job holder has achieved less than fully effective
                                results
                                against almost all of the performance criteria and indicators as specified in Performance
                                Agreement
                                and Work plan. </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="table-responsive">
                <table class='table table-bordered' id="kra_table">
                    <thead>
                        <tr>
                            <th class='large-column' id="krainput">KRA</th>
                            <th class='xxs-column'>Weight</th>
                            <th class='large-column' id="obj">Objectives</th>
                            <th class='large-column' id="pi">Performance Indicators</th>
                            <th class='large-column' id="ar">Actual Results</th>
                            <th class='medium-column'>Performance Level</th>
                            <th class="xxs-column" id="wt">Weighted Total</th>
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
                        </tr>
                    </tfoot>
                </table>
            </div>

        </div>
        <div class="content-container">
            <h2>III. Future Performance Agenda</h2>
            <h3>Work Performance Plans</h3>
            <p>Identify work behaviors that the job incumbent needs to:</p>
            <div class="table-responsive">
                <table class='table table-bordered' id='wpa-table'>
                    <thead>
                        <tr>
                            <th>Continue Doing</th>
                            <th>Stop Doing</th>
                            <th>Start Doing</th>
                        </tr>
                    </thead>
                    <tbody id='wpa_table_body'>

                    </tbody>
                </table>
            </div>

            <h3>Learning Development Plans</h3>
            <p>Identify the learning needs of the job incumbent likewise recommend specific learning methodologies for
                each
                need
                that you have mentioned.</p>
            <div class="table-responsive">
                <table class='table table-bordered' id='ldp_table'>
                    <thead>
                        <tr>
                            <th>Learning Need</th>
                            <th>Methodology</th>
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
            </div>

        </div>

        <div class="content-container">
            <h2>IV. Job Incumbent's Comments</h2>
            <div class="table-responsive">
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
                                <textarea class='textarea' value="I agree with my performance rating."
                                    name="feedback[1][{{ $appraisalId }}][question]" readonly></textarea>
                            </td>
                            <td>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio"
                                        name="feedback[1][{{ $appraisalId }}][answer]" id="inlineRadio1"
                                        value="1">
                                    <label class="form-check-label" for="inlineRadio1">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio"
                                        name="feedback[1][{{ $appraisalId }}][answer]" id="inlineRadio2"
                                        value="0">
                                    <label class="form-check-label" for="inlineRadio2">No</label>
                                </div>
                            </td>
                            <td class="td-textarea">
                                <div class="position-relative">
                                    <textarea class="textarea form-control" name="feedback[1][{{ $appraisalId }}][comment]"></textarea>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class='text-justify'>
                                <textarea class='textarea'
                                    value="My future work objectives and learning opportunities have been set for the
                            next review period."
                                    name="feedback[2][{{ $appraisalId }}][question]" readonly></textarea>
                            </td>
                            <td>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio"
                                        name="feedback[2][{{ $appraisalId }}][answer]" id="inlineRadio1"
                                        value="1">
                                    <label class="form-check-label" for="inlineRadio1">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio"
                                        name="feedback[2][{{ $appraisalId }}][answer]" id="inlineRadio2"
                                        value="0">
                                    <label class="form-check-label" for="inlineRadio2">No</label>
                                </div>
                            </td>
                            <td class="td-textarea">
                                <div class="position-relative">
                                    <textarea class="textarea form-control" name="feedback[2][{{ $appraisalId }}][comment]"></textarea>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class='text-justify'>
                                <textarea class='textarea' value="I am satisfied with the performance review discussion."
                                    name="feedback[3][{{ $appraisalId }}][question]" readonly></textarea>
                            </td>
                            <td>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio"
                                        name="feedback[3][{{ $appraisalId }}][answer]" id="inlineRadio1"
                                        value="1">
                                    <label class="form-check-label" for="inlineRadio1">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio"
                                        name="feedback[3][{{ $appraisalId }}][answer]" id="inlineRadio2"
                                        value="0">
                                    <label class="form-check-label" for="inlineRadio2">No</label>
                                </div>
                            </td>
                            <td class="td-textarea">
                                <div class="position-relative">
                                    <textarea class="textarea form-control" name="feedback[3][{{ $appraisalId }}][comment]"></textarea>
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
                                    <input class="form-check-input" type="radio"
                                        name="feedback[4][{{ $appraisalId }}][answer]" id="inlineRadio1"
                                        value="1">
                                    <label class="form-check-label" for="inlineRadio1">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio"
                                        name="feedback[4][{{ $appraisalId }}][answer]" id="inlineRadio2"
                                        value="0">
                                    <label class="form-check-label" for="inlineRadio2">No</label>
                                </div>
                            </td>
                            <td class="td-textarea">
                                <div class="position-relative">
                                    <textarea class="textarea form-control" name="feedback[4][{{ $appraisalId }}][comment]"></textarea>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="modal fade" id="signatory_modal" data-bs-backdrop="static">
            <div class="modal-dialog">
                <div class="modal-content" id="signatory">
                    <div class="modal-header">
                        <h5 class="modal-title fs-5">Signatories</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid" id="instructioncon">
                            <div class="table-responsive" id="signaturecon">
                                <table class="table" id="signtable">
                                    <thead>
                                        <tr>
                                            <th scope="col" style="width:20%" id="partieshead">PARTIES</th>
                                            <th scope="col" style="width:20%" id="fullnamehead">FULL NAME</th>
                                            <th scope="col" style="width:25%" id="signhead">SIGNATURE</th>
                                            <th scope="col" style="width:15%" id="datehead">DATE</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="signature-row" data-appraisal-id="{{ $appraisalId }}">
                                            <td id="partiescell">Evaluator</td>
                                            <td id="fullnamecell"></td>
                                            <td id='signcell' class="sign-cell">
                                                <img src="" width="100" id="signatureImage" />
                                            </td>
                                            <td id="datecell" class="date-cell"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="button" id="submit-btn-sign" class="btn btn-primary">Submit</button>
                                    </div> -->
                </div>
            </div>
        </div>

        <div class="modal fade" id="confirmation-popup-modal" data-bs-backdrop="static">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fs-5">CONFIRMATION MESSAGE</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h6 class="text-dark fs-5" id="confirmation-popup">Would you like to submit the form?</h6>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" id="submit-btn-confirm" class="btn btn-primary">Confirm</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-center gap-3 p-3">
            <button type="button" class="btn btn-primary medium-column btn-lg " id="submit-btn-form">View
                Signature</button>
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

            $(document).on('change', '#SID_table input[type="radio"]', function() {
                updateFrequencyCounter('SID_table');
            });

            $(document).on('change', '#SR_table input[type="radio"]', function() {
                updateFrequencyCounter('SR_table');
                updateBHTotal();
            });

            $(document).on('change', '#S_table input[type="radio"]', function() {
                updateFrequencyCounter('S_table');
                updateBHTotal();
            });

            $(document).on('change', '#KRA_table_body input[type="radio"]', function() {
                updateWeightedTotal();
                updateBHTotal();
            });

            loadTableData();

            updateFrequencyCounter('SID_table');
            updateFrequencyCounter('SR_table');
            updateFrequencyCounter('S_table');

            updateBHTotal();
            updateWeightedTotal();

            $('input[type="radio"]').prop('disabled', true);
            $('textarea').prop('disabled', true);

            document.getElementById('submit-btn-form').addEventListener('click', function(event) {
                var form = document.getElementById('PEappraisalForm');
                var valid = true;

                var inputElements = form.querySelectorAll('input:not([type="hidden"])');

                inputElements.forEach(function(inputElement) {
                    if (inputElement.classList.contains('is-invalid')) {
                        // Handle your validation logic here
                        valid = false;
                        console.error('Validation failed for', inputElement.name, ':', inputElement
                            .validationMessage);
                        inputElement.focus(); // Corrected line
                    }
                });

                if (!valid || !form.checkValidity()) {
                    event.preventDefault(); // Prevent the form from submitting
                    event.stopPropagation();

                    var invalidInputs = form.querySelectorAll('.is-invalid');

                    // Handle invalid inputs, display error messages, etc.
                    invalidInputs.forEach(function(invalidInput) {
                        // Handle validation messages for invalid inputs
                        console.error('Validation failed for', invalidInput.name, ':', invalidInput
                            .validationMessage);
                    });

                    // Optionally, focus on the first invalid input
                    invalidInputs[0].focus();

                    console.error('Form validation failed.');
                } else {
                    // Form validation succeeded
                    console.info('Form validation succeeded.');
                    // Set a flag or trigger the modal opening here
                    openModal();
                }
            });

            // Function to open the modal
            function openModal() {
                // console.log('submit clicked');
                $('#signatory_modal').modal('show');
            }
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

                            input[0].disabled = true;

                            // Use stored value if available
                            if (score !== null && Math.round(score * 100) === i * 100) {
                                input.prop('checked', true);
                            }

                            var span = $('<span>').addClass('ms-1').text(i);
                            label.append(input, span);
                            performanceLevelDiv.append($('<div>').addClass('col-auto').append(label));

                            // Attach event listener to input for highlighting the row on invalid
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

                            input[0].disabled = true;

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

                            input[0].disabled = true;

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
        }

        $.ajax({
            url: '{{ route('getPEKRA') }}',
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
                        var rowCount = $('#kra_table tbody tr').length;

                        if (rowCount === 1) {
                            $('#kra_table tbody tr .delete-btn').prop('disabled', true);
                        }
                    } else {
                        // console.log(data);
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
                                    'KRA[' + kraID + '][' + {{ $appraisalId }} + '][KRA]',
                                    kra.kra,
                                    true
                                )
                            ).appendTo(row);

                            $('<td>').addClass('td-textarea').append(
                                createTextArea(
                                    'KRA[' + kraID + '][' + {{ $appraisalId }} +
                                    '][KRA_weight]',
                                    kra.kra_weight,
                                    true
                                )
                            ).appendTo(row);

                            $('<td>').addClass('td-textarea').append(
                                createTextArea(
                                    'KRA[' + kraID + '][' + {{ $appraisalId }} +
                                    '][KRA_objective]',
                                    kra.objective,
                                    true
                                )
                            ).appendTo(row);

                            $('<td>').addClass('td-textarea').append(
                                createTextArea(
                                    'KRA[' + kraID + '][' + {{ $appraisalId }} +
                                    '][KRA_performance_indicator]',
                                    kra.performance_indicator,
                                    true
                                )
                            ).appendTo(row);

                            $('<td>').addClass('td-textarea').append(
                                createTextArea(
                                    'KRA[' + kraID + '][' + {{ $appraisalId }} +
                                    '][KRA_actual_result]',
                                    kra.actual_result,
                                    true
                                )
                            ).appendTo(row);

                            var performanceCell = $('<td>').appendTo(row);
                            var performanceLevelDiv = $('<div>').addClass(
                                'd-flex justify-content-center gap-2').appendTo(performanceCell);

                            for (var i = 5; i >= 1; i--) {
                                var label = $('<label>').addClass('form-check-label');
                                var input = $('<input>').prop('disabled', true).attr({
                                    type: 'radio',
                                    name: 'KRA[' + kraID + '][' + {{ $appraisalId }} +
                                        '][KRA_performance_level]',
                                    class: 'form-check-input',
                                    value: i
                                });

                                input[0].disabled = true;

                                // performanceLevelDiv.append($('<div>').addClass('col-auto').append(
                                //     label));

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

                                var span = $('<span>').addClass('ms-1').text(i);

                                label.append(input, span);

                                var kraPL = parseFloat(kra.performance_level);

                                if (Math.abs(kraPL - i) < 0.01) {
                                    input.prop('checked', true);
                                }

                                $('<div>').addClass('col-auto').append(label).appendTo(
                                    performanceLevelDiv);
                            }

                            $('<td>').addClass('td-textarea').append(
                                createTextArea(
                                    'KRA[' + kraID + '][' + {{ $appraisalId }} +
                                    '][KRA_weighted_total]',
                                    kra.weighted_total,
                                    true
                                )
                            ).appendTo(row);

                            tbody.append(row);

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
                            $('#wpa_table_body tr .delete-btn').prop('disabled', true);
                        }
                    } else {
                        data.wpaData.forEach(function(wpa, index) {
                            var wpaID = wpa.performance_plan_id;

                            var wparow = $('<tr>').addClass('align-middle');

                            $('<td>').addClass('td-textarea').append(
                                createTextArea(
                                    'WPA[' + wpaID + '][' + {{ $appraisalId }} +
                                    '][continue_doing]',
                                    wpa.continue_doing,
                                    false // Set to false to allow user input
                                )
                            ).appendTo(wparow);

                            $('<td>').addClass('td-textarea').append(
                                createTextArea(
                                    'WPA[' + wpaID + '][' + {{ $appraisalId }} + '][stop_doing]',
                                    wpa.stop_doing,
                                    false // Set to false to allow user input
                                )
                            ).appendTo(wparow);

                            $('<td>').addClass('td-textarea').append(
                                createTextArea(
                                    'WPA[' + wpaID + '][' + {{ $appraisalId }} + '][start_doing]',
                                    wpa.start_doing,
                                    false // Set to false to allow user input
                                )
                            ).appendTo(wparow);

                            wpatbody.append(wparow);
                        });
                        $('#wpa_table_body input[type="text"], #wpa_table_body textarea').trigger(
                            'input'); // Trigger input event
                    }

                    $('#ldp_table_body').empty();
                    var ldptbody = $('#ldp_table_body');

                    if (data.ldpData.length === 0) {
                        // Add a new empty row if there are no rows
                        addNewLDPRow(ldptbody);
                        var ldprowCount = $('#ldp_table_body tr').length;

                        if (ldprowCount === 1) {
                            $('#ldp_table_body tr .delete-btn').prop('disabled', true);
                        }
                    } else {
                        data.ldpData.forEach(function(ldp, index) {
                            var ldpID = ldp.development_plan_id;

                            var ldprow = $('<tr>').addClass('align-middle');
                            $('<td>').addClass('td-textarea').append(
                                createTextArea(
                                    'LDP[' + ldpID + '][' + {{ $appraisalId }} +
                                    '][learning_need]',
                                    ldp.learning_need,
                                    false // Set to false to allow user input
                                )
                            ).appendTo(ldprow);

                            $('<td>').addClass('td-textarea').append(
                                createTextArea(
                                    'LDP[' + ldpID + '][' + {{ $appraisalId }} + '][methodology]',
                                    ldp.methodology,
                                    false // Set to false to allow user input
                                )
                            ).appendTo(ldprow);

                            ldptbody.append(ldprow);
                        });
                        $('#ldp_table_body input[type="text"], #ldp_table_body textarea').trigger(
                            'input'); // Trigger input event
                    }

                    // Loop through the jicData and populate the table rows with data
                    data.jicData.forEach(function(jic, index) {
                        var row = document.querySelectorAll('#jic_table_body tr')[index];

                        if (row) {
                            var answerRadioYes = row.querySelector('input[name="feedback[' + (index +
                                1) + '][{{ $appraisalId }}][answer]"][value="1"]');
                            var answerRadioNo = row.querySelector('input[name="feedback[' + (index +
                                1) + '][{{ $appraisalId }}][answer]"][value="0"]');

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

                            var commentTextarea = row.querySelector('.textarea[name="feedback[' + (
                                index +
                                1) + '][{{ $appraisalId }}][comment]"]');
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

                            answerRadioYes.disabled = true;
                            answerRadioNo.disabled = true;
                            commentTextarea.disabled = true;
                        } else {
                            // console.log('Row not found for index ' + index);
                        }
                    });

                    data.signData.forEach(function(sign, index) {
                        var appraisalId = sign.appraisal_id;
                        var row = document.querySelector('[data-appraisal-id="' + appraisalId + '"]');

                        if (row) {
                            var fullnamecell = row.querySelector('#fullnamecell');

                            if (sign.sign_data) {
                                var fullnamecell = document.querySelector('#fullnamecell');
                                var appraisal = sign.appraisal;
                                var employee = appraisal.employee;

                                if (employee.employee_id) {
                                    // Check if all properties are defined before accessing them
                                    fullnamecell.textContent = employee.first_name + ' ' + employee
                                        .last_name;
                                }
                            }

                            var signatureImage = document.createElement('img');
                            if (sign.sign_data) {
                                // Validation for signature data
                                $('#signatureImage').attr('src', sign.sign_data);
                                signatureImage.width = 100;
                            } else {
                                var errorText = document.createElement('p');
                                errorText.textContent = 'Invalid signature data';
                                errorText.classList.add('text-danger', 'fw-bold');
                                signCell.appendChild(errorText);
                            }

                            var signCell = row.querySelector('.sign-cell');
                            var signatureImage = document.createElement('img');
                            if (sign.sign_data) {
                                // Validation for signature data
                                $('#signatureImage').attr('src', sign.sign_data);
                                signatureImage.width = 100;
                                signCell.appendChild(signatureImage);
                            } else {
                                var errorText = document.createElement('p');
                                errorText.textContent = 'Invalid signature data';
                                errorText.classList.add('text-danger', 'fw-bold');
                                signCell.appendChild(errorText);
                            }

                            var dateCell = document.querySelector('.date-cell');

                            if (sign.updated_at) {
                                // Validation for date data
                                // Convert the timestamp to a JavaScript Date object
                                var timestamp = new Date(sign.updated_at);

                                // Format the date as a string (e.g., "YYYY-MM-DD HH:MM:SS")
                                var formattedDate = timestamp
                                    .toLocaleString(); // You can customize the format further if needed

                                dateCell.textContent = formattedDate;
                            } else {
                                // Handle invalid or missing date data
                                dateCell.textContent = 'Invalid date';
                                dateCell.classList.add('text-danger', 'fw-bold');
                            }
                        }
                    });
                } else {
                    console.error('Data retrieval failed.');
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });

        function createTextArea(name, value, isReadonly) {
            return $('<div>').addClass('position-relative').append(
                $('<textarea>').addClass('textarea form-control').attr({
                    name: name,
                    disable: isReadonly
                }).prop('disabled', true).val(value)
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
            var nextKRAID = 0;

            var row = $('<tr>').addClass('align-middle');
            $('<input>').attr({
                type: 'hidden',
                name: 'KRA[' + nextKRAID + '][' + {{ $appraisalId }} +
                    '][kraID]',
                value: nextKRAID
            }).appendTo(row);

            $('<td>').addClass('td-textarea').append(
                $('<textarea>').addClass('textarea').attr('name', 'KRA[' +
                    nextKRAID +
                    '][' + {{ $appraisalId }} + '][KRA]').prop('readonly',
                    false)
                .prop('readonly', true)
            ).appendTo(row);

            $('<td>').addClass('td-textarea').append(
                $('<textarea>').addClass('textarea').attr('name', 'KRA[' +
                    nextKRAID +
                    '][' +
                    {{ $appraisalId }} + '][KRA_weight]').prop('readonly', true)
            ).appendTo(row);

            $('<td>').addClass('td-textarea').append(
                $('<textarea>').addClass('textarea').attr('name', 'KRA[' +
                    nextKRAID +
                    '][' +
                    {{ $appraisalId }} + '][KRA_objective]').prop('readonly', true)
            ).appendTo(row);

            $('<td>').addClass('td-textarea').append(
                $('<textarea>').addClass('textarea').attr('name', 'KRA[' +
                    nextKRAID +
                    '][' +
                    {{ $appraisalId }} + '][KRA_performance_indicator]').prop(
                    'readonly', true)
            ).appendTo(row);

            $('<td>').addClass('td-textarea').append(
                $('<textarea>').addClass('textarea').attr('name', 'KRA[' +
                    nextKRAID +
                    '][' +
                    {{ $appraisalId }} + '][KRA_actual_results]').prop('readonly', true)
            ).appendTo(row);

            var performanceCell = $('<td>').appendTo(row);
            var performanceLevelDiv = $('<div>').addClass(
                'd-flex justify-content-center gap-2').appendTo(performanceCell);
            for (var i = 5; i >= 1; i--) {
                var label = $('<label>').addClass('form-check-label');
                var input = $('<input>').prop('disabled', true).attr({
                    type: 'radio',
                    name: 'KRA[' + nextKRAID + '][' + {{ $appraisalId }} +
                        '][KRA_performance_level]',
                    class: 'form-check-input',
                    value: i
                });

                input[0].disabled = true;

                label.append(input, i);
                $('<div>').addClass('col-auto').append(label).appendTo(
                    performanceLevelDiv);
            }

            $('<td>').addClass('td-textarea').append(
                $('<textarea>').addClass('textarea').attr('name', 'KRA[' +
                    nextKRAID +
                    '][' +
                    {{ $appraisalId }} + '][KRA_weighted_total]').prop('readonly', true)
            ).appendTo(row);

            tbody.append(row);

            row.find('input[type="radio"][name^="KRA[' + nextKRAID + '][' +
                    {{ $appraisalId }} + '][KRA_performance_level]"]')
                .trigger('change');

            row.find('select[name^="KRA[' + nextKRAID + '][' + {{ $appraisalId }} +
                    '][KRA_weight]"]')
                .trigger('change');

            tbody.append(row);
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
            var nextWpaID = highestWpaID + 1;

            var wparow = $('<tr>').addClass('align-middle');
            $('<td>').addClass('td-textarea').append(
                $('<textarea>').addClass('textarea').attr('name', 'WPA[' +
                    nextWpaID +
                    '][' + {{ $appraisalId }} + '][continue_doing]').prop('readonly',
                    false)
            ).appendTo(wparow);

            $('<td>').addClass('td-textarea').append(
                $('<textarea>').addClass('textarea').attr('name', 'WPA[' +
                    nextWpaID +
                    '][' + {{ $appraisalId }} + '][stop_doing]').prop('readonly',
                    false)
            ).appendTo(wparow);

            $('<td>').addClass('td-textarea').append(
                $('<textarea>').addClass('textarea').attr('name', 'WPA[' +
                    nextWpaID +
                    '][' + {{ $appraisalId }} + '][start_doing]').prop('readonly',
                    false)
            ).appendTo(wparow);

            wpatbody.append(wparow);
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

            // Calculate the next available wpaID
            var nextLDPID = highestLDPID + 1;

            var ldprow = $('<tr>').addClass('align-middle');
            $('<td>').addClass('td-textarea').append(
                $('<textarea>').addClass('textarea').attr('name', 'LDP[' +
                    nextLDPID +
                    '][' + {{ $appraisalId }} + '][learning_need]').prop(
                    'readonly',
                    false)
            ).appendTo(ldprow);

            $('<td>').addClass('td-textarea').append(
                $('<textarea>').addClass('textarea').attr('name', 'LDP[' +
                    nextLDPID +
                    '][' + {{ $appraisalId }} + '][methodology]').prop(
                    'readonly',
                    false)
            ).appendTo(ldprow);

            ldptbody.append(ldprow);
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

        function updateBHTotal() {
            var BHtotal = 0;
            var BHquestionCount = 0;

            $('#SID_table tbody tr, #SR_table tbody tr, #S_table tbody tr').each(function() {
                // Count the rows
                BHquestionCount++;

                var selectedRadio = $(this).find('input[type="radio"]:checked');
                if (selectedRadio.length > 0) {
                    var value = parseFloat(selectedRadio.val());
                    if (!isNaN(value)) {
                        BHtotal += value;
                    }
                }
            });

            // Ensure questionCount is greater than zero to avoid division by zero
            var BHOveralltotal = BHquestionCount > 0 ? BHtotal / BHquestionCount : 0;

            $('#BHTotal').val(BHOveralltotal.toFixed(2));
        }

        function updateWeightedTotal() {
            // console.log("updateWeightedTotal() called");

            var totalWeight = 0;
            var totalWeighted = 0;

            $('#KRA_table_body tr').each(function() {
                var row = $(this);
                var weight = parseFloat(row.find('textarea[name^="KRA"][name$="[KRA_weight]"]').val()) /
                    100;
                var performanceLevel = parseInt(row.find(
                        'input[type="radio"][name^="KRA"][name$="[KRA_performance_level]"]:checked')
                    .val());

                if (!isNaN(weight) && !isNaN(performanceLevel)) {
                    var weightedValue = weight * performanceLevel;
                    totalWeight += weight;
                    totalWeighted += weightedValue;

                    // console.log(weightedValue);

                    row.find('textarea[name^="KRA"][name$="[KRA_weighted_total]"]')
                        .val(weightedValue.toFixed(2));

                }
            });

            totalWeight = totalWeight * 100; // Convert back to percentage for comparison

            if (totalWeight > 100) {
                isTotalWeightInvalid = true;
                $('#KRA_Weight_Total').addClass('is-invalid');
                $('textarea[name^="KRA"][name$="[KRA_weight]"]').addClass('is-invalid');
            } else {
                $('#KRA_Weight_Total').removeClass('is-invalid');
                $('textarea[name^="KRA"][name$="[KRA_weight]"]').removeClass('is-invalid');
            }

            $('#KRA_Weight_Total').val(totalWeight.toFixed(2));
            $('#KRA_Total').val(totalWeighted.toFixed(2));
        }
    </script>
@endsection

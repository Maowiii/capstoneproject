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
            </table>
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
                                            <td id="partiescell">Job Incumbent</td>
                                            <td id="fullnamecell"></td>
                                            <td id='signcell' class="sign-cell">
                                                <input type='file' id="uploadsign_1"
                                                    name="SIGN[JI][{{ $appraisalId }}]" class="form-control"
                                                    accept='image/jpeg, image/png, image/jpg'>
                                                <img src="" width="100" id="signatureImage" />
                                            </td>
                                            <td id="datecell" class="date-cell"></td>
                                        </tr>
                                        <tr>
                                            <td id="partiescell">Immediate Superior</td>
                                            <td id="fullnamecell"></td>
                                            <td id='signcell'></td>
                                            <td id="datecell"></td>
                                        </tr>
                                        <tr>
                                            <td id="partiescell">Next Higher Superior</td>
                                            <td id="fullnamecell"></td>
                                            <td id='signcell'></td>
                                            <td id="datecell" style="width:15%"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <h3>Notation</h3>
                            <div class="table-responsive" id="signaturecon">
                                <table class="table" id="signtable">
                                    <tbody>
                                        <tr>
                                            <td id="partiescell" style="width:20%">HRMD Director</td>
                                            <td id="fullnamecell" style="width:20%"></td>
                                            <td id="signcell" style="width:25%"></td>
                                            <td id="datecell" style="width:15%"></td>
                                        </tr>
                                        <tr>
                                            <td id="partiescell">VP for Administrative Affairs</td>
                                            <td id="fullnamecell"></td>
                                            <td id="signcell" style="width:25%"></td>
                                            <td id="datecell" style="width:15%"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" id="submit-btn-sign" class="btn btn-primary">Submit</button>
                    </div>
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

        <div class="d-flex justify-content-center gap-3">
            <button type="button" class="btn btn-outline-primary medium-column" id="save-btn">Save</button>
            <button type="button" class="btn btn-primary medium-column" id="submit-btn-form">Submit</button>
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
                        row.remove();
                        updateWeightedTotal();
                        var rowCount = $('#kra_table tbody tr').length;
                        if (rowCount === 1) {
                            $('#kra_table tbody tr .delete-btn').prop('disabled', true);
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
                console.log(ldpID);
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

                        var rowCount = $('#ldp_table_body tr').length;
                        if (rowCount === 1) {
                            $('#ldp_table tbody tr .ldp-delete-btn').prop('disabled', true);
                        }

                        console.log(rowCount);
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

            loadTableData();

            updateFrequencyCounter('SID_table');
            updateFrequencyCounter('SR_table');
            updateFrequencyCounter('S_table');

            updateWeightedTotal();

            // Validation code
            document.getElementById('submit-btn-form').addEventListener('click', function(event) {
                var form = document.getElementById('PEappraisalForm');
                var invalidRows = [];

                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();

                    var invalidInputs = form.querySelectorAll('.is-invalid');
                    if (invalidInputs.length > 0) {
                        invalidInputs[0].focus(); // Focus on the first invalid input
                    }

                    invalidInputs.forEach(function(invalidInput) {
                        invalidInput.scrollIntoView({
                            behavior: 'smooth'
                        });

                        // Log the custom error message for this input
                        console.error('Validation failed for', invalidInput.name, ':', invalidInput
                            .validationMessage);
                    });

                    console.error('Form validation failed.');
                    return;
                }

                var allRowsCorrected = invalidRows.every(function(invalidRow) {
                    return !invalidRow.hasClass('is-invalid');
                });

                if (allRowsCorrected) {
                    $('.is-invalid').removeClass('is-invalid');
                    $('.text-danger').removeClass('text-danger fw-bold');

                    var signInput = document.querySelector('input[name="SIGN[JI][{{ $appraisalId }}]"]');
                    signInput.required = true;

                    $('#signatory_modal').modal('show');
                    console.info('Form validation succeeded. Signature modal will open.');
                } else {
                    console.error('Please correct all invalid rows.');
                }
            });

            // Add event listener to the submit button in signatory_modal
            document.getElementById('submit-btn-sign').addEventListener('click', function(event) {
                var signInput = document.querySelector('input[name="SIGN[JI][{{ $appraisalId }}]"]');

                // Validate the sign input
                if (!signInput.value) {
                    event.preventDefault();
                    event.stopPropagation();

                    signInput.classList.add('is-invalid');
                    signInput.closest('td').classList.add('border', 'border-danger');

                    signInput.scrollIntoView({
                        behavior: 'smooth'
                    });

                    console.error('Signature validation failed.');
                    return;
                }

                if (signInput.value) {
                    console.log('if else');
                    signInput.classList.remove('is-invalid');
                    signInput.closest('td').classList.remove('border', 'border-danger');
                }

                $('#signatory_modal').modal('hide');
                $('#confirmation-popup-modal').modal('show');
            });

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
                        fieldValue: fieldValue
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

            var debounceTimer;

$('#ldp_table_body').on('input change blur', '.autosave-field', function(event) {
    var field = $(this);
    var ldpID = field.attr('name').match(/\d+/)[0];
    var fieldName = field.attr('name').split('][')[2].replace(/\]/g, '');
    var fieldValue = field.val();

    if (event.type === 'input') {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(function() {
            processBatchChanges(field, ldpID, fieldName, fieldValue);
        }, 500); // Adjust the debounce delay as needed
    } else {
        processBatchChanges(field, ldpID, fieldName, fieldValue);
    }
});

function processBatchChanges(field, ldpID, fieldName, fieldValue) {
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
            // Handle the success response if needed

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
}


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
                                );

                                $.ajax({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]')
                                            .attr('content')
                                    },
                                    url: '{{ route('saveICScores') }}',
                                    type: 'POST',
                                    data: {
                                        questionId: questionId,
                                        score: $(this).val(),
                                        appraisalId: {{ $appraisalId }}
                                    },
                                    success: function(response) {
                                        console.log('Score saved for question ID:',
                                            questionId);
                                    },
                                    error: function(xhr) {
                                        if (xhr.responseText) {
                                            console.log('Error: ' + xhr
                                                .responseText);
                                        } else {
                                            console.log('An error occurred.');
                                        }
                                    }
                                });
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

                                $.ajax({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]')
                                            .attr('content')
                                    },
                                    url: '{{ route('saveICScores') }}',
                                    type: 'POST',
                                    data: {
                                        questionId: questionId,
                                        score: $(this).val(),
                                        appraisalId: {{ $appraisalId }}
                                    },
                                    success: function(response) {
                                        console.log('Score saved for question ID:',
                                            questionId);
                                    },
                                    error: function(xhr) {
                                        if (xhr.responseText) {
                                            console.log('Error: ' + xhr
                                                .responseText);
                                        } else {
                                            console.log('An error occurred.');
                                        }
                                    }
                                });
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

                                $.ajax({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]')
                                            .attr('content')
                                    },
                                    url: '{{ route('saveICScores') }}',
                                    type: 'POST',
                                    data: {
                                        questionId: questionId,
                                        score: $(this).val(),
                                        appraisalId: {{ $appraisalId }}
                                    },
                                    success: function(response) {
                                        console.log('Score saved for question ID:',
                                            questionId);
                                    },
                                    error: function(xhr) {
                                        if (xhr.responseText) {
                                            console.log('Error: ' + xhr
                                                .responseText);
                                        } else {
                                            console.log('An error occurred.');
                                        }
                                    }
                                });
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
                                    '][KRA_kra_weight]',
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
                                performanceLevelDiv.append($('<div>').addClass('col-auto').append(
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

                            $('<td>').addClass('td-textarea').append(
                                createTextArea(
                                    'KRA[' + kraID + '][' + {{ $appraisalId }} +
                                    '][KRA_weighted_total]',
                                    kra.weighted_total,
                                    true
                                )
                            ).appendTo(row);

                            tbody.append(row);

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
                            wparow.attr('data-wpa-id', wpaID);

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
                                .attr('type', 'button')
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
                            ldprow.attr('data-ldp-id', ldpID);

                            $('<td>').addClass('td-textarea').append(
                                createTextArea(
                                    'LDP[' +
                                    ldpID +
                                    '][' + {{ $appraisalId }} + '][learning_need]',
                                    ldp.learning_need,
                                    false
                                )
                            ).appendTo(ldprow);

                            $('<td>').addClass('td-textarea').append(
                                createTextArea(
                                    'LDP[' +
                                    ldpID +
                                    '][' + {{ $appraisalId }} + '][methodology]',
                                    ldp.methodology,
                                    false
                                )
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

                    data.signData.forEach(function(sign, index) {
                        var appraisalId = sign.appraisal_id;
                        var row = document.querySelector('[data-appraisal-id="' + appraisalId + '"]');

                        if (row) {
                            var signCell = row.querySelector('.sign-cell');
                            var signatureImage = document.createElement('img');

                            if (sign.sign_data) {
                                // Validation for signature data
                                signatureImage.src = 'data:image/jpeg;base64,' + sign.sign_data;
                                signatureImage.width = 100;
                                signCell.appendChild(signatureImage);
                            } else {
                                var errorText = document.createElement('p');
                                errorText.textContent = 'Invalid signature data';
                                errorText.classList.add('text-danger', 'fw-bold');
                                signCell.appendChild(errorText);
                            }

                            var dateCell = row.querySelector('.date-cell');

                            if (sign.updated_at) {
                                // Validation for date data
                                dateCell.textContent = sign.updated_at;
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
            var highestKRAID = 0;
            tbody.find('[name^="KRA["]').each(function() {
                var nameAttr = $(this).attr('name');
                var matches = nameAttr.match(/\[([0-9]+)\]/);
                if (matches && matches.length > 1) {
                    var kraID = parseInt(matches[1]);
                    if (kraID > highestKRAID) {
                        highestKRAID = kraID;
                    }
                }
            });

            // Calculate the next available wpaID
            var nextKRAID = highestKRAID + 1;

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
                    {{ $appraisalId }} + '][KRA_kra_weight]').prop('readonly', true)
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
                    {{ $appraisalId }} + '][KRA_actual_results]').prop('readonly', false)
            ).appendTo(row);

            var performanceCell = $('<td>').appendTo(row);
            var performanceLevelDiv = $('<div>').addClass(
                'd-flex justify-content-center gap-2').appendTo(performanceCell);
            for (var i = 5; i >= 1; i--) {
                var label = $('<label>').addClass('form-check-label');
                var input = $('<input>').prop('readonly', true).attr({
                    type: 'radio',
                    name: 'KRA[' + nextKRAID + '][' + {{ $appraisalId }} +
                        '][KRA_performance_level]',
                    class: 'form-check-input',
                    value: i
                });
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
                var weight = parseFloat(row.find('textarea[name^="KRA"][name$="[KRA_weight]"]').val()) /
                    100;
                var performanceLevel = parseInt(row.find(
                        'input[type="radio"][name^="KRA"][name$="[KRA_performance_level]"]:checked')
                    .val());

                if (!isNaN(weight) && !isNaN(performanceLevel)) {
                    var weightedValue = weight * performanceLevel;
                    totalWeight += weight;
                    totalWeighted += weightedValue;

                    console.log(weightedValue);

                    row.find('textarea[name^="KRA"][name$="[KRA_weighted_total]"]').val(weightedValue
                        .toFixed(
                            2));

                }
            });

            totalWeight = totalWeight * 100;

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
/*
        function formChecker() {
            var urlParams = new URLSearchParams(window.location.search);
            var appraisalId = urlParams.get('appraisal_id');

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('pe.SEFormChecker') }}',
                type: 'POST',
                data: {
                    appraisalId: appraisalId
                },
                success: function(response) {
                    if (response.form_submitted) {
                        $('input[type="radio"]').prop('disabled', true);
                        $('textarea').prop('disabled', true);
                        $('#confirmation-alert').addClass('d-none');
                        $('#submit-btn').text('View');
                    } 
                },
                error: function(xhr, status, error) {
                    error: function(xhr) {
                        if (xhr.responseText) {
                            console.log('Error: ' + xhr
                                .responseText);
                        } else {
                            console.log('An error occurred.');
                        }
                    }
                }
            });
        }
        */
    </script>
@endsection
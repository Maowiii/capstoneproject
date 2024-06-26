@extends('layout.master')

@section('title')
    <h1>Appraisal Form</h1>
@endsection

@section('content')
    <!-- Modal -->
    <div class="modal fade mx-xl" id="consentform" data-bs-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="consentform-label">Data Privacy Consent</h1>
                    <button type="button" class="btn-close" onclick="confirmClose()"></button>
                </div>
                <div class="modal-body">
                    <!-- Add this div to display the message -->
                    <div id="closeModalMessage" class="alert alert-danger" style="display: none;">
                        <p></p>
                    </div>

                    <p>In compliance to <b>RA 10173 or the Data Protection Act of 2012 (DPA of 2012) </b> its Implementing
                        Rules and Regulations, we are detailing here the processing of the data you will provide to us.<br>

                        <br>The following are the personal data that the we may need to collect:
                        <br><i>Full Name, Adamson Email, Employee ID, Department</i>

                        <br><br>Personal data collected shall be stored in the <i>system's database</i>
                        for the duration of the employee's tenure with the organization. Upon expiration of such period, all
                        personal data
                        shall be disposed in a secure manner that will forbids further processing, unauthorized disclosure
                        and editing.<br><br>

                        The HR Department of Adamson University shall implement reasonable and appropriate
                        organizational, physical, and technical security measures to protect your personal data.<br><br>
                        Only authorized personnel shall have access to the data collected and processed.<br>

                        <br><b>Under RA 10173</b>, the following are some of the rights the data subject may exercise,
                        (for the full list of rights see: <a
                            href="//privacy.gov.ph/data-subject-rights/">www.privacy.gov.ph/data-subject-rights/</a>)<br>

                        <br><i>1. Right to be informed on the collection and processing of personal data through this
                            consent form;
                            <br>2. Right to object on the processing of personal data or to restrict the processing of
                            personal data upon request;
                            <br>3. Right to access the personal data collected and processed upon request;
                            <br>4. Right to request for rectification of personal data; and
                            <br>5. Right to withdraw his or her consent.</i><br>

                        <br>By participating, you agreed to the following statements :<br>

                        <br><i>a. Understand that there is no risk involved in participating in this endeavor.<br>

                            <br>b. The results of this data gathering will help the HRDMO of Adamson University monitor the
                            improvements of employees per department.<br>


                            <br>c. I understand that I will not receive any form of remuneration or compensation for my
                            participation.<br>

                            <br>d. I also understand that my participation in this survey is purely voluntary and that I
                            can withdraw my approval to this consent anytime and that my withdrawal will not affect my class
                            standing now or in the future.<br>

                            <br>e. I understand that my involvement in the survey is only once.<br>

                            <br>f. All my personal data will
                            be kept strictly confidential and will be revealed only upon my request and consent.<br></i>

                        <br>To exercise data subjects right and for data privacy concerns or inquiries, please
                        communicate with the Human Resources Department and Management Office of Adamson University
                        through <i>HRDMO Adamson Email:<a href="mailto:hrmdo@adamson.edu.ph"> hrmdo@adamson.edu.ph</a></i>
                        <br>
                        <!-- <<contact details of the person in-charge>> -->

                        <br>Thank you for your participation.<br>

                        <!-- <Sample Google Form Elements regarding data privacy>> -->

                        <!-- <br>I give consent to use of my personal data for the said purpose. (Y/N)<br>

                            <br>I give consent to the retention of my personal data. (Y/N)<br>

                            <br>I give consent to the sharing of my data. (Y/N)<br>

                            <br>I confirm that I have read, understand, and agree to the above-mentioned Privacy
                        Agreement.<br>
                                                
                        <br>By clicking Yes, you consent that you are willing to answer the questions
                        in this survey and you answered YES to the three questions above.
                        (Y/N) -->

                    <div class="mb-3">
                        <label class="form-check-label" id="consent1-label">
                            <input type="checkbox" id="consent1" class="form-check-input"> <span>I give consent
                                to use of my personal data for the said purpose.</span>
                        </label>
                    </div>
                    <div class="mb-3">
                        <label class="form-check-label" id="consent2-label">
                            <input type="checkbox" id="consent2" class="form-check-input"> <span>I give consent
                                to the retention of my personal data.</span>
                        </label>
                    </div>
                    <div class="mb-3">
                        <label class="form-check-label" id="consent3-label">
                            <input type="checkbox" id="consent3" class="form-check-input"> <span>I give consent
                                to the sharing of my data.</span>
                        </label>
                    </div>
                    <div class="mb-3">
                        <label class="form-check-label" id="consent4-label">
                            <input type="checkbox" id="consent4" class="form-check-input"> <span>I confirm that
                                I have read, understand, and agree to the above-mentioned Privacy Agreement.<br>
                            </span>

                            <br><i> clicking <b>"I Understand"</b>, you consent that you are willing to answer the questions
                                in this
                                survey and you answered <b>"I Understand"</b> to the four questions above.</i>
                        </label>
                    </div>

                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="confirmClose()">Close</button>
                    <button type="button" class="btn btn-primary" onclick="understood()">I Understand</button>
                </div>
            </div>
        </div>
    </div>

    <div class="position-relative">
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 99;">
            <div id="lockToast" class="toast text-danger" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <h5 class="mr-auto">Appraisal Form Locked</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                    <span aria-hidden="true"></span>
                </div>
                <div class="toast-body">
                    <p>The appraisal form is currently locked and cannot be edited.</p>
                </div>
            </div>
        </div>
    </div>

    @if(session()->has('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

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
                submit the accomplished form to HRMD on or before the  deadline. Your cooperation is highly appreciated.
                Thank
                you.
            </p>
            <div class="d-grid gap-3">
                <button type="button" class="btn btn-primary col-3" id="sendrequest"> <i class="bi bi-envelope-paper"></i> Send Request</button>
                <div id="feedback-container" class="alert alert-info d-none" role="alert">
                    <button type="button" class="btn-close float-end" data-bs-dismiss="alert" aria-label="Close"></button>
                    <strong>Feedback:</strong> <span id="feedback-text"></span>
                    <hr>
                    <div id="additional-info" class="font-italic"></div>
                </div>
            </div>
        </div>

        <div id="progressBarContainer" class="card sticky-top border-0 d-flex p-3 flex-column align-items-center">
            <h5 class="fs-6">Progress Bar</h5>
            <div id="progressBarHandler" class="progress w-75" style="height: 15px;">
                <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-label="Animated striped example" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
            </div>
        </div>

        <div class="content-container">
            <h2>I. Behavioral Competencies</h2>
            <p>
                Given the following behavioral competencies, you are to assess the incumbent's performance using the scale.
                Put
                No. 1 on the number which corresponds to your answer for each item. Please answer each item truthfully.<br>

                5 - Almost Always 4 - Frequently 3 - Sometimes 2 - Occasionally 1 - Hardly Ever
            </p>
            <h3>Core Values</h3>
            <h4>Search for Excellence</h4>
            <p>
                The highest standards of academic excellence and professionalism in service are the hallmarks of our
                educative
                endeavors. We regularly assess and transform our programs to make them effective for leaning, discovery of
                knowledge and community service. Our service ethics manifest strong sense of responsibility, competency,
                efficiency and professional conduct.
            </p>
            <h4>Sustained Integral Development</h4>
            <p>
                Education is a lifelong quest whose primary purpose is the full and integral development of the human person.
                We
                are committed to provide programs for holistic development and continuous learning. Networking with other
                educational institutions, government agencies, industries, business and other groups enhances our
                educational
                services.
            </p>
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
                                <div class="d-flex justify-content-center gap-3">
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

            <h4>Spirit of St. Vincent de Paul</h4>
            <p>
                The spirit of St. Vincent inspires and permeates our learning community, programs and services. This is shown
                in
                our sensitivity to the presence of God, compassionate service and the building of supportive relationships
                towards an effective service to persons in need.
            </p>
            <h4>Social Responsibility</h4>
            <p>
                Education at Adamson aims at developing a sense of social responsibility - a mark of an authentic Christian
                faith. Social responsibility leads us to empower the marginalized sectors of society through the creation of
                knowledge and human development. We are committed to work for the building of a society based on justice,
                peace,
                respect for human dignity and the integrity of creation.
            </p>
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
                                <div class="d-flex justify-content-center gap-3">
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

            <h4>Solidarity</h4>
            <p>
                Drawn together by a common vision and mission, we believe education is a shared responsibility and a
                collaborative effort where the gifts of persons are valued. Our learning community is a "family" where
                participation, team work, interdependence, communication and dialogue prevail. A culture of appreciation
                builds
                up our community, encouraging us towards excellence and professionalism.
            </p>
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
                                <div class="d-flex justify-content-center gap-3">
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
                            <td class='text-justify'>Performance far exceeds the standard expected of a job holder at
                                this
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
                                review/assessment indicates that the job holder has achieved as a minimum effective
                                results
                                against
                                all of the performance criteria and indicators as specified in the Performance Agreement
                                and
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
                                against almost all of the performance criteria and indicators as specified in
                                Performance
                                Agreement
                                and Work plan. </td>
                        </tr>
                    </tbody>
                </table>
                <div class="table-responsive">
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
                                            <input id="KRA_Weight_Total"
                                                class="small-column form-control total-weight" type="text"
                                                readonly>
                                        </div>
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class='text-right'>Total:</td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-3">
                                            <input id="KRA_Total"
                                                class="small-column form-control total-weighted text-center"
                                                type="text" readonly>
                                        </div>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
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
                            <th class='small-column'>Action</th>
                        </tr>
                    </thead>
                    <tbody id='wpa_table_body'>

                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-primary" id="add-wpa-btn">Add Row</button>
                </div>

                <h3>Learning Development Plans</h3>
                <p>Identify the learning needs of the job incumbent likewise recommend specific learning
                    methodologies for
                    each
                    need
                    that you have mentioned.</p>
                <div class="table-responsive">
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
                </div>
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-primary" id="add-ldp-btn">Add Row</button>
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
                                    <textarea class='textarea border-0' value="I agree with my performance rating."
                                        name="feedback[1][{{ $appraisalId }}][question]" readonly></textarea>
                                </td>
                                <td>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input autosave-field" type="radio"
                                            name="feedback[1][{{ $appraisalId }}][answer]" id="inlineRadio1"
                                            value="1" required>
                                        <label class="form-check-label" for="inlineRadio1">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input autosave-field" type="radio"
                                            name="feedback[1][{{ $appraisalId }}][answer]" id="inlineRadio2"
                                            value="0" required>
                                        <label class="form-check-label" for="inlineRadio2">No</label>
                                    </div>
                                </td>
                                <td class="td-textarea">
                                    <div class="position-relative">
                                        <textarea class="textarea form-control border-0 autosave-field jicTA"
                                            name="feedback[1][{{ $appraisalId }}][comments]"></textarea>
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
                                            name="feedback[2][{{ $appraisalId }}][answer]" id="inlineRadio1"
                                            value="1" required>
                                        <label class="form-check-label" for="inlineRadio1">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input autosave-field" type="radio"
                                            name="feedback[2][{{ $appraisalId }}][answer]" id="inlineRadio2"
                                            value="0" required>
                                        <label class="form-check-label" for="inlineRadio2">No</label>
                                    </div>
                                </td>
                                <td class="td-textarea">
                                    <div class="position-relative">
                                        <textarea class="textarea form-control border-0 autosave-field jicTA"
                                            name="feedback[2][{{ $appraisalId }}][comments]"></textarea>
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
                                            name="feedback[3][{{ $appraisalId }}][answer]" id="inlineRadio1"
                                            value="1" required>
                                        <label class="form-check-label" for="inlineRadio1">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input autosave-field" type="radio"
                                            name="feedback[3][{{ $appraisalId }}][answer]" id="inlineRadio2"
                                            value="0" required>
                                        <label class="form-check-label" for="inlineRadio2">No</label>
                                    </div>
                                </td>
                                <td class="td-textarea">
                                    <div class="position-relative">
                                        <textarea class="textarea form-control border-0 autosave-field jicTA"
                                            name="feedback[3][{{ $appraisalId }}][comments]"></textarea>
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
                                            name="feedback[4][{{ $appraisalId }}][answer]" id="inlineRadio1"
                                            value="1" required>
                                        <label class="form-check-label" for="inlineRadio1">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input autosave-field" type="radio"
                                            name="feedback[4][{{ $appraisalId }}][answer]" id="inlineRadio2"
                                            value="0" required>
                                        <label class="form-check-label" for="inlineRadio2">No</label>
                                    </div>
                                </td>
                                <td class="td-textarea">
                                    <div class="position-relative">
                                        <textarea class="textarea form-control border-0 autosave-field jicTA"
                                            name="feedback[4][{{ $appraisalId }}][comments]"></textarea>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal fade modal-lg" id="signatory_modal" data-bs-backdrop="static">
                <div class="modal-dialog">
                    <div class="modal-content" id="signatory">
                        <div class="modal-header">
                            <h5 class="modal-title fs-5">Signatories</h5>
                            <button type="button" class="btn-close common-close-button" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="container-fluid" id="instructioncon">
                                <div class="table-responsive" id="signaturecon">
                                    <table class="table" id="signtable">
                                        <thead>
                                            <tr>
                                                <th scope="col" style="width:20%" id="partieshead">PARTIES
                                                </th>
                                                <th scope="col" style="width:20%" id="fullnamehead">FULL NAME
                                                </th>
                                                <th scope="col" style="width:25%" id="signhead">SIGNATURE
                                                </th>
                                                <th scope="col" style="width:15%" id="datehead">DATE</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="signature-row" data-appraisal-id="{{ $appraisalId }}">
                                                <td id="partiescell">Job Incumbent</td>
                                                <td id="fullnamecell">{{ $appraisee->first_name ?? 'N/A' }}
                                                    {{ $appraisee->last_name ?? '' }}</td>
                                                <td id='signcell' class="sign-cell">
                                                    <input type='file' id="uploadsign"
                                                        name="SIGN[JI][{{ $appraisalId }}][file]" class="form-control"
                                                        accept='image/jpeg, image/png, image/jpg'>

                                                    <input type='hidden' id="uploadsign_1"
                                                        name="SIGN[JI][{{ $appraisalId }}]">

                                                    <img src="" width="100" id="signatureImage" />
                                                </td>
                                                <td id="datecell" class="date-cell"></td>
                                            </tr>
                                            <!-- {{-- <tr>
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
                                            </tr> --}} -->
                                        </tbody>
                                    </table>
                                </div>
                                <!-- {{-- <h3>Notation</h3>
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
                                </div> --}} -->
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
                            <button type="button" class="btn-close common-close-button" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <h6 class="text-dark fs-5" id="confirmation-popup">Would you like to submit the form?
                            </h6>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" id="submit-btn-confirm" class="btn btn-primary">Confirm</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-center gap-3 p-3">
                <button type="button" class="btn btn-primary medium-column" id="submit-btn-form">Submit</button>
            </div>
    </form>

    <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog"
        aria-labelledby="confirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="confirmationModalLabel">Confirm Deletion</h4>
                    <button type="button" class="btn-close common-close-button" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5>Are you sure you want to delete this item?</h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        aria-label="Close">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade modal-lg" id="request-popup-modal" data-bs-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fs-5">REQUEST FORM</h5>
                    <button type="button" class="btn-close common-close-button" aria-label="Close"></button>
                </div>
                <form action="{{ route('submitRequest') }}" method="POST" id="sendReqForm">
                    @csrf
                    <div class="modal-body">
                        <div id="validation-results" class="alert alert-danger" style="display: none;">
                            <ul id="validation-list"></ul>
                        </div>
                        <h5>Instructions:</h5>
                        <p class='text-justify'>This request form is designed to allow send request to have another attempt in accomplishing the appraisal form. 
                        Kindly provide the details of your request and any additional notes in the field provided.Thank you</p>
                    <label for="requestText"><h5>Request:</h5></label>
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
        var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        ///////////////////// JIC(MUST EDIT) ///////////////////////////
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

        ////////////////////////// EULA ///////////////////////////////
        
        let confirmationMode = false;

        function confirmClose() {
            if (confirmationMode) {
                closeModal();
            } else {
                // Show the confirmation message
                $('#closeModalMessage').empty();
                $('#closeModalMessage').show();
                $('#closeModalMessage').text(
                    "You are about to close the modal. If you close it, you won't be able to proceed with\nthe appraisal."
                );
                $('#closeModalMessage').addClass('alert alert-danger'); // Add alert styling

                confirmationMode = true;
            }
        }

        function goBack() {
            window.history.back();
        }

        function closeModal() {
            confirmationMode = false;
            goBack();
        }

        $('#consent1, #consent2, #consent3, #consent4').on('click', function() {
            // Remove is-invalid class when a checkbox is clicked
            $(this).removeClass('is-invalid text-danger');
            $(this).closest('label').find('span').removeClass('is-invalid text-danger');
            $('#closeModalMessage').hide();
            confirmationMode = false;
        });

        function understood() {
            confirmationMode = true;

            if (confirmationMode) {
                // Check if all checkboxes are checked
                const consent1 = $('#consent1').prop('checked');
                const consent2 = $('#consent2').prop('checked');
                const consent3 = $('#consent3').prop('checked');
                const consent4 = $('#consent4').prop('checked');
                // console.log(consent1 + ' ' + consent2 + ' ' + consent3 + ' ' + consent4);

                if (consent1 && consent2 && consent3 && consent4) {
                    // Remove is-invalid class if checkboxes are checked
                    $('#consent1, #consent2, #consent3, #consent4').removeClass('is-invalid text-danger');
                    $('#consent1-label span, #consent2-label span, #consent3-label span, #consent4-label span').removeClass(
                        'is-invalid text-danger');

                    $.ajax({
                        type: 'POST',
                        url: '{{ route('saveEULA') }}',
                        data: {
                            appraisalId: {{ $appraisalId }}
                        },
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        success: function(response) {
                            // console.log('Data Privacy Accepted.');
                        },
                        error: function(xhr, status, error) {
                            console.error(error);
                        }
                    });

                    $('#consentform').modal('hide');
                } else {
                    // Display an error message or perform any other validation logic
                    $('#closeModalMessage').empty();
                    $('#closeModalMessage').show();
                    $('#closeModalMessage').text('Please check all checkboxes before proceeding.');
                    $('#closeModalMessage').addClass('alert alert-danger'); // Add alert styling
                    confirmationMode = false;

                    // Add is-invalid class to checkboxes if they are not checked
                    if (!consent1) {
                        $('#consent1-label span').addClass('is-invalid text-danger');
                    }
                    if (!consent2) {
                        $('#consent2-label span').addClass('is-invalid text-danger');
                    }
                    if (!consent3) {
                        $('#consent3-label span').addClass('is-invalid text-danger');
                    }
                    if (!consent4) {
                        $('#consent4-label span').addClass('is-invalid text-danger');
                    }
                }
            }
        }

        ////////////////////////// ON LOAD ////////////////////////////////
        $(document).ready(function() {
            $('#add-wpa-btn').click(function() {
                addNewWPARow($('#wpa_table_body'));
                updateProgressBar();
            });

            $('#add-ldp-btn').click(function() {
                var ldptbody = $('#ldp_table_body');

                addNewLDPRow(ldptbody);
                updateProgressBar();
            });

            ///////////////////////////////////// ROW DELETION ///////////////////////////////////////////////////
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
                        updateProgressBar();
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            });

            // For the LDP delete button
            $(document).on('click', '.ldp-delete-btn', function() {
                var row = $(this).closest('tr');
                var ldpID = row.data('ldp-id'); // Assuming you have a data attribute for WPA ID on the row

                // console.log(ldpID);
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
                        updateProgressBar();
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            });

            $(document).on('change', '#SID_table input[type="radio"]', function() {
                updateFrequencyCounter('SID_table');
                updateBHTotal();
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
            });

            ///////////////////////////////////// Validation code///////////////////////////////////////////////////
            // Handle form submission and validation
            $('#submit-btn-form').on('click', function(event) {
                var form = $('.needs-validation');
                var valid = true;

                // Select all input elements inside the form
                // var inputElements = form.find('input:not([type="hidden"])');

                // inputElements.each(function(index, inputElement) {
                //     // Check if the input is marked as invalid
                //     if ($(inputElement).hasClass('is-invalid')) {
                //         valid = false;
                //         console.error('Validation failed for', inputElement.name, ':', inputElement
                //             .validationMessage);

                //         $(inputElement).addClass('is-invalid');
                //         inputElement.focus();
                //     }

                //     // Check if the input is required and its value is empty
                //     if ($(inputElement).attr('required') && $(inputElement).val().trim() === '') {
                //         valid = false;
                //         console.error('Validation failed for', inputElement.name,
                //             ': This field is required.');

                //         $(inputElement).addClass('is-invalid');
                //         inputElement.focus();

                //     }
                // });

                // Get all required input elements


                // if (!valid || !form[0].checkValidity()) {

                if (!form[0].checkValidity()) {
                    event.preventDefault(); 
                    event.stopPropagation();

                    var invalidInputs = form.find('.is-invalid');

                    if (invalidInputs.length > 0) {
                        // Handle invalid inputs, display error messages, etc.
                        invalidInputs.each(function(index, invalidInput) {
                            // Handle validation messages for invalid inputs
                            console.error('Validation failed for', invalidInput.name, ':',
                                invalidInput.validationMessage);
                            // Focus on the first invalid input
                            invalidInputs.focus();
                        });
                    }

                    var requiredInputs = form.find('input[required]');

                    requiredInputs.each(function(index, inputElement) {
                        // Wrap the DOM element in a jQuery object
                        var $inputElement = $(inputElement);

                        // Check if the required input is empty or has a validation error
                        if ($inputElement.val() === '' || $inputElement.val() === null || !
                            $inputElement[0].checkValidity()) {
                            valid = false;
                            console.error('Validation failed for', inputElement.name, ':',
                                inputElement
                                .validationMessage);

                            // Use the jQuery object to add the 'is-invalid' class
                            $inputElement.addClass('is-invalid');
                            $inputElement.closest('td').addClass('border border-danger');
                            $inputElement[0].focus();

                            // Add an input event handler to remove the 'is-invalid' class
                            $inputElement.on('input', function() {
                                $inputElement.removeClass('is-invalid');
                                $inputElement.closest('input[type="radio"][value="1"]')
                                    .removeClass('is-invalid');
                                $inputElement.closest('input[type="radio"][value="0"]')
                                    .removeClass('is-invalid');
                                $inputElement.find('.form-check-input').removeClass(
                                    'is-invalid');
                                $inputElement.closest('td').removeClass(
                                    'border border-danger');
                            });
                        }
                    });

                    var requiredInputs = form.find('textarea[required]');

                    requiredInputs.each(function(index, inputElement) {
                        // Wrap the DOM element in a jQuery object
                        var $inputElement = $(inputElement);

                        // Check if the required input is empty or has a validation error
                        if ($inputElement.val() === '' || $inputElement.val() === null || !
                            $inputElement[0].checkValidity()) {
                            valid = false;
                            console.error('Validation failed for', inputElement.name, ':',
                                inputElement
                                .validationMessage);

                            // Use the jQuery object to add the 'is-invalid' class
                            $inputElement.addClass('is-invalid');
                            $inputElement.closest('td').addClass('border border-danger');
                            $inputElement[0].focus();

                            // Add an input event handler to remove the 'is-invalid' class
                            $inputElement.on('input', function() {
                                $inputElement.removeClass('is-invalid');
                                $inputElement.find('.form-control').removeClass(
                                    'is-invalid');
                                $inputElement.closest('td').removeClass(
                                    'border border-danger');
                            });
                        }
                    });

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

            $('.common-close-button').on('click', function() {
                // console.log('close clicked');
                var $modal = $(this).closest('.modal');
                $modal.addClass('modal fade hide');
                $modal.modal('hide');
                $modal.css('display', 'none');

                var signInput = document.querySelector(
                    'input[name="SIGN[JI][{{ $appraisalId }}][file]"]');
                signInput.classList.remove('is-invalid');
                signInput.closest('td').classList.remove('border', 'border-danger');
            });

            document.getElementById('submit-btn-sign').addEventListener('click', function(event) {
                var signInput = document.querySelector(
                    'input[name="SIGN[JI][{{ $appraisalId }}][file]"]');
                var signatureImage = document.querySelector('#signatureImage');

                // Check if files are uploaded or if a signature image is displayed
                if (signInput.files.length === 0 && (signatureImage.getAttribute('src') === null ||
                        signatureImage.getAttribute('src') === '')) {
                    event.preventDefault();
                    event.stopPropagation();

                    signInput.classList.add('is-invalid');
                    signInput.closest('td').classList.add('border', 'border-danger');

                    signInput.scrollIntoView({
                        behavior: 'smooth'
                    });

                    console.error('Signature validation failed.');
                    return null;
                } else {
                    // Clear validation if files are uploaded or a signature image is displayed
                    signInput.classList.remove('is-invalid');
                    signInput.closest('td').classList.remove('border', 'border-danger');

                    $('#signatory_modal').modal('hide');
                    $('#confirmation-popup-modal').modal('show');
                }
            });

            // Get references to the file input, the image element, and the hidden input
            var fileInput = document.getElementById('uploadsign');
            var signatureImage = document.getElementById('signatureImage');
            var hiddenInput = document.getElementById("uploadsign_1");

            // Add an event listener to the file input
            fileInput.addEventListener('change', function() {
                // Check if any files are selected
                if (fileInput.files.length > 0) {
                    var file = fileInput.files[0];

                    // Check if the selected file is an image
                    if (file.type.match(/^image\//)) {
                        fileInput.classList.remove('is-invalid');
                        fileInput.closest('td').classList.remove('border', 'border-danger');

                        // Create a FileReader to read the selected file
                        var reader = new FileReader();

                        // Set up an event handler to run when the file is loaded
                        reader.onload = function(event) {
                            // Set the src attribute of the image element to the loaded image data
                            signatureImage.src = event.target.result;

                            // Set the value of the hidden input to the loaded image data
                            hiddenInput.value = event.target.result;
                        };

                        // Read the selected file as a data URL
                        reader.readAsDataURL(file);
                    } else {
                        // Handle the case where a non-image file is selected (optional)
                        alert('Please select a valid image file.');
                        fileInput.value = ''; // Clear the file input
                    }
                } else {
                    // Clear the image and the hidden input value if no file is selected
                    signatureImage.src = '';
                    hiddenInput.value = '';
                }
            });

            ///////////////////////////////////// Autosave code///////////////////////////////////////////////////
            $('#KRA_table_body').on('change', '.autosave-field', function() {
                // console.log('Change event triggered on:', event.target); // Log the target element
                var field = $(this);
                // console.log('I was a KRA. Field:', field);

                var kraID = field.attr('name').match(/\d+/)[0];
                var fieldName = field.attr('name').split('][')[2].replace(/\]/g, '');
                var fieldValue = field.val();

                // Log the field name, ID, and value
                // console.log('Field Name:', fieldName);
                // console.log('KRA ID:', kraID);
                // console.log('Field Value:', fieldValue);

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
                        // console.log('Autosave successful.');
                        // console.log('FieldName Acquired: ' + fieldName);
                    },
                    error: function(xhr, status, error) {
                        // console.log('{{ route('autosaveKRAField') }}');

                        // Handle errors if any
                        // console.error('Autosave failed:', error);
                        // console.log('FieldName Acquired: ' + fieldName);
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
                            // console.log(wpaID);
                            var closestRow = field.closest('tr');
                            // console.log(closestRow);

                            closestRow.attr('data-wpa-id', wpaID);

                            // Change the name attribute of the textareas if needed
                            closestRow.find('textarea[name="WPA[0][' +
                                    {{ $appraisalId }} + '][continue_doing]"]')
                                .attr(
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
                                    {{ $appraisalId }} + '][continue_doing]"]')
                                .val(
                                    wpa.continue_doing);
                            closestRow.find('textarea[name="WPA[' + wpaID + '][' +
                                {{ $appraisalId }} + '][stop_doing]"]').val(
                                wpa
                                .stop_doing);
                            closestRow.find('textarea[name="WPA[' + wpaID + '][' +
                                {{ $appraisalId }} + '][start_doing]"]').val(
                                wpa
                                .start_doing);
                        });

                        // Handle the success response if needed
                        // console.log('Autosave successful.');
                        // console.log('FieldName Acquired: ' + fieldName);
                    },
                    error: function(xhr, status, error) {
                        // console.log('{{ route('autosaveWPPField') }}');

                        // // Handle errors if any
                        // console.error('Autosave failed:', error);
                        // console.log('FieldName Acquired: ' + fieldName);
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
                            // console.log(ldpID);
                            var closestRow = field.closest('tr');
                            // console.log(closestRow);

                            closestRow.attr('data-ldp-id', ldpID);

                            // Change the name attribute of the textareas if needed
                            closestRow.find('textarea[name="LDP[0][' +
                                    {{ $appraisalId }} + '][learning_need]"]')
                                .attr(
                                    'name', 'LDP[' + ldpID + '][' +
                                    {{ $appraisalId }} + '][learning_need]');
                            closestRow.find('textarea[name="LDP[0][' +
                                {{ $appraisalId }} + '][methodology]"]').attr(
                                'name', 'LDP[' + ldpID + '][' +
                                {{ $appraisalId }} + '][methodology]');

                            // Update the content of the closest row based on the response data
                            closestRow.find('textarea[name="LDP[' + ldpID + '][' +
                                    {{ $appraisalId }} + '][learning_need]"]')
                                .val(ldp
                                    .learning_need);
                            closestRow.find('textarea[name="LDP[' + ldpID + '][' +
                                {{ $appraisalId }} + '][methodology]"]').val(
                                ldp
                                .methodology);
                        });

                        // Handle the success response if needed
                        // console.log('Autosave successful.');
                        // console.log('FieldName Acquired: ' + fieldName);
                    },
                    error: function(xhr, status, error) {
                        // console.log('{{ route('autosaveLDPField') }}');

                        // // Handle errors if any
                        // console.error('Autosave failed:', error);
                        // console.log('FieldName Acquired: ' + fieldName);
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
                        //updateProgressBar();

                        // Handle the success response if needed
                        // console.log('Autosave successful.');
                        // console.log('FieldName Acquired: ' + fieldName);
                    },
                    error: function(xhr, status, error) {
                        // console.log('{{ route('autosaveLDPField') }}');

                        // // Handle errors if any
                        // console.error('Autosave failed:', error);
                        // console.log('FieldName Acquired: ' + fieldName);
                    }
                });
            });

            $('#submit-btn-confirm').on('click', function() {
                var fileInput = $('#uploadsign_1')[0];

                if (fileInput && fileInput.files.length === 0) {
                    $('#uploadsign_1').addClass('is-invalid');
                    return;
                } else {
                    var selectedFile = fileInput.files[0];

                    var maxSizeInBytes = 0.5 * 1024 * 1024; // Example: 5 MB
                    if (selectedFile.size > maxSizeInBytes) {
                        alert('The uploaded esignature is too large. Please upload a smaller image.');
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
                            },
                            success: function(response) {
                                if (response.success) {
                                    loadSignature();
                                    // console.log('Esignature Updated.');
                                    formChecker();
                                } else {
                                    // console.log('Esignature Updated bot else');
                                }
                            },
                            error: function(xhr, status, error) {}
                        });
                    };

                    reader.readAsDataURL(selectedFile);
                }
            });

            ////////////////////////// SEND REQUEST ////////////////////////////////
            $('#sendrequest').click(function() {
                // Check validity and list the results
                const validationList = $('#validation-list');
                validationList.empty(); // Clear previous results

                // Check input elements for validity
                const inputElements = $('input[disabled]');
                const textarea = $('textarea[required]');
                let invalidFields = [];

                inputElements.each(function() {
                    if (!this.checkValidity()) {
                        invalidFields.push($(this).attr('name'));
                    }
                });

                if (!textarea[0].checkValidity()) {
                    invalidFields.push('Request field');
                }

                if (invalidFields.length > 0) {
                    $.each(invalidFields, function(index, fieldName) {
                        validationList.append('<li>' + fieldName + ' is not answered or invalid.</li>');
                    });

                    $('#validation-results').show();
                } else {
                    $('#validation-results').hide();
                }

                $('#request-popup-modal').modal('show');
            });

            $('#sendReqForm').on('submit', function(event) {
                event.preventDefault(); // Prevent the default form submission

                // Collect the form data
                const formData = new FormData(this);
                formData.append('appraisal_id', {{ $appraisalId }});

                // Send the data to the server using AJAX
                $.ajax({
                    url: "{{ route('submitRequest') }}",
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
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
            
            loadTableData();
            updateFrequencyCounter('SID_table');
            updateFrequencyCounter('SR_table');
            updateFrequencyCounter('S_table');

            updateBHTotal();
            updateWeightedTotal();
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
                        row.attr('data-question-id', (questionId.toString() +
                            {{ $appraisalId }}));

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
                            performanceLevelDiv.append($('<div>').addClass('col-auto').append(
                                label));

                            // Attach event listener to input for highlighting the row on invalid
                            input.on('invalid', function() {
                                $(this).addClass('is-invalid');
                                $(this).siblings('span').addClass('text-danger');
                                $( this ).find( '.form-check-input' ).closest( 'tr' ).addClass('table-danger' );
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
                                        // console.log(
                                        //     'Score saved for question ID:',
                                        //     questionId);
                                        updateProgressBar();
                                    },
                                    error: function(xhr) {
                                        if (xhr.responseText) {
                                            // console.log('Error: ' + xhr
                                            //     .responseText);
                                        } else {
                                            // console.log('An error occurred.');
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
                        row.attr('data-question-id', (questionId.toString() +
                            {{ $appraisalId }}));

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
                            performanceLevelDiv.append($('<div>').addClass('col-auto').append(
                                label));

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
                                        // console.log(
                                        //     'Score saved for question ID:',
                                        //     questionId);
                                        updateProgressBar();
                                    },
                                    error: function(xhr) {
                                        if (xhr.responseText) {
                                            // console.log('Error: ' + xhr
                                            //     .responseText);
                                        } else {
                                            // console.log('An error occurred.');
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

                    SQuestions.forEach(function(question, index) {
                        var questionId = question.question_id;
                        var questionText = question.question;
                        var questionOrder = question.question_order;
                        var score = question.score;

                        var row = $('<tr>');
                        row.attr('data-question-id', (questionId.toString() +
                            {{ $appraisalId }}));

                        var orderCell = $('<td>').text(index + 1);
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
                            performanceLevelDiv.append($('<div>').addClass('col-auto').append(
                                label));

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
                                        // console.log(
                                        //     'Score saved for question ID:',
                                        //     questionId);
                                        updateProgressBar();
                                    },
                                    error: function(xhr) {
                                        if (xhr.responseText) {
                                            // console.log('Error: ' + xhr
                                            //     .responseText);
                                        } else {
                                            // console.log('An error occurred.');
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
                    // formChecker();
                    // updateProgressBar();
                }
            });

            $.ajax({
                url: '{{ route('getPEKRA') }}',
                type: 'GET',
                data: {
                    appraisal_id: {{ $appraisalId }}
                },
                dataType: 'json',
                success: function(data) {
                    if (data.success) {
                        if (data.eulaData == 1 || data.eulaData == true) {
                            $('#consentform').remove();
                        } else {
                            $('#consentform').modal('show');
                        }

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
                                        'KRA[' + kraID + '][' + {{ $appraisalId }} + '][KRA_kra]',
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

                                        updateProgressBar();
                                    });

                                    label.append(input, span);

                                    if (kra.performance_level === i) {
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
                                $('#wpa_table_body tr .wpa-delete-btn').prop('disabled', true);
                            }
                            // console.log(wparowCount);
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
                        data.jicData.forEach(function(jic, index = 1) {
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
                                    updateProgressBar();
                                });

                                $(answerRadioNo).on('input', function() {
                                    var row = $(this).closest('tr');
                                    row.find('.is-invalid').removeClass('is-invalid');
                                    row.find('.text-danger').removeClass('text-danger fw-bold');

                                    $(this).closest('tr').removeClass('text-danger fw-bold');
                                    updateProgressBar();
                                });

                                var commentTextarea = row.querySelector('.textarea[name="feedback[' + (index +1) + '][{{ $appraisalId }}][comments]"]');
                                commentTextarea.value = jic.comments;

                                // Attach input event handlers for validation
                                $(commentTextarea).on('input', function() {
                                    $(this).removeClass('border border-danger');
                                    $(this).removeClass('is-invalid');
                                }).on('invalid', function() {
                                    $(this).addClass('is-invalid');
                                    $(this).attr('placeholder', 'Please provide a valid input');
                                }).on('change', function() {
                                    if ($(this).val().trim() === '') {
                                        $(this).addClass('is-invalid');
                                        $(this).closest('td').addClass(
                                            'border border-danger');
                                    }
                                }).on('blur', function() {
                                    if ($(this).val().trim() === '') {
                                        $(this).addClass('is-invalid');
                                        $(this).closest('td').addClass(
                                            'border border-danger');
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
                            var row = document.querySelector('.signature-row');

                            if (row) {
                                var signCell = row.querySelector('#signcell');
                                var signatureImage = document.querySelector('#signatureImage');
                                var hiddenInput = document.querySelector('#uploadsign_1');
                                var dateCell = row.querySelector('.date-cell');

                                if (sign.sign_data) {
                                    // Validation for signature data
                                    $('#signatureImage').attr('src', sign.sign_data);

                                    signatureImage.width = 100;

                                    // Update the hidden input with the loaded signature data
                                    hiddenInput.value = sign.sign_data;
                                } else {
                                    var errorText = document.createElement('p');
                                    errorText.textContent = 'Invalid signature data';
                                    errorText.classList.add('text-danger', 'fw-bold');
                                    signCell.appendChild(errorText);
                                }

                                if (sign.updated_at) {
                                    // Validation for date data
                                    const date = new Date(sign.updated_at);

                                    const formattedDate = date.toLocaleString('en-US', {
                                        year: 'numeric',
                                        month: 'long',
                                        day: 'numeric',
                                        hour: 'numeric',
                                        minute: 'numeric',
                                        second: 'numeric',
                                    });

                                    dateCell.textContent = formattedDate;
                                } else {
                                    // Handle invalid or missing date data
                                    dateCell.textContent = 'Invalid date';
                                    dateCell.classList.add('text-danger', 'fw-bold');
                                }
                            }
                        });

                        formChecker();
                        updateProgressBar();
                    } else {
                        console.error('Data retrieval failed.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
            // Update progress bar on initial page load
            // updateProgressBar();
        }

        function updateProgressBar() {
            // Initialize an object to store progress per question
            var progressPerQuestion = {};

            // Iterate over each radio button
            $('#PEappraisalForm .form-check-input[type="radio"]').each(function () {
                var questionId = $(this).attr('name').match(/\[(\d+)\]/)[1];
                var questionSection = $(this).attr('name').match(/\[([^\]]+)\]$/)[1];

                // Count the number of checked radio buttons for each question
                var checkedRadioCount = $(`#PEappraisalForm input[name*="[${questionId}][{{ $appraisalId }}][${questionSection}]"]:checked`).length;
                // console.log(`Question ID: ${questionId}, Section: ${questionSection}, Checked Count: ${checkedRadioCount}`);

                // Count the total number of radio buttons for each question
                var totalRadioCount = $(`#PEappraisalForm input[name*="[${questionId}][{{ $appraisalId }}][${questionSection}]"]`).length;
                // console.log(`Question ID: ${questionId}, Section: ${questionSection}, Total Count: ${totalRadioCount}`);

                // Calculate the progress percentage for each question
                var progressPercentage = checkedRadioCount > 0 ? 100 : 0;
                // console.log(`Question ID: ${questionId}, Section: ${questionSection}, Progress Percentage: ${progressPercentage}`);

                // Store the progress percentage for each question
                progressPerQuestion[`${questionId}_${questionSection}`] = progressPercentage;
            });

            $('#PEappraisalForm textarea.autosave-field[required]').each(function () {
                var textareaName = $(this).attr('name');
                var questionId = textareaName.match(/\[(\d+)\]/)[1];
                var questionSection = textareaName.match(/\[([^\]]+)\]$/)[1];

                // Check if the textarea has a value
                var hasValue = $(this).val().trim().length > 0;

                // Calculate the progress percentage for each textarea
                var progressPercentage = hasValue ? 100 : 0;

                // Store the progress percentage for each textarea
                progressPerQuestion[`${questionId}_${questionSection}`] = progressPercentage;
            });
            // console.log(progressPerQuestion)

            // Calculate the total progress by averaging the progress percentages for all questions
            var totalProgress = calculateAverage(Object.values(progressPerQuestion));
            totalProgress = Math.round(totalProgress);

            // Update the progress bar width
            // console.log(`Total Progress: ${totalProgress}`);
            $('#progressBar').css('width', totalProgress + '%').text(totalProgress + '%');
            $('#progressBar').attr('aria-valuenow', totalProgress);
        }


        function calculateAverage(array) {
            if (array.length === 0) {
                console.log('Array is empty. Returning 0.');
                return 0;
            }

            var sum = array.reduce(function (acc, val) {
                return acc + val;
            }, 0);

            var average = sum / array.length;

            // console.log('Array:', array);
            // console.log('Sum:', sum);
            // console.log('Average:', average);

            return average;
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
                    $(this).closest('div').removeClass(
                        'border border-danger');
                    $(this).removeClass('is-invalid');
                    updateProgressBar();
                }).on('invalid', function() {
                    $(this).addClass('is-invalid');
                    $(this).closest('div').addClass('border border-danger');
                    $(this).attr('placeholder', 'Please provide a valid input');
                }).on('change', function() {
                    if ($(this).val().trim() === '') {
                        $(this).addClass('is-invalid');
                        $(this).closest('td').addClass(
                            'border border-danger');
                    }
                    updateProgressBar();
                }).on('blur', function() {
                    if ($(this).val().trim() === '') {
                        $(this).addClass('is-invalid');
                        $(this).closest('td').addClass(
                            'border border-danger');
                    }
                    updateProgressBar();
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
                    {{ $appraisalId }} + '][KRA_actual_result]').prop('readonly', false)
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
            var wpaID = 0;

            var wparow = $('<tr>').addClass('align-middle');

            $('<td>').addClass('td-textarea').append(
                createTextArea(
                    'WPA[' + wpaID + '][' + {{ $appraisalId }} +
                    '][continue_doing]',
                    null,
                    false
                )
            ).appendTo(wparow);

            $('<td>').addClass('td-textarea').append(
                createTextArea(
                    'WPA[' + wpaID + '][' + {{ $appraisalId }} +
                    '][stop_doing]',
                    null,
                    false
                )
            ).appendTo(wparow);

            $('<td>').addClass('td-textarea').append(
                createTextArea(
                    'WPA[' + wpaID + '][' + {{ $appraisalId }} +
                    '][start_doing]',
                    null,
                    false
                )
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
            // Calculate the next available lpaID
            var ldpID = 0;

            var ldprow = $('<tr>').addClass('align-middle');

            $('<td>').addClass('td-textarea').append(
                createTextArea(
                    'LDP[' +
                    ldpID +
                    '][' + {{ $appraisalId }} + '][learning_need]',
                    null,
                    false
                )
            ).appendTo(ldprow);

            $('<td>').addClass('td-textarea').append(
                createTextArea(
                    'LDP[' +
                    ldpID +
                    '][' + {{ $appraisalId }} + '][methodology]',
                    null,
                    false
                )
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

            $('#' + tableId + ' tbody tr').each(function() {
                // Count the rows
                questionCount++;

                var selectedRadio = $(this).find('input[type="radio"]:checked');
                if (selectedRadio.length > 0) {
                    var value = parseFloat(selectedRadio.val());
                    if (!isNaN(value)) {
                        frequencyCounters[5 - value]++;
                        total += value;
                    }
                }
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
                var weight = parseFloat(row.find('textarea[name^="KRA"][name$="[KRA_kra_weight]"]').val()) /
                    100;

                var performanceLevel = parseInt(row.find(
                        'input[type="radio"][name^="KRA"][name$="[KRA_performance_level]"]:checked')
                    .val());

                if (!isNaN(weight) || !isNaN(performanceLevel)) {
                    var weightedValue = weight * performanceLevel;
                    totalWeight += weight;
                    totalWeighted += weightedValue;


                    if (isNaN(totalWeighted) || totalWeighted === null) {
                        totalWeighted = 0;
                        weightedValue = 0;
                    }

                    row.find('textarea[name^="KRA"][name$="[KRA_weighted_total]"]')
                        .val(weightedValue.toFixed(2));
                }
            });

            totalWeight = totalWeight * 100;

            if (Math.floor(totalWeight) > 100) {
                isTotalWeightInvalid = true;
                $('#KRA_Weight_Total').addClass('is-invalid');
                $('textarea[name^="KRA"][name$="[KRA_kra_weight]"]').addClass('is-invalid');
            } else {
                $('#KRA_Weight_Total').removeClass('is-invalid');
                $('textarea[name^="KRA"][name$="[KRA_kra_weight]"]').removeClass('is-invalid');
            }

            $('#KRA_Weight_Total').val(totalWeight.toFixed(2));
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
                    /* 
                    ENABLING USERS TO VIEW AND ANSWERS CERTAIN PART
                    OF THE FORM BASED ON THE DATE PHASES SET BY THE ADMIN.
                    */
                    console.log("PHASE");
                    console.log(response.phaseData);

                    if (response.submitionChecker && Object.values(response.locks).every(lock => !lock)) {
                        $('select').prop('disabled', true);
                        $('textarea').prop('disabled', true);

                        $('#add-kra-btn').prop('disabled', true);
                        $('#add-wpa-btn').prop('disabled', true);
                        $('#add-ldp-btn').prop('disabled', true);
                        $('.btn-danger').prop('disabled', true);

                        $('#lockToast').toast('show');
                        $('input[type="radio"]').prop('disabled', true);

                        $('#submit-btn-form').text('View Signature');
                        $('#uploadsign').hide();
                        $('#submit-btn-sign').hide();
                        $('#progressBarContainer').remove();

                        $('#sendreq').show();
                        $('#requestText').prop('disabled', false);
                    } else {
                        if (response.phaseData === "kra") {
                            $('textarea').prop('disabled', true);
                            $('#KRA_table_body select').prop('disabled', true);
                            $('input[type="radio"]').prop('disabled', true);

                            $('#add-wpa-btn').prop('disabled', true);
                            $('#add-ldp-btn').prop('disabled', true);
                            $('.wpa-delete-btn').prop('disabled', true);
                            $('.ldp-delete-btn').prop('disabled', true);

                            $('#submit-btn-form').hide();
                            $('#sendreq').hide();

                            if ($('#kra_table').length > 0) {
                                $('html, body').animate({
                                    scrollTop: $('#kra_table').offset().top
                                }, 100);
                            }
                        } else if (response.phaseData === "pr") {
                            $('input[type="radio"]').prop('disabled', true);
                            $('textarea').prop('readonly', true);

                            $('#KRA_table_body select').prop('disabled', true);
                            $('#KRA_table_body select').attr('disabled', true);

                            $('#add-wpa-btn').prop('disabled', true);
                            $('#add-ldp-btn').prop('disabled', true);
                            $('.wpa-delete-btn').prop('disabled', true);
                            $('.ldp-delete-btn').prop('disabled', true);

                            $('#submit-btn-form').hide();
                            $('#sendreq').hide();

                            $('#KRA_table_body [name$="[KRA_actual_result]"]').prop('readonly', false);

                            if ($('#kra_table').length > 0) {
                                $('html, body').animate({
                                    scrollTop: $('#kra_table').offset().top
                                }, 100);
                            }
                        } else if (response.phaseData === "eval") {
                            $('#KRA_table_body textarea').prop('readonly', true);
                            $('#KRA_table_body select').prop('disabled', true);

                            $('#KRA_table_body [name$="[KRA_actual_result]"]').prop('readonly', false);
                            $('#KRA_table_body [name$="[KRA_performance_level]"]').prop('disabled', false);

                            $('#sendreq').hide();
                        } else if (response.phaseData === "lock") {
                            $('select').prop('disabled', true);
                            $('textarea').prop('disabled', true);

                            $('#add-kra-btn').prop('disabled', true);
                            $('#add-wpa-btn').prop('disabled', true);
                            $('#add-ldp-btn').prop('disabled', true);
                            $('.btn-danger').prop('disabled', true);

                            $('#lockToast').toast('show');
                            $('input[type="radio"]').prop('disabled', true);

                            $('#submit-btn-form').hide();
                            $('#uploadsign').hide();
                            $('#submit-btn-sign').hide();

                            $('#sendreq').show();
                            $('#requestText').prop('disabled', false);
                        }
                    }
                    
                    //////////////// FOR UNLOCKING CERTAIN PARTS OF THE FORM /////////////////
                    console.log("LOCK");
                    console.log(response.locks);

                    if (response.locks.kra) {
                        $('#KRA_table_body select').prop('disabled', true);
                        $('input[type="radio"]').prop('disabled', true);
                        $('textarea').prop('disabled', true);

                        $('#add-kra-btn').prop('disabled', true);
                        $('#add-wpa-btn').prop('disabled', true);
                        $('#add-ldp-btn').prop('disabled', true);
                        $('.btn-danger').prop('disabled', true);

                        $('#lockToast').toast('hide');
                        $('#submit-btn-form').text('View Signature');
                        $('#uploadsign').hide();
                        $('#submit-btn-sign').hide();
                        $('#sendreq').hide();

                        // $('html, body').animate({
                        //     scrollTop: $('#kra_table').offset().top
                        // }, 3000);
                    }

                    if (response.locks.pr) {
                        $('#KRA_table_body select').prop('disabled', true);
                        $('input[type="radio"]').prop('disabled', true);
                        $('textarea').prop('disabled', true);

                        $('#KRA_table_body [name$="[KRA_actual_result]"]').prop('disabled', false);

                        $('#add-kra-btn').prop('disabled', true);
                        $('#add-wpa-btn').prop('disabled', true);
                        $('#add-ldp-btn').prop('disabled', true);
                        $('.btn-danger').prop('disabled', true);
                        
                        $('#lockToast').toast('hide');
                        $('#submit-btn-form').text('View Signature');
                        $('#uploadsign').hide();
                        $('#submit-btn-sign').hide();
                        $('#sendreq').hide();

                        // $('html, body').animate({
                        //     scrollTop: $('#kra_table').offset().top
                        // }, 100);
                    }

                    if (response.locks.eval) {
                        $('input[type="radio"]').prop('disabled', false);
                        $('#KRA_table_body select').prop('disabled', false);
                        $('textarea').prop('disabled', false);

                        $('#add-kra-btn').prop('disabled', false);
                        $('#add-wpa-btn').prop('disabled', false);
                        $('#add-ldp-btn').prop('disabled', false);
                        $('.btn-danger').prop('disabled', false);
                        
                        $('#lockToast').toast('hide');
                        $('#submit-btn-form').text('Submit');
                        $('#submit-btn-form').show();
                        $('#uploadsign').show();
                        $('#submit-btn-sign').show();
                        $('#sendreq').hide();
                    }

                    if (response.locks.lock) {
                        $('input[type="radio"]').prop('disabled', false);
                        $('#KRA_table_body select').prop('disabled', false);
                        $('textarea').prop('disabled', false);
                        $('#lockToast').toast('hide');
                        $('#sendreq').hide();
                    }

                    //////////////// SEND REQUEST /////////////////
                    if (response.hasRequest) {
                        if (response.status === 'Pending') {
                            $('#sendrequest').removeClass('btn-primary').text('');
                            $('#sendrequest').addClass('btn-outline-primary').text('Request Sent').prop('disabled', true).append('<i>').addClass('bi bi-envelope-paper');
                        } else if (response.status === 'Approved' || response.status === 'Disapproved') {
                            // Display feedback and appropriate UI for approved or disapproved requests
                                $('#feedback-text').text(response.feedback);

                            // Check if approver_name and approved_at are available
                            if (response.approver_name && response.approved_at) {
                                const approverInfo = `Noted by ${response.approver_name} on ${response.approved_at}`;
                                $('#additional-info').text(approverInfo).addClass('font-italic');
                            }

                            $('#feedback-container').removeClass('d-none');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    if (xhr.responseText) {
                        $('#lockToast .toast-body').text('You do not have permission to view this form.');
                        $('#lockToast').toast('show');

                        $('.content-container').remove();
                        $('.content-body').remove();
                        $('.modal').remove();
                        $('.fade').remove();
                        $('#submit-btn-sign').remove();
                        $('#sendreq').remove();
                        $('#consentform').remove();
                        $('#consentform').modal('hide');

                        // Create a new div element
                        var errorDiv = $('<div></div>');

                        // Set the attributes and content for the error div
                        errorDiv.attr('id', 'error-message');
                        errorDiv.addClass('alert alert-danger content-container');
                        errorDiv.text('An error occurred: You do not have permission to view this form.');

                        // Append the error div to a specific element (e.g., the body)
                        errorDiv.appendTo('.content-section');
                    } else {
                        // console.log('An error occurred.');
                    }
                }
            });
        }

        // var totalProgress = 0;

        // function calculateRadioProgress(questionId, questionSection) {
        //     // Determine the total count of radio buttons based on the pattern in the name attribute
        //     var isGroupWith5Buttons = $(`input[name*="[${questionId}][{{ $appraisalId }}][${questionSection}]"]:first`).length > 0;
        //     console.log(`Processing questionId: ${questionId}, questionSection: ${questionSection}, isGroupWith5Buttons: ${isGroupWith5Buttons}`);

        //     // Get the total count of radio buttons in the specified group
        //     var totalRadioCount = 0;
        //     var checkedRadioCount = 0;

        //     if (isGroupWith5Buttons) {
        //         totalRadioCount = $(`input[name*="[${questionId}][{{ $appraisalId }}][${questionSection}]"]`).length;
        //         checkedRadioCount = $(`input[name*="[${questionId}][{{ $appraisalId }}][${questionSection}]"]:checked`).length;
        //     } else {
        //         if (questionSection === 'feedback') {
        //             totalRadioCount = $(`input[name="feedback_${questionId}"]`).length;
        //             checkedRadioCount = $(`input[name="feedback_${questionId}"]:checked`).length;
        //         }
        //     }
        //     console.log("totalRadioCount: " + totalRadioCount);

        //     // Adjusted calculation for totalQuestions and weight
        //     var totalQuestions = isGroupWith5Buttons ? totalRadioCount / 5 : totalRadioCount / 2;
        //     console.log("totalQuestions: " + totalQuestions);
        //     var totalWeights = 100; // Total weight should add up to 100%
        //     var weight = totalQuestions > 0 ? totalWeights / totalQuestions : 0;
        //     console.log("weight: " + weight);

        //     // Calculate progress per checked radio button
        //     var progressPerRadio = totalRadioCount > 0 ? (checkedRadioCount / totalRadioCount) * weight : 0;
        //     console.log("progressPerRadio: " + progressPerRadio);

        //     return progressPerRadio;
        // }

        // function calculateTotalProgress() {
        //     var totalRadioProgress = 0;
        //     var totalRadioCount = 0;

        //     // Iterate over each question
        //     $('#PEappraisalForm .form-check-input[type="radio"]').each(function () {
        //         var questionId = $(this).attr('name').match(/\[(\d+)\]/)[1];
        //         var questionSection = $(this).attr('name').match(/\[([^\]]+)\]$/)[1];

        //         totalRadioProgress += calculateRadioProgress(questionId, questionSection);
        //         totalRadioCount++; // Count the number of questions
        //     });

        //     $('#PEappraisalForm [textarea]').each(function () {
        //         console.log($(this));
        //     });
        //     console.log(totalRadioProgress)
        //     // Calculate the total progress based on your criteria
        //     var totalProgress = (totalRadioProgress); // Adjust the formula as needed

        //     return totalProgress;
        // }

        // function updateProgressBar(){
 
        //     // Calculate the total progress 
        //     var totalProgress = Math.round(calculateTotalProgress());

        //     // Update the width of the progress bar
        //     $('#progressBar').css('width', totalProgress + '%').text(totalProgress + "%");
        // }
    </script>
@endsection

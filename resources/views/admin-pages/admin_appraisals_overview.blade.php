@extends('layout.master')

@section('title')
    <h1>Appraisals Overview</h1>
@endsection

@section('content')
    <div class="row g-3 align-items-start mb-3">
        <div class="col-auto">
            <h4>School Year:</h4>
        </div>
        <div class="col">
            <select class="form-select align-middle" id="evaluation-year-select">
                @if (!$activeEvalYear)
                    <option value="">Select an Evaluation Year (no ongoing evaluation)</option>
                @endif
                @foreach ($evaluationYears as $year)
                    <option value="{{ $year->sy_start }}_{{ $year->sy_end }}"
                        @if ($activeEvalYear && $year->eval_id === $activeEvalYear->eval_id) selected @endif>
                        {{ $year->sy_start }} - {{ $year->sy_end }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class='d-flex gap-3'>
        <div class="content-container text-middle" id="school-year-container"></div>
        <div class="content-container text-middle" id="kra-encoding-container"></div>
        <div class="content-container text-middle" id="performance-review-container"></div>
        <div class="content-container text-middle" id="evaluation-container"></div>
    </div>

    <div class="content-container">
        <div class="table-responsive">
            <div class="input-group mb-2 search-box">
                <input type="text" class="form-control" placeholder="Search" id="search">
                <button class="btn btn-outline-secondary" type="button">
                    <i class='bx bx-search'></i>
                </button>
            </div>

            <table class="table table-bordered" id="admin_appraisals_table">
                <thead class="align-middle">
                    <tr>
                        <th>Name</th>
                        <th>Department</th>
                        <th>Self-<br>Evaluation</th>
                        <th>Immediate<br>Superior</th>
                        <th>Internal<br>Customer 1</th>
                        <th>Internal<br>Customer 2</th>
                        <th>Action</th>
                        <th>Summary</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <nav id="appraisal_pagination_container">
            <ul class="pagination pagination-sm justify-content-end" id="appraisal_pagination"></ul>
        </nav>
    </div>

    <div class="modal fade" id="SummaryModal" data-bs-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="SummaryModal-label">Summary of Ratings</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Loading spinner -->
                    <div class="d-flex justify-content-center align-items-center">
                        <strong id="loadingText" class="text-primary" role="status" aria-hidden="true">Loading...</strong>
                        <div id="loading" class="spinner-border text-primary" role="status" style="display: none;"
                            aria-hidden="true">
                        </div>
                    </div>
                    <div>
                        <div id="SummaryModalBody">
                            <h5>Behavioral Competencies</h5>
                            <div class="table-responsive">
                                <table class='table table-bordered'>
                                    <thead>
                                        <tr>
                                            <th>Components</th>
                                            <th>%</th>
                                            <th>Rating</th>
                                            <th>Weighted Total</th>
                                        </tr>
                                    </thead>
                                    <tbody id="summary_score_body">
                                        <tr>
                                            <td>Self-Evaluation</td>
                                            <td id="SE_perc"></td>
                                            <td id="SE_rating"></td>
                                            <td id="SE_wtotal"></td>
                                        </tr>
                                        <tr>
                                            <td>Internal Customer 1</td>
                                            <td id="IC1_perc"></td>
                                            <td id="IC1_rating"></td>
                                            <td id="IC1_wtotal"></td>
                                        </tr>
                                        <tr>
                                            <td>Internal Customer 2</td>
                                            <td id="IC2_perc"></td>
                                            <td id="IC2_rating"></td>
                                            <td id="IC2_wtotal"></td>
                                        </tr>
                                        <tr>
                                            <td>Immediate Superior</td>
                                            <td id="IS_perc"></td>
                                            <td id="IS_rating"></td>
                                            <td id="IS_wtotal"></td>
                                        </tr>
                                    <tfoot>
                                        <tr>
                                            <td></td>
                                            <td class="text-end" colspan="2">Weighted Total:</td>
                                            <td id="BC_rtotal"></td>
                                        </tr>
                                    </tfoot>
                                    </tbody>
                                </table>
                            </div>
                            <h5>Final Ratings</h5>
                            <div class="table-responsive">
                                <table class='table table-bordered'>
                                    <thead>
                                        <tr>
                                            <th>Components</th>
                                            <th>%</th>
                                            <th>Rating</th>
                                            <th>Weighted Total</th>
                                        </tr>
                                    </thead>
                                    <tbody id="summary_score_body">
                                        <tr>
                                            <td>Behavioral Competencies</td>
                                            <td id="BC_perc"></td>
                                            <td id="BC_rating"></td>
                                            <td id="BC_wtotal"></td>
                                        </tr>
                                        <tr>
                                            <td>Key Results Area</td>
                                            <td id="KRA_perc"></td>
                                            <td id="KRA_rating"></td>
                                            <td id="KRA_wtotal"></td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td class="text-end" colspan="2">Final Score</td>
                                            <td id="FS_wtotal" colspan="2"></td>
                                        </tr>
                                        <tr>
                                            <td class="text-end" colspan="2">Description</td>
                                            <td id="descrip" colspan="2"></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="signatory_modal" data-bs-backdrop="static">
        <div class="modal-dialog modal-xl" style="width:90%">
            <div class="modal-content" id="signatory">
                <div class="modal-header">
                    <h5 class="modal-title fs-5">Signatories</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-sm" id="signtable">
                            <thead>
                                <tr>
                                    <th scope="col">Parties</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Signature</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">KRA</th>
                                    <th scope="col">Performance Review</th>
                                    <th scope="col">Evaluation</th>
                                    <th scope="col">Form</th>
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

    <script>
        $(document).ready(function() {
            loadAdminAppraisalsTable();

            var globalSelectedYear = null;

            $('#evaluation-year-select').change(function() {
                var selectedYear = $(this).val();
                globalSelectedYear = selectedYear;
                loadAdminAppraisalsTable(selectedYear, null);
                // console.log('Selected Year: ' + selectedYear);
            });

            $('#search').on('input', function() {
                var query = $(this).val();
                loadAdminAppraisalsTable(globalSelectedYear, query)
            });

            function loadAdminAppraisalsTable(selectedYear = null, search = null, page = 1) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ route('loadAdminAppraisals') }}",
                    type: 'GET',
                    data: {
                        selectedYear: selectedYear,
                        search: search,
                        page: page
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#admin_appraisals_table tbody').empty();
                            var groupedAppraisals = response.groupedAppraisals;

                            $.each(groupedAppraisals, function(employeeId, data) {
                                var employee = data.employee;
                                var appraisals = data.appraisals;
                                var employeeID = employee.employee_id;

                                var row = $("<tr class='align-middle'>").data("employeeID",
                                    employeeID);
                                row.append($("<td>").text(employee.first_name + ' ' + employee
                                    .last_name));

                                if (typeof employee.department.department_name ===
                                    "undefined") {
                                    row.append($("<td>").text("-"));
                                } else {
                                    row.append($("<td>").text(employee.department
                                        .department_name));
                                }

                                $.each(appraisals, function(index, appraisal) {
                                    var cell = $("<td>");

                                    // Self Evaluation
                                    if (appraisal.evaluation_type ===
                                        'self evaluation') {
                                        if (appraisal.date_submitted !== null) {
                                            var url =
                                                "{{ route('viewPEGOAppraisal', ['appraisal_id' => ':appraisal_id']) }}";
                                            url += "?sy=" + encodeURIComponent(
                                                selectedYear);
                                            url += "&appraisal_id=" +
                                                encodeURIComponent(appraisal
                                                    .appraisal_id);
                                            url += "&appraisee_account_id=" +
                                                encodeURIComponent(appraisal.employee
                                                    .account_id);
                                            url += "&appraisee_name=" +
                                                encodeURIComponent(employee.first_name +
                                                    ' ' + employee.last_name);
                                            url += "&appraisee_department=" +
                                                encodeURIComponent(appraisal.employee
                                                    .department.department_name);

                                            var link = $("<a>")
                                                .attr("href", url.replace(
                                                    ':appraisal_id', appraisal
                                                    .appraisal_id))
                                                .addClass("appraisal-link")
                                                .html(
                                                    '<i class="bx bx-check-circle"></i>'
                                                );

                                            cell.append(link);
                                        } else {
                                            var url =
                                                "{{ route('viewPEGOAppraisal', ['appraisal_id' => ':appraisal_id']) }}";
                                            url += "?sy=" + encodeURIComponent(
                                                selectedYear);
                                            url += "&appraisal_id=" +
                                                encodeURIComponent(appraisal
                                                    .appraisal_id);
                                            url += "&appraisee_account_id=" +
                                                encodeURIComponent(appraisal.employee
                                                    .account_id);
                                            url += "&appraisee_name=" +
                                                encodeURIComponent(employee.first_name +
                                                    ' ' + employee.last_name);
                                            url += "&appraisee_department=" +
                                                encodeURIComponent(appraisal.employee
                                                    .department.department_name);

                                            var link = $("<a>")
                                                .attr("href", url.replace(
                                                    ':appraisal_id', appraisal
                                                    .appraisal_id))
                                                .addClass("appraisal-link")
                                                .html('<i class="bx bx-x-circle"></i>');

                                            cell.append(link);
                                        }
                                    }
                                    // IS Evaluation
                                    else if (appraisal.evaluation_type ===
                                        'is evaluation') {
                                        if (appraisal.date_submitted !== null) {
                                            var url =
                                                "{{ route('viewPEGOAppraisal', ['appraisal_id' => ':appraisal_id']) }}";
                                            url += "?sy=" + encodeURIComponent(
                                                selectedYear);
                                            url += "&appraisal_id=" +
                                                encodeURIComponent(appraisal
                                                    .appraisal_id);
                                            url += "&appraisee_account_id=" +
                                                encodeURIComponent(appraisal.employee
                                                    .account_id);
                                            url += "&appraisee_name=" +
                                                encodeURIComponent(employee.first_name +
                                                    ' ' + employee.last_name);
                                            url += "&appraisee_department=" +
                                                encodeURIComponent(appraisal.employee
                                                    .department.department_name);

                                            var link = $("<a>")
                                                .attr("href", url.replace(
                                                    ':appraisal_id', appraisal
                                                    .appraisal_id))
                                                .addClass("appraisal-link")
                                                .html(
                                                    '<i class="bx bx-check-circle"></i>'
                                                );

                                            cell.append(link);
                                        } else {
                                            var url =
                                                "{{ route('viewPEGOAppraisal', ['appraisal_id' => ':appraisal_id']) }}";
                                            url += "?sy=" + encodeURIComponent(
                                                selectedYear);
                                            url += "&appraisal_id=" +
                                                encodeURIComponent(appraisal
                                                    .appraisal_id);
                                            url += "&appraisee_account_id=" +
                                                encodeURIComponent(appraisal.employee
                                                    .account_id);
                                            url += "&appraisee_name=" +
                                                encodeURIComponent(employee.first_name +
                                                    ' ' + employee.last_name);
                                            url += "&appraisee_department=" +
                                                encodeURIComponent(appraisal.employee
                                                    .department.department_name);

                                            var link = $("<a>")
                                                .attr("href", url.replace(
                                                    ':appraisal_id', appraisal
                                                    .appraisal_id))
                                                .addClass("appraisal-link")
                                                .html('<i class="bx bx-x-circle"></i>');

                                            cell.append(link);
                                        }
                                        // Internal Customer
                                    } else if (appraisal.evaluation_type ===
                                        'internal customer 1') {
                                        if (appraisal.date_submitted !== null) {
                                            var url =
                                                "{{ route('ad.viewICEvaluationForm') }}";
                                            url += "?sy=" + encodeURIComponent(
                                                selectedYear);
                                            url += "&appraisal_id=" +
                                                encodeURIComponent(appraisal
                                                    .appraisal_id);
                                            url += "&appraisee_account_id=" +
                                                encodeURIComponent(
                                                    appraisal.employee.account_id);
                                            url += "&appraisee_name=" +
                                                encodeURIComponent(employee
                                                    .first_name + ' ' + employee
                                                    .last_name);
                                            url += "&appraisee_department=" +
                                                encodeURIComponent(
                                                    appraisal.employee.department
                                                    .department_name);

                                            cell.append(
                                                $("<a>")
                                                .attr("href", url)
                                                .addClass("appraisal-link")
                                                .html(
                                                    '<i class="bx bx-check-circle"></i>'
                                                )
                                            );
                                        } else {
                                            cell.append(
                                                $("<a>").html(
                                                    '<i class="bx bx-x-circle"></i>'
                                                )
                                            );
                                        }
                                    } else if (appraisal.evaluation_type ===
                                        'internal customer 2') {
                                        if (appraisal.date_submitted !== null) {
                                            var url =
                                                "{{ route('ad.viewICEvaluationForm') }}";
                                            url += "?sy=" + encodeURIComponent(
                                                selectedYear);
                                            url += "&appraisal_id=" +
                                                encodeURIComponent(appraisal
                                                    .appraisal_id);
                                            url += "&appraisee_account_id=" +
                                                encodeURIComponent(
                                                    appraisal.employee.account_id);
                                            url += "&appraisee_name=" +
                                                encodeURIComponent(employee
                                                    .first_name + ' ' + employee
                                                    .last_name);
                                            url += "&appraisee_department=" +
                                                encodeURIComponent(
                                                    appraisal.employee.department
                                                    .department_name);

                                            cell.append(
                                                $("<a>")
                                                .attr("href", url)
                                                .addClass("appraisal-link")
                                                .html(
                                                    '<i class="bx bx-check-circle"></i>'
                                                )
                                            );
                                        } else {
                                            cell.append(
                                                $("<a>").html(
                                                    '<i class="bx bx-x-circle"></i>'
                                                )
                                            );
                                        }
                                    }
                                    row.append(cell);
                                });

                                row.append($(
                                    "<td><button type='button' class='btn btn-outline-primary view-btn'>View</button></td>"
                                ));

                                var summaryButton = $('<button>').addClass(
                                        'btn btn-outline-primary')
                                    .text('Summary').on('click', function() {
                                        // Open the SummaryModal when the "Summary" button is clicked
                                        if (response.appraisals.date_submitted !== null) {
                                            $('#loading').show();
                                            $('#SummaryModal').find('#loadingText').show()
                                                .text('Loading');
                                            $('#SummaryModalBody').hide();

                                            $.ajax({
                                                url: '{{ route('getScoreSummary') }}',
                                                type: 'POST',
                                                data: {
                                                    employeeID: employeeID
                                                },
                                                headers: {
                                                    'X-CSRF-TOKEN': $(
                                                        'meta[name="csrf-token"]'
                                                    ).attr('content')
                                                },
                                                success: function(response) {
                                                    $('#loading').hide();
                                                    $('#loadingText').hide();
                                                    $('#SummaryModalBody')
                                                        .show();

                                                    if (response.success) {
                                                        // console.log(response);
                                                        $('#SummaryModal').find(
                                                                '#SE_perc')
                                                            .text(response
                                                                .appraiseeFinalScores
                                                                .scoreWeights
                                                                .self_eval_weight +
                                                                '%');
                                                        $('#SummaryModal').find(
                                                                '#SE_rating')
                                                            .text((response
                                                                    .appraiseeFinalScores
                                                                    .appraisalRatings[
                                                                        'self evaluation'
                                                                    ])
                                                                .toFixed(2));
                                                        $('#SummaryModal').find(
                                                                '#SE_wtotal')
                                                            .text(response
                                                                .appraiseeFinalScores
                                                                .weightedTotals[
                                                                    'self evaluation'
                                                                ].toFixed(2)
                                                            );

                                                        $('#SummaryModal').find(
                                                                '#IC1_perc')
                                                            .text(response
                                                                .appraiseeFinalScores
                                                                .scoreWeights
                                                                .ic1_weight +
                                                                '%');
                                                        $('#SummaryModal').find(
                                                                '#IC1_rating')
                                                            .text((response
                                                                    .appraiseeFinalScores
                                                                    .appraisalRatings[
                                                                        'internal customer 1'
                                                                    ])
                                                                .toFixed(2));
                                                        $('#SummaryModal').find(
                                                                '#IC1_wtotal')
                                                            .text(response
                                                                .appraiseeFinalScores
                                                                .weightedTotals[
                                                                    'internal customer 1'
                                                                ].toFixed(2)
                                                            );

                                                        $('#SummaryModal').find(
                                                                '#IC2_perc')
                                                            .text(response
                                                                .appraiseeFinalScores
                                                                .scoreWeights
                                                                .ic2_weight +
                                                                '%');
                                                        $('#SummaryModal').find(
                                                                '#IC2_rating')
                                                            .text((response
                                                                    .appraiseeFinalScores
                                                                    .appraisalRatings[
                                                                        'internal customer 2'
                                                                    ])
                                                                .toFixed(2));
                                                        $('#SummaryModal').find(
                                                                '#IC2_wtotal')
                                                            .text(response
                                                                .appraiseeFinalScores
                                                                .weightedTotals[
                                                                    'internal customer 2'
                                                                ].toFixed(2)
                                                            );

                                                        $('#SummaryModal').find(
                                                                '#IS_perc')
                                                            .text(response
                                                                .appraiseeFinalScores
                                                                .scoreWeights
                                                                .is_weight + '%'
                                                            );
                                                        $('#SummaryModal').find(
                                                                '#IS_rating')
                                                            .text((response
                                                                    .appraiseeFinalScores
                                                                    .appraisalRatings[
                                                                        'is evaluation'
                                                                    ])
                                                                .toFixed(2));
                                                        $('#SummaryModal').find(
                                                                '#IS_wtotal')
                                                            .text(response
                                                                .appraiseeFinalScores
                                                                .weightedTotals[
                                                                    'is evaluation'
                                                                ].toFixed(2)
                                                            );

                                                        $('#SummaryModal').find(
                                                                '#BC_rtotal')
                                                            .text(response
                                                                .appraiseeFinalScores
                                                                .behavioralCompetenciesRating
                                                                .toFixed(2));

                                                        // Update the Final Ratings section
                                                        $('#SummaryModal').find(
                                                                '#BC_perc')
                                                            .text(response
                                                                .appraiseeFinalScores
                                                                .scoreWeights
                                                                .bh_weight + '%'
                                                            );
                                                        $('#SummaryModal').find(
                                                                '#BC_rating')
                                                            .text(response
                                                                .appraiseeFinalScores
                                                                .behavioralCompetenciesRating
                                                                .toFixed(2));
                                                        $('#SummaryModal').find(
                                                                '#BC_wtotal')
                                                            .text(response
                                                                .appraiseeFinalScores
                                                                .behavioralCompetenciesWeightedTotal
                                                                .toFixed(2));

                                                        $('#SummaryModal').find(
                                                                '#KRA_perc')
                                                            .text(response
                                                                .appraiseeFinalScores
                                                                .scoreWeights
                                                                .kra_weight +
                                                                '%');
                                                        $('#SummaryModal').find(
                                                                '#KRA_rating')
                                                            .text(response
                                                                .appraiseeFinalScores
                                                                .kraRating
                                                                .toFixed(2));
                                                        $('#SummaryModal').find(
                                                                '#KRA_wtotal')
                                                            .text(response
                                                                .appraiseeFinalScores
                                                                .kraWeightedTotal
                                                                .toFixed(2));

                                                        function isBetween(
                                                            value, min, max) {
                                                            return value >=
                                                                min && value <=
                                                                max;
                                                        }

                                                        // Update the Final Score and Description
                                                        function setDescription(
                                                            value) {
                                                            if (isBetween(value,
                                                                    4.85, 5.00
                                                                )) {
                                                                $('#SummaryModal')
                                                                    .find(
                                                                        '#descrip'
                                                                    ).text(
                                                                        'Outstanding'
                                                                    );
                                                            } else if (
                                                                isBetween(value,
                                                                    4.25, 4.84)
                                                            ) {
                                                                $('#SummaryModal')
                                                                    .find(
                                                                        '#descrip'
                                                                    ).text(
                                                                        'Very Satisfactory'
                                                                    );
                                                            } else if (
                                                                isBetween(value,
                                                                    3.50, 4.24)
                                                            ) {
                                                                $('#SummaryModal')
                                                                    .find(
                                                                        '#descrip'
                                                                    ).text(
                                                                        'Satisfactory'
                                                                    );
                                                            } else if (
                                                                isBetween(value,
                                                                    2.75, 3.49)
                                                            ) {
                                                                $('#SummaryModal')
                                                                    .find(
                                                                        '#descrip'
                                                                    ).text(
                                                                        'Fair');
                                                            } else {
                                                                $('#SummaryModal')
                                                                    .find(
                                                                        '#descrip'
                                                                    ).text(
                                                                        'Poor');
                                                            }
                                                        }

                                                        setDescription(response
                                                            .appraiseeFinalScores
                                                            .finalGrade);
                                                        $('#SummaryModal').find(
                                                                '#FS_wtotal')
                                                            .text(response
                                                                .appraiseeFinalScores
                                                                .finalGrade
                                                                .toFixed(2));

                                                        // $('#SummaryModal').modal('show');
                                                    } else {
                                                        $('#SummaryModalBody')
                                                            .hide();
                                                        $('#SummaryModal').find(
                                                                '#SE_perc')
                                                            .text('');
                                                        $('#SummaryModal').find(
                                                                '#SE_rating')
                                                            .text('');
                                                        $('#SummaryModal').find(
                                                                '#SE_wtotal')
                                                            .text('');

                                                        $('#SummaryModal').find(
                                                                '#IC1_perc')
                                                            .text('');
                                                        $('#SummaryModal').find(
                                                                '#IC1_rating')
                                                            .text('');
                                                        $('#SummaryModal').find(
                                                                '#IC1_wtotal')
                                                            .text('');

                                                        $('#SummaryModal').find(
                                                                '#IC2_perc')
                                                            .text('');
                                                        $('#SummaryModal').find(
                                                                '#IC2_rating')
                                                            .text('');
                                                        $('#SummaryModal').find(
                                                                '#IC2_wtotal')
                                                            .text('');

                                                        $('#SummaryModal').find(
                                                                '#IS_perc')
                                                            .text('');
                                                        $('#SummaryModal').find(
                                                                '#IS_rating')
                                                            .text('');
                                                        $('#SummaryModal').find(
                                                                '#IS_wtotal')
                                                            .text('');

                                                        $('#SummaryModal').find(
                                                                '#BC_rtotal')
                                                            .text('');

                                                        // Update the Final Ratings section
                                                        $('#SummaryModal').find(
                                                                '#BC_perc')
                                                            .text('');
                                                        $('#SummaryModal').find(
                                                                '#BC_rating')
                                                            .text('');
                                                        $('#SummaryModal').find(
                                                                '#BC_wtotal')
                                                            .text('');

                                                        $('#SummaryModal').find(
                                                                '#KRA_perc')
                                                            .text('');
                                                        $('#SummaryModal').find(
                                                                '#KRA_rating')
                                                            .text('');
                                                        $('#SummaryModal').find(
                                                                '#KRA_wtotal')
                                                            .text('');

                                                        $('#SummaryModal').find(
                                                                '#loadingText')
                                                            .show().text(
                                                                'Pending...');
                                                    }
                                                },
                                                error: function(xhr, status,
                                                    error) {
                                                    $('#loading').hide();
                                                    // console.log(error);
                                                }
                                            });
                                        } else {
                                            $('#SummaryModalBody').hide();

                                            $('#SummaryModal').find('#SE_perc').text('');
                                            $('#SummaryModal').find('#SE_rating').text('');
                                            $('#SummaryModal').find('#SE_wtotal').text('');

                                            $('#SummaryModal').find('#IC1_perc').text('');
                                            $('#SummaryModal').find('#IC1_rating').text('');
                                            $('#SummaryModal').find('#IC1_wtotal').text('');

                                            $('#SummaryModal').find('#IC2_perc').text('');
                                            $('#SummaryModal').find('#IC2_rating').text('');
                                            $('#SummaryModal').find('#IC2_wtotal').text('');

                                            $('#SummaryModal').find('#IS_perc').text('');
                                            $('#SummaryModal').find('#IS_rating').text('');
                                            $('#SummaryModal').find('#IS_wtotal').text('');

                                            $('#SummaryModal').find('#BC_rtotal').text('');

                                            // Update the Final Ratings section
                                            $('#SummaryModal').find('#BC_perc').text('');
                                            $('#SummaryModal').find('#BC_rating').text('');
                                            $('#SummaryModal').find('#BC_wtotal').text('');

                                            $('#SummaryModal').find('#KRA_perc').text('');
                                            $('#SummaryModal').find('#KRA_rating').text('');
                                            $('#SummaryModal').find('#KRA_wtotal').text('');

                                            $('#SummaryModal').find('#FS_wtotal').text('');

                                            $('#SummaryModal').find('#loadingText').show()
                                                .text('Pending...');
                                        }
                                        $('#SummaryModal').modal('show');
                                    });

                                row.append($('<td>').append(summaryButton));

                                $(document).on('click', '.view-btn', function() {
                                    var closestTr = $(this).closest('tr');

                                    var employeeID = closestTr.data(
                                        'employeeID');

                                    $('#signatory_modal').modal('show');
                                    loadSignatureOverview(employeeID, selectedYear);
                                });

                                $('#admin_appraisals_table tbody').append(row);
                            });

                            if (response.selectedYearDates) {
                                $('#school-year-container').html('<h4>School Year:</h4><p>' + response
                                    .selectedYearDates.sy_start + ' - ' + response
                                    .selectedYearDates.sy_end +
                                    '</p>');
                                $('#kra-encoding-container').html('<h4>KRA Encoding:</h4><p>' +
                                    formatDate(response
                                        .selectedYearDates.kra_start) + ' - ' + formatDate(response
                                        .selectedYearDates.kra_end) +
                                    '</p>');
                                $('#performance-review-container').html('<h4>Performance:</h4><p>' +
                                    formatDate(
                                        response
                                        .selectedYearDates.pr_start) + ' - ' + formatDate(response
                                        .selectedYearDates.pr_end) +
                                    '</p>');
                                $('#evaluation-container').html('<h4>Evaluation:</h4><p>' + formatDate(
                                        response
                                        .selectedYearDates.eval_start) + ' - ' + formatDate(response
                                        .selectedYearDates
                                        .eval_end) +
                                    '</p>');
                            }
                        } else {
                            $('#school-year-container').html('<h4>School Year:</h4><p>' + '-' + '</p>');
                            $('#kra-encoding-container').html('<h4>KRA Encoding:</h4><p>' + '-' +
                                '</p>');
                            $('#performance-review-container').html('<h4>Performance:</h4><p>' + '-' +
                                '</p>');
                            $('#evaluation-container').html('<h4>Evaluation:</h4><p>' + '-' + '</p>');
                            $('#admin_appraisals_table tbody').empty();
                            var row = $("<tr class='middle-align'></tr>").append(
                                "<td colspan='7'><p class='text-secondary fst-italic mt-0'>There is no ongoing evaluation.</p></td>"
                            );
                            $('#admin_appraisals_table tbody').append(row);

                            // console.log('Error: ' + response.error);
                        }

                        var totalPage = response.appraisals.last_page;
                        var currentPage = response.appraisals.current_page;
                        var paginationLinks = response.appraisals.links;
                        console.log(response);
                        $('#appraisal_pagination').empty();
                        // console.log(response.appraisals);
                        for (var totalPageCounter = 1; totalPageCounter <=
                            totalPage; totalPageCounter++) {
                            (function(pageCounter) {
                                var pageItem = $('<li>').addClass('page-item');
                                if (pageCounter === currentPage) {
                                    pageItem.addClass('active');
                                }

                                var pageButton = $('<button>').addClass('page-link').text(
                                    pageCounter);
                                pageButton.click(function() {
                                    // Redirect to the selected page
                                    loadAdminAppraisalsTable(selectedYear, search,
                                        pageCounter);
                                });

                                pageItem.append(pageButton);
                                $('#appraisal_pagination').append(pageItem);
                            })(totalPageCounter);
                        }
                    },
                    error: function(xhr, status, error) {
                        var errorMessage = xhr.responseJSON && xhr.responseJSON.error ? xhr
                            .responseJSON.error : 'An error occurred.';
                        // console.log(errorMessage);
                    }
                });
            }

            function formatDate(dateString) {
                return new Date(dateString).toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: '2-digit',
                });
            }

            function loadSignatureOverview(employeeID, selectedYear = null) {
                // console.log('Load Signature Overview');
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ route('ad.loadSignaturesOverview') }}",
                    type: 'GET',
                    data: {
                        employeeID: employeeID,
                        selectedYear: selectedYear
                    },
                    success: function(response) {
                        if (response.success) {
                            console.log(response);
                            $('#signtable tbody').empty();

                            const appraisalTypeMap = {
                                'self evaluation': 'Appraisee',
                                'is evaluation': 'Immediate Superior',
                                'internal customer 1': 'Internal Customer 1',
                                'internal customer 2': 'Internal Customer 2'
                            };

                            response.appraisals.forEach(function(appraisal) {
                                console.log(appraisal.evaluation_type);
                                const employee = appraisal.employee;
                                const evaluator = appraisal.evaluator;
                                const evaluatorFullName = evaluator ? evaluator.first_name +
                                    ' ' + evaluator
                                    .last_name : '-';
                                const appraisalType = appraisal.evaluation_type;
                                var row = $('<tr class="align-middle">');
                                var appraisalId = appraisal.appraisal_id;

                                const appraisalTypeText = appraisalTypeMap[appraisalType] ||
                                    appraisalType;

                                row.append($('<td>').text(appraisalTypeText));

                                if (employee) {
                                    row.append($('<td>').text(evaluatorFullName));
                                } else {
                                    row.append($('<td>').text('-'));
                                }

                                var viewButton = $('<button>', {
                                    'type': 'button',
                                    'class': 'btn btn-outline-primary view-esig-btn',
                                    'appraisal-id': appraisalId,
                                    'text': 'View'
                                });

                                if (appraisal.date_submitted != null) {
                                    row.append($('<td>').html(viewButton));
                                    row.append($('<td>').text(appraisal.date_submitted));
                                } else {
                                    row.append($('<td>').text('-'));
                                    row.append($('<td>').text('-'));
                                }

                                if (appraisal.evaluation_type.includes('internal customer')) {
                                    row.append($('<td>').text(''));
                                    row.append($('<td>').text(''));
                                    row.append($('<td>').text(''));
                                } else {
                                    var kraToggleButton = $('<div>', {
                                        'class': 'form-check form-switch',
                                        'style': 'display: flex; justify-content: center;',
                                    });

                                    var prToggleButton = $('<div>', {
                                        'class': 'form-check form-switch',
                                        'style': 'display: flex; justify-content: center;',
                                    });

                                    var evalToggleButton = $('<div>', {
                                        'class': 'form-check form-switch',
                                        'style': 'display: flex; justify-content: center;',
                                    });

                                    var kraInput = $('<input>', {
                                        'class': 'form-check-input kra-toggle-btn',
                                        'type': 'checkbox',
                                        'appraisal-id': appraisalId,
                                        'employee-id': appraisal.employee_id,
                                    });
                                    kraToggleButton.append(kraInput);

                                    var prInput = $('<input>', {
                                        'class': 'form-check-input pr-toggle-btn',
                                        'type': 'checkbox',
                                        'appraisal-id': appraisalId,
                                        'employee-id': appraisal.employee_id,
                                    });
                                    prToggleButton.append(prInput);

                                    var evalInput = $('<input>', {
                                        'class': 'form-check-input eval-toggle-btn',
                                        'type': 'checkbox',
                                        'appraisal-id': appraisalId,
                                        'employee-id': appraisal.employee_id,
                                    });
                                    evalToggleButton.append(evalInput);
                                    row.append($('<td>').html(kraToggleButton));
                                    row.append($('<td>').html(prToggleButton));
                                    row.append($('<td>').html(evalToggleButton));

                                    kraInput.prop('checked', appraisal.kra_locked === 1);
                                    prInput.prop('checked', appraisal.pr_locked === 1);
                                    evalInput.prop('checked', appraisal.eval_locked === 1);
                                }

                                var formLockToggleButton = $('<div>', {
                                    'class': 'form-check form-switch',
                                    'style': 'display: flex; justify-content: center;',
                                });

                                var formLockInput = $('<input>', {
                                    'class': 'form-check-input form-toggle-btn',
                                    'type': 'checkbox',
                                    'appraisal-id': appraisalId,
                                    'employee-id': appraisal.employee_id,
                                });

                                formLockToggleButton.append(formLockInput);
                                row.append($('<td>').html(formLockToggleButton));
                                formLockInput.prop('checked', appraisal.locked === 1);

                                $('#signtable tbody').append(row);
                            });

                        } else {
                            // console.log('Error: ' + response.error);
                        }
                    },
                    error: function(xhr, status, error) {
                        var errorMessage = xhr.responseJSON && xhr.responseJSON.error ? xhr
                            .responseJSON.error : 'An error occurred.';
                        // console.log(errorMessage);
                    }
                });
            }

            $(document).on('click', '.view-esig-btn', function() {
                var appraisalID = $(this).attr('appraisal-id');
                // console.log('Global Selected Year: ' + globalSelectedYear);
                loadSignature(appraisalID, globalSelectedYear);
            });

            function loadSignature(appraisalID, selectedYear = null) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{ route('ad.loadSignature') }}',
                    type: 'GET',
                    data: {
                        appraisalID: appraisalID,
                        selectedYear: selectedYear
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#modalImage').attr('src', response.sign_data);
                        } else {
                            // console.log('Fail');
                        }
                    },
                    error: function(xhr, status, error) {
                        // console.log(error);
                    }
                });

                $('#signatory_modal').modal('hide');
                $('#imageModal').modal('show');
            }

            $(document).on('change', '.form-toggle-btn', function() {
                var appraisalID = $(this).attr('appraisal-id');
                var employeeID = $(this).attr('employee-id');
                var $button = $(this);
                console.log('Appraisal ID for Form Toggle: ' + appraisalID);

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{ route('ad.lockUnlockAppraisal') }}',
                    type: 'POST',
                    data: {
                        appraisalID: appraisalID,
                    },
                    success: function(response) {
                        if (response.success) {
                            if (response.locked == true) {
                                $button.text('Unlock');
                            } else {
                                $button.text('Lock');
                            }
                        } else {
                            // console.log('Error: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        // console.log(error);
                    }
                });

            });

            $(document).on('change', '.kra-toggle-btn', function() {
                var appraisalID = $(this).attr('appraisal-id');
                var employeeID = $(this).attr('employee-id');
                var $button = $(this); // Store the clicked button element
                var buttonText = $button.text(); // Get the text content of the button

                console.log('Appraisal ID KRA: ' + appraisalID);

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{ route('ad.toggleKRALock') }}',
                    type: 'POST',
                    data: {
                        appraisalID: appraisalID,
                    },
                    success: function(response) {
                        if (response.success) {
                            // if (response.locked == true) {
                            //     $button.text('Unlock');
                            // } else {
                            //     $button.text('Lock');
                            // }
                            if (buttonText == 'Unlock') {
                                $button.text('Lock');
                            } else {
                                $button.text('Unlock');
                            }
                        } else {
                            // console.log('Error: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        // console.log(error);
                    }
                });
            });

            $(document).on('change', '.pr-toggle-btn', function() {
                var appraisalID = $(this).attr('appraisal-id');
                var employeeID = $(this).attr('employee-id');
                var $button = $(this); // Store the clicked button element
                var buttonText = $button.text(); // Get the text content of the button

                console.log('Appraisal ID PR: ' + appraisalID);

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{ route('ad.togglePRLock') }}',
                    type: 'POST',
                    data: {
                        appraisalID: appraisalID,
                    },
                    success: function(response) {
                        if (response.success) {
                            // if (response.locked == true) {
                            //     $button.text('Lock');
                            // } else {
                            //     $button.text('Unlock');
                            // }

                            if (buttonText == 'Unlock') {
                                $button.text('Lock');
                            } else {
                                $button.text('Unlock');
                            }
                        } else {
                            // console.log('Error: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        // console.log(error);
                    }
                });
            });

            $(document).on('change', '.eval-toggle-btn', function() {
                var appraisalID = $(this).attr('appraisal-id');
                var employeeID = $(this).attr('employee-id');
                var $button = $(this); // Store the clicked button element
                var buttonText = $button.text(); // Get the text content of the button

                console.log('Appraisal ID EVAL: ' + appraisalID);

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{ route('ad.toggleEvalLock') }}',
                    type: 'POST',
                    data: {
                        appraisalID: appraisalID,
                    },
                    success: function(response) {
                        if (response.success) {
                            // if (response.locked == true) {
                            //     $button.text('Lock');
                            // } else {
                            //     $button.text('Unlock');
                            // }

                            if (buttonText == 'Unlock') {
                                $button.text('Lock');
                            } else {
                                $button.text('Unlock');
                            }
                        } else {
                            // console.log('Error: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        // console.log(error);
                    }
                });
            });

            $(document).on('click', '#esig-close-btn', function() {
                $('#imageModal').modal('hide');
            });
        });
    </script>
@endsection

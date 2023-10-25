@extends('layout.master')

@section('title')
    <h1>Appraisals Overview</h1>
@endsection

@section('content')
    <div class='d-flex gap-3'>
        <div class="content-container text-middle">
            <h4>School Year:</h4>
            @if ($activeEvalYear)
                <p>{{ $activeEvalYear->sy_start }} - {{ $activeEvalYear->sy_end }}</p>
            @else
                <p>-</p>
            @endif
        </div>
        <div class="content-container text-middle">
            <h4>KRA Encoding:</h4>
            @if ($activeEvalYear)
                <p>{{ date('F d, Y', strtotime($activeEvalYear->kra_start)) }} -
                    {{ date('F d, Y', strtotime($activeEvalYear->kra_end)) }}</p>
            @else
                <p>-</p>
            @endif
        </div>
        <div class="content-container text-middle">
            <h4>Performance Review:</h4>
            @if ($activeEvalYear)
                <p>{{ date('F d, Y', strtotime($activeEvalYear->pr_start)) }} -
                    {{ date('F d, Y', strtotime($activeEvalYear->pr_end)) }}</p>
            @else
                <p>-</p>
            @endif
        </div>
        <div class="content-container text-middle">
            <h4>Evaluation:</h4>
            @if ($activeEvalYear)
                <p>{{ date('F d, Y', strtotime($activeEvalYear->eval_start)) }} -
                    {{ date('F d, Y', strtotime($activeEvalYear->eval_end)) }}</p>
            @else
                <p>-</p>
            @endif
        </div>
    </div>
    <div class="content-container">
        <div class="table-responsive">
        <table class='table' id="IS_appraisals_table">
            <thead>
                <tr>
                    <th class='medium-column'>Name</th>
                    <th class='medium-column'>Self-Evaluation</th>
                    <th>Internal Customer 1</th>
                    <th>Internal Customer 2</th>
                    <th>Status</th>
                    <th>Action</th>
                    <th>Summary</th>
                </tr>
            </thead>
            <tbody id="IS_appraisals_table_body">

            </tbody>
        </table>
        </div>
        <nav id="is_pagination_container">
            <ul class="pagination pagination-sm justify-content-end" id="is_pagination"></ul>
        </nav>
    </div>

    <div class="modal fade" id="ISModal1" data-bs-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="ISModal-label">Choose 2 Internal Customers:</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="input-group mb-3 search-box">
                        <input type="text" class="form-control" placeholder="Search">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class='bx bx-search'></i>
                        </button>
                    </div>
                    <table class='table table-bordered' id="chooseModalTable1">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Department</th>
                            </tr>
                        </thead>
                        <tbody id="employee_table_body" class="text-justify emp_modal">

                        </tbody>
                    </table>
                    <nav id="ismodal1_pagination_container">
                        <ul class="pagination pagination-sm justify-content-end" id="ismodal1_pagination"></ul>
                    </nav>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ISModal2" data-bs-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="ISModal-label">Choose 2 Internal Customers:</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <input type="text" id="employee_search" class="form-control" placeholder="Search">
                    </div>
                    <table class='table table-bordered'>
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Department</th>
                            </tr>
                        </thead>
                        <tbody id="employee_table_body" class="text-justify emp_modal">

                        </tbody>
                    </table>
                    <nav id="ismodal2_pagination_container">
                        <ul class="pagination pagination-sm justify-content-end" id="ismodal2_pagination"></ul>
                    </nav>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
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
    </div>
    <script>
        $(document).ready(function() {
            loadTableData();
        });

        function refreshPage() {
            location.reload();
        }

        var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        var container = null; // Declare the container variable

        function loadTableData(page = 1) {
            $.ajax({
                url: '{{ route('getISData') }}',
                type: 'GET',
                data: {
                    page: page
                },
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    if (response.success) {
                        $('#IS_appraisals_table_body').empty();
                        // console.log(response);
                        var appraisees = response.appraisee.data;
                        var appraisals = response.appraisals.data;

                        appraisees.forEach(function(appraisee) {
                            var newRow = $('<tr>').attr('id', appraisee.employee_id).append(
                                $('<td>').text(appraisee.first_name + ' ' + appraisee.last_name)
                            );
                            // console.log('Before filtering:');
                            // console.log(appraisals); // Log the original appraisals data

                            var employeeAppraisals = appraisals.filter(function(appraisal) {
                                return appraisal.employee_id === appraisee.employee_id;
                            });

                            var viewLink = null;
                            var ic1Link = null;
                            var ic2Link = null;
                            var AppraiseLink = null;

                            employeeAppraisals.forEach(function(appraisal) {
                                var appraisal_id = encodeURIComponent(appraisal.appraisal_id);

                                if (appraisal.evaluation_type === 'self evaluation') {
                                    if (appraisal.date_submitted !== null) {
                                        viewLink = $('<a>').addClass('btn btn-outline-primary')
                                            .attr('href',
                                                `{{ route('viewPEGOAppraisal', ['appraisal_id' => ':appraisal_id']) }}`
                                                .replace(':appraisal_id', appraisal_id))
                                            .text('View');
                                    } else {
                                        viewLink = $('<a>').addClass('btn btn-outline-primary disabled')
                                            .text('View'); 
                                    }
                                } else if (appraisal.evaluation_type === 'internal customer 1') {
                                    if (appraisal.evaluator_id === null || appraisal.evaluator_id === 0) {
                                        ic1Link = $('<a>').addClass(
                                                'btn ic1 btn-outline-primary')
                                            .attr('data-bs-target', '#ISModal1')
                                            .attr('data-bs-toggle', 'modal')
                                            .attr('data-appraisal-id', appraisal
                                                .appraisal_id)
                                            .attr('data-employee-id', appraisee
                                                .employee_id)
                                            .text('Choose IC1').on('click', function() {
                                                // Get the appraisal_id from the clicked link
                                                var appraisalId = $(this).data(
                                                    'appraisal-id');
                                                var employeeId = $(this).data(
                                                    'employee-id');
                                                // Set the data attribute for the modal
                                                $('#ISModal1').attr('data-appraisal-id',
                                                    appraisalId);

                                                loadEmployeeData(employeeId);

                                            });
                                    } else {
                                        if (appraisal.date_submitted !== null) {
                                        var url =
                                            "{{ route('viewAppraisal', ['appraisal_id' => ':appraisal_id']) }}";
                                        url += "?appraisal_id=" + encodeURIComponent(appraisal
                                            .appraisal_id);
                                        url += "&appraisee_account_id=" + encodeURIComponent(
                                            appraisal.employee.account_id);
                                        url += "&appraisee_name=" + encodeURIComponent(appraisal
                                            .employee
                                            .first_name + ' ' + appraisal.employee.last_name
                                            );
                                        url += "&appraisee_department=" + encodeURIComponent(
                                            appraisal.employee.department.department_name);

                                        ic1Link = $('<a>').addClass('btn btn-outline-primary')
                                            .attr('href', url.replace(':appraisal_id', appraisal
                                                .appraisal_id))
                                            .text(appraisal.evaluator.first_name + ' ' +
                                                appraisal.evaluator.last_name);
                                        } else {
                                            ic1Link = $('<a>').addClass('btn btn-outline-primary disabled')
                                                .text(appraisal.evaluator.first_name + ' ' + appraisal.evaluator.last_name); 
                                        }  
                                    }
                                } else if (appraisal.evaluation_type === 'internal customer 2') {
                                    if (appraisal.evaluator_id === null || appraisal.evaluator_id === 0) {
                                        ic2Link = $('<a>').addClass(
                                                'btn ic2 btn-outline-primary')
                                            .attr('data-bs-target', '#ISModal2')
                                            .attr('data-bs-toggle', 'modal')
                                            .attr('data-appraisal-id', appraisal.appraisal_id)
                                            .attr('data-employee-id', appraisee
                                                .employee_id)
                                            // Include the appraisal ID here
                                            .text('Choose IC2').on('click', function() {
                                                // console.log('waz clicked');
                                                // Get the appraisal_id from the clicked link
                                                var appraisalId = $(this).data(
                                                    'appraisal-id');

                                                var employeeId = $(this).data(
                                                    'employee-id');
                                                // console.log(appraisalId);

                                                // Set the data attribute for the modal
                                                $('#ISModal2').attr('data-appraisal-id',
                                                    appraisalId);
                                                // console.log(appraisalId);
                                                loadEmployeeData(employeeId);
                                            });
                                    } else {
                                        if (appraisal.date_submitted !== null) {
                                            var url =
                                            "{{ route('viewAppraisal', ['appraisal_id' => ':appraisal_id']) }}";
                                            url += "?appraisal_id=" + encodeURIComponent(appraisal.appraisal_id);
                                            url += "&appraisee_account_id=" + encodeURIComponent(
                                                appraisal.employee.account_id);
                                            url += "&appraisee_name=" + encodeURIComponent(appraisal
                                                .employee.first_name + ' ' + appraisal.employee.last_name);
                                            url += "&appraisee_department=" + encodeURIComponent(
                                                appraisal.employee.department.department_name);

                                            ic2Link = $('<a>').addClass('btn btn-outline-primary')
                                                .attr('href', url.replace(':appraisal_id', appraisal.appraisal_id))
                                                .text(appraisal.evaluator.first_name + ' ' + appraisal.evaluator.last_name);
                                        } else {
                                            ic2Link = $('<a>').addClass('btn btn-outline-primary disabled')
                                                .text(appraisal.evaluator.first_name + ' ' + appraisal.evaluator.last_name); 
                                        }                                       
                                    }
                                } else if (appraisal.evaluation_type === 'is evaluation') {
                                    if (appraisal.date_submitted !== null) {
                                        AppraiseLink = $('<a>').addClass(
                                                'btn btn-outline-primary')
                                            .attr('href',
                                                `{{ route('viewAppraisal', ['appraisal_id' => ':appraisal_id']) }}`
                                                .replace(':appraisal_id', appraisal_id))
                                            .text('View');
                                    } else {
                                        AppraiseLink = $('<a>').addClass(
                                                'btn btn-outline-primary')
                                            .attr('href',
                                                `{{ route('viewAppraisal', ['appraisal_id' => ':appraisal_id']) }}`
                                                .replace(':appraisal_id', appraisal_id))
                                            .text('Appraise');
                                    }
                                }
                            });

                            var status = response.status[appraisee.employee_id];

                            var summaryButton = $('<button>').addClass('btn btn-outline-primary')
                                .text('Summary').on('click', function() {
                                    // Open the SummaryModal when the "Summary" button is clicked
                                    if (response.appraisals.date_submitted !== null) {
                                        $('#loading').show();
                                        $('#SummaryModal').find('#loadingText').show().text(
                                            'Loading');
                                        $('#SummaryModalBody').hide();

                                        $.ajax({
                                            url: '{{ route('getScoreSummary') }}',
                                            type: 'POST',
                                            data: {
                                                employeeID: appraisee.employee_id
                                            },
                                            headers: {
                                                'X-CSRF-TOKEN': csrfToken
                                            },
                                            success: function(response) {
                                                $('#loading').hide();
                                                $('#loadingText').hide();
                                                $('#SummaryModalBody').show();

                                                if (response.success) {
                                                    // console.log(response);
                                                    $('#SummaryModal').find('#SE_perc')
                                                        .text(response
                                                            .appraiseeFinalScores
                                                            .scoreWeights
                                                            .self_eval_weight + '%');
                                                    $('#SummaryModal').find(
                                                        '#SE_rating').text((response
                                                            .appraiseeFinalScores
                                                            .appraisalRatings[
                                                                'self evaluation'])
                                                        .toFixed(2));
                                                    $('#SummaryModal').find(
                                                        '#SE_wtotal').text(response
                                                        .appraiseeFinalScores
                                                        .weightedTotals[
                                                            'self evaluation']
                                                        .toFixed(2));

                                                    $('#SummaryModal').find('#IC1_perc')
                                                        .text(response
                                                            .appraiseeFinalScores
                                                            .scoreWeights.ic1_weight +
                                                            '%');
                                                    $('#SummaryModal').find(
                                                        '#IC1_rating').text((
                                                        response
                                                        .appraiseeFinalScores
                                                        .appraisalRatings[
                                                            'internal customer 1'
                                                            ]).toFixed(2));
                                                    $('#SummaryModal').find(
                                                        '#IC1_wtotal').text(response
                                                        .appraiseeFinalScores
                                                        .weightedTotals[
                                                            'internal customer 1']
                                                        .toFixed(2));

                                                    $('#SummaryModal').find('#IC2_perc')
                                                        .text(response
                                                            .appraiseeFinalScores
                                                            .scoreWeights.ic2_weight +
                                                            '%');
                                                    $('#SummaryModal').find(
                                                        '#IC2_rating').text((
                                                        response
                                                        .appraiseeFinalScores
                                                        .appraisalRatings[
                                                            'internal customer 2'
                                                            ]).toFixed(2));
                                                    $('#SummaryModal').find(
                                                        '#IC2_wtotal').text(response
                                                        .appraiseeFinalScores
                                                        .weightedTotals[
                                                            'internal customer 2']
                                                        .toFixed(2));

                                                    $('#SummaryModal').find('#IS_perc')
                                                        .text(response
                                                            .appraiseeFinalScores
                                                            .scoreWeights.is_weight +
                                                            '%');
                                                    $('#SummaryModal').find(
                                                        '#IS_rating').text((response
                                                            .appraiseeFinalScores
                                                            .appraisalRatings[
                                                                'is evaluation'])
                                                        .toFixed(2));
                                                    $('#SummaryModal').find(
                                                        '#IS_wtotal').text(response
                                                        .appraiseeFinalScores
                                                        .weightedTotals[
                                                            'is evaluation']
                                                        .toFixed(2));

                                                    $('#SummaryModal').find(
                                                        '#BC_rtotal').text(response
                                                        .appraiseeFinalScores
                                                        .behavioralCompetenciesRating
                                                        .toFixed(2));

                                                    // Update the Final Ratings section
                                                    $('#SummaryModal').find('#BC_perc')
                                                        .text(response
                                                            .appraiseeFinalScores
                                                            .scoreWeights.bh_weight +
                                                            '%');
                                                    $('#SummaryModal').find(
                                                        '#BC_rating').text(response
                                                        .appraiseeFinalScores
                                                        .behavioralCompetenciesRating
                                                        .toFixed(2));
                                                    $('#SummaryModal').find(
                                                        '#BC_wtotal').text(response
                                                        .appraiseeFinalScores
                                                        .behavioralCompetenciesWeightedTotal
                                                        .toFixed(2));

                                                    $('#SummaryModal').find('#KRA_perc')
                                                        .text(response
                                                            .appraiseeFinalScores
                                                            .scoreWeights.kra_weight +
                                                            '%');
                                                    $('#SummaryModal').find(
                                                        '#KRA_rating').text(response
                                                        .appraiseeFinalScores
                                                        .kraRating.toFixed(2));
                                                    $('#SummaryModal').find(
                                                        '#KRA_wtotal').text(response
                                                        .appraiseeFinalScores
                                                        .kraWeightedTotal.toFixed(2)
                                                        );

                                                    function isBetween(value, min,
                                                    max) {
                                                        return value >= min && value <=
                                                            max;
                                                    }

                                                    // Update the Final Score and Description
                                                    function setDescription(value) {
                                                        if (isBetween(value, 4.85,
                                                            5.00)) {
                                                            $('#SummaryModal').find(
                                                                '#descrip').text(
                                                                'Outstanding');
                                                        } else if (isBetween(value,
                                                                4.25, 4.84)) {
                                                            $('#SummaryModal').find(
                                                                '#descrip').text(
                                                                'Very Satisfactory');
                                                        } else if (isBetween(value,
                                                                3.50, 4.24)) {
                                                            $('#SummaryModal').find(
                                                                '#descrip').text(
                                                                'Satisfactory');
                                                        } else if (isBetween(value,
                                                                2.75, 3.49)) {
                                                            $('#SummaryModal').find(
                                                                '#descrip').text(
                                                                'Fair');
                                                        } else {
                                                            $('#SummaryModal').find(
                                                                '#descrip').text(
                                                                'Poor');
                                                        }
                                                    }

                                                    setDescription(response
                                                        .appraiseeFinalScores
                                                        .finalGrade);
                                                    $('#SummaryModal').find(
                                                        '#FS_wtotal').text(response
                                                        .appraiseeFinalScores
                                                        .finalGrade.toFixed(2));

                                                    // $('#SummaryModal').modal('show');
                                                } else {
                                                    $('#SummaryModalBody').hide();
                                                    $('#SummaryModal').find('#SE_perc')
                                                        .text('');
                                                    $('#SummaryModal').find(
                                                        '#SE_rating').text('');
                                                    $('#SummaryModal').find(
                                                        '#SE_wtotal').text('');

                                                    $('#SummaryModal').find('#IC1_perc')
                                                        .text('');
                                                    $('#SummaryModal').find(
                                                        '#IC1_rating').text('');
                                                    $('#SummaryModal').find(
                                                        '#IC1_wtotal').text('');

                                                    $('#SummaryModal').find('#IC2_perc')
                                                        .text('');
                                                    $('#SummaryModal').find(
                                                        '#IC2_rating').text('');
                                                    $('#SummaryModal').find(
                                                        '#IC2_wtotal').text('');

                                                    $('#SummaryModal').find('#IS_perc')
                                                        .text('');
                                                    $('#SummaryModal').find(
                                                        '#IS_rating').text('');
                                                    $('#SummaryModal').find(
                                                        '#IS_wtotal').text('');

                                                    $('#SummaryModal').find(
                                                        '#BC_rtotal').text('');

                                                    // Update the Final Ratings section
                                                    $('#SummaryModal').find('#BC_perc')
                                                        .text('');
                                                    $('#SummaryModal').find(
                                                        '#BC_rating').text('');
                                                    $('#SummaryModal').find(
                                                        '#BC_wtotal').text('');

                                                    $('#SummaryModal').find('#KRA_perc')
                                                        .text('');
                                                    $('#SummaryModal').find(
                                                        '#KRA_rating').text('');
                                                    $('#SummaryModal').find(
                                                        '#KRA_wtotal').text('');

                                                    $('#SummaryModal').find(
                                                        '#loadingText').show().text(
                                                        'Pending...');
                                                }
                                            },
                                            error: function(xhr, status, error) {
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

                                        $('#SummaryModal').find('#loadingText').show().text(
                                            'Pending...');
                                    }
                                    $('#SummaryModal').modal('show');
                                });

                            newRow.append(
                                $('<td>').append(viewLink),
                                $('<td>').append($('<div>').append(ic1Link)),
                                $('<td>').append($('<div>').append(ic2Link)),
                                $('<td>').text(status),
                                $('<td>').append($('<div>').append(AppraiseLink)),
                                $('<td>').append(summaryButton)
                            );

                            $('#IS_appraisals_table_body').append(newRow);
                        });

                        // Handle pagination
                        var totalPage = response.appraisee.last_page;
                        var currentPage = response.appraisee.current_page;
                        var paginationLinks = response.appraisals.links;
                        $('#is_pagination').empty();
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
                                    loadTableData(pageCounter);
                                });
                                pageItem.append(pageButton);
                                $('#is_pagination').append(pageItem);
                            })(totalPageCounter);
                        }
                    } else {
                        // console.log(response.error);

                    }
                },
                error: function(xhr, status, error) {
                    // console.log(error);
                }
            });
        }

        function toggleRowCheckbox(rowId) {
            $('#' + rowId).toggleClass('selected');
        }

        function saveSelection() {
            var selectedRows = [];
            $('.selected').each(function() {
                selectedRows.push($(this).attr('id'));
            });
        }

        var selectedRows = [];

        function loadEmployeeData(excludedEmployeeId, page = 1) {
            $.ajax({
                url: '{{ route('getEmployeesData') }}',
                type: 'GET',
                data: {
                    excludedEmployeeId: excludedEmployeeId,
                    page: page
                }, // Use an object
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    if (response.success) {
                        $('.emp_modal').empty();

                        var employees = response.employees.data;
                        // console.log(response);
                        for (var i = 0; i < employees.length; i++) {
                            var employee = employees[i];
                            // Check if the employee_id matches the excludedEmployeeId and is not the current appraisee
                            if (employee.employee_id !== excludedEmployeeId && response.evaluatorId !== employee
                                .employee_id) {
                                // Create and append the row if there is no match

                                var newRow = $('<tr>').addClass('row-checkbox').append(
                                    $('<div>').attr('id', 'checkboxes').append(
                                        $('<input>').attr('type', 'checkbox').attr('name', 'ic').attr(
                                            'value', employee.employee_id).prop('disabled',
                                            false),
                                        $('<label>').addClass(
                                            'chooseIC text-center d-flex justify-content-center').attr(
                                            'for', employee.employee_id).append(
                                            $('<td>').text(employee.first_name + ' ' + employee.last_name),
                                        ),
                                    ),
                                    $('<td>').text(employee.department_name)
                                );

                                newRow.on('click', function() {
                                    var checkbox = $(this).find('input[type="checkbox"]');
                                    var isChecked = checkbox.prop('checked');
                                    var checkedCount = $('input[type="checkbox"]:checked').length;

                                    if (isChecked || checkedCount < 2) {
                                        checkbox.prop('checked', !isChecked);
                                        $(this).toggleClass('row-selected', !isChecked);

                                        // Set the employee ID in the modal title
                                        $('#ISModal1 .modal-title').data('employee-id', employee
                                            .employee_id);

                                        updateSelectedRows();
                                    }
                                });

                                $('.emp_modal').append(newRow);
                            }
                        }
                        // Handle pagination
                        var totalPage = response.employees.last_page;
                        var currentPage = response.employees.current_page;

                        $('#ismodal1_pagination').empty();
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
                                    loadEmployeeData(excludedEmployeeId, pageCounter);
                                });
                                pageItem.append(pageButton);
                                $('#ismodal1_pagination').append(pageItem);
                            })(totalPageCounter);
                        }

                        $('#ismodal2_pagination').empty();
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
                                    loadEmployeeData(excludedEmployeeId, pageCounter);
                                });
                                pageItem.append(pageButton);
                                $('#ismodal2_pagination').append(pageItem);
                            })(totalPageCounter);
                        }

                    } else {
                        // console.log(response.error);
                    }
                },
                error: function(xhr, status, error) {
                    // console.log(error);
                }
            });
        }

        function updateSelectedRows() {
            selectedRows = [];
            $('input[type="checkbox"]:checked').each(function() {
                var row = $(this).closest('tr');
                selectedRows.push(row);
            });
        }

        $(document).on('click', '#ISModal1 .btn-primary', function() {
            $('#employee_table_body').empty();
            $('#ISModal1 .search-box').hide();

            for (var i = 0; i < selectedRows.length; i++) {
                var row = selectedRows[i];
                $('#employee_table_body').append(row);
            }

            // Check if container is defined before appending
            if (container) {
                $('#ISModal1 .modal-body').append(container);
            }
            selectedRows = [];
        });

        // Variables to keep track of selected employees
        var selectedEmployees = [];
        var selectedICs = []; // To store selected employee IDs

        // Event listener for the "Submit" button in ISModal1 and ISModal2
        $('#ISModal1 .btn-primary, #ISModal2 .btn-primary').on('click', function() {
            // Check which modal is active
            activeModal = $(this).closest('.modal');

            // Reset the selected employees and employee IDs arrays
            selectedEmployees = [];
            selectedICs = [];

            // Collect selected ICs and employee names
            $('input[name="ic"]:checked').each(function() {
                selectedICs.push($(this).val());
                selectedEmployees.push($(this).data('employee-name')); // Capture selected employee names
            });

            // Get the employee ID from the modal title (assuming you have set it)
            var appraisalIdToUpdate = activeModal.data('appraisal-id');

            $.ajax({
                url: '{{ route('assignInternalCustomer') }}',
                type: 'POST',
                data: {
                    employee_id: selectedICs,
                    appraisalId: appraisalIdToUpdate
                },
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    if (response.success) {
                        // Assignment successful, you can display a success message if needed
                        // console.log('Internal Customer(s) assigned successfully.');

                        // Check if the active modal is ISModal1 and two names are selected
                        if (activeModal.attr('id') === 'ISModal1' && selectedEmployees.length === 2) {
                            // Update ISModal1 with the first selected employee's name
                            $('#ISModal1 .modal-body').html('<p>Selected Employee in ISModal1: ' +
                                selectedEmployees[0] + '</p>');
                        } else if (activeModal.attr('id') === 'ISModal2' && selectedEmployees.length ===
                            2) {
                            // Update ISModal2 with the second selected employee's name
                            $('#ISModal2 .modal-body').html('<p>Selected Employee in ISModal2: ' +
                                selectedEmployees[1] + '</p>');

                        }
                        // Close the active modal
                        activeModal.modal('hide');
                        refreshPage();
                    } else {
                        // Handle errors, e.g., display an error message
                        // console.log('Error: ' + response.error);
                    }
                },
                error: function(xhr, status, error) {
                    // Handle AJAX errors
                    // console.log(error);
                }
            });
        });
    </script>
@endsection

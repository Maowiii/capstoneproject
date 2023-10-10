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
        <table class='table' id="IS_appraisals_table">
            <thead>
                <tr>
                    <th class='large-column'>Name</th>
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

    <div class="modal fade modal-md" id="SummaryModal" data-bs-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="SummaryModal-label">Summary of Ratings</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <h5>Behavioral Competencies</h5>
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
                                <td id="SE_perc" data-val="0.1">10%</td>
                                <td id="SE_rating"></td>
                                <td id="SE_wtotal"></td>
                            </tr>
                            <tr>
                                <td>Internal Customer 1</td>
                                <td id="IC1_perc" data-val="0.2">20%</td>
                                <td id="IC1_rating"></td>
                                <td id="IC1_wtotal"></td>                            </tr>
                            <tr>
                                <td>Internal Customer 2</td>
                                <td id="IC2_perc" data-val="0.2">20%</td>
                                <td id="IC2_rating"></td>
                                <td id="IC2_wtotal"></td>                            </tr>
                            <tr>
                                <td>Immediate Superior</td>
                                <td id="IS_perc" data-val="0.5">50%</td>
                                <td id="IS_rating"></td>
                                <td id="IS_wtotal"></td>                            </tr>
                        </tbody>
                    </table>
                    <h5>Final Ratings</h5>
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
                                <td id="BC_rating" data-val="0.4">40%</td>
                                
                                <td id="BC_wtotal"></td>                            </tr>
                            </tr>
                            <tr>
                                <td>Key Results Area</td>
                                <td id="KRA_rating" data-val="0.6">60%</td>
                                <td id="KRA_wtotal"></td>                            </tr>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td></td>
                                <td>Final Score</td>
                                <td id="FS_wtotal"></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Description</td>
                                <td id=""></td>
                            </tr>
                        </tfoot>
                    </table>
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
                            console.log('Before filtering:');
                            console.log(appraisals); // Log the original appraisals data

                            var employeeAppraisals = appraisals.filter(function(appraisal) {
                                return appraisal.employee_id === appraisee.employee_id;
                            });

                            var viewLink = null;
                            var ic1Link = null;
                            var ic2Link = null;
                            var AppraiseLink = null;

                            var summaryButton = $('<button>').addClass('btn btn-outline-primary')
                                .text('Summary').on('click', function() {
                                    // Open the SummaryModal when the "Summary" button is clicked
                                    $('#SummaryModal').modal('show');
                                });

                            employeeAppraisals.forEach(function(appraisal) {
                                var appraisal_id = encodeURIComponent(appraisal.appraisal_id);

                                if (appraisal.evaluation_type === 'self evaluation') {
                                    viewLink = $('<a>').addClass('btn btn-outline-primary')
                                        .attr('href',
                                            `{{ route('viewPEGOAppraisal', ['appraisal_id' => ':appraisal_id']) }}`
                                            .replace(':appraisal_id', appraisal_id))
                                        .text('View');

                                        if (appraisal.date_submitted !== null) {
                                            var SETotalTd = $('#SummaryModal').find('#SE_rating');
                                            SETotalTd.text(appraisal.bh_score);

                                            var SEperc = $('#SummaryModal').find('#SE_perc').data('val');
                                            var SE_wtotal = (SEperc * appraisal.bh_score).toFixed(2);

                                            var SE_wtotalTD = $('#SummaryModal').find('#SE_wtotal');
                                            SE_wtotalTD.text(SE_wtotal);
                                        }
                                } else if (appraisal.evaluation_type ===
                                    'internal customer 1') {
                                    if (appraisal.evaluator_id === null) {
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
                                        var url =
                                            "{{ route('viewAppraisal', ['appraisal_id' => ':appraisal_id']) }}";
                                        url += "?appraisal_id=" + encodeURIComponent(appraisal
                                            .appraisal_id);
                                        url += "&appraisee_account_id=" + encodeURIComponent(
                                            appraisal.employee.account_id);
                                        url += "&appraisee_name=" + encodeURIComponent(appraisal.employee
                                            .first_name + ' ' + appraisal.employee.last_name);
                                        url += "&appraisee_department=" + encodeURIComponent(
                                            appraisal.employee.department.department_name);

                                        ic1Link = $('<a>').addClass('btn btn-outline-primary')
                                            .attr('href', url.replace(':appraisal_id', appraisal
                                                .appraisal_id))
                                            .text(appraisal.evaluator.first_name + ' ' +
                                                appraisal.evaluator.last_name);


                                        if (appraisal.date_submitted !== null) {
                                            var SETotalTd = $('#SummaryModal').find('#IC1_rating');
                                            SETotalTd.text(appraisal.ic_score);

                                            var SEperc = $('#SummaryModal').find('#IC1_perc').data('val');
                                            var SE_wtotal = (SEperc * appraisal.ic_score).toFixed(2);

                                            var SE_wtotalTD = $('#SummaryModal').find('#IC1_wtotal');
                                            SE_wtotalTD.text(SE_wtotal);
                                        }
                                    }
                                } else if (appraisal.evaluation_type ===
                                    'internal customer 2') {
                                    if (appraisal.evaluator_id === null) {
                                        ic2Link = $('<a>').addClass(
                                                'btn ic2 btn-outline-primary')
                                            .attr('data-bs-target', '#ISModal2')
                                            .attr('data-bs-toggle', 'modal')
                                            .attr('data-appraisal-id', appraisal.appraisal_id)
                                            .attr('data-employee-id', appraisee
                                                .employee_id)
                                            // Include the appraisal ID here
                                            .text('Choose IC2').on('click', function() {
                                                console.log('waz clicked');
                                                // Get the appraisal_id from the clicked link
                                                var appraisalId = $(this).data(
                                                    'appraisal-id');

                                                var employeeId = $(this).data(
                                                    'employee-id');
                                                console.log(appraisalId);

                                                // Set the data attribute for the modal
                                                $('#ISModal2').attr('data-appraisal-id',
                                                    appraisalId);
                                                console.log(appraisalId);
                                                loadEmployeeData(employeeId);
                                            });
                                    } else {
                                        var url =
                                            "{{ route('viewAppraisal', ['appraisal_id' => ':appraisal_id']) }}";
                                        url += "?appraisal_id=" + encodeURIComponent(appraisal
                                            .appraisal_id);
                                        url += "&appraisee_account_id=" + encodeURIComponent(
                                            appraisal.employee.account_id);
                                        url += "&appraisee_name=" + encodeURIComponent(appraisal.employee
                                            .first_name + ' ' + appraisal.employee.last_name);
                                        url += "&appraisee_department=" + encodeURIComponent(
                                            appraisal.employee.department.department_name);

                                        ic2Link = $('<a>').addClass('btn btn-outline-primary')
                                            .attr('href', url.replace(':appraisal_id', appraisal
                                                .appraisal_id))
                                            .text(appraisal.evaluator.first_name + ' ' +
                                                appraisal.evaluator.last_name);

                                        if (appraisal.date_submitted !== null) {
                                            var SETotalTd = $('#SummaryModal').find('#IC2_rating');
                                            SETotalTd.text(appraisal.ic_score);

                                            var SEperc = $('#SummaryModal').find('#IC2_perc').data('val');
                                            var SE_wtotal = (SEperc * appraisal.ic_score).toFixed(2);

                                            var SE_wtotalTD = $('#SummaryModal').find('#IC2_wtotal');
                                            SE_wtotalTD.text(SE_wtotal);
                                        }
                                    }
                                } else if (appraisal.evaluation_type === 'is evaluation') {
                                    if (appraisal.date_submitted !== null) {
                                        AppraiseLink = $('<a>').addClass(
                                                'btn btn-outline-primary')
                                            .attr('href',
                                                `{{ route('viewPEGOAppraisal', ['appraisal_id' => ':appraisal_id']) }}`
                                                .replace(':appraisal_id', appraisal_id))
                                            .text('View');

                                        if (appraisal.date_submitted !== null) {
                                            var SETotalTd = $('#SummaryModal').find('#IS_rating');
                                            SETotalTd.text(appraisal.bh_score);

                                            var SEperc = $('#SummaryModal').find('#IS_perc').data('val');
                                            var SE_wtotal = (SEperc * appraisal.bh_score).toFixed(2);

                                            var SE_wtotalTD = $('#SummaryModal').find('#IS_wtotal');
                                            SE_wtotalTD.text(SE_wtotal);
                                        }
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

                            newRow.append(
                                $('<td>').append(viewLink),
                                $('<td>').append($('<div>').append(ic1Link)),
                                $('<td>').append($('<div>').append(ic2Link)),
                                $('<td>').text(status),
                                $('<td>').append($('<div>').append(AppraiseLink)),
                                $('<td>').append(summaryButton) 
                            );

                            $('#IS_appraisals_table_body').append(newRow);

                            if(response.final_score[appraisee.employee_id]){
                                var finalScore = response.final_score[appraisee.employee_id];
                                console.log(finalScore);

                                var BCperc = $('#SummaryModal').find('#BC_rating').data('val');
                                var KRAperc = $('#SummaryModal').find('#KRA_rating').data('val');

                                var BCTd = $('#SummaryModal').find('#BC_wtotal');
                                BCTd.text(finalScore.behavioralCompetenciesGrade);

                                var KRATd = $('#SummaryModal').find('#KRA_wtotal');
                                KRATd.text(finalScore.kraFS);

                                var finalGradeTd = $('#SummaryModal').find('#BC_wtotal');
                                finalGradeTd.text(finalScore.finalGrade);
                            } 
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
                        console.log(response.error);

                    }
                },
                error: function(xhr, status, error) {
                    console.log(error);
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
                        console.log(response);
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
                        console.log(response.error);
                    }
                },
                error: function(xhr, status, error) {
                    console.log(error);
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
                        console.log('Internal Customer(s) assigned successfully.');

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
                        console.log('Error: ' + response.error);
                    }
                },
                error: function(xhr, status, error) {
                    // Handle AJAX errors
                    console.log(error);
                }
            });
        });
    </script>
@endsection

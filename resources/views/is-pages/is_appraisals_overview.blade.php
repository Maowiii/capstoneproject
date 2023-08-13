@extends('layout.master')
 
@section('title')
    <h1>Appraisals Overview</h1>
@endsection

@section('content')
    <div class='d-flex gap-3'>
        <div class="content-container text-middle">
            <h4>School Year:</h4>
        </div>

        <div class="content-container text-middle">
            <h4>KRA Encoding:</h4>
        </div>

        <div class="content-container text-middle">
            <h4>Performance Review:</h4>
        </div>

        <div class="content-container text-middle">
            <h4>Evaluation:</h4>
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
                </tr>
            </thead>
            <tbody id="IS_appraisals_table_body">

            </tbody>
        </table>
    </div>

    <div class="modal fade" id="ISModal1" data-bs-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="ISModal-label">Choose the Internal Customer:</h1>
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
                        <tbody id="employee_table_body" class="text-justify">

                        </tbody>
                    </table>
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
                    <h1 class="modal-title fs-5" id="ISModal-label">Choose the Internal Customer:</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class='table table-bordered'>
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Department</th>
                            </tr>
                        </thead>
                        <tbody id="employee_table_body">

                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            loadTableData();
            loadEmployeeData();
        });

        var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        function loadTableData() {
            $.ajax({
                url: '{{ route('getISData') }}',
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    if (response.success) {
                        $('#IS_appraisals_table_body').empty();

                        var appraisees = response.appraisee;
                        var appraisals = response.appraisals;

                        appraisees.forEach(function(appraisee) {
                            var newRow = $('<tr>').attr('id', appraisee.employee_id).append(
                                $('<td>').text(appraisee.first_name + ' ' + appraisee.last_name)
                            );

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
                                    viewLink = $('<a>').addClass('btn btn-outline-primary')
                                        .attr('href',
                                            `{{ route('viewAppraisal', ['appraisal_id' => ':appraisal_id']) }}`
                                            .replace(':appraisal_id', appraisal_id))
                                        .text('View');
                                } else if (appraisal.evaluation_type ===
                                    'internal customer 1') {
                                    if (appraisal.evaluator_id === null) {
                                        ic1Link = $('<a>').addClass('btn btn-outline-primary')
                                            .attr('data-bs-target', '#ISModal1')
                                            .attr('data-bs-toggle', 'modal')
                                            .text('Choose IC1');
                                    } else {
                                        ic1Link = $('<a>').addClass('btn btn-outline-primary')
                                            .attr('href',
                                                `{{ route('viewAppraisal', ['appraisal_id' => ':appraisal_id']) }}`
                                                .replace(':appraisal_id', appraisal_id))
                                            .text(appraisal.evaluator.first_name + ' ' +
                                                appraisal.evaluator.last_name);
                                    }
                                } else if (appraisal.evaluation_type ===
                                    'internal customer 2') {
                                    if (appraisal.evaluator_id === null) {
                                        ic2Link = $('<a>').addClass('btn btn-outline-primary')
                                            .attr('data-bs-target', '#ISModal2')
                                            .attr('data-bs-toggle', 'modal')
                                            .text('Choose IC2');
                                    } else {
                                        ic2Link = $('<a>').addClass('btn btn-outline-primary')
                                            .attr('href',
                                                `{{ route('viewAppraisal', ['appraisal_id' => ':appraisal_id']) }}`
                                                .replace(':appraisal_id', appraisal_id))
                                            .text(appraisal.evaluator.first_name + ' ' +
                                                appraisal.evaluator.last_name);
                                    }
                                } else if (appraisal.evaluation_type === 'is evaluation') {
                                    AppraiseLink = $('<a>').addClass('btn btn-outline-primary')
                                        .attr('href',
                                            `{{ route('viewAppraisal', ['appraisal_id' => ':appraisal_id']) }}`
                                            .replace(':appraisal_id', appraisal_id))
                                        .text('Appraise');
                                }
                            });

                            newRow.append(
                                $('<td>').append(viewLink),
                                $('<td>').append($('<div>').append(ic1Link)),
                                $('<td>').append($('<div>').append(ic2Link)),
                                $('<td>').text('Pending'),
                                $('<td>').append(
                                    $('<div>').append(AppraiseLink)
                                )
                            );

                            $('#IS_appraisals_table_body').append(newRow);
                        });
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

        function loadEmployeeData() {
            $.ajax({
                url: '{{ route('getEmployeesData') }}',
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    if (response.success) {
                        $('#employee_table_body').empty();

                        var employees = response.employees;
                        for (var i = 0; i < employees.length; i++) {
                            var employee = employees[i];

                            var newRow = $('<tr>').addClass('row-checkbox').append(
                                $('<div>').attr('id', 'checkboxes').append(
                                    $('<input>').attr('type', 'checkbox').attr('name', 'ic').attr('value',
                                        employee.employee_id),
                                    $('<label>').addClass('chooseIC text-justify').attr('for', employee
                                        .employee_id).append(
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
                                    updateSelectedRows();
                                }
                            });

                            $('#employee_table_body').append(newRow);
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

            $('#ISModal1 .modal-body').append(container);


            selectedRows = [];
        });
    </script>
@endsection

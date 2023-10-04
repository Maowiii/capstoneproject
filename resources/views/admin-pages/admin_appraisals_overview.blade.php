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
                    <th>Self-Evaluation</th>
                    <th>IS Evaluation</th>
                    <th>Internal Customer 1</th>
                    <th>Internal Customer 2</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <div class="modal fade" id="signatory_modal" data-bs-backdrop="static">
        <div class="modal-dialog modal-xl" style="width:90%">
            <div class="modal-content" id="signatory">
                <div class="modal-header">
                    <h5 class="modal-title fs-5">Signatories</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
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
                console.log('Selected Year: ' + selectedYear);
            });

            $('#search').on('input', function() {
                var query = $(this).val();
                loadAdminAppraisalsTable(globalSelectedYear, query)
            });

            function loadAdminAppraisalsTable(selectedYear = null, search = null) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ route('loadAdminAppraisals') }}",
                    type: 'GET',
                    data: {
                        selectedYear: selectedYear,
                        search: search
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
                                                "{{ route('ad.viewSelfEvaluationForm') }}";
                                            url += "?appraisal_id=" +
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
                                            var url =
                                                "{{ route('ad.viewSelfEvaluationForm') }}";
                                            url += "?appraisal_id=" +
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
                                                    '<i class="bx bx-x-circle"></i>'
                                                )
                                            );
                                        }
                                    }
                                    // IS Evaluation
                                    else if (appraisal.evaluation_type ===
                                        'is evaluation') {
                                        if (appraisal.date_submitted !== null) {
                                            var url =
                                                "{{ route('ad.viewISEvaluationForm') }}";
                                            url += "?appraisal_id=" +
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
                                            var url =
                                                "{{ route('ad.viewISEvaluationForm') }}";
                                            url += "?appraisal_id=" +
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
                                                    '<i class="bx bx-x-circle"></i>'
                                                )
                                            );
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

                            console.log('Error: ' + response.error);
                        }
                    },
                    error: function(xhr, status, error) {
                        var errorMessage = xhr.responseJSON && xhr.responseJSON.error ? xhr
                            .responseJSON.error : 'An error occurred.';
                        console.log(errorMessage);
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
                console.log('Load Signature Overview');
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
                            $('#signtable tbody').empty();

                            const appraisalTypeMap = {
                                'self evaluation': 'Appraisee',
                                'is evaluation': 'Immediate Superior',
                                'internal customer 1': 'Internal Customer 1',
                                'internal customer 2': 'Internal Customer 2'
                            };

                            response.appraisals.forEach(function(appraisal) {
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

                                var kraToggleButton = $('<button>', {
                                    'type': 'button',
                                    'class': 'btn btn-outline-primary kra-toggle-btn',
                                    'appraisal-id': appraisalId,
                                    'employee-id': appraisal.employee_id,
                                });

                                var formLockToggleButton = $('<button>', {
                                    'type': 'button',
                                    'class': 'btn btn-outline-primary form-toggle-btn',
                                    'appraisal-id': appraisalId,
                                    'employee-id': appraisal.employee_id,
                                });

                                var prToggleButton = $('<button>', {
                                    'type': 'button',
                                    'class': 'btn btn-outline-primary pr-toggle-btn',
                                    'appraisal-id': appraisalId,
                                    'employee-id': appraisal.employee_id,
                                });

                                var evalToggleButton = $('<button>', {
                                    'type': 'button',
                                    'class': 'btn btn-outline-primary eval-toggle-btn',
                                    'appraisal-id': appraisalId,
                                    'employee-id': appraisal.employee_id,
                                });

                                kraToggleButton.text(appraisal.kra_locked === 1 ? 'Unlock' :
                                    'Lock');
                                prToggleButton.text(appraisal.pr_locked === 1 ? 'Unlock' :
                                    'Lock');
                                evalToggleButton.text(appraisal.eval_locked === 1 ? 'Unlock' :
                                    'Lock');
                                formLockToggleButton.text(appraisal.locked === 1 ? 'Unlock' :
                                    'Lock');

                                row.append($('<td>').html(kraToggleButton));
                                row.append($('<td>').html(prToggleButton));
                                row.append($('<td>').html(evalToggleButton));
                                row.append($('<td>').html(formLockToggleButton));

                                $('#signtable tbody').append(row);
                            });

                        } else {
                            console.log('Error: ' + response.error);
                        }
                    },
                    error: function(xhr, status, error) {
                        var errorMessage = xhr.responseJSON && xhr.responseJSON.error ? xhr
                            .responseJSON.error : 'An error occurred.';
                        console.log(errorMessage);
                    }
                });
            }

            $(document).on('click', '.view-esig-btn', function() {
                var appraisalID = $(this).attr('appraisal-id');
                console.log('Global Selected Year: ' + globalSelectedYear);
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
                            console.log('Fail');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                });

                $('#signatory_modal').modal('hide');
                $('#imageModal').modal('show');
            }

            $(document).on('click', '.form-toggle-btn', function() {
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
                            console.log('Error: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                });

            });

            $(document).on('click', '.kra-toggle-btn', function() {
                var appraisalID = $(this).attr('appraisal-id');
                var employeeID = $(this).attr('employee-id');
                var $button = $(this); // Store the clicked button element
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
                            if (response.locked == true) {
                                $button.text('Unlock');
                            } else {
                                $button.text('Lock');
                            }
                        } else {
                            console.log('Error: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                });
            });

            $(document).on('click', '.pr-toggle-btn', function() {
                var appraisalID = $(this).attr('appraisal-id');
                var employeeID = $(this).attr('employee-id');
                var $button = $(this); // Store the clicked button element
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
                            if (response.locked == true) {
                                $button.text('Lock');
                            } else {
                                $button.text('Unlock');
                            }
                        } else {
                            console.log('Error: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                });
            });

            $(document).on('click', '.eval-toggle-btn', function() {
                var appraisalID = $(this).attr('appraisal-id');
                var employeeID = $(this).attr('employee-id');
                var $button = $(this); // Store the clicked button element
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
                            if (response.locked == true) {
                                console.log('Unlock Button.');
                                $button.text('Lock');
                            } else {
                                $button.text('Lock');
                                console.log('Unlock Button.');
                            }
                        } else {
                            console.log('Error: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                });
            });

            $(document).on('click', '#esig-close-btn', function() {
                $('#imageModal').modal('hide');
            });
        });
    </script>
@endsection

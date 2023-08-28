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
        <div class="input-group mb-2 search-box">
            <input type="text" class="form-control" placeholder="Search">
            <button class="btn btn-outline-secondary" type="button">
                <i class='bx bx-search'></i>
            </button>
        </div>
        <table class="table table-bordered" id="admin_appraisals_table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Department</th>
                    <th>Self-Evaluation</th>
                    <th>IS Evaluation</th>
                    <th>Internal Customer 1</th>
                    <th>Internal Customer 2</th>
                    <th>Signatures</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
    <script>
        $(document).ready(function() {
            loadAdminAppraisalsTable();
        });

        function loadAdminAppraisalsTable() {
            $.ajax({
                url: "{{ route('loadAdminAppraisals') }}",
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        var groupedAppraisals = response.groupedAppraisals;

                        $.each(groupedAppraisals, function(employeeId, data) {
                            var employee = data.employee;
                            var appraisals = data.appraisals;

                            var row = $("<tr>");
                            row.append($("<td>").text(employee.first_name + ' ' + employee.last_name));

                            if (typeof employee.department.department_name === "undefined") {
                                row.append($("<td>").text("-"));
                            } else {
                                row.append($("<td>").text(employee.department.department_name));
                            }

                            $.each(appraisals, function(index, appraisal) {
                                var cell = $("<td>");

                                // Self Evaluation
                                if (appraisal.evaluation_type === 'self evaluation') {
                                    if (appraisal.date_submitted !== null) {
                                        var url = "{{ route('ad.viewSelfEvaluationForm') }}";
                                        url += "?appraisal_id=" + encodeURIComponent(appraisal
                                            .appraisal_id);
                                        url += "&appraisee_account_id=" + encodeURIComponent(
                                            appraisal.employee.account_id);
                                        url += "&appraisee_name=" + encodeURIComponent(employee
                                            .first_name + ' ' + employee.last_name);
                                        url += "&appraisee_department=" + encodeURIComponent(
                                            appraisal.employee.department.department_name);

                                        cell.append(
                                            $("<a>")
                                            .attr("href", url)
                                            .addClass("appraisal-link")
                                            .html('<i class="bx bx-check-circle"></i>')
                                        );
                                    } else {
                                        var url = "{{ route('ad.viewSelfEvaluationForm') }}";
                                        url += "?appraisal_id=" + encodeURIComponent(appraisal
                                            .appraisal_id);
                                        url += "&appraisee_account_id=" + encodeURIComponent(
                                            appraisal.employee.account_id);
                                        url += "&appraisee_name=" + encodeURIComponent(employee
                                            .first_name + ' ' + employee.last_name);
                                        url += "&appraisee_department=" + encodeURIComponent(
                                            appraisal.employee.department.department_name);

                                        cell.append(
                                            $("<a>")
                                            .attr("href", url)
                                            .addClass("appraisal-link")
                                            .html('<i class="bx bx-x-circle"></i>')
                                        );
                                    }
                                }
                                // IS Evaluation
                                else if (appraisal.evaluation_type === 'is evaluation') {
                                    if (appraisal.date_submitted !== null) {
                                        var url = "{{ route('ad.viewISEvaluationForm') }}";
                                        url += "?appraisal_id=" + encodeURIComponent(appraisal
                                            .appraisal_id);
                                        url += "&appraisee_account_id=" + encodeURIComponent(
                                            appraisal.employee.account_id);
                                        url += "&appraisee_name=" + encodeURIComponent(employee
                                            .first_name + ' ' + employee.last_name);
                                        url += "&appraisee_department=" + encodeURIComponent(
                                            appraisal.employee.department.department_name);

                                        cell.append(
                                            $("<a>")
                                            .attr("href", url)
                                            .addClass("appraisal-link")
                                            .html('<i class="bx bx-check-circle"></i>')
                                        );
                                    } else {
                                        var url = "{{ route('ad.viewISEvaluationForm') }}";
                                        url += "?appraisal_id=" + encodeURIComponent(appraisal
                                            .appraisal_id);
                                        url += "&appraisee_account_id=" + encodeURIComponent(
                                            appraisal.employee.account_id);
                                        url += "&appraisee_name=" + encodeURIComponent(employee
                                            .first_name + ' ' + employee.last_name);
                                        url += "&appraisee_department=" + encodeURIComponent(
                                            appraisal.employee.department.department_name);

                                        cell.append(
                                            $("<a>")
                                            .attr("href", url)
                                            .addClass("appraisal-link")
                                            .html('<i class="bx bx-x-circle"></i>')
                                        );
                                    }
                                }
                                // Two Internal Customers
                                else if (appraisal.evaluation_type.startsWith(
                                        'internal customer')) {
                                    if (appraisal.date_submitted !== null) {
                                        var url = "{{ route('ad.viewICEvaluationForm') }}";
                                        url += "?appraisal_id=" + encodeURIComponent(appraisal
                                            .appraisal_id);
                                        url += "&appraisee_account_id=" + encodeURIComponent(
                                            appraisal.employee.account_id);
                                        url += "&appraisee_name=" + encodeURIComponent(employee
                                            .first_name + ' ' + employee.last_name);
                                        url += "&appraisee_department=" + encodeURIComponent(
                                            appraisal.employee.department.department_name);

                                        cell.append(
                                            $("<a>")
                                            .attr("href", url)
                                            .addClass("appraisal-link")
                                            .html('<i class="bx bx-check-circle"></i>')
                                        );
                                    } else {
                                        cell.append(
                                            $("<a>").html('<i class="bx bx-x-circle"></i>')
                                        );
                                    }
                                }

                                row.append(cell);
                            });

                            $('#admin_appraisals_table tbody').append(row);
                        });

                    } else {
                        console.log(response.error);
                    }
                },
                error: function(xhr, status, error) {
                    var errorMessage = xhr.responseJSON && xhr.responseJSON.error ? xhr
                        .responseJSON.error : 'An error occurred.';
                    console.log(errorMessage);
                }
            });
        }
    </script>
@endsection

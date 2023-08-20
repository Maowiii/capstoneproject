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

                            var row = "<tr>";
                            row += "<td>" + employee.first_name + ' ' + employee.last_name + "</td>";
                            if (typeof employee.department.department_name === "undefined") {
                                row += "<td>" + "-" + "</td>";
                            } else {
                                row += "<td>" + employee.department.department_name + "</td>";
                            }


                            $.each(appraisals, function(index, appraisal) {
                                row += "<td>";

                                if (appraisal.evaluation_type === 'self evaluation') {
                                    if (appraisal.date_submitted !== null) {
                                        var url =
                                            "{{ route('loadAdminSelfEvaluationForm', ['appraisal_id' => ':appraisal_id']) }}";
                                        url = url.replace(':appraisal_id', encodeURIComponent(
                                            appraisal.appraisal_id));
                                        row +=
                                            `<a href="${url}" class="appraisal-link"><i class='bx bx-check-circle'></i></a>`;
                                    } else {
                                        row += `<i class='bx bx-x-circle'></i>`;
                                    }
                                } else if (appraisal.evaluation_type === 'is evaluation') {
                                    if (appraisal.date_submitted !== null) {
                                        var url =
                                            "{{ route('loadAdminISEvaluationForm', ['appraisal_id' => ':appraisal_id']) }}";
                                        url = url.replace(':appraisal_id', encodeURIComponent(
                                            appraisal.appraisal_id));
                                        row +=
                                            `<a href="${url}" class="appraisal-link"><i class='bx bx-check-circle'></i></a>`;
                                    } else {
                                        row += `<i class='bx bx-x-circle'></i>`;
                                    }
                                } else if (appraisal.evaluation_type.startsWith(
                                        'internal customer')) {
                                    if (appraisal.date_submitted !== null) {
                                        var url =
                                            "{{ route('loadAdminICEvaluationForm', ['appraisal_id' => ':appraisal_id']) }}";
                                        url = url.replace(':appraisal_id', encodeURIComponent(
                                            appraisal.appraisal_id));
                                        row +=
                                            `<a href="${url}" class="appraisal-link"><i class='bx bx-check-circle'></i></a>`;
                                    } else {
                                        row += `<i class='bx bx-x-circle'></i>`;
                                    }
                                } else {
                                    row += "-";
                                }

                                // ... (continue adding other data from each appraisal)

                                row += "</td>";
                            });


                            row += "</tr>";

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

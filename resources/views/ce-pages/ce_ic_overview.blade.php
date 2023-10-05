@extends('layout.master')

@section('title')
    <h1>Internal Customers Overview</h1>
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
            <h4>Evaluation Deadline:</h4>
            @if ($activeEvalYear)
                <p>{{ date('F d, Y', strtotime($activeEvalYear->eval_end)) }}</p>
            @else
                <p>-</p>
            @endif
        </div>
    </div>
    <div class="content-container">
        <div class="table-responsive">
            <table class='table table-bordered' id="ic_overview_table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Department</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="ic_overview_body">
                </tbody>
            </table>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $.ajax({
                url: "{{ route('getICAssign') }}",
                type: "GET",
                dataType: "json",
                success: function(data) {
                    if (data.success) {
                        console.log(data);

                        var tableBody = $('#ic_overview_body');
                        tableBody.empty();

                        $.each(data, function(index, assignment) {
                            var row = $('<tr>').addClass('align-middle');

                            row.append(
                                $('<td>').text(
                                    `${assignment.employee.first_name} ${assignment.employee.last_name}`
                                ),
                                $('<td>').text(assignment.employee.department
                                    .department_name),
                                $('<td>').text((typeof assignment.date_submitted ===
                                        'string' &&
                                        assignment.date_submitted.trim() !== '') ?
                                    'Submitted' :
                                    'Pending'),
                                (assignment.date_submitted === null) ?
                                $('<td>').append(
                                    $('<button>')
                                    .text('Appraise')
                                    .addClass('btn btn-outline-primary')
                                    .click(function() {
                                        window.location.href =
                                            "{{ route('ce.viewICAppraisalForm') }}" +
                                            "?appraisal_id=" + encodeURIComponent(
                                                assignment
                                                .appraisal_id) +
                                            "&appraisee_account_id=" +
                                            encodeURIComponent(
                                                assignment.employee.account_id) +
                                            "&appraisee_name=" + encodeURIComponent(
                                                `${assignment.employee.first_name} ${assignment.employee.last_name}`
                                            ) +
                                            "&appraisee_department=" +
                                            encodeURIComponent(
                                                assignment.employee.department
                                                .department_name);
                                    })
                                ) :
                                $('<td>').append(
                                    $('<button>')
                                    .text('View')
                                    .addClass('btn btn-primary')
                                    .click(function() {
                                        window.location.href =
                                            "{{ route('ce.viewICAppraisalForm') }}" +
                                            "?appraisal_id=" + encodeURIComponent(
                                                assignment
                                                .appraisal_id) +
                                            "&appraisee_account_id=" +
                                            encodeURIComponent(
                                                assignment.employee.account_id) +
                                            "&appraisee_name=" + encodeURIComponent(
                                                `${assignment.employee.first_name} ${assignment.employee.last_name}`
                                            ) +
                                            "&appraisee_department=" +
                                            encodeURIComponent(
                                                assignment.employee.department
                                                .department_name);
                                    })
                                )
                            );
                            tableBody.append(row);
                        });
                    } else {
                        var tableBody = $('#ic_overview_body');
                        tableBody.empty();
                        var row = $("<tr class='middle-align'></tr>").append(
                            "<td colspan='7'><p class='text-secondary fst-italic mt-0'>There is no ongoing evaluation.</p></td>"
                        );
                        tableBody.append(row);
                    }

                },
                error: function(xhr, status, error) {
                    console.log(error);
                }
            });
        });
    </script>
@endsection

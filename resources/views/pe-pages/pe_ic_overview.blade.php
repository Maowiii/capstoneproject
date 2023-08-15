@extends('layout.master')

@section('title')
    <h1>Internal Customers Overview</h1>
@endsection

@section('content')
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
            console.log("Account ID:", {{ session('account_id') }});

            $.ajax({
                url: "{{ route('getICAssign') }}",
                type: "GET",
                dataType: "json",
                success: function(data) {
                    console.log(data); // Check the structure of the data object

                    var tableBody = $('#ic_overview_body');
                    tableBody.empty();

                    // Iterate over the retrieved data
                    $.each(data, function(index, assignment) {
                        var row = $('<tr>');
                        var nameColumn = $('<td>').text(assignment.employee.first_name + ' ' +
                            assignment.employee.last_name);
                        var departmentColumn = $('<td>').text(assignment.employee.department
                            .department_name);
                        var statusColumn = $('<td>').text(assignment.status);
                        var actionColumn;
                        if (assignment.status === 'complete') {
                            actionColumn = $('<td>').append($('<button>').text('View').addClass(
                                'btn btn-info'));
                        } else if (assignment.status === 'pending') {
                            actionColumn = $('<td>').append(
                                $('<button>').text('Appraise').addClass('btn btn-warning')
                                .click(function() {
                                    window.location.href =
                                        "{{ route('appraisalForm') }}" +
                                        "?appraiser_name=" + assignment.evaluator
                                        .first_name + "+" + assignment.evaluator
                                        .last_name +
                                        "&appraiser_department=" + assignment.evaluator
                                        .department.department_name +
                                        "&appraisee_name=" + assignment.employee
                                        .first_name + "+" + assignment.employee
                                        .last_name +
                                        "&appraisee_department=" + assignment.employee
                                        .department.department_name;
                                })
                            );
                        } else {
                            actionColumn = $('<td>').text('Unknown');
                        }
                        row.append(nameColumn, departmentColumn, statusColumn, actionColumn);
                        tableBody.append(row);
                    });
                },
                error: function(xhr, status, error) {
                    console.log(error);
                }
            });
        });
    </script>
@endsection

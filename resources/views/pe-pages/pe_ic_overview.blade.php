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
            <nav id="ic_pagination_container">
                <ul class="pagination pagination-sm justify-content-end" id="ic_pagination"></ul>
            </nav>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            var currentPage = 1;

            function loadICAssignData(page = 1) {
                $.ajax({
                    url: "{{ route('getICAssign') }}",
                    type: "GET",
                    dataType: "json",
                    data: {
                        page: page
                    },
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(response) {
                        if (response.success) {
                            console.log(response);

                            var tableBody = $('#ic_overview_body');
                            tableBody.empty();
                            $.each(response.assignments.data, function(index, assignment) {
                                var row = $('<tr>').addClass('align-middle');

                                row.append(
                                    $('<td>').text(
                                        `${assignment.employee.first_name} ${assignment.employee.last_name}`
                                    ),
                                    $('<td>').text(assignment.employee.department
                                        .department_name
                                    ), 
                                    $('<td>').text(
                                        assignment.date_submitted !== null ? 'Submitted' :
                                        'Pending'
                                    ),
                                    typeof assignment.date_submitted === 'string' &&
                                    assignment.date_submitted.trim() !== '' ?
                                    $('<td>').append(
                                        $('<button>')
                                        .text('View')
                                        .addClass('btn btn-primary')
                                        .click(function() {
                                            window.location.href =
                                                "{{ route('showICForm') }}" +
                                                "?appraisal_id=" + encodeURIComponent(
                                                    assignment
                                                    .appraisal_id) +
                                                "&appraisee_account_id=" +
                                                encodeURIComponent(
                                                    assignment.employee.account_id
                                                ) +
                                                "&appraisee_name=" + encodeURIComponent(
                                                    `${assignment.employee.first_name} ${assignment.employee.last_name}`
                                                ) +
                                                "&appraisee_department=" +
                                                encodeURIComponent(
                                                    assignment.employee.department
                                                    .department_name
                                                );
                                        })
                                    ) :
                                    assignment.date_submitted === null ?
                                    $('<td>').append(
                                        $('<button>')
                                        .text('Appraise')
                                        .addClass('btn btn-outline-primary')
                                        .click(function() {
                                            window.location.href =
                                                "/pe-internal-customers/appraisalForm" +
                                                "?appraisal_id=" + encodeURIComponent(
                                                    assignment
                                                    .appraisal_id) +
                                                "&appraisee_account_id=" +
                                                encodeURIComponent(
                                                    assignment.employee.account_id
                                                ) +
                                                "&appraisee_name=" + encodeURIComponent(
                                                    `${assignment.employee.first_name} ${assignment.employee.last_name}`
                                                ) +
                                                "&appraisee_department=" +
                                                encodeURIComponent(
                                                    assignment.employee.department
                                                    .department_name
                                                );
                                        })
                                    ) :
                                    $('<td>').text('Unknown')
                                );

                                tableBody.append(row);
                            });

                            // Handle pagination
                            var totalPage = response.assignments.last_page;
                            var currentPage = response.assignments.current_page;

                            $('#ic_pagination').empty();
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
                                        loadICAssignData(pageCounter);
                                    });
                                    pageItem.append(pageButton);
                                    $('#ic_pagination').append(pageItem);
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

            // Initialize the page with the first page of data
            loadICAssignData(currentPage);
        });
    </script>
@endsection

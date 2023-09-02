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
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>

    <div class="modal fade" id="signatory_modal" data-bs-backdrop="static">
        <div class="modal-dialog modal-xl">
            <div class="modal-content" id="signatory">
                <div class="modal-header">
                    <h5 class="modal-title fs-5">Signatories</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table" id="signtable">
                        <thead>
                            <tr>
                                <th scope="col" style="width:20%" id="partieshead">PARTIES</th>
                                <th scope="col" style="width:20%" id="fullnamehead">FULL NAME</th>
                                <th scope="col" style="width:25%" id="signhead">SIGNATURE</th>
                                <th scope="col" style="width:15%" id="datehead">DATE</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">

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
        });

        function loadAdminAppraisalsTable() {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('loadAdminAppraisals') }}",
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        var groupedAppraisals = response.groupedAppraisals;

                        $.each(groupedAppraisals, function(employeeId, data) {
                            var employee = data.employee;
                            var appraisals = data.appraisals;
                            var employeeID = employee.employee_id;

                            var row = $("<tr>").data("employeeID", employeeID);
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
                                } else if (appraisal.evaluation_type ===
                                    'internal customer 1') {
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
                                } else if (appraisal.evaluation_type ===
                                    'internal customer 2') {
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

                            row.append($(
                                "<button type='button' class='btn btn-outline-primary' id='view-btn'>View</button>"
                            ));

                            $(document).on('click', '#view-btn', function() {
                                var closestTr = $(this).closest('tr');

                                var employeeID = closestTr.data(
                                    'employeeID');

                                $('#signatory_modal').modal('show');
                                loadSignatureOverview(employeeID);
                                console.log(employeeID);
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

        function loadSignatureOverview(employeeID) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('ad.loadSignaturesOverview') }}",
                type: 'GET',
                data: {
                    employeeID: employeeID
                },
                success: function(response) {
                    if (response.success) {
                        $('#signtable tbody').empty();
                        console.log(response.appraisals);

                        response.appraisals.forEach(function(appraisal) {
                            const employee = appraisal.employee;
                            const evaluator = appraisal.evaluator;
                            const evaluatorFullName = evaluator ? evaluator.first_name + ' ' + evaluator
                                .last_name : '-';

                            const appraisalType = appraisal.evaluation_type;

                            var row = $('<tr>');
                            var appraisalId = appraisal.appraisal_id;


                            if (appraisalType === 'self evaluation') {
                                row.append($('<td class="align-middle">').text('Appraisee'));
                            } else if (appraisalType === 'is evaluation') {
                                row.append($('<td class="align-middle">').text('Immediate Superior'));
                            } else if (appraisalType === 'internal customer 1') {
                                row.append($('<td class="align-middle">').text('Internal Customer 1'));
                            } else if (appraisalType === 'internal customer 2') {
                                row.append($('<td class="align-middle">').text('Internal Customer 2'));
                            }

                            if (employee) {
                                row.append($('<td class="align-middle">').text(evaluatorFullName));
                            } else {
                                row.append($('<td class="align-middle">').text('-'));
                            }

                            var viewButton =
                                '<button type="button" class="btn btn-outline-primary view-esig-btn" appraisal-id="' +
                                appraisalId + '">View</button>';

                            if (appraisal.date_submitted != null) {
                                row.append($('<td class="align-middle">').html(viewButton));

                                row.append($('<td class="align-middle">').text(appraisal.date_submitted));
                            } else {
                                row.append($('<td class="align-middle">').text('-'));
                                row.append($('<td class="align-middle">').text('-'));
                            }

                            $('#signtable tbody').append(row);
                        });
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
            console.log('Button clicked with appraisal ID: ' + appraisalID);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('ad.loadSignature') }}',
                type: 'GET',
                data: {
                    appraisalID: appraisalID,
                },
                success: function(response) {
                    if (response.success) {
                        console.log('success');
                        $('#modalImage').attr('src', response.sign_data);
                    } else {
                        console.log('fail');
                    }
                },
                error: function(xhr, status, error) {
                    console.log(error);
                }
            });

            $('#signatory_modal').modal('hide');
            $('#imageModal').modal('show');
        });

        $(document).on('click', '#esig-close-btn', function() {
            $('#imageModal').modal('hide');
        });
    </script>
@endsection

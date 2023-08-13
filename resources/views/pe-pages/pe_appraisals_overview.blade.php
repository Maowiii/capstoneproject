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
        <table class='table'>
            <thead>
                <tr>
                    <th>Self-Evaluation</th>
                    <th>Internal Customer 1</th>
                    <th>Internal Customer 2</th>
                    <th>Immediate Superior</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="PE_appraisals_table_body">

            </tbody>
        </table>
    </div>
    <script>
        $(document).ready(function() {
            loadTableData();
        });

        var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        function loadTableData() {
            $.ajax({
                url: '{{ route('getPEData') }}',
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    if (response.success) {
                        $('#PE_appraisals_table_body').empty();

                        var appraisees = response.appraisee;
                        var appraisals = response.appraisals;
                        console.log(appraisals);
                        console.log(appraisees);

                        appraisees.forEach(function(appraisee) {
                            var newRow = $('<tr>').attr('id', appraisee.employee_id);

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
                                            `{{ route('viewPEAppraisal', ['appraisal_id' => ':appraisal_id']) }}`
                                            .replace(':appraisal_id', appraisal_id))
                                        .text('View');
                                } else if (appraisal.evaluation_type ===
                                    'internal customer 1') {
                                    if (appraisal.evaluator_id === null) {
                                        ic1Link = $('<a>').addClass('btn btn-outline-primary')
                                            .text('N/A');
                                    } else {
                                        ic1Link = $('<a>').addClass('btn btn-outline-primary')
                                            .attr('href',
                                                `{{ route('viewPEAppraisal', ['appraisal_id' => ':appraisal_id']) }}`
                                                .replace(':appraisal_id', appraisal_id))
                                            .text(appraisal.evaluator.first_name + ' ' +
                                                appraisal.evaluator.last_name);
                                    }
                                } else if (appraisal.evaluation_type ===
                                    'internal customer 2') {
                                    if (appraisal.evaluator_id === null) {
                                        ic2Link = $('<a>').addClass('btn btn-outline-primary')
                                            .text('N/A');
                                    } else {
                                        ic2Link = $('<a>').addClass('btn btn-outline-primary')
                                            .attr('href',
                                                `{{ route('viewPEAppraisal', ['appraisal_id' => ':appraisal_id']) }}`
                                                .replace(':appraisal_id', appraisal_id))
                                            .text(appraisal.evaluator.first_name + ' ' +
                                                appraisal.evaluator.last_name);
                                    }
                                } else if (appraisal.evaluation_type === 'is evaluation') {
                                    AppraiseLink = $('<a>').addClass('btn btn-outline-primary')
                                        .attr('href',
                                            `{{ route('viewPEAppraisal', ['appraisal_id' => ':appraisal_id']) }}`
                                            .replace(':appraisal_id', appraisal_id))
                                        .text('Appraise');
                                }
                            });

                            newRow.append(
                                $('<td>').append(viewLink),
                                $('<td>').append($('<div>').append(ic1Link)),
                                $('<td>').append($('<div>').append(ic2Link)),
                                $('<td>').append(
                                    $('<div>').append(AppraiseLink)
                                ),
                                $('<td>').text('Pending'),
                            );

                            $('#PE_appraisals_table_body').append(newRow);
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
    </script>
@endsection

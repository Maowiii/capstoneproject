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
        <table class='table'>
            <thead>
                <tr>
                    <th>Self-Evaluation</th>
                    <th>Immediate Superior</th>
                    <th>Internal Customer 1</th>
                    <th>Internal Customer 2</th>
                    <th>Status</th>
                    <th>Final Score</th>
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

                        appraisees.forEach(function(appraisee) {
                            // Create a new table row for each appraisee
                            var newRow = $('<tr>').attr('id', appraisee.employee_id);

                            // Filter appraisals for the current appraisee
                            var employeeAppraisals = appraisals.filter(function(appraisal) {
                                return appraisal.employee_id === appraisee.employee_id;
                            });

                            var viewLink = null;
                            var icLink = null;
                            var AppraiseLink = null;

                            var hasSelfEvaluation = false; // Flag to track if self-evaluation is found

                            employeeAppraisals.forEach(function(appraisal) {
                                console.log(appraisal);
                                var appraisal_id = encodeURIComponent(appraisal.appraisal_id);

                                console.log(appraisal.date_submitted)
                                if (appraisal.evaluation_type === 'self evaluation') {
                                    hasSelfEvaluation = true; // Set the flag to true   
                                    if (appraisal.date_submitted !== null) {
                                        // Append the Self-Evaluation link to the first <td>
                                        viewLink = $('<a>').addClass('btn btn-outline-primary')
                                            .attr('href',
                                                `{{ route('viewPEGOAppraisal', ['appraisal_id' => ':appraisal_id']) }}`
                                                .replace(':appraisal_id', appraisal_id))
                                            .text('View');
                                        newRow.append($('<td>').append(viewLink));
                                    } else {
                                        viewLink = $('<a>').addClass('btn btn-outline-primary')
                                            .attr('href',
                                                `{{ route('viewPEAppraisal', ['appraisal_id' => ':appraisal_id']) }}`
                                                .replace(':appraisal_id', appraisal_id))
                                            .text('Appraise');
                                        newRow.append($('<td>').append(viewLink));
                                    }
                                } else if (appraisal.evaluation_type === 'is evaluation') {
                                    if (appraisal.date_submitted !== null) {
                                        AppraiseLink = $('<a>').addClass(
                                                'btn btn-outline-primary')
                                            .attr('href',
                                                `{{ route('viewPEAppraisal', ['appraisal_id' => ':appraisal_id']) }}`
                                                .replace(':appraisal_id', appraisal_id))
                                            .text('View');

                                        newRow.append(
                                            $('<td>').append(
                                                $('<div>').append(AppraiseLink)
                                            ),
                                        );
                                    } else {
                                        AppraiseLink = $('<a>').addClass(
                                                'btn btn-outline-secondary disabled')
                                            .attr('href',
                                                `{{ route('viewPEAppraisal', ['appraisal_id' => ':appraisal_id']) }}`
                                                .replace(':appraisal_id', appraisal_id))
                                            .text('View');

                                        AppraiseLink.on('click', function(event) {
                                            event
                                                .preventDefault(); // Prevent the default navigation action
                                            // Optionally, change the visual appearance to indicate it's disabled
                                            $(this).addClass(
                                                'disabled'
                                            ); // Add a CSS class to style it as disabled
                                        });
                                        newRow.append(
                                            $('<td>').append(
                                                $('<div>').append(AppraiseLink)
                                            ),
                                        );
                                    }
                                } else if (appraisal.evaluation_type.startsWith(
                                        'internal customer')) {
                                    if (appraisal.evaluator_id === null) {
                                        // Append "N/A" to the second or third <td>
                                        icLink = $('<a>').addClass(
                                                'btn btn-outline-secondary disabled')
                                            .text('View');
                                    } else {
                                        // Append the Internal Customer link to the second or third <td>
                                        icLink = $('<a>').addClass('btn btn-outline-primary')
                                            .attr('href',
                                                `{{ route('viewPEAppraisal', ['appraisal_id' => ':appraisal_id']) }}`
                                                .replace(':appraisal_id', appraisal_id))
                                            .text('View');
                                    }
                                    newRow.append($('<td>').append($('<div>').append(icLink)));
                                }
                            });

                            console.log(response.status);

                            if (response.status === 'Complete') {
                                newRow.append($('<td>').text(response.status));
                            } else if (response.status === 'Pending') {
                                newRow.append($('<td>').text(response.status));
                            } else {
                                // Handle other status values if needed

                            }

                            // Check if the user has submitted the self-evaluation
                            if (hasSelfEvaluation) {
                                if (response.final_score !== null) {
                                    // If the self-evaluation is submitted and final score is available, append the final score
                                    newRow.append($('<td>').text(response.final_score));
                                } else {
                                    // If the self-evaluation is submitted but final score is not available, display "Pending"
                                    newRow.append($('<td>').text("-"));
                                }
                            } else {
                                // If the user has no self-evaluation, display an empty cell
                                newRow.append($('<td>'));
                            }

                            // Append the row to the table body
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

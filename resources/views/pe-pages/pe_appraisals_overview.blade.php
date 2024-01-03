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
            <option value="{{ $year->sy_start }}_{{ $year->sy_end }}" @if ($activeEvalYear && $year->eval_id ===
                $activeEvalYear->eval_id) selected @endif>
                {{ $year->sy_start }} - {{ $year->sy_end }}
            </option>
            @endforeach
        </select>
    </div>
</div>

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
            {{ date('F d, Y', strtotime($activeEvalYear->kra_end)) }}
        </p>
        @else
        <p>-</p>
        @endif
    </div>
    <div class="content-container text-middle">
        <h4>Performance Review:</h4>
        @if ($activeEvalYear)
        <p>{{ date('F d, Y', strtotime($activeEvalYear->pr_start)) }} -
            {{ date('F d, Y', strtotime($activeEvalYear->pr_end)) }}
        </p>
        @else
        <p>-</p>
        @endif
    </div>
    <div class="content-container text-middle">
        <h4>Evaluation:</h4>
        @if ($activeEvalYear)
        <p>{{ date('F d, Y', strtotime($activeEvalYear->eval_start)) }} -
            {{ date('F d, Y', strtotime($activeEvalYear->eval_end)) }}
        </p>
        @else
        <p>-</p>
        @endif
    </div>
</div>
<div class="content-container">
    <div class="table-responsive">
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
</div>
<script>
    $( document ).ready( function (){
        loadTableData();
    });

    var csrfToken = document.querySelector( 'meta[name="csrf-token"]' ).getAttribute( 'content' );

    var globalSelectedYear = null;
    const activeYear = $('#evaluation-year-select').val();

    $( '#evaluation-year-select' ).change( function ()
    {
        var selectedYear = $( this ).val();
        globalSelectedYear = selectedYear;
        loadTableData( selectedYear );
        // console.log('Selected Year: ' + selectedYear);
        // console.log('Active Year: ' + activeYear);
    } );

    function loadTableData ( selectedYear = null )
    {
        $.ajax( {
            url: '{{ route('getPEData') }}',
            type: 'GET',
            data: {
                selectedYear: selectedYear,
            },
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function ( response )
            {
                if ( response.success )
                {
                    $( '#PE_appraisals_table_body' ).empty();

                    var appraisees = response.appraisee;
                    var appraisals = response.appraisals;

                    appraisees.forEach( function ( appraisee )
                    {
                        // Create a new table row for each appraisee
                        var newRow = $( '<tr>' ).attr( 'id', appraisee.employee_id );

                        // Filter appraisals for the current appraisee
                        var employeeAppraisals = appraisals.filter( function ( appraisal )
                        {
                            return appraisal.employee_id === appraisee.employee_id;
                        } );

                        var viewLink = null;
                        var ic1Link = null;
                        var ic2Link = null;
                        var AppraiseLink = null;

                        var hasSelfEvaluation = false; // Flag to track if self-evaluation is found

                        employeeAppraisals.forEach( function ( appraisal )
                        {
                            // console.log(appraisal);
                            var appraisal_id = encodeURIComponent( appraisal.appraisal_id );
                            console.log( employeeAppraisals );
                            // console.log(appraisal.date_submitted)
                            if ( appraisal.evaluation_type === 'self evaluation' )
                            {
                                hasSelfEvaluation = true;

                                console.log(activeYear === selectedYear || selectedYear == null);
                                if (activeYear === selectedYear || selectedYear == null){
                                    if ( appraisal.date_submitted !== null )
                                    {
                                        // Append the Self-Evaluation link to the first <td>
                                        viewLink = $( '<a>' ).addClass( 'btn btn-outline-primary' )
                                            .attr( 'href',
                                                `{{ route('viewPEAppraisal', ['appraisal_id' => ':appraisal_id']) }}`
                                                    .replace( ':appraisal_id', appraisal_id ) )
                                            .text( 'View' );

                                        newRow.append( $( '<td>' ).append( viewLink ) );
                                    } else
                                    {
                                        viewLink = $( '<a>' ).addClass( 'btn btn-outline-primary' )
                                            .attr( 'href',
                                                `{{ route('viewPEAppraisal', ['appraisal_id' => ':appraisal_id']) }}`
                                                    .replace( ':appraisal_id', appraisal_id ) )
                                            .text( 'Appraise' );

                                        newRow.append( $( '<td>' ).append( viewLink ) );
                                    }
                                } else {
                                    // Append the Self-Evaluation link to the first <td>
                                    var url = "{{ route('viewPEGOAppraisal', ['appraisal_id' => ':appraisal_id']) }}";
                                    url += "?sy=" + encodeURIComponent( selectedYear );
                                    url += "&appraisal_id=" + encodeURIComponent( appraisal.appraisal_id );
                                    url += "&appraisee_account_id=" + encodeURIComponent( appraisal.employee.account_id );
                                    url += "&appraisee_name=" + encodeURIComponent( appraisal.employee.first_name + ' ' + appraisal.employee.last_name );
                                    url += "&appraisee_department=" + encodeURIComponent( appraisal.employee.department.department_name );

                                    AppraiseLink = $( '<a>' )
                                        .addClass( 'btn btn-outline-primary' )
                                        .attr( "href", url.replace( ':appraisal_id', appraisal.appraisal_id ) )
                                        .text( 'View' );

                                    newRow.append( $( '<td>' ).append( $( '<div>' ).append( AppraiseLink ) ) );
                                }
                                    
                            } else if ( appraisal.evaluation_type === 'is evaluation' )
                            {
                                if ( appraisal.date_submitted !== null )
                                {
                                    var url = "{{ route('viewPEGOAppraisal', ['appraisal_id' => ':appraisal_id']) }}";
                                    url += "?sy=" + encodeURIComponent( selectedYear );
                                    url += "&appraisal_id=" + encodeURIComponent( appraisal.appraisal_id );
                                    url += "&appraisee_account_id=" + encodeURIComponent( appraisal.employee.account_id );
                                    url += "&appraisee_name=" + encodeURIComponent( appraisal.employee.first_name + ' ' + appraisal.employee.last_name );
                                    url += "&appraisee_department=" + encodeURIComponent( appraisal.employee.department.department_name );

                                    AppraiseLink = $( '<a>' )
                                        .addClass( 'btn btn-outline-primary' )
                                        .attr( "href", url.replace( ':appraisal_id', appraisal.appraisal_id ) )
                                        .text( 'View' );

                                    newRow.append( $( '<td>' ).append( $( '<div>' ).append( AppraiseLink ) ) );
                                } else
                                {
                                    AppraiseLink = $( '<a>' ).addClass( 'btn btn-outline-secondary disabled' ).text( 'View' );

                                    newRow.append(
                                        $( '<td>' ).append(
                                            $( '<div>' ).append( AppraiseLink )
                                        ),
                                    );
                                }
                            } else if ( appraisal.evaluation_type ===
                                'internal customer 1' )
                            {
                                if ( appraisal.evaluator_id === null )
                                {
                                    ic1Link = $( '<a>' ).addClass( 'btn btn-outline-secondary disabled' ).text( 'View' );

                                    newRow.append( $( '<td>' ).append( $( '<div>' ).append( ic1Link ) ) );
                                } else
                                {
                                    if ( appraisal.date_submitted !== null )
                                    {
                                        var url = "{{ route('viewPEAppraisal', ['appraisal_id' => ':appraisal_id']) }}";

                                        url += "?sy=" + encodeURIComponent( selectedYear );
                                        url += "&appraisal_id=" + encodeURIComponent( appraisal.appraisal_id );
                                        url += "&appraisee_account_id=" + encodeURIComponent( appraisal.employee.account_id );
                                        url += "&appraisee_name=" + encodeURIComponent( appraisal.employee.first_name + ' ' + appraisal.employee.last_name );
                                        url += "&appraisee_department=" + encodeURIComponent( appraisal.employee.department.department_name );

                                        ic1Link = $( '<a>' ).addClass( 'btn btn-outline-primary' )
                                            .attr( 'href', url.replace( ':appraisal_id', appraisal.appraisal_id ) )
                                            .text( 'View' );
                                    } else
                                    {
                                        ic1Link = $( '<a>' ).addClass( 'btn btn-outline-primary disabled' )
                                            .text( 'View' );
                                    }

                                    newRow.append( $( '<td>' ).append( $( '<div>' ).append( ic1Link ) ) );
                                }
                            } else if ( appraisal.evaluation_type === 'internal customer 2' )
                            {
                                if ( appraisal.evaluator_id === null )
                                {
                                    ic2Link = $( '<a>' ).addClass( 'btn btn-outline-secondary disabled' ).text( 'View' );

                                    newRow.append( $( '<td>' ).append( $( '<div>' ).append( ic2Link ) ) );
                                } else
                                {
                                    if ( appraisal.date_submitted !== null )
                                    {
                                        var url = "{{ route('viewPEAppraisal', ['appraisal_id' => ':appraisal_id']) }}";

                                        url += "?sy=" + encodeURIComponent( selectedYear );
                                        url += "&appraisal_id=" + encodeURIComponent( appraisal.appraisal_id );
                                        url += "&appraisee_account_id=" + encodeURIComponent( appraisal.employee.account_id );
                                        url += "&appraisee_name=" + encodeURIComponent( appraisal.employee.first_name + ' ' + appraisal.employee.last_name );
                                        url += "&appraisee_department=" + encodeURIComponent( appraisal.employee.department.department_name );

                                        ic2Link = $( '<a>' ).addClass( 'btn btn-outline-primary' )
                                            .attr( 'href', url.replace( ':appraisal_id', appraisal.appraisal_id ) )
                                            .text( 'View' );
                                    } else
                                    {
                                        ic2Link = $( '<a>' ).addClass( 'btn btn-outline-primary disabled' )
                                            .text( 'View' );
                                    }

                                    newRow.append( $( '<td>' ).append( $( '<div>' ).append( ic2Link ) ) );
                                }
                            }
                        } );

                        // console.log(response.status);

                        if ( response.status === 'Complete' )
                        {
                            newRow.append( $( '<td>' ).text( response.status ) );
                        } else if ( response.status === 'Pending' )
                        {
                            newRow.append( $( '<td>' ).text( response.status ) );
                        }

                        // Check if the user has submitted the self-evaluation
                        // console.log(hasSelfEvaluation);
                        // console.log(response.final_score);

                        if ( hasSelfEvaluation )
                        {
                            if ( response.final_score.length !== 0 && response.status === 'Complete' )
                            {
                                // If the self-evaluation is submitted and final score is available, append the final score
                                // var finalScore = parseFloat(response.final_score).toFixed(2);
                                var finalScore = ( response.final_score * 100 ) / 100;
                                // console.log(finalScore);
                                // console.log(finalScore.toFixed(2));

                                newRow.append( $( '<td>' ).text( finalScore.toFixed( 2 ) ) );
                            } else
                            {
                                newRow.append( $( '<td>' ).text( '-' ) );
                            }
                        } else if ( !hasSelfEvaluation )
                        {
                            // If the user has no self-evaluation, display an empty cell
                            newRow.append( $( '<td>' ).text( '-' ) );
                        }

                        // Append the row to the table body
                        $( '#PE_appraisals_table_body' ).append( newRow );
                    } );
                } else
                {
                    // console.log(response.error);
                }
            },
            error: function ( xhr, status, error )
            {
                // console.log(error);
            }
        } );
    }
</script>
@endsection
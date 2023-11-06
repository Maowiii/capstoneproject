@extends('layout.master')

@section('title')
    <h1 id="employee_analytics-heading">Employee Analytics</h1>
@endsection

@section('content')
    <div class='d-flex gap-3'>
        <div class="content-container text-middle" id="avg-score-container">
            <h4>Average Total Score:</h4>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            const employeeID = new URLSearchParams(window.location.search).get('employee_id');
            const employeeName = new URLSearchParams(window.location.search).get('full_name');

            if (employeeName) {
                $('#employee_analytics-heading').text(employeeName);
            }

        });

       
    </script>
@endsection

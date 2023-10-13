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
            const selectedYear = new URLSearchParams(window.location.search).get('sy');

            console.log('Selected Year: ' + selectedYear);
            console.log('Employee ID: ' + employeeID);

            if (departmentName) {
                $('#department-heading').text(departmentName);
            }

            loadCards(selectedYear, employeeID);
        });

        function loadCards(selectedYear = null, employeeID = null) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('ad.loadAvgScore') }}',
                type: 'GET',
                data: {
                    selectedYear: selectedYear,
                    employeeID: employeeID
                },
                success: function(response) {
                    if (response.success) {
                        $('#avg-score-container').html('<h4>Average Score:</h4><p>' + response.avgTotalScore +
                            '</p>');
                    } else {
                        console.log('Load Cards failed.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", status, error);
                }
            });
        }
    </script>
@endsection

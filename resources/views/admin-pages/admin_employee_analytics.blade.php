@extends('layout.master')

@section('title')
    <h1 id="employee_analytics-heading">Employee Analytics</h1>
@endsection

@section('content')
    <div class="container-fluid content-container d-flex flex-column">
        <h2 class="text-center">Employee Information:</h2>
        <div class="mb-3">
            <label class="form-label">Email address:</label>
            <input type="email" class="form-control" id="email" placeholder="name@example.com" readonly>
        </div>
        <div class="mb-3">
            <label class="form-label">First Name:</label>
            <input type="text" class="form-control" id="first_name" readonly>
        </div>
        <div class="mb-3">
            <label class="form-label">Last Name:</label>
            <input type="text" class="form-control" id="last_name" readonly>
        </div>
        <div class="mb-3">
            <label class="form-label">Department:</label>
            <input type="text" class="form-control" id="department" readonly>
        </div>
        <div class="mb-3">
            <label class="form-label">Job Title:</label>
            <input type="text" class="form-control" id="job_title" readonly>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            const employeeID = new URLSearchParams(window.location.search).get('employee_id');
            const employeeName = new URLSearchParams(window.location.search).get('full_name');

            if (employeeName) {
                $('#employee_analytics-heading').text(employeeName);
            }

            getEmployeeInformation(employeeID);

        });

        function getEmployeeInformation(employeeID) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('ad.getEmployeeInformation') }}',
                type: 'GET',
                data: {
                    employeeID: employeeID,
                },
                success: function(response) {
                    console.log(response);
                    if (response.success) {
                        var employee = response.employee;
                        var account = response.account;
                        $('#email').val(account.email);
                        $('#first_name').val(employee.first_name);
                        $('#last_name').val(employee.last_name);
                        $('#department').val(employee.department.department_name);
                        $('#job_title').val(employee.job_title);
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

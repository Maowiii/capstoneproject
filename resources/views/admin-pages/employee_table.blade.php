@extends('layout.master')

@section('title')
    <h1>Employees Accounts</h1>
@endsection

@section('content')
    <div class="content-container">
        <div class="input-group mb-2 search-box">
            <input type="text" class="form-control" placeholder="Search">
            <button class="btn btn-outline-secondary" type="button">
                <i class='bx bx-search'></i>
            </button>
        </div>
        <table class="table" id="employee_table">
            <thead>
                <tr>
                    <th scope="col" class="large-column">Employee # | Email</th>
                    <th scope="col" class="medium-column">First Name</th>
                    <th scope="col" class="medium-column">Last Name</th>
                    <th scope="col" class="medium-column">Default Password</th>
                    <th scope="col" class="small-column">Type</th>
                    <th scope="col" class="large-column">Department</th>
                    <th scope="col" class="small-column">Status</th>
                    <th scope="col" class="medium-column">Action</th>
                </tr>
            </thead>
            <tbody id="employee_table_body">
            </tbody>
        </table>
        <div class='d-flex justify-content-end gap-3 mt-2'>
            <input class="form-control large-column" type="file">
            <button class="btn btn-primary large-column">Upload Excel</button>
        </div>
        <div class='d-flex justify-content-end'>
            <button class="btn btn-primary large-column mt-2" type="button" data-bs-toggle="modal"
                data-bs-target="#addUserModal">Add User</button>
        </div>
    </div>

    <div class="toast-container position-fixed bottom-0 end-0 p-3"></div>

    <!-- Modals -->
    <div class="modal fade" id="resetPasswordModal" tabindex="-1" role="dialog" aria-labelledby="resetPasswordModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="resetPasswordModalLabel">Confirm Password Reset</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to reset the password for <span id="resetPasswordName"></span>?
                    <p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmResetPassword">Reset Password</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Add New User</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" action="{{ route('add-new-employee') }}">
                    @csrf
                    <div class="modal-body">
                        <div>
                            <label>Adamson Email:</label>
                            <input type="email" class="form-control" value="{{ old('email') }}" name="email">
                        </div>
                        <span class="text-danger">
                            @error('email')
                                {{ $message }}
                            @enderror
                        </span>

                        <div class="mt-2">
                            <label>Employee Number:</label>
                            <input type="number" class="form-control" value="{{ old('employee_number') }}"
                                name="employee_number">
                        </div>
                        <span class="text-danger">
                            @error('employee_number')
                                {{ $message }}
                            @enderror
                        </span>

                        <div class="mt-2">
                            <label>First Name:</label>
                            <input type="text" class="form-control" value="{{ old('first_name') }}" name="first_name">
                        </div>
                        <span class="text-danger">
                            @error('first_name')
                                {{ $message }}
                            @enderror
                        </span>

                        <div class="mt-2">
                            <label>Last Name:</label>
                            <input type="text" class="form-control" value="{{ old('last_name') }}" name="last_name">
                        </div>
                        <span class="text-danger">
                            @error('last_name')
                                {{ $message }}
                            @enderror
                        </span>

                        <div class="mt-2">
                            <label>Type (User Level):</label>
                            <select class="form-control" name="type">
                                <option value="" selected>
                                    Select Type</option>
                                <option value="AD">Adminstrator</option>
                                <option value="IS">Immediate Superior</option>
                                <option value="PE">Permanent Employee</option>
                                <option value="CE">Contractual Employee</option>
                            </select>
                        </div>
                        <span class="text-danger">
                            @error('type')
                                {{ $message }}
                            @enderror
                        </span>

                        <div class="mt-2">
                            <label>Department:</label>

                            <select class="form-control" name="department">
                                <option value="" selected>Select Department</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->department_id }}">
                                        {{ $department->department_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <span class="text-danger">
                            @error('department')
                                {{ $message }}
                            @enderror
                        </span>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Add User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if (session('showAddUserModal'))
        <script>
            $(document).ready(function() {
                $('#addUserModal').modal('show');
            });
        </script>
    @endif

    <script>
        $(document).ready(function() {
            loadTableData();
        });

        var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        function createResetButton(account) {
            return $('<div>').addClass('input-group').attr('id', 'passwordContainer-' +
                account.account_id).append(
                $('<button>').addClass('btn btn-outline-primary').html(
                    '<i class="bx bx-reset"></i> | Reset Password').click(function() {
                    $('#resetPasswordName').text(account.employee.first_name +
                        ' ' + account.employee.last_name);
                    $('#resetPasswordModal').modal('show');
                    $('#confirmResetPassword').off('click').click(function() {
                        resetPassword(account.account_id);
                        $('#resetPasswordModal').modal('hide');
                    });
                })
            );
        }

        function loadTableData() {
            $.ajax({
                url: '{{ route('employees.getData') }}',
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    if (response.success) {
                        $('#employee_table_body').empty();

                        var accounts = response.accounts;
                        for (var i = 0; i < accounts.length; i++) {
                            var account = accounts[i];
                            var statusButton = account.status === 'active' ? 'Deactivate' : 'Activate';
                            var statusAction = account.status === 'active' ? 'deactivate' : 'activate';

                            var newRow = $('<tr>').attr('id', account.account_id).append(
                                $('<td>').text(account.email),
                                $('<td>').text(account.employee.first_name),
                                $('<td>').text(account.employee.last_name),
                                $('<td>').append(createResetButton(account)),
                                $('<td>').text(account.type),
                                $('<td>').text(account.employee.department ? account.employee.department
                                    .department_name : ''),

                                $('<td>').text(account.status),
                                $('<td>').append(
                                    $('<button>').addClass('btn btn-outline-danger').text(statusButton)
                                    .attr('onclick', 'changeStatus(' + account.account_id + ', "' +
                                        statusAction + '")')
                                )
                            );
                            $('#employee_table_body').append(newRow);
                            $('#confirmResetPassword').off('click').click(function() {
                                // Call the resetPassword function with the account_id
                                resetPassword(account.account_id);
                                // Close the modal
                                $('#resetPasswordModal').modal('hide');
                            });
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

        function resetPassword(accountId) {
            $.ajax({
                url: '{{ route('employeeResetPassword') }}',
                type: 'POST',
                data: {
                    account_id: accountId
                },
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    if (response.success) {
                        console.log('Password reset successfully.');
                        // Create and show a toast message
                        var toast = $('<div>').addClass('toast align-items-center text-bg-primary border-0')
                            .attr('role', 'alert').attr('aria-live', 'assertive').attr('aria-atomic', 'true')
                            .append(
                                $('<div>').addClass('d-flex').append(
                                    $('<div>').addClass('toast-body').text('Password reset successful.'),
                                    $('<button>').addClass('btn-close btn-close-white me-2 m-auto')
                                    .attr('data-bs-dismiss', 'toast').attr('aria-label', 'Close')
                                )
                            );

                        // Append the toast to the container and show it
                        $('.toast-container').append(toast);
                        var toastInstance = new bootstrap.Toast(toast[0]);
                        toastInstance.show();
                    } else {
                        console.log('Password reset failed.');
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Error:', error);
                }
            });
        }

        function changeStatus(accountId, action) {
            $.ajax({
                url: '/employees/update-status',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                data: {
                    account_id: accountId,
                    action: action
                },
                success: function(response) {
                    if (response.success) {
                        if (action === 'activate') {
                            $('#employee_table tr#' + accountId + ' button.btn-outline-danger').text(
                                'Deactivate');
                        } else if (action === 'deactivate') {
                            $('#employee_table tr#' + accountId + ' button.btn-outline-danger').text(
                                'Activate');
                        }
                        loadTableData();
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

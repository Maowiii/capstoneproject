@extends('layout.master')

@section('title')
    <h1>Employees Accounts</h1>
@endsection

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            </button>
            <ul>
                <!-- Display each error message -->
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        
        <script>
            // Automatically close the alert after a specified duration (e.g., 5 seconds)
            $(document).ready(function() {
                $(".alert").delay(5000).slideUp(200, function() {
                    $(this).alert('close');
                });
            });
        </script>
    @endif

    <div class="content-container">
        <div class="table-responsive">
        <div class="input-group mb-2 search-box">
            <div id="import-status" class="mt-2"></div>
            <input type="text" class="form-control" placeholder="Name" id="search">
            <button class="btn btn-outline-secondary" type="button">
                <i class='bx bx-search'></i>
            </button>
        </div>

        <table class="table" id="employee_table">
            <thead>
                <tr>
                    <th scope="col" class="large-column">Email | Employee #</th>
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
        </div>
        <nav id="accounts_pagination_container">
            <ul class="pagination pagination-sm justify-content-end" id="accounts_pagination"></ul>
        </nav>
        <div class='d-flex justify-content-end gap-3 mt-2'>
            <form method="post" action="{{ route('import-new-employee') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-2">
                    <input class="form-control large-column" type="file" name="file">
                </div>
                <button class="btn btn-primary large-column" type="submit">Upload Excel</button>
            </form>
        </div>
        <div class='d-flex justify-content-end'>
            <button class="btn btn-primary large-column mt-2" type="button" data-bs-toggle="modal"
                data-bs-target="#addUserModal" id="add-user-modal-btn">Add User</button>
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
                    <h1 class="modal-title fs-5" id="addUserModalTitle">Add New User</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" action="{{ route('add-new-employee') }}">
                    @csrf
                    <div class="modal-body">
                        <div>
                            <label>Adamson Email:</label>
                            <input type="email" class="form-control" value="{{ old('email') }}" name="email"
                                id="email">
                        </div>
                        <span class="text-danger">
                            @error('email')
                                {{ $message }}
                            @enderror
                        </span>

                        <div class="mt-2">
                            <label>Employee Number:</label>
                            <input type="number" class="form-control" value="{{ old('employee_number') }}"
                                name="employee_number" id="employee_number">
                        </div>
                        <span class="text-danger">
                            @error('employee_number')
                                {{ $message }}
                            @enderror
                        </span>

                        <div class="mt-2">
                            <label>First Name:</label>
                            <input type="text" class="form-control" value="{{ old('first_name') }}" name="first_name"
                                id="first_name">
                        </div>
                        <span class="text-danger">
                            @error('first_name')
                                {{ $message }}
                            @enderror
                        </span>

                        <div class="mt-2">
                            <label>Last Name:</label>
                            <input type="text" class="form-control" value="{{ old('last_name') }}" name="last_name"
                                id="last_name">
                        </div>
                        <span class="text-danger">
                            @error('last_name')
                                {{ $message }}
                            @enderror
                        </span>

                        <div class="mt-2">
                            <label>Type (User Level):</label>
                            <select class="form-control" name="type" id="type">
                                <option value="" selected>
                                    Select Type</option>
                                <option value="AD">Admin</option>
                                <option value="IS">Immediate Superior</option>
                                <option value="PE">Self-Evaluation</option>
                                <option value="CE">Internal Customer</option>
                            </select>
                        </div>
                        <span class="text-danger">
                            @error('type')
                                {{ $message }}
                            @enderror
                        </span>

                        <div class="mt-2">
                            <label>Department:</label>
                            <select class="form-control" name="department" id="department">
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
                        <button type="submit" class="btn btn-primary d-none" id="add-user-btn">Add User</button>
                        <button type="button" class="btn btn-primary d-none" id="save-user-btn">Save User</button>
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
        var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        function createResetButton(account) {
            return $('<div>').addClass('input-group justify-content-center').attr('id', 'passwordContainer-' +
                account.account_id).append(
                $('<button>').addClass('btn btn-outline-primary').html(
                    'Reset').click(function() {
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

        $('#search').on('input', function() {
            var search = $(this).val();
            loadTableData(search);
        });

        function loadTableData(search = null, page = 1) {
            $.ajax({
                url: '{{ route('ad.getEmployeesData') }}',
                type: 'GET',
                data: {
                    search: search,
                    page: page
                },
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    if (response.success) {
                        var accounts = response.accounts;
                        var currentPage = response.current_page;
                        var data = response.accounts.data;

                        $('#employee_table_body').empty();

                        for (var i = 0; i < data.length; i++) {
                            var account = data[i];
                            var statusButton = account.status === 'active' ? 'Deactivate' : 'Activate';
                            var statusAction = account.status === 'active' ? 'deactivate' : 'activate';

                            var newRow = $('<tr>').attr('id', account.account_id).addClass('text-center');
                            newRow.append($('<td>').html(account.email +
                                '<br /><p class="fst-italic text-secondary">' +
                                account.employee.employee_number + '</p>'));
                            newRow.append($('<td>').text(account.employee.first_name));
                            newRow.append($('<td>').text(account.employee.last_name));
                            newRow.append($('<td>').append(createResetButton(account)));

                            if (account.type == 'AD') {
                                newRow.append($('<td>').text('Admin'));
                            } else if (account.type == 'IS') {
                                newRow.append($('<td>').text('Immediate Superior'));
                            } else if (account.type == 'PE') {
                                newRow.append($('<td>').text('Employee'));
                            } else {
                                newRow.append($('<td>').text('Internal Customer'));
                            }
                            newRow.append($('<td>').text(account.employee.department ? account.employee
                                .department
                                .department_name : ''));
                            newRow.append($('<td>').text(account.status));
                            newRow.append($('<td>').append(
                                $('<div>').addClass('btn-group').attr('role', 'group')
                                .append(
                                    $('<button>').addClass('btn btn-outline-danger').text(statusButton)
                                    .attr('onclick', 'changeStatus(' + account.account_id + ', "' +
                                        statusAction + '")'),
                                    $('<button>').addClass('btn btn-outline-primary edit-btn')
                                    .html('<i class="bx bx-edit"></i>')
                                    .attr('employee-id', account.employee.employee_id)
                                )
                            ));
                            $('#employee_table_body').append(newRow);
                        }

                        $('#accounts_pagination').empty();

                        var paginationLinks = response.accounts.links;
                        totalPage = response.accounts.last_page;
                        currentPage = response.accounts.current_page;
                        $('#accounts_pagination').empty();
                        for (totalPageCounter = 1; totalPageCounter <= totalPage; totalPageCounter++) {
                            (function(pageCounter) {
                                var pageItem = $('<li>').addClass('page-item');
                                if (pageCounter === currentPage) {
                                    pageItem.addClass('active');
                                }
                                var pageButton = $('<button>').addClass('page-link').text(pageCounter);
                                pageButton.click(function() {
                                    loadTableData(search, pageCounter);
                                });
                                pageItem.append(pageButton);
                                $('#accounts_pagination').append(pageItem);
                            })(totalPageCounter);
                        }

                        $('#confirmResetPassword').off('click').click(function() {
                            resetPassword(account.account_id);
                            $('#resetPasswordModal').modal('hide');
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
                        var toast = $('<div>').addClass(
                                'toast align-items-center text-bg-primary border-0')
                            .attr('role', 'alert').attr('aria-live', 'assertive').attr(
                                'aria-atomic', 'true')
                            .append(
                                $('<div>').addClass('d-flex').append(
                                    $('<div>').addClass('toast-body').text(
                                        'Password reset successful.'),
                                    $('<button>').addClass('btn-close btn-close-white me-2 m-auto')
                                    .attr('data-bs-dismiss', 'toast').attr('aria-label', 'Close')
                                )
                            );
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
                            $('#employee_table tr#' + accountId + ' button.btn-outline-danger')
                                .text(
                                    'Deactivate');
                        } else if (action === 'deactivate') {
                            $('#employee_table tr#' + accountId + ' button.btn-outline-danger')
                                .text(
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
        $(document).ready(function() {
            loadTableData();
            var selectedEmployeeId = null;

            $(document).on('click', '.edit-btn', function() {
                var employeeId = $(this).attr('employee-id');
                selectedEmployeeId = employeeId;
                console.log('Employee ID: ' + employeeId);
                $('#addUserModal').modal('show');
                $('#save-user-btn').removeClass('d-none');
                $('#add-user-btn').addClass('d-none');
                $('#addUserModalTitle').text('Edit User');

                $.ajax({
                    url: "{{ route('ad.editEmployee') }}",
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    data: {
                        employeeId: employeeId
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#email').val(response.employee.account.email);
                            $('#employee_number').val(response.employee.employee_number);
                            $('#first_name').val(response.employee.first_name);
                            $('#last_name').val(response.employee.last_name);
                            $('#type').val(response.employee.account.type);
                            var accountType = response.employee.account.type;
                            if (accountType == 'AD') {
                                $('#department').prop('disabled', true);
                            }
                            $('#department').val(response.employee.department_id);
                        } else {
                            console.log('Error: ' + response.error);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log('Error: ' + error);
                    }
                });
            });

            $(document).on('click', '#add-user-modal-btn', function() {
                $('#addUserModalTitle').text('Add New User');
                $('#add-user-btn').removeClass('d-none');

                $('#addUserModal').find('input[type="text"]').val('');
                $('#addUserModal').find('input[type="email"]').val('');
                $('#addUserModal').find('input[type="number"]').val('');
                $('#addUserModal').find('select').val('');
                $('#addUserModal').find('.text-danger').hide();
            });

            $(document).on('click', '#save-user-btn', function() {
                var employeeId = selectedEmployeeId;
                var email = $('#email').val();
                var employeeNumber = $('#employee_number').val();
                var firstName = $('#first_name').val();
                var lastName = $('#last_name').val();
                var type = $('#type').val();
                var department = $('#department').val();

                $.ajax({
                    url: "{{ route('ad.saveEmployee') }}",
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    data: {
                        employeeId: employeeId,
                        email: email,
                        employeeNumber: employeeNumber,
                        firstName: firstName,
                        lastName: lastName,
                        type: type,
                        department: department
                    },
                    success: function(response) {
                        console.log(response);
                        if (response.success) {
                            $('#addUserModalTitle').text('Add New User')
                            $('#addUserModal').modal('hide');
                            $('#addUserModal').hide();
                            loadTableData();
                        } else {
                            console.log('Error: ' + response.error);

                            $('#add-user-btn').removeClass('d-none');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log('Error: ' + error);
                    }
                });
            });

            $(document).on('change', '#type', function() {
                var selectedType = $(this).val();
                if (selectedType != 'AD') {
                    $('#department').prop('disabled', false);
                } else {
                    $('#department').prop('disabled', true);
                }
            });
        });
    </script>
@endsection

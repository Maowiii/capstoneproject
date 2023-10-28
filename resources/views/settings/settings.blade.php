@extends('layout.master')

@section('title')
    <h1>Account Settings</h1>
@endsection

@section('content')
    <div class="content-container">
        <h3>Basic Information</h3>
        <div class="row g-3 align-items-center mb-3">
            <div class="col-1">
                <label for="fullName"> Full Name</label>
            </div>
            <div class="col-4">
                <input type="text" class="form-control" id="fullName" readonly>
            </div>
        </div>
        <div class="row g-3 align-items-center mb-3">
            <div class="col-1">
                <label for="emailAddress">Adamson Mail</label>
            </div>
            <div class="col-4">
                <input type="text" class="form-control" id="adamsonMail" readonly>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <h3>Change Password:</h3>
            </div>
            <div class='mt-3'>
                <button type="button" class="btn btn-primary" id="open-password-modal-btn">Change Password</button>
            </div>
            <div class="modal fade" id="passchange" data-bs-backdrop="static">
                <div class="modal-dialog" style="width:90%">
                    <div class="modal-content" id="passwordmodal">
                        <div class="modal-header">
                            <h5 class="modal-title fs-5">Password Change:</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action=" {{ route('settings.changePassword') }} ">
                                @csrf
                                <div>
                                    <label>Current Password:</label>
                                    <div class="input-group w-90">
                                        <input type="password"
                                            class="form-control w-90 @error('current_password') is-invalid @enderror"
                                            value="{{ old('current_password') }}" name="current_password"
                                            id="current_password">
                                        <button class="btn btn-outline-secondary" type="button"
                                            onclick="togglePasswordVisibility('current_password')">
                                            <i class='bx bx-show'></i>
                                        </button>
                                    </div>
                                    <span class="text-danger">
                                        @error('current_password')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>

                                <div class='mt-3'>
                                    <label>New Password:</label>
                                    <div class='input-group w-90'>
                                        <input type="password"
                                            class="form-control w-90 @error('new_password') is-invalid @enderror"
                                            value="{{ old('new_password') }}" name="new_password" id="new_password">
                                        <button class="btn btn-outline-secondary" type="button"
                                            onclick="togglePasswordVisibility('new_password')">
                                            <i class='bx bx-show'></i>
                                        </button>
                                    </div>
                                    <span class="text-danger">
                                        @error('new_password')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>
                                <div class='mt-3'>
                                    <label>Confirm Password:</label>
                                    <div class='input-group w-90'>
                                        <input type="password"
                                            class="form-control w-90 @error('confirm_password') is-invalid @enderror"
                                            value="{{ old('confirm_password') }}" name="confirm_password"
                                            id="confirm_password">
                                        <button class="btn btn-outline-secondary" type="button"
                                            onclick="togglePasswordVisibility('confirm_password')">
                                            <i class='bx bx-show'></i>
                                        </button>
                                    </div>
                                    <span class="text-danger">
                                        @error('confirm_password')
                                            {{ $message }}
                                        @enderror
                                    </span>
                                </div>

                                @if (session('success'))
                                    <div class="alert alert-success w-90 mt-3">
                                        {{ session('success') }}
                                    </div>
                                @endif

                                @if (session('error'))
                                    <div class="alert alert-danger w-90 mt-3">
                                        {{ session('error') }}
                                    </div>
                                @endif
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary" id="change-password-btn">Change
                                        Password</button>
                                </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function fetchEmployeeInfo() {
                // console.log('Fetching employee information...');
                $.ajax({
                    url: '{{ route('employeeInfo') }}',
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        if (data.success) {
                            $('#fullName').val(data.first_name + ' ' + data.last_name);
                            $('#adamsonMail').val(data.email);
                        } else {
                            console.error('Failed to retrieve employee information.');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        console.error('An error occurred while processing your request.');
                    }
                });
            }

            $(document).ready(function() {
                fetchEmployeeInfo();
            });

            function togglePasswordVisibility(inputId) {
                var passwordInput = document.getElementById(inputId);
                var passwordToggleIcon = document.getElementById(inputId + "-toggle-icon");

                if (passwordInput.type === "password") {
                    passwordInput.type = "text";
                    passwordToggleIcon.classList.remove("bx-show");
                    passwordToggleIcon.classList.add("bx-hide");
                } else {
                    passwordInput.type = "password";
                    passwordToggleIcon.classList.remove("bx-hide");
                    passwordToggleIcon.classList.add("bx-show");
                }
            }
            var modal = document.getElementById("passchange");
            var openBtn = document.getElementById("open-password-modal-btn");

            openBtn.onclick = function() {
                var modalInstance = new bootstrap.Modal(modal);
                modalInstance.show();
            };
        </script>
    @endsection

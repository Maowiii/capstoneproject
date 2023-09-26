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
                <input type="text" class="form-control" value="" readonly>
            </div>
        </div>
        <div class="row g-3 align-items-center mb-3">
            <div class="col-1">
                    <label for="emailAddress">Adamson Mail</label>
                </div>
                <div class="col-4">
                <input type="text" class="form-control" value="" readonly>
            </div>
        </div>
        <div class="row g-3 align-items-center mb-3">
            <div class="col-1">
                    <label for="password">Password  </label>
                </div>
                <div class="col-2">
                    <input type="text" class="form-control" value="" readonly>
                </div>
                <div class="col">
                    <button type="button" class="btn btn-primary">Change Password</button>
                </div>
        </div>
    </div>

    <script>
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
    </script>
@endsection

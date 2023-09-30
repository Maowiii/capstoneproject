<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AdU - Appraisal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('style.css') }}">
</head>

<body background="white" id="loginPage">
    <div class="container" id="formcard">
        <img src="{{ asset('assets/adulogo.png') }}" id="logo">
        @if (session('message'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <form method="post" action="{{ route('login-user') }}">
            @csrf
            <div>
                <input type="text" class="form-control" id="email" placeholder="Adamson Email" name="email"
                    value="{{ old('email') }}">
            </div>

            <span class="text-danger">
                @error('email')
                    {{ $message }}
                @enderror
            </span>

            <div class="input-group" id="passwordContainer">
                <input type="password" class="form-control" id="password" placeholder="Password" name="password"
                    value="{{ old('password') }}">
                <button class="btn btn-outline-secondary" type="button" id="togglePasswordButton"
                    onclick="togglePasswordVisibility()">
                    <i class='bx bx-show' id="password-toggle-icon"></i>
                </button>
            </div>
            <span class="text-danger">
                @error('password')
                    {{ $message }}
                @enderror
            </span>


            @if (Session::has('password-reset-success'))
                <div class="alert alert-success" role="alert" id="alert-success">
                    {{ Session::get('password-reset-success') }}
                </div>
            @endif
            @if (Session::has('success'))
                <div class="alert alert-success" role="alert">
                    {{ Session::get('success') }}
                </div>
            @endif
            @if (Session::has('fail'))
                <div class="alert alert-danger" role="alert">
                    {{ Session::get('fail') }}
                </div>
            @endif

            <div id="resetPassword">
                <a href="{{ route('viewResetPassword') }}" id="forgotPassword">Forgot Password?</a>
            </div>
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary" id="signinButton" value="Sign in">Sign in</button>
            </div>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous">
    </script>
    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById("password");
            const passwordToggleIcon = document.getElementById("password-toggle-icon");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                passwordToggleIcon.classList.remove("bi-eye-fill");
                passwordToggleIcon.classList.add("bi-eye-slash-fill");
            } else {
                passwordInput.type = "password";
                passwordToggleIcon.classList.remove("bi-eye-slash-fill");
                passwordToggleIcon.classList.add("bi-eye-fill");
            }
        }

        $(document).ready(function() {
            $('#alert-success').alert();
            setTimeout(() => {
                $('#alert-success').alert('close');
            }, 3000);
        });
    </script>
</body>

</html>

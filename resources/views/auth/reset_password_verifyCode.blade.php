<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../style.css">
</head>

<body background="white" id="loginPage">
    <div class="container" id="formcard">
        <center>
            <h1>RESET PASSWORD</h1>
        </center>
        <div class="container" id="codeContainer">
            <center>
                <p>A four-digit code has been sent to your email. Enter the code below:</p>
            </center>
            <form method="POST" id="codeForm" action="{{ route('reset-password-verify-code') }}">
                @csrf
                <div class="d-flex justify-content-center">
                    <input type="text" class="form-control mx-1" id="code1" maxlength="1"
                        oninput="moveToNext(this, 'code2')" onkeypress="return isDigitKey(event)" required
                        name='code1'>
                    <input type="text" class="form-control mx-1" id="code2" maxlength="1"
                        oninput="moveToNext(this, 'code3')" onkeypress="return isDigitKey(event)" required
                        name='code2'>
                    <input type="text" class="form-control mx-1" id="code3" maxlength="1"
                        oninput="moveToNext(this, 'code4')" onkeypress="return isDigitKey(event)" required
                        name='code3'>
                    <input type="text" class="form-control mx-1" id="code4" maxlength="1"
                        onkeypress="return isDigitKey(event)" required name='code4'>

                </div>
                @if (Session::has('fail'))
                    <div class="alert alert-danger" role="alert">
                        {{ Session::get('fail') }}
                    </div>
                @endif
                <div class="d-grid gap-2 mt-3">
                    <button type="submit" class="btn btn-primary" id="verifyButton">Verify</button>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function moveToNext(currentInput, nextInputId) {
            if (currentInput.value.length == currentInput.maxLength) {
                document.getElementById(nextInputId).focus();
            }
        }

        function isDigitKey(event) {
            var charCode = event.which ? event.which : event.keyCode;
            if (charCode < 48 || charCode > 57) {
                event.preventDefault();
                return false;
            }
            return true;
        }
    </script>
</body>

</html>

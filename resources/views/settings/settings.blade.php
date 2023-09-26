@extends('layout.master')

@section('title')
    <h1>Account Settings</h1>
@endsection

@section('content')
    <div class="container-fluid" id="settingcon">
        <div class="row">
            <div class="col-md-12">
                <h3>Change Password:</h3>
            </div>
            <form>
                <div class='mb-3'>
                    <label>Current Password:</label>
                    <input type="password" class="form-control w-25" value="{{ old('current_password') }}"
                        name="current_password" id="current_password">
                </div>
                <div class='mb-3'>
                    <label>New Password:</label>
                    <input type="password" class="form-control w-25" value="{{ old('new_password') }}" name="new_password"
                        id="new_password">
                </div>
                <div class='mb-3'>
                    <label>Confirm Password:</label>
                    <input type="password" class="form-control w-25" value="{{ old('confirm_password') }}"
                        name="confirm_password" id="confirm_password">
                </div>
                <div>
                    <button type="button" class="btn btn-primary" id="change-password-btn">Change Password</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        $(document).on('click', '#change-password-btn', function() {
            var current_password = $.trim($('#current_password').val());
            var new_password = $.trim($('#new_password').val());
            var confirm_password = $.trim($('#confirm_password').val());

            if (current_password === '') {

                alert('Please fill in all password fields.');
            } else {
                if (current_password !== 'correct_current_password') {
                    alert('Incorrect current password.');
                } else if (new_password !== confirm_password) {
                    alert('New password and confirmation do not match.');
                } else {
                    // Passwords are valid and match, proceed with your logic
                    // For security, you should consider hashing and securely storing passwords
                    // and handling password change securely on the server side.
                }
            }

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('changePassword') }}',
                type: 'POST',
                data: {
                    sy: sy,
                    appraisalId: appraisalId,
                },
                success: function(response) {
                    if (response.success) {
                        $('#signtable tbody').empty();
                        var newRow = $('<tr>').addClass('align-middle');
                        newRow.append($('<td>').text('Internal Customer'));
                        newRow.append($('<td>').text(response.full_name));

                        $('#modalImage').attr('src', response.sign_data);

                        if (response.sign_data) {
                            newRow.append($('<td>').addClass('align-middle').html(
                                '<button type="button" class="btn btn-outline-primary" id="view-sig-btn">' +
                                'View Signature' +
                                '</button>'
                            ));
                        }

                        if (response.date_submitted) {
                            newRow.append($('<td>').text(response.date_submitted));
                        } else {
                            newRow.append($('<td>').text('-'));
                        }

                        $('#signtable tbody').append(newRow);
                    } else {
                        console.log('fail');
                    }
                },
                error: function(xhr, status, error) {
                    console.log(error);
                }
            });
        });
    </script>
@endsection

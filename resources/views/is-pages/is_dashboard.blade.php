@extends('layout.master')

@section('title')
    <h1>Dashboard</h1>
@endsection


@section('content')
    @if ($first_login == 'true')
        <script>
            $(document).ready(function() {
                $('#firstLoginModal').modal('show');
            });
        </script>
    @endif

    <div class="d-flex justify-content-between gap-3">
        <div class="dashboard-small-container flex-fill" id="appraisalContainer"></div>
        <div class="dashboard-small-container flex-fill" id="KRAsContainer"></div>
        <div class="dashboard-small-container flex-fill" id="assignedICContainer"></div>
    </div>
    <div class="content-container">
        <h4>Notifications:</h4>
        <ul class="list-group" id="notifications"></ul>
    </div>

    <div class="modal fade" id="firstLoginModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Welcome to HRMDO!</h1>
                </div>
                <div class="modal-body">
                    <div>
                        <p>Please enter your following details:</p>
                        <label>Immediate Superior Position:</label>
                        <input type="text" class="form-control" value="{{ old('position') }}" id="position">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="submit-btn">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            getNotifications();
        });

        $('#position').change(function() {
            $('#position').removeClass('is-invalid');
        });

        $('#submit-btn').click(function() {
            submitISPosition();
        });

        function submitISPosition() {
            var position = $('#position').val();

            if (position.trim() === '') {
                $('#position').addClass('is-invalid');
                return;
            } else {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{ route('is.submitISPosition') }}',
                    type: 'POST',
                    data: {
                        position: position,
                    },
                    success: function(response) {
                        if (response.success) {
                            // console.log('Position Submitted');
                            $('#firstLoginModal').modal('hide');
                        } else {

                        }
                    },
                    error: function(xhr, status, error) {
                        // console.log(error);
                    }
                });
            }
        }

        function getNotifications() {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('is.getNotifications') }}',
                type: 'GET',
                success: function(response) {
                    if (response.notifications && response.notifications.length > 0) {
                        var notificationsContainer = $('#notifications');

                        response.notifications.forEach(function(notification) {
                            var listItem = $('<li class="list-group-item">');
                            var notificationContent = $('<div class="d-flex align-items-center">');
                            var icon = $('<i class="bx bx-fw bx-bell"></i>');
                            var text = $('<div>').text(notification);

                            notificationContent.append(icon);
                            notificationContent.append(text);
                            listItem.append(notificationContent);

                            notificationsContainer.append(listItem);
                        });
                    }
                    if (response.data) {
                        // console.log(response.data);

                        var totalICCount = response.data.totalICCount;
                        var assignedICCount = response.data.assignedICCount;
                        var encodedKRACount = response.data.encodedKRACount;
                        var totalKRACount = response.data.totalKRACount;
                        var totalAppraisalCount = response.data.totalAppraisalCount;
                        var completedAppraisalCount = response.data.completedAppraisalCount;

                        $('#appraisalContainer').html(
                            '<h4 class="text-center">Completed Appraisals:</h4><h5 class="text-center">' +
                            (completedAppraisalCount ? completedAppraisalCount + '/' + totalAppraisalCount :
                                '-') + '</h5>');
                        $('#KRAsContainer').html(
                            '<h4 class="text-center">KRA Encoded:</h4><h5 class="text-center">' +
                            (encodedKRACount ? (encodedKRACount/totalAppraisalCount) + '/' + totalAppraisalCount : '-') + '</h5>');
                        $('#assignedICContainer').html(
                            '<h4 class="text-center">Assigned IC:</h4><h5 class="text-center">' +
                            (assignedICCount ? assignedICCount + '/' + totalICCount : '-') + '</h5>');
                    }

                },
                error: function(xhr, status, error) {
                    // console.log(error);
                }
            });
        }
    </script>
@endsection

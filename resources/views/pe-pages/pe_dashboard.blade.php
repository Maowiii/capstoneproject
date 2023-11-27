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
        <div class="dashboard-container flex-grow-1">
            <h4>Notifications:</h4>
            <ul class="list-group" id="notifications"></ul>
        </div>
        <!--<div class="dashboard-container" id="chart">
            <h4>Key Results Area:</h4>
            <canvas id="donutChart"></canvas>
        </div>-->
    </div>

    <!-- Modal -->
    <div class="modal fade" id="firstLoginModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Welcome to HRMDO!</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div>
                        <p>You must first choose your immediate superior and input your job title.</p>
                        <div class="mt-3">
                            <label>Your Job Title:</label>
                            <input type="text" class="form-control" value="{{ old('job_title') }}" id="job_title"
                                placeholder="Job Title">
                        </div>
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

        $('#job_title').change(function() {
            $('#job_title').removeClass('is-invalid');
        });

        $('#submit-btn').click(function() {
            submitPEFirstLogin();
        });

        function submitPEFirstLogin() {
            var job_title = $('#job_title').val();

            if (job_title.trim() === '') {
                $('#job_title').addClass('is-invalid');
                return;
            } else {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{ route('pe.submitFirstLogin') }}',
                    type: 'POST',
                    data: {
                        job_title: job_title,
                    },
                    success: function(response) {
                        if (response.success) {
                            // console.log('Job Title Submitted');
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
                url: '{{ route('pe.getNotifications') }}',
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
                },
                error: function(xhr, status, error) {
                    // console.log(error);
                }
            });
        }
    </script>
@endsection

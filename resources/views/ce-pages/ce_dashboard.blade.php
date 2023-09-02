@extends('layout.master')

@section('title')
    <h1>Dashboard</h1>
@endsection

@section('content')
    <div class="d-flex justify-content-between gap-3">
        <div class="dashboard-container flex-grow-1">
            <h4>Notifications:</h4>
            <ul class="list-group" id="notifications"></ul>
        </div>
        <div class="dashboard-container" id="chart">
            <h4>Appraisals:</h4>
            <canvas id="donutChart"></canvas>
        </div>
    </div>

    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
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
                        <div>
                            <label>Immediate Superior</label>
                            <select class="form-control" name="department">
                                <option value="" selected>Select Immediate Superior</option>
                                @foreach ($IS as $is)
                                    <option value="{{ $is->account_id }}">
                                        {{ $is->employee->first_name }} {{ $is->employee->last_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <span class="text-danger">
                            @error('email')
                                {{ $message }}
                            @enderror
                        </span>

                        <div>
                            <label class="mt-3">Job Title:</label>
                            <input class="form-control" type="text" placeholder="Job Title">
                        </div>
                        <span class="text-danger">
                            @error('email')
                                {{ $message }}
                            @enderror
                        </span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Understood</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            getNotifications();
            loadAppraisalDonut();
        });

        function loadAppraisalDonut() {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: ' {{ route('ce.getRemainingAppraisals') }}',
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        const completedAppraisalCount = response.completedAppraisalsCount;
                        const pendingAppraisalCount = response.pendingAppraisalsCount;
                        const totalAppraisalCount = response.totalAppraisalsCount;

                        if (totalAppraisalCount == 0) {
                            $('#chart').html(
                                '<h4>Appraisals:</h4><p>You are currently not assigned to evaluate anyone.</p>'
                            );
                        } else {
                            const donutChartCanvas = $("#donutChart");

                            new Chart(donutChartCanvas, {
                                type: "doughnut",
                                data: {
                                    labels: ["Completed", "Remaining"],
                                    datasets: [{
                                        data: [completedAppraisalCount, pendingAppraisalCount],
                                        backgroundColor: ["#164783", "#C3D7F1"],
                                    }, ],
                                }
                            });
                        }
                    } else {
                        $('#chart').html(
                                '<h4>Appraisals:</h4><p>There is no ongoing evaluation.</p>'
                            );
                    }
                },
                error: function(xhr, status, error) {
                    console.log(error);
                }
            });
        }

        function getNotifications() {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('ce.getNotifications') }}',
                type: 'GET',
                success: function(response) {
                    if (response.notifications && response.notifications.length > 0) {
                        var notificationsContainer = $('#notifications');

                        response.notifications.forEach(function(notification) {
                            var listItem = $('<li class="list-group-item">');
                            var notificationContent = $('<div class="d-flex align-items-center">');
                            var icon = $('<i class="bx bx-bell me-3"></i>');
                            var text = $('<div>').text(notification);

                            notificationContent.append(icon);
                            notificationContent.append(text);
                            listItem.append(notificationContent);

                            notificationsContainer.append(listItem);
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.log(error);
                }
            });
        }
    </script>
@endsection

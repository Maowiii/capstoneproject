@extends('layout.master')

@section('title')
    <h1>Dashboard</h1>
@endsection

@section('content')
    <div class="content-container">
        <ul class="list-group" id="notifications"></ul>

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
            });

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
                        console.log(error);
                    }
                });
            }
        </script>
    @endsection

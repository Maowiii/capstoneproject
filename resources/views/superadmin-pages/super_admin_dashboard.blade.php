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
    </div>

    <script>
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '{{ route('sa.getNotifications') }}', // Correct route name
            type: 'GET',
            success: function(response) {
                if (response.notifications && response.notifications.length > 0) {
                    var notificationsContainer = $('#notifications');
                    var uniqueNotifications = [...new Set(response.notifications)]; // Remove duplicates

                    uniqueNotifications.forEach(function(notification) {
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
                // Handle error
            }
        });
    </script>
@endsection

@extends('layout.master')

@section('title')
    <h1 id="department-heading">Department</h1>
@endsection

@section('content')
    <div class='d-flex gap-3'>
        <div class="content-container text-middle" id="total-pe-container">
            <h4>Total Permanent Employees:</h4>
            <p>-</p>
        </div>
        <div class="content-container text-middle" id="avg-score-container">
            <h4>Average Total Score:</h4>
        </div>
    </div>

    <div class="d-flex gap-3">
        <div class="content-container">
            <h2>Point System:</h2>
        </div>
        <div class="content-container">
            <h2>Graph:</h2>
        </div>
    </div>

    <div class="content-container">
        <h2>Behavioral Competencies:</h2>
        <h4>Sustained Integral Development:</h4>
        <table class="table table-sm mb-3" id="sid_table">
            <thead>
                <th>#</th>
                <th>Question</th>
                <th class="medium-column">Average Score</th>
            </thead>
            <tbody></tbody>
        </table>
        <h4>Social Responsibility:</h4>
        <table class="table table-sm mb-3" id="sr_table">
            <thead>
                <th>#</th>
                <th>Question</th>
                <th class="medium-column">Average Score</th>
            </thead>
            <tbody></tbody>
        </table>
        <h4>Solidarity</h4>
        <table class="table table-sm mb-3" id="s_table">
            <thead>
                <th>#</th>
                <th>Question</th>
                <th class="medium-column">Average Score</th>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <div class="content-container">
        <h2>Internal Customers:</h2>
        <table class="table table-sm mb-3" id="ic_table">
            <thead>
                <th>#</th>
                <th>Question</th>
                <th class="medium-column">Average Score</th>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <script>
        $(document).ready(function() {
            const departmentName = new URLSearchParams(window.location.search).get('department_name');
            const departmentID = new URLSearchParams(window.location.search).get('department_id');
            const selectedYear = new URLSearchParams(window.location.search).get('sy');

            console.log('Selected Year: ' + selectedYear);
            console.log('Department ID: ' + departmentID);

            if (departmentName) {
                $('#department-heading').text(departmentName);
            }

            loadQuestions(selectedYear);
            loadCards(selectedYear, departmentID);
        });

        function loadCards(selectedYear = null, departmentID = null) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('ad.loadDepartmentalCards') }}',
                type: 'GET',
                data: {
                    selectedYear: selectedYear,
                    departmentID: departmentID
                },
                success: function(response) {
                    if (response.success) {
                        console.log(response);
                        $('#total-pe-container').html('<h4>Total Permanent Employees:</h4><p>' + response
                            .totalPermanentEmployees + '</p>');

                        $('#avg-score-container').html('<h4>Average Score:</h4><p>' + response.avgTotalScore +
                            '</p>');
                    } else {}
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", status, error);
                }
            });
        }

        function loadPointsSystem(selectedYear = null, departmentID = null) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('ad.loadPointSystem') }}',
                type: 'GET',
                data: {
                    selectedYear: selectedYear,
                    departmentID: departmentID
                },
                success: function(response) {
                    if (response.success) {

                    } else {}
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", status, error);
                }
            });
        }

        function loadQuestions(selectedYear = null) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('ad.loadQuestions') }}',
                type: 'GET',
                data: {
                    selectedYear: selectedYear,
                },
                success: function(response) {
                    if (response.success) {
                        if (response.sid) {
                            var sidTable = $('#sid_table tbody');
                            sidTable.empty();

                            $.each(response.sid, function(index, item) {
                                var row = $("<tr class='text-center'>");
                                row.append($("<td>").text(item
                                    .question_order));
                                row.append($("<td class='text-start'>").text(item
                                    .question));
                                row.append($("<td>").text('-'));

                                sidTable.append(row);
                            });
                        }

                        if (response.sr) {
                            var srTable = $('#sr_table tbody');
                            srTable.empty();

                            $.each(response.sr, function(index, item) {
                                var row = $("<tr>");
                                row.append($("<td>").text(item
                                    .question_order));
                                row.append($("<td class='text-start'>").text(item
                                    .question));
                                row.append($("<td>").text('-'));
                                srTable.append(row);
                            });
                        }

                        if (response.s) {
                            var sTable = $('#s_table tbody');
                            sTable.empty();

                            $.each(response.s, function(index, item) {
                                var row = $("<tr>");
                                row.append($("<td>").text(item
                                    .question_order));
                                row.append($("<td class='text-start'>").text(item
                                    .question));
                                row.append($("<td>").text('-'));
                                sTable.append(row);
                            });
                        }

                        if (response.ic) {
                            var icTable = $('#ic_table tbody');
                            icTable.empty();

                            $.each(response.ic, function(index, item) {
                                var row = $("<tr>");
                                row.append($("<td>").text(item
                                    .question_order));
                                row.append($("<td class='text-start'>").text(item
                                    .question));
                                row.append($("<td>").text('-'));
                                icTable.append(row);
                            });
                        }
                    }
                }
            })
        }
    </script>
@endsection

@extends('layout.master')

@section('title')
    <h1 id="department-heading">Department</h1>
@endsection

@section('content')
    <div class='d-flex gap-3'>
        <div class="content-container text-middle" id="total-permanent-employees-container">
            <h4>Total Permanent Employees:</h4>
        </div>
        <div class="content-container text-middle" id="avg-total-score-container">
            <h4>Average Total Score:</h4>
        </div>
    </div>

    <div class="content-container">
        <h2>Behavioral Competencies:</h2>
        <h4>Sustained Integral Development:</h4>
        <table class="table table-sm mb-3" id="sid_table">
            <thead>
                <th>#</th>
                <th>Question</th>
                <th>Average Score</th>
            </thead>
            <tbody></tbody>
        </table>
        <h4>Social Responsibility:</h4>
        <table class="table table-sm mb-3" id="sr_table">
            <thead>
                <th>#</th>
                <th>Question</th>
                <th>Average Score</th>
            </thead>
            <tbody></tbody>
        </table>
        <h4>Solidarity</h4>
        <table class="table table-sm mb-3" id="s_table">
            <thead>
                <th>#</th>
                <th>Question</th>
                <th>Average Score</th>
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
                <th>Average Score</th>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <script>
        $(document).ready(function() {
            const departmentName = new URLSearchParams(window.location.search).get('department_name');
            const selectedYear = new URLSearchParams(window.location.search).get('sy');

            console.log('Selected Year: ' + selectedYear);

            if (departmentName) {
                $('#department-heading').text(departmentName);
            }

            loadQuestions(selectedYear);
        });

        function loadQuestions(selectedYear) {
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
                        console.log(response);
                        if (response.sid) {
                            var sidTable = $('#sid_table tbody');
                            sidTable.empty();

                            $.each(response.sid, function(index, item) {
                                var row = $("<tr class='text-center'>");
                                row.append($("<td>").text(item
                                    .question_order));
                                row.append($("<td class='text-start'>").text(item
                                    .question));
                                row.append($("<td class='text-center'>").text('-'));

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
                                row.append($("<td>").text(item
                                    .question));
                                row.append($("<td class='text-center'>").text('-'));
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
                                row.append($("<td>").text(item
                                    .question));
                                row.append($("<td class='text-center'>").text('-'));
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
                                row.append($("<td>").text(item
                                    .question));
                                row.append($("<td class='text-center'>").text('-'));
                                icTable.append(row);
                            });
                        }
                    }
                }
            })
        }
    </script>
@endsection

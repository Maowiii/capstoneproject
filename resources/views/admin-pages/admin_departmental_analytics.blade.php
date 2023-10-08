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
        <!-- Point System -->
        <div class="content-container">
            <h2>Point System:</h2>
            <h4>Oustanding Employees:</h4>
            <table class="table table-sm" id="outstanding_table">
                <thead>
                    <th>Name</th>
                    <th>Score</th>
                </thead>
                <tbody></tbody>
            </table>
            <h4>Very Satisfactory:</h4>
            <table class="table table-sm" id="verySatisfactory_table">
                <thead>
                    <th>Name</th>
                    <th>Score</th>
                </thead>
                <tbody></tbody>
            </table>
            <h4>Satisfactory:</h4>
            <table class="table table-sm" id="satisfactory_table">
                <thead>
                    <th>Name</th>
                    <th>Score</th>
                </thead>
                <tbody></tbody>
            </table>
            <h4>Fair:</h4>
            <table class="table table-sm" id="fair_table">
                <thead>
                    <th>Name</th>
                    <th>Score</th>
                </thead>
                <tbody></tbody>
            </table>
            <h4>Poor:</h4>
            <table class="table table-sm" id="poor_table">
                <thead>
                    <th>Name</th>
                    <th>Score</th>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <div class="content-container">
            <canvas id="point_system_bar_chart" aria-label="chart" height="350" width="580"></canvas>
        </div>
    </div>

    <!-- BEHAVIORAL COMPETENCIES -->
    <!-- Sustained Integral Development -->
    <div class="d-flex gap-3">
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
        </div>
        <div class="content-container">
            <h4>TEST</h4>
        </div>
    </div>
    <!-- Social Responsibility -->
    <div class="d-flex gap-3">
        <div class="content-container">
            <h4>Social Responsibility:</h4>
            <table class="table table-sm mb-3" id="sr_table">
                <thead>
                    <th>#</th>
                    <th>Question</th>
                    <th class="medium-column">Average Score</th>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <div class="content-container">
            <h4>TEST</h4>
        </div>
    </div>
    <!-- Solidarity -->
    <div class="d-flex gap-3">
        <div class="content-container">
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
            <h4>TEST</h4>
        </div>
    </div>

    <!-- Internal Customers -->
    <div class="d-flex gap-3">
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
        <div class="floating-container">
            <div class="fixed-bottom p-4">
                <div class="d-flex justify-content-end">
                        <button class="btn btn-secondary btn-circle" id="print-button">
                            <i class='bx bxs-printer'></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function printReport() {
            window.print(); // Open the browser's print dialog
        }
        $(document).ready(function() {
            const departmentName = new URLSearchParams(window.location.search).get('department_name');
            const departmentID = new URLSearchParams(window.location.search).get('department_id');
            const selectedYear = new URLSearchParams(window.location.search).get('sy');

            console.log('Selected Year: ' + selectedYear);
            console.log('Department ID: ' + departmentID);

            if (departmentName) {
                $('#department-heading').text(departmentName);
            }

            loadBCQuestions(selectedYear, departmentID);
            loadICQuestions(selectedYear, departmentID);
            loadCards(selectedYear, departmentID);
            loadPointsSystem(selectedYear, departmentID);
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
                url: '{{ route('ad.loadPointsSystem') }}',
                type: 'GET',
                data: {
                    selectedYear: selectedYear,
                    departmentID: departmentID
                },
                success: function(response) {
                    if (response.success) {
                        const categories = [{
                                label: 'Outstanding',
                                key: 'outstanding',
                                tableId: '#outstanding_table tbody'
                            },
                            {
                                label: 'Very Satisfactory',
                                key: 'verySatisfactory',
                                tableId: '#verySatisfactory_table tbody'
                            },
                            {
                                label: 'Satisfactory',
                                key: 'satisfactory',
                                tableId: '#satisfactory_table tbody'
                            },
                            {
                                label: 'Fair',
                                key: 'fair',
                                tableId: '#fair_table tbody'
                            },
                            {
                                label: 'Poor',
                                key: 'poor',
                                tableId: '#poor_table tbody'
                            },
                        ];

                        const pointSystemCanvas = $('#point_system_bar_chart');

                        new Chart(pointSystemCanvas, {
                            type: 'bar',
                            data: {
                                labels: categories.map(category => category.label),
                                datasets: [{
                                    label: "Number of employee per category",
                                    data: categories.map(category => response[category.key] ?
                                        response[category.key].length : 0),
                                    backgroundColor: '#c3d7f1',
                                    borderWidth: 1,
                                }],
                            },
                            options: {
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        max: Math.max(...categories.map(category => response[category
                                                .key] ? response[category.key].length : 0)) +
                                            1,
                                        ticks: {
                                            stepSize: 1,
                                        },
                                    },
                                },
                            },
                        });

                        categories.forEach(category => {
                            const table = $(category.tableId);
                            table.empty();
                            const row = $("<tr class='text-center'>");

                            if (response[category.key].length > 0) {
                                $.each(response[category.key], function(index, item) {
                                    const fullName =
                                        `${item.employee.first_name} ${item.employee.last_name}`;
                                    row.append($("<td>").text(fullName));
                                    row.append($("<td>").text(item.final_score));
                                    table.append(row.clone());
                                });
                            } else {
                                row.append($("<td colspan='2'>").text("-"));
                                table.append(row);
                            }
                        });
                    }

                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", status, error);
                }
            });
        }

        function loadICQuestions(selectedYear = null, departmentID = null) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('ad.loadICQuestions') }}',
                type: 'GET',
                data: {
                    selectedYear: selectedYear,
                    departmentID: departmentID
                },
                success: function(response) {
                    if (response.success) {
                        console.log(response);
                        if (response.ic) {
                            var icTable = $('#ic_table tbody');
                            icTable.empty();

                            $.each(response.ic, function(index,
                                item) {
                                var row = $("<tr class='text-center'>");
                                row.append($("<td>").text(item.question_order));
                                row.append($("<td class='text-start'>").text(item.question));
                                row.append($("<td>").text(item
                                    .average_score));

                                icTable.append(row);
                            });

                            var averageScores = response.ic.map(function(item) {
                                return item.average_score;
                            });

                            var questionLabels = response.ic.map(function(item) {
                                return 'Q' + item.question_order;
                            });

                            var icBarChart = $('#ic_bar_chart');

                            new Chart(icBarChart, {
                                type: 'bar',
                                data: {
                                    labels: questionLabels,
                                    datasets: [{
                                        label: 'Average Score',
                                        data: averageScores,
                                        backgroundColor: '#c3d7f1',
                                        borderWidth: 1
                                    }]
                                },
                                options: {
                                    scales: {
                                        y: {
                                            beginAtZero: true,
                                            max: 5,
                                            ticks: {
                                                stepSize: 1,
                                            },
                                        },
                                    },
                                },
                            });

                        }
                    }

                }
            });
        }

        function loadBCQuestions(selectedYear = null, departmentID = null) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('ad.loadBCQuestions') }}',
                type: 'GET',
                data: {
                    selectedYear: selectedYear,
                    departmentID: departmentID
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
                    }
                }
            })
        }
    </script>
@endsection

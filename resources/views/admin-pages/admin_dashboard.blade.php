@extends('layout.master')

@section('title')
    <h1>Dashboard</h1>
@endsection

@section('content')
    <div class="row g-3 align-items-start mb-3">
        <div class="col-auto">
            <h4>School Year:</h4>
        </div>
        <div class="col">
            <select class="form-select align-middle" id="evaluation-year-select">
                @if (!$activeEvalYear)
                    <option value="">Select an Evaluation Year (no ongoing evaluation)</option>
                @endif
                @foreach ($evaluationYears as $year)
                    <option value="{{ $year->sy_start }}_{{ $year->sy_end }}"
                        @if ($activeEvalYear && $year->eval_id === $activeEvalYear->eval_id) selected @endif>
                        {{ $year->sy_start }} - {{ $year->sy_end }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class='d-flex gap-3'>
        <div class="content-container text-middle" id="total-pe-container">
            <h4>Total Permanent Employees:</h4>
            <p>-</p>
        </div>
        <div class="content-container text-middle" id="total-appraisals-container">
            <h4>Appraisals Completed:</h4>
            <p>-</p>
        </div>
        <div class="content-container text-middle" id="avg-score-container">
            <h4>Average Total Score:</h4>
            <p>-</p>
        </div>
    </div>

    <div class="content-container">
        <div class="input-group mb-2 search-box">
            <input type="text" class="form-control" placeholder="Department" id="search">
            <button class="btn btn-outline-secondary" type="button">
                <i class='bx bx-search'></i>
            </button>
        </div>
        <table class='table table-bordered table-sm align-middle' id="departments_table">
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Department</th>
                    <th>Average Score</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
        <nav id="department_pagination_container">
            <ul class="pagination pagination-sm justify-content-end" id="department_pagination"></ul>
        </nav>
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
            <canvas id="sid_bar_chart" aria-label="chart" height="350" width="580"></canvas>
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
            <canvas id="sr_bar_chart" aria-label="chart" height="350" width="580"></canvas>
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
            <canvas id="s_bar_chart" aria-label="chart" height="350" width="580"></canvas>
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
        <div class="content-container">
            <canvas id="ic_bar_chart" aria-label="chart" height="350" width="580"></canvas>
        </div>
        <div class="floating-container">
            <div class="fixed-bottom p-4">
                <div class="d-flex justify-content-end">
                    <div class="d-flex flex-column">
                        <button class="btn btn-primary btn-circle mb-2" id="download-button">
                            <i class='bx bxs-download'></i>
                        </button>
                        <button class="btn btn-secondary btn-circle" id="print-button" onclick="printReport()">
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
            var globalSelectedYear = null;

            $('#evaluation-year-select').change(function() {
                var selectedYear = $(this).val();
                globalSelectedYear = selectedYear;
                console.log('Selected Year: ' + selectedYear);
                loadDepartmentTable(selectedYear, null);
                loadICQuestions(selectedYear);
                loadBCQuestions(selectedYear);
                loadPointsSystem(selectedYear);
                loadCards(selectedYear);
            });

            $('#search').on('input', function() {
                var query = $(this).val();
                console.log('Query: ' + query);
                loadDepartmentTable(globalSelectedYear, query);
            });

            loadDepartmentTable(globalSelectedYear, null);
            loadICQuestions(globalSelectedYear);
            loadBCQuestions(globalSelectedYear);
            loadPointsSystem(globalSelectedYear);
            loadCards(globalSelectedYear);
        });

        function loadCards(selectedYear = null) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('ad.loadCards') }}',
                type: 'GET',
                data: {
                    selectedYear: selectedYear,
                },
                success: function(response) {
                    if (response.success) {
                        $('#total-pe-container').html('<h4>Total Permanent Employees:</h4><p>' + response
                            .totalPermanentEmployees + '</p>');

                        $('#avg-score-container').html('<h4>Average Score:</h4><p>' + response.avgTotalScore +
                            '</p>');

                        $('#total-appraisals-container').html('<h4>Appraisals Completed:</h4><p>' + response
                            .totalAppraisals + '</p>');
                    } else {
                        console.log('Load Cards failed.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", status, error);
                }
            });
        }

        function loadDepartmentTable(selectedYear = null, search = null, page = 1) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('ad.loadDepartmentTable') }}',
                type: 'GET',
                data: {
                    selectedYear: selectedYear,
                    search: search,
                    page: page
                },
                success: function(response) {
                    if (response.success) {
                        console.log(response);
                        var departments = response.departments.data;

                        departments.sort(function(a, b) {
                            return a.rank - b.rank;
                        });

                        $('#departments_table tbody').empty();

                        for (var i = 0; i < departments.length; i++) {
                            var department = departments[i];
                            console.log(department);
                            var row = $('<tr class="text-center">');
                            row.append($('<td>').text(department.department.rank));

                            var departmentNameLink = $('<a>')
                                .attr('href', "{{ route('ad.viewDepartment') }}?sy= " + selectedYear +
                                    "&department_id=" + department.department.department_id + '&department_name=' +
                                    encodeURIComponent(department.department.department_name))
                                .text(department.department.department_name);

                            var td = $('<td>').append(departmentNameLink);
                            row.append(td);

                            if (department.average_score) {
                                row.append($('<td>').text(department.average_score));
                            } else {
                                row.append($('<td>').text('-'));
                            }

                            $('#departments_table tbody').append(row);
                        }

                        totalPage = response.departments.last_page;
                        currentPage = response.departments.current_page;
                        $('#department_pagination').empty();
                        for (totalPageCounter = 1; totalPageCounter <= totalPage; totalPageCounter++) {
                            (function(pageCounter) {
                                var pageItem = $('<li>').addClass('page-item');
                                if (pageCounter === currentPage) {
                                    pageItem.addClass('active');
                                }
                                var pageButton = $('<button>').addClass('page-link').text(pageCounter);
                                pageButton.click(function() {
                                    loadDepartmentTable(selectedYear, search, pageCounter);
                                });
                                pageItem.append(pageButton);
                                $('#department_pagination').append(pageItem);
                            })(totalPageCounter);
                        }
                    } else {
                        $('#departments_table tbody').empty();
                        var row = $(
                            '<tr><td colspan="3"><p class="text-secondary fst-italic mt-0">There is no ongoing evaluation. Please select a past evaluation on top.</p></td></tr>'
                        );
                        $('#departments_table tbody').append(row);
                    }
                },
                error: function(xhr, status, error) {
                    var errorMessage = xhr.responseJSON && xhr.responseJSON.error ? xhr
                        .responseJSON.error : 'An error occurred.';
                    console.log(errorMessage);
                }
            });
        }

        function loadICQuestions(selectedYear = null) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('ad.loadDashboardICQuestions') }}',
                type: 'GET',
                data: {
                    selectedYear: selectedYear,
                },
                success: function(response) {
                    if (response.success) {
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
                            var canvas = icBarChart[0];

                            if (canvas) {
                                var existingChart = Chart.getChart(canvas);
                                if (existingChart) {
                                    existingChart.destroy();
                                }
                            }

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
                    } else {
                        var row = $(
                            '<tr><td colspan="3"><p>-</p></td></tr>'
                        );
                        var icTable = $('#ic_table tbody');
                        icTable.empty();
                        icTable.append(row);

                        const icBarChart = $('#ic_bar_chart');
                        var canvas = icBarChart[0];

                        if (canvas) {
                            var existingChart = Chart.getChart(canvas);
                            if (existingChart) {
                                existingChart.destroy();
                            }
                        }

                        new Chart(icBarChart, {
                            type: 'bar',
                            data: {
                                labels: ['Questions'],
                                datasets: [{
                                    label: "Average score per question",
                                    data: [0, 0, 0, 0, 0],
                                    backgroundColor: '#c3d7f1',
                                    borderWidth: 1,
                                }],
                            },
                            options: {
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        max: 1,
                                        ticks: {
                                            stepSize: 1,
                                        },
                                    },
                                },
                            },
                        });
                    }
                }
            });
        }

        function loadBCQuestions(selectedYear = null) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('ad.loadDashboardBCQuestions') }}',
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
                                row.append($("<td>").text(item.average_score));

                                sidTable.append(row);
                            });

                            var averageScores = response.sid.map(function(item) {
                                return item.average_score;
                            });

                            var questionLabels = response.sid.map(function(item) {
                                return 'Q' + item.question_order;
                            });

                            var sidBarChart = $('#sid_bar_chart');
                            var canvas = sidBarChart[0];

                            if (canvas) {
                                var existingChart = Chart.getChart(canvas);
                                if (existingChart) {
                                    existingChart.destroy();
                                }
                            }

                            new Chart(sidBarChart, {
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

                        if (response.sr) {
                            var srTable = $('#sr_table tbody');
                            srTable.empty();

                            $.each(response.sr, function(index, item) {
                                var row = $("<tr>");
                                row.append($("<td>").text(item
                                    .question_order));
                                row.append($("<td class='text-start'>").text(item
                                    .question));
                                row.append($("<td>").text(item.average_score));
                                srTable.append(row);
                            });

                            var averageScores = response.sr.map(function(item) {
                                return item.average_score;
                            });

                            var questionLabels = response.sr.map(function(item) {
                                return 'Q' + item.question_order;
                            });

                            var srBarChart = $('#sr_bar_chart');
                            var canvas = srBarChart[0];

                            if (canvas) {
                                var existingChart = Chart.getChart(canvas);
                                if (existingChart) {
                                    existingChart.destroy();
                                }
                            }

                            new Chart(srBarChart, {
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

                        if (response.s) {
                            var sTable = $('#s_table tbody');
                            sTable.empty();

                            $.each(response.s, function(index, item) {
                                var row = $("<tr>");
                                row.append($("<td>").text(item
                                    .question_order));
                                row.append($("<td class='text-start'>").text(item
                                    .question));
                                row.append($("<td>").text(item.average_score));
                                sTable.append(row);
                            });

                            var averageScores = response.s.map(function(item) {
                                return item.average_score;
                            });

                            var questionLabels = response.s.map(function(item) {
                                return 'Q' + item.question_order;
                            });

                            var sBarChart = $('#s_bar_chart');
                            var canvas = sBarChart[0];

                            if (canvas) {
                                var existingChart = Chart.getChart(canvas);
                                if (existingChart) {
                                    existingChart.destroy();
                                }
                            }

                            new Chart(sBarChart, {
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
                    } else {
                        var placeholderRow = $('<tr><td colspan="3"><p>-</p></td></tr>');

                        var sidTable = $('#sid_table tbody');
                        var srTable = $('#sr_table tbody');
                        var sTable = $('#s_table tbody');

                        sidTable.empty();
                        srTable.empty();
                        sTable.empty();

                        sidTable.append(placeholderRow.clone());
                        srTable.append(placeholderRow.clone());
                        sTable.append(placeholderRow.clone());

                        const sidBarChart = $('#sid_bar_chart');
                        var canvas = sidBarChart[0];

                        if (canvas) {
                            var existingChart = Chart.getChart(canvas);
                            if (existingChart) {
                                existingChart.destroy();
                            }
                        }

                        new Chart(sidBarChart, {
                            type: 'bar',
                            data: {
                                labels: ['Questions'],
                                datasets: [{
                                    label: "Average score per question",
                                    data: [0, 0, 0, 0, 0],
                                    backgroundColor: '#c3d7f1',
                                    borderWidth: 1,
                                }],
                            },
                            options: {
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        max: 1,
                                        ticks: {
                                            stepSize: 1,
                                        },
                                    },
                                },
                            },
                        });

                        const srBarChart = $('#sr_bar_chart');
                        var canvas = srBarChart[0];

                        if (canvas) {
                            var existingChart = Chart.getChart(canvas);
                            if (existingChart) {
                                existingChart.destroy();
                            }
                        }

                        new Chart(srBarChart, {
                            type: 'bar',
                            data: {
                                labels: ['Questions'],
                                datasets: [{
                                    label: "Average score per question",
                                    data: [0, 0, 0, 0, 0],
                                    backgroundColor: '#c3d7f1',
                                    borderWidth: 1,
                                }],
                            },
                            options: {
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        max: 1,
                                        ticks: {
                                            stepSize: 1,
                                        },
                                    },
                                },
                            },
                        });

                        const sBarChart = $('#s_bar_chart');
                        var canvas = sBarChart[0];

                        if (canvas) {
                            var existingChart = Chart.getChart(canvas);
                            if (existingChart) {
                                existingChart.destroy();
                            }
                        }

                        new Chart(sBarChart, {
                            type: 'bar',
                            data: {
                                labels: ['Questions'],
                                datasets: [{
                                    label: "Average score per question",
                                    data: [0, 0, 0, 0, 0],
                                    backgroundColor: '#c3d7f1',
                                    borderWidth: 1,
                                }],
                            },
                            options: {
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        max: 1,
                                        ticks: {
                                            stepSize: 1,
                                        },
                                    },
                                },
                            },
                        });
                    }
                }
            })
        }

        function loadPointsSystem(selectedYear = null) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('ad.loadDashboardPointsSystem') }}',
                type: 'GET',
                data: {
                    selectedYear: selectedYear,
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

                        const pointSystemBarChart = $('#point_system_bar_chart');
                        var canvas = pointSystemBarChart[0];

                        if (canvas) {
                            var existingChart = Chart.getChart(canvas);
                            if (existingChart) {
                                existingChart.destroy();
                            }
                        }

                        new Chart(pointSystemBarChart, {
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
                    } else {
                        var outstandingTable = $('#outstanding_table tbody');
                        var verySatisfactoryTable = $('#verySatisfactory_table tbody');
                        var satisfactoryTable = $('#satisfactory_table tbody');
                        var fairTable = $('#fair_table tbody');
                        var poorTable = $('#poor_table tbody');

                        var placeholderRow = $('<tr><td colspan="3"><p>-</p></td></tr>');

                        outstandingTable.empty();
                        verySatisfactoryTable.empty();
                        satisfactoryTable.empty();
                        fairTable.empty();
                        poorTable.empty();

                        outstandingTable.append(placeholderRow.clone());
                        verySatisfactoryTable.append(placeholderRow.clone());
                        satisfactoryTable.append(placeholderRow.clone());
                        fairTable.append(placeholderRow.clone());
                        poorTable.append(placeholderRow.clone());

                        const pointSystemBarChart = $('#point_system_bar_chart');
                        var canvas = pointSystemBarChart[0];

                        if (canvas) {
                            var existingChart = Chart.getChart(canvas);
                            if (existingChart) {
                                existingChart.destroy();
                            }
                        }

                        new Chart(pointSystemBarChart, {
                            type: 'bar',
                            data: {
                                labels: ['Outstanding', 'Very Satisfactory', 'Satisfactory', 'Fair',
                                    'Poor'
                                ],
                                datasets: [{
                                    label: "Number of employee per category",
                                    data: [0, 0, 0, 0, 0],
                                    backgroundColor: '#c3d7f1',
                                    borderWidth: 1,
                                }],
                            },
                            options: {
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        max: 1,
                                        ticks: {
                                            stepSize: 1,
                                        },
                                    },
                                },
                            },
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", status, error);
                }
            });
        }
    </script>
@endsection

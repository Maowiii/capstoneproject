@extends('layout.master')

@section('title')
    <h1>Dashboard</h1>
@endsection

@section('content')
    <div class="row g-3 align-items-start mb-3">
        <h2 id="school-year-heading">School Year: -</h2>
        {{-- <div class="col-auto">
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
        </div> --}}
    </div>

    <!-- Cards -->
    <div class="row">
        <div class="col mb-3">
            <div class="content-container text-center h-100">
                <h4>Total Appraisees:</h4>
                <p id="total-pe">-</p>
            </div>
        </div>
        <div class="col mb-3">
            <div class="content-container text-center h-100">
                <h4>Appraisals Completed:</h4>
                <p id="total-appraisals">-</p>
            </div>
        </div>
        <div class="col mb-3">
            <div class="content-container text-center h-100">
                <h4>Average Total Score:</h4>
                <p id="avg-score">-</p>
            </div>
        </div>
    </div>

    <div class="container-fluid content-container d-flex flex-column align-items-center text-center">
        <h2 class="text-center">Yearly performance trend:</h2>
        <div class="w-100" style="height: 300px">
            <canvas id="lineChart" aria-label="chart"></canvas>
        </div>
    </div>

    <!-- Departments Table -->
    <div class="content-container container-fluid">
        <div class="table-responsive">
            <div class="input-group mb-2 w-25 float-end">
                <input type="text" class="form-control" placeholder="Department" id="search">
                <button class="btn btn-outline-secondary" type="button">
                    <i class='bx bx-search'></i>
                </button>
            </div>
            <table class='table table-bordered table-sm align-middle' id="departments_table">
                <thead>
                    <tr>
                        <th>Department</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <nav id="department_pagination_container">
            <ul class="pagination pagination-sm justify-content-end" id="department_pagination"></ul>
        </nav>
    </div>

    <!-- Employees Table -->
    {{-- <div class="content-container container-fluid">
        <div class="table-responsive">
            <div class="input-group mb-2 search-box">
                <input type="text" class="form-control" placeholder="Name" id="namesearch">
                <button class="btn btn-outline-secondary" type="button">
                    <i class='bx bx-search'></i>
                </button>
            </div>
            <table class='table table-bordered table-sm align-middle' id="employees_table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Department</th>
                        <th>Trends</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <nav id="employee_pagination_container">
            <ul class="pagination pagination-sm justify-content-end" id="employee_pagination"></ul>
        </nav>
    </div> --}}

    <!-- POINT CATEGORIES -->
    <div class="container-fluid content-container d-flex flex-column align-items-center text-center">
        <h2>Point Categories:</h2>
        <div class="w-100" style="height: 300px">
            <canvas id="point_system_bar_chart" aria-label="chart"></canvas>
        </div>
    </div>

    <!-- BEHAVIORAL COMPETENCIES -->
    <!-- Sustained Integral Development -->
    <div class="row">
        <div class="col-lg-6 mb-3">
            <div class="content-container h-100">
                <h2 class="text-center">Behavioral Competencies:</h2>
                <h4 class="text-center">Search for Excellence and Sustained Integral Development:</h4>
                <h5 class="text-center" id="sid-school-year">School Year:</h5>
                <h5 class="text-center" id="sid-total-avg-score">Total Average Score:</h5>
                <div class="table-responsive">
                    <table class="table table-sm mb-3" id="sid_table">
                        <thead>
                            <th>#</th>
                            <th>Question</th>
                            <th class="medium-column">Average Score</th>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-3">
            <div class="content-container h-100">
                <h4 class="text-center">Search for Excellence and Sustained Integral Development performance over the
                    years:</h4>
                <canvas id="sid_trend_chart" aria-label="chart" height="350" width="580"></canvas>
                <h4 class="text-center mt-3">Search for Excellence and Sustained Integral Development average score per
                    question:</h4>
                <canvas id="sid_bar_chart" aria-label="chart" height="350" width="580"></canvas>
            </div>
        </div>
    </div>

    <!-- Social Responsibility -->
    <div class="row">
        <div class="col-lg-6 mb-3">
            <div class="content-container h-100">
                <h4 class="text-center">Spirit of St. Vincent de Paul and Social Responsibility:</h4>
                <h5 class="text-center" id="sr-school-year">School Year:</h5>
                <h5 class="text-center" id="sr-total-avg-score">Total Average Score:</h5>
                <div class="table-responsive">
                    <table class="table table-sm mb-3" id="sr_table">
                        <thead>
                            <th>#</th>
                            <th>Question</th>
                            <th class="medium-column">Average Score</th>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-3">
            <div class="content-container h-100">
                <h4 class="text-center">Spirit of St. Vincent de Paul and Social Responsibility performance over the years:
                </h4>
                <canvas id="sr_trend_chart" aria-label="chart"></canvas>
                <h4 class="text-center mt-3">Spirity of St. Vincent de Paul and Social Responsibility average score per
                    question:</h4>
                <canvas id="sr_bar_chart" aria-label="chart"></canvas>
            </div>
        </div>
    </div>

    <!-- Solidarity -->
    <div class="row">
        <div class="col-lg-6 mb-3">
            <div class="content-container h-100">
                <h4 class="text-center">Solidarity</h4>
                <h5 class="text-center" id="s-school-year">School Year:</h5>
                <h5 class="text-center" id="s-total-avg-score">Total Average Score:</h5>
                <div class="table-responsive">
                    <table class="table table-sm mb-3" id="s_table">
                        <thead>
                            <th>#</th>
                            <th>Question</th>
                            <th class="medium-column">Average Score</th>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-3">
            <div class="content-container h-100">
                <h4 class="text-center">Solidarity performance over the years:</h4>
                <canvas id="s_trend_chart" aria-label="chart"></canvas>
                <h4 class="text-center mt-3">Solidarity average score per question:</h4>
                <canvas id="s_bar_chart" aria-label="chart"></canvas>
            </div>
        </div>
    </div>

    <!-- Internal Customers -->
    <div class="row">
        <div class="col-lg-6 mb-3">
            <div class="content-container h-100">
                <h2 class="text-center">Internal Customers:</h2>
                <h5 class="text-center" id="ic-school-year">School Year:</h5>
                <h5 class="text-center" id ="ic-total-avg-score">Total Average Score:</h5>
                <div class="table-responsive">
                    <table class="table table-sm mb-3" id="ic_table">
                        <thead>
                            <th>#</th>
                            <th>Question</th>
                            <th class="medium-column">Average Score</th>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-3">
            <div class="content-container h-100">
                <h4 class="text-center">Internal Customer performance over the years:</h4>
                <canvas id="ic_trend_chart" aria-label="chart"></canvas>
                <h4 class="text-center mt-3">Internal Customer average score per question:</h4>
                <canvas id="ic_bar_chart" aria-label="chart"></canvas>
            </div>
        </div>
    </div>

    <div class="floating-container">
        <div class="fixed-right p-4">
            <div class="d-flex justify-content-end">
                <button class="btn btn-secondary btn-circle" id="print-button" onclick="printReport()">
                    <i class='bx bxs-printer'></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="scoreModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="scoreModalTitle">Modal title</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h4 id="scoreModalQuestion">Text</h4>
                    <table class="table table-sm mb-3" id="scoreModalTable">
                        <thead>
                            <th>Name</th>
                            <th>Score</th>
                        </thead>
                        <tbody></tbody>
                    </table>
                    <nav id="score_pagination_container">
                        <ul class="pagination pagination-sm justify-content-end" id="score_pagination"></ul>
                    </nav>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function printReport() {
            window.print();
        }

        $(document).ready(function() {
            var globalSelectedYear = null;
            $('#evaluation-year-select').change(function() {
                var selectedYear = $(this).val();
                globalSelectedYear = selectedYear;
                // console.log('Selected Year: ' + selectedYear);
                loadDepartmentTable(selectedYear, null);
                loadICQuestions(selectedYear);
                loadBCQuestions(selectedYear);
                loadPointsSystem(selectedYear);
                loadCards(selectedYear);
            });

            $('#search').on('input', function() {
                var query = $(this).val();
                // console.log('Query: ' + query);
                loadDepartmentTable(query, null);
            });

            $('#namesearch').on('input', function() {
                var query = $(this).val();
                // console.log('Name Query: ' + query);
                loadEmployeesTable(query);
            });

            loadDepartmentTable(globalSelectedYear, null);
            loadSIDQuestions(globalSelectedYear);
            loadSRQuestions(globalSelectedYear);
            loadICQuestions(globalSelectedYear);
            loadSQuestions(globalSelectedYear);
            loadCards(globalSelectedYear);
            //loadEmployeesTable(null);

            loadPointsSystem();
            loadSIDChart();
            loadSRChart();
            loadSChart();
            loadICChart();
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
                        $('#school-year-heading').text('School Year: ' + response.schoolYear);
                        $('#total-pe').text(response
                            .totalPermanentEmployees);

                        $('#avg-score').text(response.avgTotalScore);

                        $('#total-appraisals').text(response.totalAppraisals);
                    } else {
                        // console.log('Load Cards failed.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", status, error);
                }
            });
        }

        function loadDepartmentTable(search = null, page = 1) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('ad.loadDepartmentTable') }}',
                type: 'GET',
                data: {
                    search: search,
                    page: page
                },
                success: function(response) {
                    //console.log(response);
                    if (response.success) {
                        var departments = response.departments.data;

                        $('#departments_table tbody').empty();

                        for (var i = 0; i < departments.length; i++) {
                            var department = departments[i];
                            var row = $('<tr class="text-center">');

                            var departmentNameLink = $('<a>')
                                .attr('href', "{{ route('ad.viewDepartment') }}" + '?department_id=' +
                                    department.department_id +
                                    '&department_name=' + encodeURIComponent(department.department_name))
                                .text(department.department_name);

                            var td = $('<td>').append(departmentNameLink);
                            row.append(td);

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
                                    loadDepartmentTable(search, pageCounter);
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
                    // console.log(errorMessage);
                }
            });
        }

        function loadSIDQuestions(selectedYear = null) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('ad.loadDashboardSIDQuestions') }}',
                type: 'GET',
                data: {
                    selectedYear: selectedYear,
                },
                success: function(response) {
                    //console.log(response);
                    if (response.success) {
                        if (response.sid) {
                            var totalAvgScore = response.total_avg_score;
                            $('#sid-total-avg-score').text('Total Average Score: ' + totalAvgScore);
                            $('#sid-school-year').text('School Year: ' + response.school_year.replace(/_/g,
                                '-'));
                            var sidTable = $('#sid_table tbody');
                            sidTable.empty();

                            $.each(response.sid, function(index,
                                item) {

                                var row = $("<tr class='text-center'>");

                                row.append($("<td>").text(item.question_order));
                                row.append($("<td class='text-start'>").text(item.question));

                                if (item.average_score < totalAvgScore) {
                                    var cell = $("<td>");
                                    var container = $("<div>");

                                    var anchor = $("<a href='#'>")
                                        .text(item.average_score)
                                        .addClass("view-ic-score")
                                        .data("schoolYear", response.school_year)
                                        .data("questionID", item.question_id)
                                        .on("click", function(e) {
                                            e.preventDefault();
                                            var schoolYear = $(this).data("schoolYear");
                                            var questionID = $(this).data("questionID");
                                            viewScoreModal(schoolYear, questionID);
                                        });

                                    var icon = $("<i>")
                                        .addClass('bx bxs-down-arrow')
                                        .css('color', '#dc3545')
                                        .attr('data-toggle', 'tooltip')
                                        .attr('data-placement', 'top')
                                        .attr('title',
                                            'This question is below the total average score.');

                                    container.append(anchor, icon);
                                    cell.append(container);

                                    icon.tooltip();

                                    row.append(cell);
                                } else {
                                    var cell = $("<td>");
                                    var anchor = $("<a href='#'>")
                                        .text(item.average_score)
                                        .addClass("view-ic-score")
                                        .data("schoolYear", response.school_year)
                                        .data("questionID", item.question_id)
                                        .on("click", function(e) {
                                            e.preventDefault();
                                            var schoolYear = $(this).data("schoolYear");
                                            var questionID = $(this).data("questionID");

                                            viewScoreModal(schoolYear, questionID);
                                        });

                                    cell.append(anchor);
                                    row.append(cell);
                                }

                                sidTable.append(row);
                            });

                        }
                    } else {
                        var row = $(
                            '<tr><td colspan="3"><p>-</p></td></tr>'
                        );
                        var sidTable = $('#sid_table tbody');
                        sidTable.empty();
                        sidTable.append(row);
                    }
                },
                error: function(xhr, status, error) {
                    var errorMessage = xhr.responseJSON && xhr.responseJSON.error ? xhr
                        .responseJSON.error : 'An error occurred.';
                    // console.log(errorMessage);
                }
            });
        }

        function loadSIDChart() {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('ad.loadSIDChart') }}',
                type: 'GET',
                success: function(response) {
                    //console.log(response);
                    if (response.success) {
                        // IC Trends Chart
                        var sidTrendChart = $('#sid_trend_chart');
                        var canvas = sidTrendChart[0];

                        if (canvas) {
                            var existingChart = Chart.getChart(canvas);
                            if (existingChart) {
                                existingChart.destroy();
                            }
                        }

                        // Assuming you have the data object you provided
                        var data = response;

                        var years = Object.keys(data.data);

                        var scores = years.map(year => parseFloat(data.data[year].total_average_score));

                        for (var i = 0; i < years.length; i++) {
                            years[i] = years[i].replace(/_/g, '-');
                        }

                        new Chart(sidTrendChart, {
                            type: 'line',
                            data: {
                                labels: years,
                                datasets: [{
                                    label: 'Total Average Score',
                                    data: scores,
                                    backgroundColor: '#164783',
                                    borderColor: '#164783',
                                    borderWidth: 1,
                                    pointBackgroundColor: '#164783',
                                    pointBorderColor: '#164783',
                                    pointRadius: 5,
                                    pointHoverRadius: 8,
                                }, ],
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: true,
                                aspectRatio: 2,
                                onClick: function(event, elements) {
                                    if (elements.length > 0) {
                                        var index = elements[0].index;
                                        var clickedYear = years[index];

                                        console.log('Clicked Year: ' + clickedYear);
                                        loadSIDQuestions(clickedYear);
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: false,
                                        max: 5,
                                        ticks: {
                                            stepSize: 1,
                                        },
                                    },
                                },
                            },
                        });

                        // Per Question Chart
                        var sidBarChart = $('#sid_bar_chart');
                        var canvas = sidBarChart[0];

                        if (canvas) {
                            var existingChart = Chart.getChart(canvas);
                            if (existingChart) {
                                existingChart.destroy();
                            }
                        }

                        var data = {
                            labels: [],
                            datasets: []
                        };

                        var years = Object.keys(response.data);
                        var uniqueIds = [];

                        years.forEach(function(year) {
                            var yearData = response.data[year];
                            var dataset = {
                                label: year,
                                data: [],
                                backgroundColor: getRandomColor(),
                            };

                            var ids = Object.keys(yearData);

                            ids = ids.filter(function(questionId) {
                                return questionId !== 'total_average_score';
                            });

                            ids.forEach(function(questionId) {
                                if (!uniqueIds.includes(questionId)) {
                                    uniqueIds.push(questionId);
                                }
                            });

                            var mappedQuestionIds = uniqueIds.map(function(questionId, index) {
                                return 'Q' + (index + 1);
                            });

                            ids.forEach(function(questionId) {
                                var averageScore = parseFloat(yearData[questionId]
                                    .average_score || 0);
                                dataset.data.push(averageScore);
                            });

                            data.labels = mappedQuestionIds;

                            data.datasets.push(dataset);
                        });

                        new Chart(sidBarChart, {
                            type: 'bar',
                            data: data,
                            options: {
                                responsive: true,
                                maintainAspectRatio: true,
                                aspectRatio: 2,
                                onClick: function(event, elements) {
                                    if (elements.length > 0) {
                                        var clickedElementIndex = elements[0].index;
                                        var clickedDatasetIndex = elements[0].datasetIndex;
                                        var clickedYear = this.data.datasets[clickedDatasetIndex]
                                            .label;
                                        var questionId = uniqueIds[clickedElementIndex];

                                        console.log('Clicked Element Index: ' +
                                            clickedElementIndex);
                                        console.log('Clicked Year: ' + clickedYear);
                                        console.log('Clicked Question ID: ' + questionId);
                                        viewScoreModal(clickedYear, questionId, page = 1);

                                    }
                                },
                                scales: {
                                    x: {
                                        stacked: true,
                                    },
                                    y: {
                                        beginAtZero: false,
                                        max: 5,
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
                    var errorMessage = xhr.responseJSON && xhr.responseJSON.error ? xhr
                        .responseJSON.error : 'An error occurred.';
                    // console.log(errorMessage);
                }
            });
        }

        function loadSRQuestions(selectedYear = null) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('ad.loadDashboardSRQuestions') }}',
                type: 'GET',
                data: {
                    selectedYear: selectedYear,
                },
                success: function(response) {
                    //console.log(response);
                    if (response.success) {
                        if (response.sr) {
                            var totalAvgScore = response.total_avg_score;
                            $('#sr-total-avg-score').text('Total Average Score: ' + totalAvgScore);
                            $('#sr-school-year').text('School Year: ' + response.school_year.replace(/_/g,
                                '-'));

                            var srTable = $('#sr_table tbody');
                            srTable.empty();

                            $.each(response.sr, function(index,
                                item) {

                                var row = $("<tr class='text-center'>");

                                row.append($("<td>").text(item.question_order));
                                row.append($("<td class='text-start'>").text(item.question));

                                if (item.average_score < totalAvgScore) {
                                    var cell = $("<td>");
                                    var container = $("<div>");

                                    var anchor = $("<a href='#'>")
                                        .text(item.average_score)
                                        .addClass("view-ic-score")
                                        .data("schoolYear", response.school_year)
                                        .data("questionID", item.question_id)
                                        .on("click", function(e) {
                                            e.preventDefault();
                                            var schoolYear = $(this).data("schoolYear");
                                            var questionID = $(this).data("questionID");
                                            viewScoreModal(schoolYear, questionID);
                                        });

                                    var icon = $("<i>")
                                        .addClass('bx bxs-down-arrow')
                                        .css('color', '#dc3545')
                                        .attr('data-toggle', 'tooltip')
                                        .attr('data-placement', 'top')
                                        .attr('title',
                                            'This question is below the total average score.');

                                    container.append(anchor, icon);
                                    cell.append(container);

                                    icon.tooltip();

                                    row.append(cell);
                                } else {
                                    var cell = $("<td>");
                                    var anchor = $("<a href='#'>")
                                        .text(item.average_score)
                                        .addClass("view-ic-score")
                                        .data("schoolYear", response.school_year)
                                        .data("questionID", item.question_id)
                                        .on("click", function(e) {
                                            e.preventDefault();
                                            var schoolYear = $(this).data("schoolYear");
                                            var questionID = $(this).data("questionID");

                                            viewScoreModal(schoolYear, questionID);
                                        });

                                    cell.append(anchor);
                                    row.append(cell);
                                }

                                srTable.append(row);
                            });

                        }
                    } else {
                        var row = $(
                            '<tr><td colspan="3"><p>-</p></td></tr>'
                        );
                        var srTable = $('#sr_table tbody');
                        srTable.empty();
                        srTable.append(row);
                    }
                },
                error: function(xhr, status, error) {
                    var errorMessage = xhr.responseJSON && xhr.responseJSON.error ? xhr
                        .responseJSON.error : 'An error occurred.';
                    // console.log(errorMessage);
                }
            });
        }

        function loadSRChart() {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('ad.loadSRChart') }}',
                type: 'GET',
                success: function(response) {
                    //console.log(response);
                    if (response.success) {
                        // IC Trends Chart
                        var srTrendChart = $('#sr_trend_chart');
                        var canvas = srTrendChart[0];

                        if (canvas) {
                            var existingChart = Chart.getChart(canvas);
                            if (existingChart) {
                                existingChart.destroy();
                            }
                        }

                        var data = response;
                        var years = Object.keys(data.data);

                        var scores = years.map(year => parseFloat(data.data[year].total_average_score));

                        for (var i = 0; i < years.length; i++) {
                            years[i] = years[i].replace(/_/g, '-');
                        }

                        new Chart(srTrendChart, {
                            type: 'line',
                            data: {
                                labels: years,
                                datasets: [{
                                    label: 'Total Average Score',
                                    data: scores,
                                    backgroundColor: '#164783',
                                    borderColor: '#164783',
                                    borderWidth: 1,
                                    pointBackgroundColor: '#164783',
                                    pointBorderColor: '#164783',
                                    pointRadius: 5,
                                    pointHoverRadius: 8,
                                }, ],
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: true,
                                aspectRatio: 2,
                                onClick: function(event, elements) {
                                    if (elements.length > 0) {
                                        var index = elements[0].index;
                                        var clickedYear = years[index];

                                        console.log('Clicked Year: ' + clickedYear);
                                        loadSRQuestions(clickedYear);
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: false,
                                        max: 5,
                                        ticks: {
                                            stepSize: 1,
                                        },
                                    },
                                },
                            },
                        });

                        // Per Question Chart
                        var srBarChart = $('#sr_bar_chart');
                        var canvas = srBarChart[0];

                        if (canvas) {
                            var existingChart = Chart.getChart(canvas);
                            if (existingChart) {
                                existingChart.destroy();
                            }
                        }

                        var data = {
                            labels: [],
                            datasets: []
                        };

                        var years = Object.keys(response.data);
                        var uniqueIds = [];

                        years.forEach(function(year) {
                            var yearData = response.data[year];
                            var dataset = {
                                label: year,
                                data: [],
                                backgroundColor: getRandomColor(),
                            };

                            var ids = Object.keys(yearData);

                            ids = ids.filter(function(questionId) {
                                return questionId !== 'total_average_score';
                            });

                            ids.forEach(function(questionId) {
                                if (!uniqueIds.includes(questionId)) {
                                    uniqueIds.push(questionId);
                                }
                            });

                            var mappedQuestionIds = uniqueIds.map(function(questionId, index) {
                                return 'Q' + (index + 1);
                            });

                            ids.forEach(function(questionId) {
                                var averageScore = parseFloat(yearData[questionId]
                                    .average_score || 0);
                                dataset.data.push(averageScore);
                            });

                            data.labels = mappedQuestionIds;

                            data.datasets.push(dataset);
                        });

                        new Chart(srBarChart, {
                            type: 'bar',
                            data: data,
                            options: {
                                responsive: true,
                                maintainAspectRatio: true,
                                aspectRatio: 2,
                                onClick: function(event, elements) {
                                    if (elements.length > 0) {
                                        var clickedElementIndex = elements[0].index;
                                        var clickedDatasetIndex = elements[0].datasetIndex;
                                        var clickedYear = this.data.datasets[clickedDatasetIndex]
                                            .label;
                                        var questionId = uniqueIds[clickedElementIndex];

                                        console.log('Clicked Element Index: ' +
                                            clickedElementIndex);
                                        console.log('Clicked Year: ' + clickedYear);
                                        console.log('Clicked Question ID: ' + questionId);
                                        viewScoreModal(clickedYear, questionId, page = 1);

                                    }
                                },
                                scales: {
                                    x: {
                                        stacked: true,
                                    },
                                    y: {
                                        beginAtZero: false,
                                        max: 5,
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
                    var errorMessage = xhr.responseJSON && xhr.responseJSON.error ? xhr
                        .responseJSON.error : 'An error occurred.';
                    // console.log(errorMessage);
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
                    //console.log(response);
                    if (response.success) {
                        if (response.ic) {
                            var totalAvgScore = response.total_avg_score;
                            $('#ic-total-avg-score').text('Total Average Score: ' + totalAvgScore);
                            $('#ic-school-year').text('School Year: ' + response.school_year.replace(/_/g,
                                '-'));

                            var icTable = $('#ic_table tbody');
                            icTable.empty();

                            $.each(response.ic, function(index,
                                item) {

                                var row = $("<tr class='text-center'>");

                                row.append($("<td>").text(item.question_order));
                                row.append($("<td class='text-start'>").text(item.question));

                                if (item.average_score < totalAvgScore) {
                                    var cell = $("<td>");
                                    var container = $("<div>");

                                    var anchor = $("<a href='#'>")
                                        .text(item.average_score)
                                        .addClass("view-ic-score")
                                        .data("schoolYear", response.school_year)
                                        .data("questionID", item.question_id)
                                        .on("click", function(e) {
                                            e.preventDefault();
                                            var schoolYear = $(this).data("schoolYear");
                                            var questionID = $(this).data("questionID");
                                            viewScoreModal(schoolYear, questionID);
                                        });

                                    var icon = $("<i>")
                                        .addClass('bx bxs-down-arrow')
                                        .css('color', '#dc3545')
                                        .attr('data-toggle', 'tooltip')
                                        .attr('data-placement', 'top')
                                        .attr('title',
                                            'This question is below the total average score.');

                                    container.append(anchor, icon);
                                    cell.append(container);

                                    icon.tooltip();

                                    row.append(cell);
                                } else {
                                    var cell = $("<td>");
                                    var anchor = $("<a href='#'>")
                                        .text(item.average_score)
                                        .addClass("view-ic-score")
                                        .data("schoolYear", response.school_year)
                                        .data("questionID", item.question_id)
                                        .on("click", function(e) {
                                            e.preventDefault();
                                            var schoolYear = $(this).data("schoolYear");
                                            var questionID = $(this).data("questionID");

                                            viewScoreModal(schoolYear, questionID);
                                        });

                                    cell.append(anchor);
                                    row.append(cell);
                                }

                                icTable.append(row);
                            });

                        }
                    } else {
                        var row = $(
                            '<tr><td colspan="3"><p>-</p></td></tr>'
                        );
                        var icTable = $('#ic_table tbody');
                        icTable.empty();
                        icTable.append(row);
                    }
                },
                error: function(xhr, status, error) {
                    var errorMessage = xhr.responseJSON && xhr.responseJSON.error ? xhr
                        .responseJSON.error : 'An error occurred.';
                    // console.log(errorMessage);
                }
            });
        }

        function loadICChart() {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('ad.loadICChart') }}',
                type: 'GET',
                success: function(response) {
                    //console.log(response);
                    if (response.success) {
                        // IC Trends Chart
                        var icTrendChart = $('#ic_trend_chart');
                        var canvas = icTrendChart[0];

                        if (canvas) {
                            var existingChart = Chart.getChart(canvas);
                            if (existingChart) {
                                existingChart.destroy();
                            }
                        }

                        var data = response;

                        var years = Object.keys(data.data);
                        var scores = years.map(year => parseFloat(data.data[year].total_average_score));
                        for (var i = 0; i < years.length; i++) {
                            years[i] = years[i].replace(/_/g, '-');
                        }

                        new Chart(icTrendChart, {
                            type: 'line',
                            data: {
                                labels: years,
                                datasets: [{
                                    label: 'Total Average Score',
                                    data: scores,
                                    backgroundColor: '#164783',
                                    borderColor: '#164783',
                                    borderWidth: 1,
                                    pointBackgroundColor: '#164783',
                                    pointBorderColor: '#164783',
                                    pointRadius: 5,
                                    pointHoverRadius: 8,
                                }, ],
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: true,
                                aspectRatio: 2,
                                onClick: function(event, elements) {
                                    if (elements.length > 0) {
                                        var index = elements[0].index;
                                        var clickedYear = years[index];

                                        console.log('Clicked Year: ' + clickedYear);
                                        loadICQuestions(clickedYear);
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: false,
                                        max: 5,
                                        ticks: {
                                            stepSize: 1,
                                        },
                                    },
                                },
                            },
                        });

                        // Per Question Chart
                        var icBarChart = $('#ic_bar_chart');
                        var canvas = icBarChart[0];

                        if (canvas) {
                            var existingChart = Chart.getChart(canvas);
                            if (existingChart) {
                                existingChart.destroy();
                            }
                        }

                        var data = {
                            labels: [],
                            datasets: []
                        };

                        var years = Object.keys(response.data);
                        var uniqueIds = [];

                        years.forEach(function(year) {
                            var yearData = response.data[year];
                            var dataset = {
                                label: year,
                                data: [],
                                backgroundColor: getRandomColor(),
                            };

                            var ids = Object.keys(yearData);

                            ids = ids.filter(function(questionId) {
                                return questionId !== 'total_average_score';
                            });

                            ids.forEach(function(questionId) {
                                if (!uniqueIds.includes(questionId)) {
                                    uniqueIds.push(questionId);
                                }
                            });

                            var mappedQuestionIds = uniqueIds.map(function(questionId, index) {
                                return 'Q' + (index + 1);
                            });

                            ids.forEach(function(questionId) {
                                var averageScore = parseFloat(yearData[questionId]
                                    .average_score || 0);
                                dataset.data.push(averageScore);
                            });

                            data.labels = mappedQuestionIds;

                            data.datasets.push(dataset);
                        });

                        new Chart(icBarChart, {
                            type: 'bar',
                            data: data,
                            options: {
                                responsive: true,
                                maintainAspectRatio: true,
                                aspectRatio: 2,
                                onClick: function(event, elements) {
                                    if (elements.length > 0) {
                                        var clickedElementIndex = elements[0].index;
                                        var clickedDatasetIndex = elements[0].datasetIndex;
                                        var clickedYear = this.data.datasets[clickedDatasetIndex]
                                            .label;
                                        var questionId = uniqueIds[clickedElementIndex];

                                        console.log('Clicked Element Index: ' +
                                            clickedElementIndex);
                                        console.log('Clicked Year: ' + clickedYear);
                                        console.log('Clicked Question ID: ' + questionId);
                                        viewScoreModal(clickedYear, questionId, page = 1);

                                    }
                                },
                                scales: {
                                    x: {
                                        stacked: true,
                                    },
                                    y: {
                                        beginAtZero: false,
                                        max: 5,
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
                    var errorMessage = xhr.responseJSON && xhr.responseJSON.error ? xhr
                        .responseJSON.error : 'An error occurred.';
                    // console.log(errorMessage);
                }
            });
        }

        function loadSChart() {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('ad.loadSChart') }}',
                type: 'GET',
                success: function(response) {
                    //console.log(response);
                    if (response.success) {
                        // IC Trends Chart

                        var sTrendChart = $('#s_trend_chart');
                        var canvas = sTrendChart[0];

                        if (canvas) {
                            var existingChart = Chart.getChart(canvas);
                            if (existingChart) {
                                existingChart.destroy();
                            }
                        }

                        // Assuming you have the data object you provided
                        var data = response;

                        var years = Object.keys(data.data);
                        var scores = years.map(year => parseFloat(data.data[year].total_average_score));
                        for (var i = 0; i < years.length; i++) {
                            years[i] = years[i].replace(/_/g, '-');
                        }

                        new Chart(sTrendChart, {
                            type: 'line',
                            data: {
                                labels: years,
                                datasets: [{
                                    label: 'Total Average Score',
                                    data: scores,
                                    backgroundColor: '#164783',
                                    borderColor: '#164783',
                                    borderWidth: 1,
                                    pointBackgroundColor: '#164783',
                                    pointBorderColor: '#164783',
                                    pointRadius: 5,
                                    pointHoverRadius: 8,
                                }, ],
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: true,
                                aspectRatio: 2,
                                onClick: function(event, elements) {
                                    if (elements.length > 0) {
                                        var index = elements[0].index;
                                        var clickedYear = years[index];

                                        console.log('Clicked Year: ' + clickedYear);
                                        loadSQuestions(clickedYear);
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: false,
                                        max: 5,
                                        ticks: {
                                            stepSize: 1,
                                        },
                                    },
                                },
                            },
                        });

                        // Per Question Chart
                        var sBarChart = $('#s_bar_chart');
                        var canvas = sBarChart[0];

                        if (canvas) {
                            var existingChart = Chart.getChart(canvas);
                            if (existingChart) {
                                existingChart.destroy();
                            }
                        }

                        var data = {
                            labels: [],
                            datasets: []
                        };

                        var years = Object.keys(response.data);
                        var uniqueIds = [];

                        years.forEach(function(year) {
                            var yearData = response.data[year];
                            var dataset = {
                                label: year,
                                data: [],
                                backgroundColor: getRandomColor(),
                            };

                            var ids = Object.keys(yearData);

                            ids = ids.filter(function(questionId) {
                                return questionId !== 'total_average_score';
                            });

                            ids.forEach(function(questionId) {
                                if (!uniqueIds.includes(questionId)) {
                                    uniqueIds.push(questionId);
                                }
                            });

                            var mappedQuestionIds = uniqueIds.map(function(questionId, index) {
                                return 'Q' + (index + 1);
                            });

                            ids.forEach(function(questionId) {
                                var averageScore = parseFloat(yearData[questionId]
                                    .average_score || 0);
                                dataset.data.push(averageScore);
                            });

                            data.labels = mappedQuestionIds;

                            data.datasets.push(dataset);
                        });

                        new Chart(sBarChart, {
                            type: 'bar',
                            data: data,
                            options: {
                                responsive: true,
                                maintainAspectRatio: true,
                                aspectRatio: 2,
                                onClick: function(event, elements) {
                                    if (elements.length > 0) {
                                        var clickedElementIndex = elements[0].index;
                                        var clickedDatasetIndex = elements[0].datasetIndex;
                                        var clickedYear = this.data.datasets[clickedDatasetIndex]
                                            .label;
                                        var questionId = uniqueIds[clickedElementIndex];

                                        console.log('Clicked Element Index: ' +
                                            clickedElementIndex);
                                        console.log('Clicked Year: ' + clickedYear);
                                        console.log('Clicked Question ID: ' + questionId);
                                        viewScoreModal(clickedYear, questionId, page = 1);

                                    }
                                },
                                scales: {
                                    x: {
                                        stacked: true,
                                    },
                                    y: {
                                        beginAtZero: false,
                                        max: 5,
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
                    var errorMessage = xhr.responseJSON && xhr.responseJSON.error ? xhr
                        .responseJSON.error : 'An error occurred.';
                    // console.log(errorMessage);
                }
            });
        }

        function loadSQuestions(selectedYear = null) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('ad.loadDashboardSQuestions') }}',
                type: 'GET',
                data: {
                    selectedYear: selectedYear,
                },
                success: function(response) {
                    //console.log(response);
                    if (response.success) {
                        if (response.s) {
                            var totalAvgScore = response.total_avg_score;
                            $('#s-total-avg-score').text('Total Average Score: ' + totalAvgScore);
                            $('#s-school-year').text('School Year: ' + response.school_year.replace(/_/g, '-'));

                            var sTable = $('#s_table tbody');
                            sTable.empty();

                            $.each(response.s, function(index,
                                item) {

                                var row = $("<tr class='text-center'>");

                                row.append($("<td>").text(item.question_order));
                                row.append($("<td class='text-start'>").text(item.question));

                                if (item.average_score < totalAvgScore) {
                                    var cell = $("<td>");
                                    var container = $("<div>");

                                    var anchor = $("<a href='#'>")
                                        .text(item.average_score)
                                        .addClass("view-ic-score")
                                        .data("schoolYear", response.school_year)
                                        .data("questionID", item.question_id)
                                        .on("click", function(e) {
                                            e.preventDefault();
                                            var schoolYear = $(this).data("schoolYear");
                                            var questionID = $(this).data("questionID");
                                            viewScoreModal(schoolYear, questionID);
                                        });

                                    var icon = $("<i>")
                                        .addClass('bx bxs-down-arrow')
                                        .css('color', '#dc3545')
                                        .attr('data-toggle', 'tooltip')
                                        .attr('data-placement', 'top')
                                        .attr('title',
                                            'This question is below the total average score.');

                                    container.append(anchor, icon);
                                    cell.append(container);

                                    icon.tooltip();

                                    row.append(cell);
                                } else {
                                    var cell = $("<td>");
                                    var anchor = $("<a href='#'>")
                                        .text(item.average_score)
                                        .addClass("view-ic-score")
                                        .data("schoolYear", response.school_year)
                                        .data("questionID", item.question_id)
                                        .on("click", function(e) {
                                            e.preventDefault();
                                            var schoolYear = $(this).data("schoolYear");
                                            var questionID = $(this).data("questionID");

                                            viewScoreModal(schoolYear, questionID);
                                        });

                                    cell.append(anchor);
                                    row.append(cell);
                                }

                                sTable.append(row);
                            });

                        }
                    } else {
                      console.log('Load S Questions: Failed.');
                        var row = $(
                            '<tr><td colspan="3"><p>-</p></td></tr>'
                        );
                        var sTable = $('#s_table tbody');
                        sTable.empty();
                        sTable.append(row);
                    }
                },
                error: function(xhr, status, error) {
                    var errorMessage = xhr.responseJSON && xhr.responseJSON.error ? xhr
                        .responseJSON.error : 'An error occurred.';
                    // console.log(errorMessage);
                }
            });
        }

        function viewScoreModal(schoolYear, questionID, page = 1) {
            console.log('School Year: ' + schoolYear);
            console.log('Question ID: ' + questionID);
            $('#scoreModal').modal('show');
            $('#scoreModalTitle').text(schoolYear + ': ')

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('ad.viewScoreModal') }}',
                type: 'GET',
                data: {
                    selectedYear: schoolYear,
                    questionID: questionID,
                    page: page,
                },
                success: function(response) {
                    console.log(response);
                    if (response.success) {
                        $('#scoreModalQuestion').text('Question: "' + response.question.question + '"');

                        var scoreTable = $('#scoreModalTable tbody');
                        scoreTable.empty();

                        $.each(response.questionAnswers.data, function(index,
                            item) {
                            var row = $("<tr class='text-center'>");

                            var fullName = item.first_name + ' ' + item.last_name;
                            var employeeID = item.employee_id;

                            var link = $("<a>")
                                .text(fullName)
                                .attr("href", "{{ route('ad.viewEmployeeAnalytics') }}?employee_id=" +
                                    employeeID + "&full_name=" + fullName);

                            var cell = $("<td>").append(link);

                            row.append(cell);
                            row.append($("<td>").text(item.score));

                            scoreTable.append(row);
                        });

                        totalPage = response.questionAnswers.last_page;
                        currentPage = response.questionAnswers.current_page;
                        $('#score_pagination').empty();
                        for (totalPageCounter = 1; totalPageCounter <= totalPage; totalPageCounter++) {
                            (function(pageCounter) {
                                var pageItem = $('<li>').addClass('page-item');
                                if (pageCounter === currentPage) {
                                    pageItem.addClass('active');
                                }
                                var pageButton = $('<button>').addClass('page-link').text(pageCounter);
                                pageButton.click(function() {
                                    viewScoreModal(schoolYear, questionID, pageCounter);
                                });
                                pageItem.append(pageButton);
                                $('#score_pagination').append(pageItem);
                            })(totalPageCounter);
                        }
                    }
                },
                error: function(xhr, status, error) {
                    var errorMessage = xhr.responseJSON && xhr.responseJSON.error ? xhr
                        .responseJSON.error : 'An error occurred.';
                    // console.log(errorMessage);
                }
            });
        }

        function loadPointsSystem() {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('ad.loadDashboardPointsSystem') }}',
                type: 'GET',
                success: function(response) {
                    //console.log(response);
                    if (response.success) {

                        const pointSystemBarChart = $('#point_system_bar_chart');
                        var canvas = pointSystemBarChart[0];

                        if (canvas) {
                            var existingChart = Chart.getChart(canvas);
                            if (existingChart) {
                                existingChart.destroy();
                            }
                        }

                        var data = {
                            labels: ['Outstanding', 'Very Satisfactory', 'Satisfactory', 'Fair', 'Poor'],
                            datasets: []
                        };

                        var years = Object.keys(response.data);

                        years.forEach(function(year) {
                            var yearData = response.data[year];
                            var dataset = {
                                label: year,
                                data: yearData,
                                backgroundColor: getRandomColor(),
                            };

                            data.datasets.push(dataset);
                        });

                        new Chart(pointSystemBarChart, {
                            type: 'bar',
                            data: data,
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                // aspectRatio: 2,
                                onClick: function(event, elements) {
                                    if (elements.length > 0) {
                                        var clickedElementIndex = elements[0].index;
                                        var clickedDatasetIndex = elements[0].datasetIndex;
                                        var clickedYear = this.data.datasets[clickedDatasetIndex]
                                            .label;
                                        var clickedCategory = data.labels[clickedElementIndex];

                                        console.log('Clicked Element Index: ' +
                                            clickedElementIndex);
                                        console.log('Clicked Year: ' + clickedYear);
                                        console.log('Clicked Category: ' + clickedCategory);
                                        viewCategory(clickedYear, clickedCategory);
                                    }
                                },
                                scales: {
                                    x: {
                                        stacked: true,
                                    },
                                    y: {
                                        beginAtZero: false,
                                        max: 5,
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

        function viewCategory(schoolYear, category, page = 1) {
            $('#scoreModal').modal('show');
            $('#scoreModalTitle').text('School Year: ' + schoolYear);
            $('#scoreModalQuestion').text('Category: ' + category);

            console.log('School Year: ' + schoolYear);
            console.log('Category: ' + category);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('ad.viewCategory') }}',
                type: 'GET',
                data: {
                    selectedYear: schoolYear,
                    category: category,
                    page: page,
                },
                success: function(response) {
                    console.log(response);
                    if (response.success) {
                        var modalTable = $('#scoreModalTable tbody');
                        modalTable.empty();

                        $.each(response.data.data, function(index, item) {
                            var row = $("<tr class='text-center'>");

                            var fullName = item.employee.first_name + ' ' + item.employee.last_name;
                            var score = item.final_score;
                            var employeeID = item.employee_id;

                            var link = $("<a>")
                                .text(fullName)
                                .attr("href", "{{ route('ad.viewEmployeeAnalytics') }}?employee_id=" +
                                    employeeID + "&full_name=" + fullName);

                            var cell = $("<td>").append(link);

                            row.append(cell);
                            row.append($("<td>").text(score));

                            modalTable.append(row);
                        });


                        totalPage = response.data.last_page;
                        currentPage = response.data.current_page;
                        $('#score_pagination').empty();
                        for (totalPageCounter = 1; totalPageCounter <= totalPage; totalPageCounter++) {
                            (function(pageCounter) {
                                var pageItem = $('<li>').addClass('page-item');
                                if (pageCounter === currentPage) {
                                    pageItem.addClass('active');
                                }
                                var pageButton = $('<button>').addClass('page-link').text(pageCounter);
                                pageButton.click(function() {
                                    viewCategory(schoolYear, category, pageCounter);
                                });
                                pageItem.append(pageButton);
                                $('#score_pagination').append(pageItem);
                            })(totalPageCounter);
                        }
                    }
                },
                error: function(xhr, status, error) {
                    var errorMessage = xhr.responseJSON && xhr.responseJSON.error ? xhr
                        .responseJSON.error : 'An error occurred.';
                    // console.log(errorMessage);
                }
            });
        }

        // function loadEmployeesTable(namesearch = null, page = 1) {
        //     $.ajax({
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         },
        //         url: '',
        //         type: 'GET',
        //         data: {
        //             search: namesearch,
        //             page: page
        //         },
        //         success: function(response) {
        //             if (response.success) {
        //                 var employees = response.employees.data;
        //                 employees.sort((a, b) => a.employee.first_name.localeCompare(b.employee.first_name));

        //                 $('#employees_table tbody').empty();

        //                 for (var i = 0; i < employees.length; i++) {
        //                     var employee = employees[i].employee;

        //                     var row = $('<tr class="text-center">');

        //                     row.append($('<td>').text(employee.first_name + ' ' + employee.last_name));
        //                     row.append($('<td>').text(employee.department.department_name));
        //                     row.append($('<td>').append($('<button>')
        //                         .addClass('btn btn-outline-primary view-employee-btn')
        //                         .data('employee-id', employee.employee_id)
        //                         .text('View')));

        //                     $('#employees_table tbody').append(row);
        //                 }

        //                 totalPage = response.employees.last_page;
        //                 currentPage = response.employees.current_page;
        //                 $('#employee_pagination').empty();
        //                 for (totalPageCounter = 1; totalPageCounter <= totalPage; totalPageCounter++) {
        //                     (function(pageCounter) {
        //                         var pageItem = $('<li>').addClass('page-item');
        //                         if (pageCounter === currentPage) {
        //                             pageItem.addClass('active');
        //                         }
        //                         var pageButton = $('<button>').addClass('page-link').text(pageCounter);
        //                         pageButton.click(function() {
        //                             loadEmployeesTable(namesearch, pageCounter);
        //                         });
        //                         pageItem.append(pageButton);
        //                         $('#employee_pagination').append(pageItem);
        //                     })(totalPageCounter);
        //                 }
        //             } else {
        //                 $('#employees_table tbody').empty();
        //                 var row = $(
        //                     '<tr><td colspan="2"><p class="text-secondary fst-italic mt-0">No employees found.</p></td></tr>'
        //                 );
        //                 $('#employees_table tbody').append(row);
        //             }
        //         },
        //         error: function(xhr, status, error) {
        //             var errorMessage = xhr.responseJSON && xhr.responseJSON.error ? xhr.responseJSON.error :
        //                 'An error occurred.';
        //             // console.log(errorMessage);
        //         }
        //     });
        // }

        function fetchAndDisplayLineChart() {
            $.get('{{ route('ad.getFinalScoresPerYear') }}', function(data) {
                if (data.success) {
                    var ctx = document.getElementById('lineChart').getContext('2d');
                    var scoresPerYear = data.scoresPerYear;
                    var labels = [];
                    var allData = []; // An array to store all data

                    for (var yearRange in scoresPerYear) {
                        if (scoresPerYear.hasOwnProperty(yearRange)) {
                            labels.push(yearRange);
                            var data = scoresPerYear[yearRange].total_score ? scoresPerYear[yearRange].total_score :
                                0;

                            allData.push(data);
                        }
                    }
                    var lineChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Average Score',
                                data: allData, // Use the concatenated data
                                backgroundColor: '#164783',
                                borderColor: '#164783',
                                borderWidth: 1,
                                pointBackgroundColor: '#164783',
                                pointBorderColor: '#164783',
                                pointRadius: 5,
                                pointHoverRadius: 8,
                            }],
                        },
                        options: {
                            onClick: function(event, elements) {
                                if (elements.length > 0) {
                                    var index = elements[0].index;
                                    var clickedYear = labels[index].replace(/-/g,
                                        '_');

                                    console.log('Clicked Year: ' + clickedYear);
                                    loadCards(clickedYear);
                                    loadSIDQuestions(clickedYear);
                                    loadSRQuestions(clickedYear);
                                    loadICQuestions(clickedYear);
                                    loadSQuestions(clickedYear);
                                }
                            },
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                x: {
                                    display: true,
                                    title: {
                                        display: true,
                                        text: 'Evaluation Year'
                                    }
                                },
                                y: {
                                    display: true,
                                    title: {
                                        display: true,
                                        text: 'Average Final Score'
                                    },
                                    beginAtZero: false,
                                    max: 5,
                                    ticks: {
                                        stepSize: 1,
                                    },
                                }
                            }
                        }
                    });
                }
            });
        }

        function getRandomColor() {
            var randomBlue = Math.floor(Math.random() * 32).toString(16);
            return '#0000' + (randomBlue + '0'.repeat(2 - randomBlue.length));
        }

        fetchAndDisplayLineChart();
    </script>
@endsection

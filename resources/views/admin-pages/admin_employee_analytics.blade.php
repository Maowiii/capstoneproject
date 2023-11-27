@extends('layout.master')

@section('title')
    <h1 id="employee_analytics-heading">Employee Analytics</h1>
@endsection

@section('content')
    <div class="container-fluid content-container d-flex flex-column">
        <h2 class="text-center">Employee Information:</h2>
        <div class="row">
            <div class="col-12 col-sm-6 col-md-4 mb-3">
                <label class="form-label">Email address:</label>
                <input type="email" class="form-control" id="email" placeholder="name@example.com" readonly>
            </div>
            <div class="col-12 col-sm-6 col-md-4 mb-3">
                <label class="form-label">First Name:</label>
                <input type="text" class="form-control" id="first_name" readonly>
            </div>
            <div class="col-12 col-sm-6 col-md-4 mb-3">
                <label class="form-label">Last Name:</label>
                <input type text" class="form-control" id="last_name" readonly>
            </div>
            <div class="col-12 col-sm-6 col-md-4 mb-3">
                <label class="form-label">Department:</label>
                <input type="text" class="form-control" id="department" readonly>
            </div>
            <div class="col-12 col-sm-6 col-md-4 mb-3">
                <label class="form-label">Job Title:</label>
                <input type="text" class="form-control" id="job_title" readonly>
            </div>
        </div>
    </div>

    <!-- Yearly Performance Trend -->
    <div class="container-fluid content-container d-flex flex-column align-items-center text-center">
        <h2 class="text-center">Employee Performance trend:</h2>
        <div class="w-100" style="height: 300px">
            <canvas id="yearly_trend" aria-label="chart"></canvas>
        </div>
    </div>

    <!-- KRA -->
    <div class="row">
        <div class="col-lg-6 mb-3">
            <div class="content-container h-100">
                <h4 class="text-center">Key Results Area:</h4>
                <h5 class="text-center" id="kra-school-year">School Year:</h5>
                <h5 class="text-center" id="s-total-avg-score">Total Average Score:</h5>
                <h5 class="text-center" id="immediate-superior">Total Average Score:</h5>
                <div class="table-responsive">
                    <table class="table table-sm mb-3" id="kra_table">
                        <thead class="align-middle">
                            <th>KRA</th>
                            <th>Objective</th>
                            <th>Performance Indicator</th>
                            <th>Actual Result</th>
                            <th>Self Evaluation Score</th>
                            <th>Immediate Superior Score</th>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-3">
            <div class="content-container h-100">
                <h4 class="text-center">KRA performance over the years:</h4>
                <canvas id="kra_trend" aria-label="chart" height="350" width="580"></canvas>
            </div>
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

    <script>
        $(document).ready(function() {
            const employeeID = new URLSearchParams(window.location.search).get('employee_id');
            const employeeName = new URLSearchParams(window.location.search).get('full_name');

            if (employeeName) {
                $('#employee_analytics-heading').text(employeeName);
            }

            getEmployeeInformation(employeeID);
            loadEmployeeYearlyTrend(employeeID);
            loadKRATrend(employeeID);
            loadKRAS(employeeID, null);
            loadSIDChart(employeeID);
            loadSIDQuestions(employeeID, null);
            loadSRChart(employeeID);
            loadSRQuestions(employeeID, null);
            loadSChart(employeeID);
            loadSQuestions(employeeID, null);
            loadICChart(employeeID);
            loadICQuestions(employeeID, null);

        });

        function getEmployeeInformation(employeeID) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('ad.getEmployeeInformation') }}',
                type: 'GET',
                data: {
                    employeeID: employeeID,
                },
                success: function(response) {
                    // console.log(response);
                    if (response.success) {
                        var employee = response.employee;
                        var account = response.account;
                        $('#email').val(account.email);
                        $('#first_name').val(employee.first_name);
                        $('#last_name').val(employee.last_name);
                        $('#department').val(employee.department.department_name);
                        $('#job_title').val(employee.job_title);
                    }
                },
                error: function(xhr, status, error) {
                    var errorMessage = xhr.responseJSON && xhr.responseJSON.error ? xhr
                        .responseJSON.error : 'An error occurred.';
                    console.log(errorMessage);
                }
            });
        }

        function loadSIDQuestions(employeeID, selectedYear = null, ) {
            console.log('Employee ID: ' + employeeID);
            console.log('Selected Year: ' + selectedYear);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('ad.loadEmployeeSIDQuestions') }}',
                type: 'GET',
                data: {
                    selectedYear: selectedYear,
                    employeeID: employeeID,
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
                                            viewScoreModal(departmentID, schoolYear, questionID);
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

                                            viewScoreModal(departmentID, schoolYear, questionID);
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

        function loadSIDChart(employeeID) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('ad.loadEmployeeSIDChart') }}',
                type: 'GET',
                data: {
                    employeeID: employeeID,
                },
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
                                        loadSIDQuestions(employeeID, clickedYear);
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
                                        viewScoreModal(departmentID, clickedYear, questionId);

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

        function loadSRQuestions(employeeID, selectedYear = null) {
            console.log('Employee ID: ' + employeeID);
            console.log('Selected Year: ' + selectedYear);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('ad.loadEmployeeSRQuestions') }}',
                type: 'GET',
                data: {
                    selectedYear: selectedYear,
                    employeeID: employeeID,
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
                                            viewScoreModal(departmentID, schoolYear, questionID);
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

                                            viewScoreModal(departmentID, schoolYear, questionID);
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

        function loadSRChart(employeeID) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('ad.loadEmployeeSRChart') }}',
                type: 'GET',
                data: {
                    employeeID: employeeID,
                },
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
                                        loadSRQuestions(employeeID, clickedYear);
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
                                        viewScoreModal(departmentID, clickedYear, questionId, page =
                                            1);

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

        function loadSChart(employeeID) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('ad.loadEmployeeSChart') }}',
                type: 'GET',
                data: {
                    employeeID: employeeID,
                },
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
                                        loadSQuestions(employeeID, clickedYear);
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
                                        viewScoreModal(departmentID, clickedYear, questionId, page =
                                            1);

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

        function loadSQuestions(employeeID, selectedYear = null) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('ad.loadEmployeeSQuestions') }}',
                type: 'GET',
                data: {
                    selectedYear: selectedYear,
                    employeeID: employeeID,
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
                                            viewScoreModal(departmentID, schoolYear, questionID);
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

                                            viewScoreModal(departmentID, schoolYear, questionID);
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

        function loadICQuestions(employeeID, selectedYear = null) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('ad.loadEmployeeICQuestions') }}',
                type: 'GET',
                data: {
                    selectedYear: selectedYear,
                    employeeID: employeeID,
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
                                            viewScoreModal(departmentID, schoolYear, questionID);
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

                                            viewScoreModal(departmentID, schoolYear, questionID);
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

        function loadICChart(employeeID) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('ad.loadEmployeeICChart') }}',
                type: 'GET',
                data: {
                    employeeID: employeeID,
                },
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
                                        loadICQuestions(employeeID, clickedYear);
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
                                        viewScoreModal(departmentID, clickedYear, questionId, page =
                                            1);

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

        function loadEmployeeYearlyTrend(employeeID) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('ad.loadEmployeeYearlyTrend') }}',
                type: 'GET',
                data: {
                    employeeID: employeeID,

                },
                success: function(response) {
                    //console.log(response);
                    if (response.success) {
                        var yearlyTrend = $('#yearly_trend');
                        var canvas = yearlyTrend[0];

                        if (canvas) {
                            var existingChart = Chart.getChart(canvas);
                            if (existingChart) {
                                existingChart.destroy();
                            }
                        }

                        var data = [];

                        var labels = Object.keys(response.scoresPerYear);
                        for (var year in response.scoresPerYear) {
                            var yearScores = response.scoresPerYear[year];

                            for (var i = 0; i < yearScores.length; i++) {
                                var finalScore = parseFloat(yearScores[i].final_score);
                                data.push(finalScore);
                            }
                        }

                        new Chart(yearlyTrend, {
                            type: 'line',
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: 'Final Score',
                                    data: data,
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
                },
                error: function(xhr, status, error) {
                    var errorMessage = xhr.responseJSON && xhr.responseJSON.error ? xhr
                        .responseJSON.error : 'An error occurred.';
                    // console.log(errorMessage);
                }
            });
        }

        function viewScoreModal(departmentID, schoolYear, questionID, page = 1) {
            console.log('School Year: ' + schoolYear);
            console.log('Question ID: ' + questionID);
            $('#scoreModal').modal('show');
            $('#scoreModalTitle').text(schoolYear + ': ')

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('ad.viewDepartmentalScoreModal') }}',
                type: 'GET',
                data: {
                    departmentID: departmentID,
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
                                    viewScoreModal(departmentID, schoolYear, questionID,
                                        pageCounter);
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

        function loadKRATrend(employeeID) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('ad.loadEmployeeKRATrend') }}',
                type: 'GET',
                data: {
                    employeeID: employeeID,
                },
                success: function(response) {
                    //console.log(response);
                    if (response.success) {
                        var kraTrend = $('#kra_trend');
                        var canvas = kraTrend[0];

                        if (canvas) {
                            var existingChart = Chart.getChart(canvas);
                            if (existingChart) {
                                existingChart.destroy();
                            }
                        }

                        var data = [];

                        var labels = Object.keys(response.scoresPerYear);
                        for (var year in response.scoresPerYear) {
                            var yearScore = response.scoresPerYear[year];

                            var finalScore = parseFloat(yearScore);
                            if (!isNaN(finalScore)) {
                                data.push(finalScore);
                            }
                        }

                        new Chart(kraTrend, {
                            type: 'line',
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: 'Final Score',
                                    data: data,
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
                                        loadKRAS(employeeID, clickedYear);
                                    }
                                },
                                responsive: true,
                                maintainAspectRatio: true,
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
                },
                error: function(xhr, status, error) {
                    var errorMessage = xhr.responseJSON && xhr.responseJSON.error ? xhr
                        .responseJSON.error : 'An error occurred.';
                    // console.log(errorMessage);
                }
            });
        }

        function loadKRAS(employeeID, selectedYear = null) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('ad.loadEmployeeKRA') }}',
                type: 'GET',
                data: {
                    employeeID: employeeID,
                    selectedYear: selectedYear,
                },
                success: function(response) {
                    console.log(response);
                    if (response.success) {
                        var kraTable = $('#kra_table tbody');

                        kraTable.empty();
                        var kraMap = {};

                        $.each(response.appraisals, function(index, appraisal) {
                            $.each(appraisal.kras, function(index, kra) {
                                var kraKey = kra.kra;

                                if (!kraMap[kraKey]) {
                                    kraMap[kraKey] = {
                                        kra: kra.kra,
                                        objective: kra.objective,
                                        performance_indicator: kra.performance_indicator,
                                        actual_result: kra.actual_result,
                                        self_evaluation_score: 0,
                                        superior_evaluation_score: 0,
                                    };
                                }

                                if (appraisal.evaluation_type === 'self evaluation') {
                                    kraMap[kraKey].self_evaluation_score += kra
                                        .performance_level;
                                } else if (appraisal.evaluation_type === 'is evaluation') {
                                    kraMap[kraKey].superior_evaluation_score += kra
                                        .performance_level;
                                }
                            });
                        });

                        $.each(kraMap, function(kraKey, kraData) {
                            kraTable.append(
                                '<tr>' +
                                '<td>' + kraData.kra + '</td>' +
                                '<td>' + kraData.objective + '</td>' +
                                '<td>' + kraData.performance_indicator + '</td>' +
                                '<td>' + kraData.actual_result + '</td>' +
                                '<td>' + kraData.self_evaluation_score + '</td>' +
                                '<td>' + kraData.superior_evaluation_score + '</td>' +
                                '</tr>'
                            );
                        });
                    }
                },
                error: function(xhr, status, error) {
                    var errorMessage = xhr.responseJSON && xhr.responseJSON.error ? xhr.responseJSON.error :
                        'An error occurred.';
                    // console.log(errorMessage);
                }
            });
        }


        function getRandomColor() {
            var randomBlue = Math.floor(Math.random() * 32).toString(16);
            return '#0000' + (randomBlue + '0'.repeat(2 - randomBlue.length));
        }
    </script>
@endsection

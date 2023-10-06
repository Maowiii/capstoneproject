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
        <div class="content-container text-middle" id="total-permanent-employees-container">
            <h4>Total Permanent Employees:</h4>
            <p>-</p>
        </div>
        <div class="content-container text-middle" id="avg-total-score-container">
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
            <h4>Oustanding Students:</h4>
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
            <h2>Graph:</h2>
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
        <div class="content-container">
            <h2>Graph:</h2>
            <canvas id="ic_bar_chart" aria-label="chart" height="350" width="580"></canvas>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            var globalSelectedYear = null;

            $('#evaluation-year-select').change(function() {
                var selectedYear = $(this).val();
                globalSelectedYear = selectedYear;
                console.log('Selected Year: ' + selectedYear);
                loadDepartmentTable(selectedYear, null);
                loadICQuestions(selectedYear);
            });

            $('#search').on('input', function() {
                var query = $(this).val();
                console.log('Query: ' + query);
                loadDepartmentTable(globalSelectedYear, query);
            });

            loadDepartmentTable(globalSelectedYear, null);
            loadICQuestions(globalSelectedYear);
            loadBCQuestions(globalSelectedYear);
        });

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
                        var departments = response.departments
                            .data;
                        $('#departments_table tbody').empty();

                        for (var i = 0; i < departments.length; i++) {
                            var department = departments[i];
                            var row = $('<tr class="text-center">');
                            row.append($('<td>').text(i + 1));

                            var departmentNameLink = $('<a>')
                                .attr('href', "{{ route('ad.viewDepartment') }}?sy= " + selectedYear +
                                    "&department_id=" + department
                                    .department_id + '&department_name=' + encodeURIComponent(department
                                        .department_name))
                                .text(department.department_name);

                            var td = $('<td>').append(departmentNameLink);
                            row.append(td);

                            row.append($('<td>').text('-'));

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
                        console.log(response);
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
                                row.append($("<td>").text(item.average_score));

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
                                row.append($("<td>").text(item.average_score));
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
                                row.append($("<td>").text(item.average_score));
                                sTable.append(row);
                            });
                        }
                    } else {
                        console.log('Fail');
                    }
                }
            })
        }
    </script>
@endsection

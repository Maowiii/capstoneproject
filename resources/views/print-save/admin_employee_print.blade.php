@extends('layout.print')

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
                <input type="text" class="form-control" id="last_name" readonly>
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

    <div class="row g-3 align-items-start mb-3">
        <h2 id="school-year-heading">School Year: -</h2>
    </div>
    
    <!-- KRA -->
    <div class="row">
        <div class="content-container h-100">
            <h4 class="text-center">Key Results Area:</h4>
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
    </div>

    <!-- BEHAVIORAL COMPETENCIES -->
    <!-- Sustained Integral Development -->
    <div class="row">
        <div class="content-container h-100">
            <h2 class="text-center">Behavioral Competencies:</h2>
            <h4 class="text-center">Search for Excellence and Sustained Integral Development:</h4>
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

    <!-- Social Responsibility -->
    <div class="row">
        <div class="content-container h-100">
            <h4 class="text-center">Spirit of St. Vincent de Paul and Social Responsibility:</h4>
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


    <!-- Solidarity -->
    <div class="row">
        <div class="content-container h-100">
            <h4 class="text-center">Solidarity</h4>
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

    <!-- Internal Customers -->
    <div class="row">
        <div class="content-container h-50">
            <h2 class="text-center">Internal Customers:</h2>
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

    <script>
    $(document).ready(function() {
        const employeeID = new URLSearchParams(window.location.search).get('employee_id');
        const employeeName = new URLSearchParams(window.location.search).get('full_name');

        if (employeeName) {
            $('#employee_analytics-heading').text(employeeName);
        }

        getEmployeeInformation(employeeID);
        loadKRAS(employeeID, null);
        loadSIDQuestions(employeeID, null);
        loadSRQuestions(employeeID, null);
        loadSQuestions(employeeID, null);
        loadICQuestions(employeeID, null);

        function loadDataAndPrint(employeeID) {
            Promise.all([
                loadKRAS(employeeID, null),
                loadSIDQuestions(employeeID, null),
                loadSRQuestions(employeeID, null),
                loadSQuestions(employeeID, null),
                loadICQuestions(employeeID, null)
            ]).then(function() {
                // All data has been loaded, now print
                window.print();
            }).catch(function(error) {
                // Handle error if any of the data loading fails
                console.error('Error loading data:', error);
            });
        }

        loadDataAndPrint(employeeID);
    });

    function getEmployeeInformation(employeeID) {
        return new Promise(function(resolve, reject) {
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
                        resolve();
                    } else {
                        reject('Error: Unable to retrieve employee information.');
                    }
                },
                error: function(xhr, status, error) {
                    var errorMessage = xhr.responseJSON && xhr.responseJSON.error ? xhr
                        .responseJSON.error : 'An error occurred.';
                    reject(errorMessage);
                }
            });
        });
    }

    function loadSIDQuestions(employeeID, selectedYear = null, ) {
        return new Promise(function(resolve, reject) {
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
                            $('#school-year-heading').text('School Year: ' + response.school_year.replace(/_/g,
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
                            resolve(response);
                        }
                    } else {
                        var row = $(
                            '<tr><td colspan="3"><p>-</p></td></tr>'
                        );
                        var sidTable = $('#sid_table tbody');
                        sidTable.empty();
                        sidTable.append(row);
                        reject('Error loading SID questions');
                    }
                },
                error: function(xhr, status, error) {
                    var errorMessage = xhr.responseJSON && xhr.responseJSON.error ? xhr
                        .responseJSON.error : 'An error occurred.';
                    reject(errorMessage);
                }
            });
        });
    }
    function loadSRQuestions(employeeID, selectedYear = null) {
        return new Promise(function(resolve, reject) {
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
                            resolve(response);
                        }
                    } else {
                        var row = $(
                            '<tr><td colspan="3"><p>-</p></td></tr>'
                        );
                        var srTable = $('#sr_table tbody');
                        srTable.empty();
                        srTable.append(row);
                        reject('Error loading SR questions');
                    }
                },
                error: function(xhr, status, error) {
                    var errorMessage = xhr.responseJSON && xhr.responseJSON.error ? xhr
                        .responseJSON.error : 'An error occurred.';
                    reject(errorMessage);
                }
            });
        });
    }

    function loadSQuestions(employeeID, selectedYear = null) {
        return new Promise(function(resolve, reject) {
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
                            resolve(response);
                        }
                    } else {
                        console.log('Load S Questions: Failed.');
                        var row = $(
                            '<tr><td colspan="3"><p>-</p></td></tr>'
                        );
                        var sTable = $('#s_table tbody');
                        sTable.empty();
                        sTable.append(row);
                        reject('Error loading S questions');
                    }
                },
                error: function(xhr, status, error) {
                    var errorMessage = xhr.responseJSON && xhr.responseJSON.error ? xhr
                        .responseJSON.error : 'An error occurred.';
                    reject(errorMessage);
                }
            });
        });
    }

    function loadICQuestions(employeeID, selectedYear = null) {
        return new Promise(function(resolve, reject) {
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
                            resolve(response);
                        }
                    } else {
                        var row = $(
                            '<tr><td colspan="3"><p>-</p></td></tr>'
                        );
                        var icTable = $('#ic_table tbody');
                        icTable.empty();
                        icTable.append(row);
                        reject('Error loading IC questions');
                    }
                },
                error: function(xhr, status, error) {
                    var errorMessage = xhr.responseJSON && xhr.responseJSON.error ? xhr
                        .responseJSON.error : 'An error occurred.';
                    reject(errorMessage);
                }
            });
        });
    }
    function loadKRAS(employeeID, selectedYear = null) {
        return new Promise(function(resolve, reject) {
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
                        resolve(response);
                    } else {
                        var kraTable = $('#kra_table tbody');
                        kraTable.empty();
                        kraTable.append('<tr><td colspan="6">-</td></tr>');
                        reject('Error loading KRAs');
                    }
                },
                error: function(xhr, status, error) {
                    var errorMessage = xhr.responseJSON && xhr.responseJSON.error ? xhr.responseJSON.error :
                        'An error occurred.';
                    reject(errorMessage);
                }
            });
        });
    }        
</script>

@endsection

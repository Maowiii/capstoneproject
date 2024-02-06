@extends('layout.print')

@section('title')
    <h1 id="department-heading">Department</h1>
@endsection

@section('content')
    <div class="row g-3 align-items-start mb-3">
        <h2 id="school-year-heading">School Year: -</h2>
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
            const departmentName = new URLSearchParams(window.location.search).get('department_name');
            const departmentID = new URLSearchParams(window.location.search).get('department_id');

            console.log('Department ID: ' + departmentID);

            if (departmentName) {
                $('#department-heading').text(departmentName);
            }

            function loadDataAndPrint(departmentID) {
                Promise.all([
                    loadCards(departmentID, null),
                    loadSIDQuestions(departmentID, null),
                    loadSRQuestions(departmentID, null),
                    loadSQuestions(departmentID, null),
                    loadICQuestions(departmentID, null)
                ]).then(function() {
                    // All data has been loaded, now print
                    window.print();
                }).catch(function(error) {
                    // Handle error if any of the data loading fails
                    console.error('Error loading data:', error);
                });
            }

            loadDataAndPrint(departmentID);
        });
         
        function loadCards(departmentID, selectedYear = null) {
            return new Promise(function(resolve, reject) {
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
                            $('#school-year-heading').text('School Year: ' + response.schoolYear);
                            $('#total-pe').text(response.totalPermanentEmployees);
                            $('#avg-score').text(response.avgTotalScore);
                            $('#total-appraisals').text(response.totalAppraisals);
                            resolve(); // Resolve the promise
                        } else {
                            reject('Load Cards failed.'); // Reject the promise
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error:", status, error);
                        reject('AJAX Error'); // Reject the promise
                    }
                });
            });
        }

function loadSIDQuestions(departmentID, selectedYear = null) {
    return new Promise(function(resolve, reject) {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '{{ route('ad.loadDepartmentalSIDQuestions') }}',
            type: 'GET',
            data: {
                selectedYear: selectedYear,
                departmentID: departmentID,
            },
            success: function(response) {
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
                resolve(); // Resolve the promise
            },
            error: function(xhr, status, error) {
                var errorMessage = xhr.responseJSON && xhr.responseJSON.error ? xhr
                    .responseJSON.error : 'An error occurred.';
                console.error(errorMessage);
                reject(errorMessage); // Reject the promise
            }
        });
    });
}

function loadSRQuestions(departmentID, selectedYear = null) {
    return new Promise(function(resolve, reject) {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '{{ route('ad.loadDepartmentalSRQuestions') }}',
            type: 'GET',
            data: {
                departmentID: departmentID,
                selectedYear: selectedYear,
            },
            success: function(response) {
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
                resolve(); // Resolve the promise
            },
            error: function(xhr, status, error) {
                var errorMessage = xhr.responseJSON && xhr.responseJSON.error ? xhr
                    .responseJSON.error : 'An error occurred.';
                console.error(errorMessage);
                reject(errorMessage); // Reject the promise
            }
        });
    });
}

function loadSQuestions(departmentID, selectedYear = null) {
    return new Promise(function(resolve, reject) {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '{{ route('ad.loadDepartmentalSQuestions') }}',
            type: 'GET',
            data: {
                departmentID: departmentID,
                selectedYear: selectedYear,
            },
            success: function(response) {
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
                resolve(); // Resolve the promise
            },
            error: function(xhr, status, error) {
                var errorMessage = xhr.responseJSON && xhr.responseJSON.error ? xhr
                    .responseJSON.error : 'An error occurred.';
                console.error(errorMessage);
                reject(errorMessage); // Reject the promise
            }
        });
    });
}

function loadICQuestions(departmentID, selectedYear = null) {
    return new Promise(function(resolve, reject) {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '{{ route('ad.loadDepartmentalICQuestions') }}',
            type: 'GET',
            data: {
                departmentID: departmentID,
                selectedYear: selectedYear,
            },
            success: function(response) {
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
                resolve(); // Resolve the promise
            },
            error: function(xhr, status, error) {
                var errorMessage = xhr.responseJSON && xhr.responseJSON.error ? xhr
                    .responseJSON.error : 'An error occurred.';
                console.error(errorMessage);
                reject(errorMessage); // Reject the promise
            }
        });
    });
}
    </script>
@endsection

@extends('layout.print')

@section('title')
    <h1 id="school-year-heading">School Year: -</h1>
@endsection

@section('content')

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
            var globalSelectedYear = null;

            function loadDataAndPrint(globalSelectedYear) {
                Promise.all([
                    loadCards(globalSelectedYear),
                    loadSIDQuestions(globalSelectedYear),
                    loadSRQuestions(globalSelectedYear),
                    loadICQuestions(globalSelectedYear),
                    loadSQuestions(globalSelectedYear)
                ]).then(function() {
                    // All data has been loaded, now print
                    window.print();
                }).catch(function(error) {
                    // Handle error if any of the data loading fails
                    console.error('Error loading data:', error);
                });
            }

            function loadCards(selectedYear = null) {
                return new Promise(function(resolve, reject) {
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
                                $('#total-pe').text(response.totalPermanentEmployees);
                                totalAppraisals = response.totalPermanentEmployees;

                                $('#avg-score').text(response.avgTotalScore);

                                $('#total-appraisals').text(response.totalAppraisals);
                                resolve();
                            } else {
                                reject('Load Cards failed.');
                                // console.log('Load Cards failed.');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("AJAX Error:", status, error);
                            reject('AJAX Error');
                        }
                    });
                });
            }

            function loadSIDQuestions(selectedYear = null) {
                return new Promise(function(resolve, reject) {
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
                            if (response.success) {
                                if (response.sid) {
                                    var totalAvgScore = response.total_avg_score;
                                    $('#sid-total-avg-score').text('Total Average Score: ' + totalAvgScore);
                                    $('#sid-school-year').text('School Year: ' + response.school_year.replace(/_/g, '-'));
                                    var sidTable = $('#sid_table tbody');
                                    sidTable.empty();

                                    $.each(response.sid, function(index, item) {
                                        var row = $("<tr class='text-center'>");
                                        row.append($("<td>").text(item.question_order));
                                        row.append($("<td class='text-start'>").text(item.question));

                                        var cell = $("<td>");
                                        cell.text(item.average_score); // No longer a link

                                        row.append(cell);

                                        sidTable.append(row);
                                    });
                                    resolve();
                                }
                            } else {
                                var row = $('<tr><td colspan="3"><p>-</p></td></tr>');
                                var sidTable = $('#sid_table tbody');
                                sidTable.empty();
                                sidTable.append(row);
                                reject('Load SID Questions failed.');
                            }
                        },
                        error: function(xhr, status, error) {
                            var errorMessage = xhr.responseJSON && xhr.responseJSON.error ? xhr.responseJSON.error : 'An error occurred.';
                            // console.log(errorMessage);
                            reject('AJAX Error');
                        }
                    });
                });
            }

            function loadSRQuestions(selectedYear = null) {
                return new Promise(function(resolve, reject) {
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
                            if (response.success) {
                                if (response.sr) {
                                    var totalAvgScore = response.total_avg_score;
                                    $('#sr-total-avg-score').text('Total Average Score: ' + totalAvgScore);
                                    $('#sr-school-year').text('School Year: ' + response.school_year.replace(/_/g, '-'));

                                    var srTable = $('#sr_table tbody');
                                    srTable.empty();

                                    $.each(response.sr, function(index, item) {
                                        var row = $("<tr class='text-center'>");
                                        row.append($("<td>").text(item.question_order));
                                        row.append($("<td class='text-start'>").text(item.question));

                                        var cell = $("<td>").text(item.average_score);
                                        row.append(cell);

                                        srTable.append(row);
                                    });
                                    resolve();
                                }
                            } else {
                                var row = $('<tr><td colspan="3"><p>-</p></td></tr>');
                                var srTable = $('#sr_table tbody');
                                srTable.empty();
                                srTable.append(row);
                                reject('Load SR Questions failed.');
                            }
                        },
                        error: function(xhr, status, error) {
                            var errorMessage = xhr.responseJSON && xhr.responseJSON.error ? xhr.responseJSON.error : 'An error occurred.';
                            // console.log(errorMessage);
                            reject('AJAX Error');
                        }
                    });
                });
            }

            function loadICQuestions(selectedYear = null) {
                return new Promise(function(resolve, reject) {
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
                                    var totalAvgScore = response.total_avg_score;
                                    $('#ic-total-avg-score').text('Total Average Score: ' + totalAvgScore);
                                    $('#ic-school-year').text('School Year: ' + response.school_year.replace(/_/g, '-'));

                                    var icTable = $('#ic_table tbody');
                                    icTable.empty();

                                    $.each(response.ic, function(index, item) {
                                        var row = $("<tr class='text-center'>");
                                        row.append($("<td>").text(item.question_order));
                                        row.append($("<td class='text-start'>").text(item.question));

                                        var cell = $("<td>").text(item.average_score);
                                        if (item.average_score < totalAvgScore) {
                                            cell.css('color', '#dc3545');
                                            cell.attr('title', 'This question is below the total average score.');
                                            cell.tooltip();
                                        }
                                        row.append(cell);
                                        icTable.append(row);
                                    });
                                    resolve();
                                }
                            } else {
                                var row = $('<tr><td colspan="3"><p>-</p></td></tr>');
                                var icTable = $('#ic_table tbody');
                                icTable.empty();
                                icTable.append(row);
                                reject('Load IC Questions failed.');
                            }
                        },
                        error: function(xhr, status, error) {
                            var errorMessage = xhr.responseJSON && xhr.responseJSON.error ? xhr.responseJSON.error : 'An error occurred.';
                            // console.log(errorMessage);
                            reject('AJAX Error');
                        }
                    });
                });
            }

            function loadSQuestions(selectedYear = null) {
                return new Promise(function(resolve, reject) {
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
                            if (response.success && response.s) {
                                var totalAvgScore = response.total_avg_score;
                                $('#s-total-avg-score').text('Total Average Score: ' + totalAvgScore);
                                $('#s-school-year').text('School Year: ' + response.school_year.replace(/_/g, '-'));

                                var sTable = $('#s_table tbody');
                                sTable.empty();

                                $.each(response.s, function(index, item) {
                                    var row = $("<tr class='text-center'>");
                                    row.append($("<td>").text(item.question_order));
                                    row.append($("<td class='text-start'>").text(item.question));

                                    var cell = $("<td>").text(item.average_score);
                                    if (item.average_score < totalAvgScore) {
                                        cell.css('color', '#dc3545');
                                        cell.attr('title', 'This question is below the total average score.');
                                    }
                                    row.append(cell);
                                    sTable.append(row);
                                });
                                resolve();
                            } else {
                                console.log('Load S Questions: Failed.');
                                var row = $('<tr><td colspan="3"><p>-</p></td></tr>');
                                var sTable = $('#s_table tbody');
                                sTable.empty();
                                sTable.append(row);
                                reject('Load S Questions failed.');
                            }
                        },
                        error: function(xhr, status, error) {
                            var errorMessage = xhr.responseJSON && xhr.responseJSON.error ? xhr.responseJSON.error : 'An error occurred.';
                            console.log(errorMessage);
                            reject('AJAX Error');
                        }
                    });
                });
            }

            // Trigger data loading and printing when the evaluation year changes
            (function() {
                var selectedYear = $('#evaluation-year-select').val();
                globalSelectedYear = selectedYear;
                console.log('Selected Year: ' + selectedYear);
                loadICQuestions(selectedYear);
                loadCards(selectedYear).then(function() {
                    loadDataAndPrint(globalSelectedYear);
                });

            })();
            
        });
    </script>

 @endsection
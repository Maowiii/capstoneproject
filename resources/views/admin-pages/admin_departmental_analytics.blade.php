@extends('layout.master')

@section('title')
    <h1 id="department-heading">Department</h1>
@endsection

@section('content')
    <div class='d-flex gap-3'>
        <div class="content-container text-middle" id="total-pe-container">
            <h4>Total Appraisees:</h4>
            <p>-</p>
        </div>
        <div class="content-container text-middle" id="total-appraisals-container">
            <h4>Appraisals Completed:</h4>
            <p>-</p>
        </div>
        <div class="content-container text-middle" id="avg-score-container">
            <h4>Average Total Score:</h4>
        </div>
    </div>

    <div class="content-container">
        <canvas id="lineChart" aria-label="chart" height="140" width="580"></canvas>
    </div>

    <div class="d-flex gap-3">
        <!-- Point System -->
        <div class="content-container">
            <h2>Point System:</h2>
            <h4>Oustanding Employees:</h4>
            <div class="table-wrapper">
                <table class="table table-sm" id="outstanding_table">
                    <thead>
                        <th>Name</th>
                        <th>Score</th>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="table-wrapper">
                <h4>Very Satisfactory:</h4>
                <table class="table table-sm" id="verySatisfactory_table">
                    <thead>
                        <th>Name</th>
                        <th>Score</th>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <h4>Satisfactory:</h4>
            <table class="table table-sm" id="satisfactory_table">
                <thead>
                    <th>Name</th>
                    <th>Score</th>
                </thead>
                <tbody></tbody>
            </table>
            <h4>Fair:</h4>
            <div class="table-wrapper">
                <table class="table table-sm" id="fair_table">
                    <thead>
                        <th>Name</th>
                        <th>Score</th>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <h4>Poor:</h4>
            <div class="table-wrapper">
                <table class="table table-sm" id="poor_table">
                    <thead>
                        <th>Name</th>
                        <th>Score</th>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
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
            <div class="fixed-right p-4">
                <div class="d-flex justify-content-end">
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
            fetchAndDisplayDepartmentLineChart(departmentID);
        });

        function fetchAndDisplayDepartmentLineChart(departmentID) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('ad.departmentLineChart') }}',
                type: 'POST',
                data: {
                    departmentID: departmentID
                },
                success: function(data) {
                    if (data.success) {
                        var ctx = document.getElementById('lineChart').getContext('2d');
                        var scoresByDepartment = data.scoresByDepartment;

                        var data = {
                            labels: Object.keys(scoresByDepartment),
                            datasets: [{
                            label: 'Average Scores',
                            data: Object.values(scoresByDepartment).map((scores) => scores[0] ? scores[0].total_score : 0),
                            fill: false,
                            borderColor: 'rgb(75, 192, 192)',
                            tension: 0.1,
                            }]
                        };

                        var config = {
                            type: 'line',
                            data: data,
                            options: {
                                scales: {
                                    y: {
                                    beginAtZero: true,
                                    }
                                }
                            }
                        };

                        
                        // const ctx = document.getElementById('line-chart').getContext('2d');
                        new Chart(ctx, config);

                        // var historicalData = {
                        //     labels: [], // This will set the x-axis labels
                        //     datasets: []
                        // };

                        // // var labels = [];
                        // // var datasets = [];

                        // for (var yearRange in scoresByDepartment) {
                        //     // console.log(yearRange);
                        //     if (scoresByDepartment.hasOwnProperty(yearRange)) {
                        //         // console.log(yearRange);
                        //         historicalData.labels.push(yearRange);
                        //         var data = scoresByDepartment[yearRange].map(item => parseFloat(item.total_score));

                        //         console.log(`Label: ${yearRange}, Data: ${data}`); // Add this line for debugging

                        //         historicalData.datasets.push({
                        //             label: yearRange,
                        //             data: data,
                        //             borderColor: 'rgba(75, 192, 192, 1)',
                        //             borderWidth: 2,
                        //             fill: false,
                        //         });
                        //     }
                        // }

                        // // labels.sort();
                        
                        // historicalData.labels.sort();
                        // // historicalData.datasets = datasets;
                        // // console.log('X-Axis Labels: ', historicalData.labels);

                        // // console.log(historicalData);

                        // var labels = Object.keys(scoresByDepartment).sort();

                        // var datasets = labels.map(function(label) {
                        //     var totalScores = scoresByDepartment[label] || []; // Handle null values

                        //     totalScores = totalScores.map(function(item) {
                        //         return item.total_score !== null ? item.total_score : 0;
                        //     });

                        //     return {
                        //         label: label,
                        //         data: totalScores,
                        //         borderColor: 'rgba(75, 192, 192, 1)',
                        //         borderWidth: 2,
                        //         fill: false,
                        //     };
                        // console.log(datasets);
                        // });

                        // historicalData.labels = historicalData.labels.concat(labels);
                        // historicalData.datasets = historicalData.datasets.concat(datasets);

                        // // console.log(historicalData);

                        // var lineChart = new Chart(ctx, {
                        //     type: 'line',
                        //     data: historicalData,
                        //     options: {
                        //         scales: {
                        //             x: {
                        //                 type: 'category', // Set the x-axis type to 'category'
                        //                 display: true,
                        //                 title: {
                        //                     display: true,
                        //                     text: 'Evaluation Year'
                        //                 },
                        //             },
                        //             y: {
                        //                 display: true,
                        //                 title: {
                        //                     display: true,
                        //                     text: 'Total Final Score'
                        //                 }
                        //             }
                        //         }
                        //     }
                        // });

                        // var datasetLabels = lineChart.data.datasets.map(dataset => dataset.label);
                        // var datasetData = lineChart.data.datasets.map(dataset => dataset.data);
                        // // console.log('Dataset Labels: ', datasetLabels);  
                        // // console.log('Dataset Data: ', datasetData); 
                        // console.log(lineChart.data);
                    }
                },
                error: function(xhr, status, error) {
                    // Handle any errors here
                    console.error(error);
                }
            });
        }

// console.log(scoresByDepartment);
// var labels = Object.keys(scoresByDepartment).sort();
// var datasets = labels.map(function(label) {
//     var totalScores = scoresByDepartment[label] || []; // Handle null values
//     totalScores = totalScores.map(function(item) {
//         return item.total_score !== null ? item.total_score : 0;
//     });
//     return {
//         label: label,
//         data: totalScores,
//         borderColor: 'rgba(75, 192, 192, 1)',
//         borderWidth: 2,
//         fill: false,
//     };
// });

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
                        $('#total-pe-container').html('<h4>Total Appraisees:</h4><p>' + response
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
                                            max: 6,
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
                                            max: 6,
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
                                            max: 6,
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
                                            max: 6,
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
    </script>
@endsection

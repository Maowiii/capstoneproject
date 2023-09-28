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
                @foreach ($evaluationYears as $year)
                    <option value="{{ $year->sy_start }}_{{ $year->sy_end }}"
                        @if ($year->eval_id === $activeEvalYear->eval_id) selected @endif>
                        {{ $year->sy_start }} - {{ $year->sy_end }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class='d-flex gap-3'>
        <div class="content-container text-middle" id="total-permanent-employees-container">
            <h4>Total Permanent Employees:</h4>
        </div>
        <div class="content-container text-middle" id="avg-total-score-container">
            <h4>Average Total Score:</h4>
        </div>
        <div class="content-container text-middle" id="performance-review-container"></div>
        <div class="content-container text-middle" id="evaluation-container"></div>
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
            <ul class="pagination pagination-sm justify-content-end" id="department_pagination">
                <!-- Pagination buttons will be dynamically added here -->
            </ul>
        </nav>

    </div>

    <script>
        $(document).ready(function() {
            var globalSelectedYear = null;

            $('#evaluation-year-select').change(function() {
                var selectedYear = $(this).val();
                globalSelectedYear = selectedYear;
                console.log('Selected Year: ' + selectedYear);
            });

            $('#search').on('input', function() {
                var query = $(this).val();
                loadDeparmentTable(globalSelectedYear, query);
            });

            loadDepartmentTable();
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

                        console.log(response.departments);

                        for (var i = 0; i < departments.length; i++) {
                            var department = departments[i];
                            var row = $('<tr class="align-middle">');
                            row.append($('<td>').text(i + 1));
                            row.append($('<td>').text(department.department_name));
                            row.append($('<td>').text('-')); // Keep the hyphen here

                            $('#departments_table tbody').append(row);
                        }

                        totalPage = response.departments.last_page;
                        currentPage = response.departments.current_page;
                        console.log('Total Page: ' + totalPage);
                        console.log('Current Page: ' + currentPage);
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

        function departmentPagination() {
            var departmentPagination = $('#department_pagination');

        }
    </script>
@endsection

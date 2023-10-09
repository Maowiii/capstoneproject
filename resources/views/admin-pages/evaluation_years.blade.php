@extends('layout.master')

@section('title')
    <h1>Evaluation Years</h1>
@endsection

@section('content')
    <div class="content-container">
        <table class="table table-bordered" id="evalyear_table">
            <thead>
                <tr>
                    <th class='xxs-column align-middle text-center'>#</th>
                    <th class='small-column align-middle text-center'>School Year</th>
                    <th class='medium-column align-middle text-center'>KRA Encoding Date</th>
                    <th class='medium-column align-middle text-center'>Performance Review Date</th>
                    <th class='medium-column align-middle text-center'>Evaluation Date</th>
                    <th class='small-column align middle text-center'>Status</th>
                    <th class='small-column align-middle text-center'>Action</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
        <nav id="evalyear_pagination_container">
            <ul class="pagination pagination-sm justify-content-end" id="evalyear_pagination"></ul>
        </nav>
        <div class="d-flex justify-content-end">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#startNewEvalYear">Start
                New Evaluation Year</button>
        </div>

        <div class="modal fade" id="startNewEvalYear" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">Start New Evaluation Year</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST" onsubmit="submitEvalYear(event)" id="evalYearForm">
                        @csrf
                        <div class="modal-body">
                            <p>Fill up the following information to start a new evaluation year:</p>
                            <label>
                                <h6>School Year:</h6>
                            </label>
                            <div class="row">
                                <div class="col">
                                    <label>Start Date:</label>
                                </div>
                                <div class="col">
                                    <label>End Date:</label>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <select class='form-control' name="sy_start" id="sy_start" onchange="updateEndYear()">
                                        <option value="">--Select School Year--</option>
                                        <?php
                                        $currentYear = now()->year;
                                        $startYear = $currentYear - 2;
                                        ?>
                                        @for ($year = $startYear; $year <= 2099; $year++)
                                            <option value="{{ $year }}"
                                                @if (old('sy_end') == $year) selected @endif>{{ $year }}
                                            </option>
                                        @endfor

                                    </select>
                                </div>
                                <div class="col">
                                    <input class='form-control' type='number' name="sy_end" id="sy_end"
                                        value="{{ old('sy_end') }}" readonly>
                                </div>
                                <span class="text-danger error-message" id="sy_start_error">
                                    @error('sy_start')
                                        {{ $message }}
                                    @enderror
                                </span>
                                <span class="text-danger error-message" id="sy_end_error">
                                    @error('sy_end')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>

                            <label>
                                <h6>KRA Encoding:</h6>
                            </label>
                            <div class="row">
                                <div class="col">
                                    <label>Start Date:</label>
                                </div>
                                <div class="col">
                                    <label>End Date:</label>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <input class='form-control' type='date' placeholder="KRA starting date"
                                        name="kra_start" id="kra_start" onchange="updateEndDate('kra', true)"
                                        value="{{ old('kra_start') }}">
                                </div>
                                <div class="col">
                                    <input class='form-control' type='date' placeholder="KRA ending date" name="kra_end"
                                        id="kra_end" onchange="updateEndDate('kra')" value="{{ old('kra_end') }}"
                                        disabled>
                                </div>
                                <span class="text-danger error-message" id="kra_start_error">
                                    @error('kra_start')
                                        {{ $message }}
                                    @enderror
                                </span>
                                <span class="text-danger error-message" id="kra_end_error">
                                    @error('kra_end')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>

                            <label>
                                <h6>Performance Review:</h6>
                            </label>
                            <div class="row">
                                <div class="col">
                                    <label>Start Date:</label>
                                </div>
                                <div class="col">
                                    <label>End Date:</label>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <input class='form-control' type='date'
                                        placeholder="Performance review starting date" name="pr_start" id="pr_start"
                                        onchange="updateEndDate('pr', true)" value="{{ old('pr_start') }}" disabled>
                                </div>
                                <div class="col">
                                    <input class='form-control' type='date' placeholder="Performance review ending date"
                                        name="pr_end" id="pr_end" onchange="updateEndDate('pr')"
                                        value="{{ old('pr_end') }}" disabled>
                                </div>
                                <span class="text-danger error-message" id="pr_start_error">
                                    @error('pr_start')
                                        {{ $message }}
                                    @enderror
                                </span>
                                <span class="text-danger error-message" id="pr_end_error">
                                    @error('pr_end')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>

                            <label>
                                <h6>Evaluation:</h6>
                            </label>
                            <div class="row">
                                <div class="col">
                                    <label>Start Date:</label>
                                </div>
                                <div class="col">
                                    <label>End Date:</label>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <input class='form-control' type='date' placeholder="Evaluation starting date"
                                        id="eval_start" name="eval_start" onchange="updateEndDate('eval', true)"
                                        value="{{ old('eval_start') }}" disabled>
                                </div>
                                <div class="col">
                                    <input class='form-control' type='date' placeholder="Evaluation ending date"
                                        id="eval_end" name="eval_end" onchange="updateEndDate('eval')"
                                        value="{{ old('eval_end') }}" disabled>
                                </div>
                                <span class="text-danger error-message" id="eval_start_error">
                                    @error('eval_start')
                                        {{ $message }}
                                    @enderror
                                </span>
                                <span class="text-danger error-message" id="eval_end_error">
                                    @error('eval_end')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                        </div>
                        <div class="alert alert-warning mx-3 d-none" role="alert" id="confirmationAlert">
                            <i class='bx bx-info-circle'></i> Please double-check the inputted values before submitting.
                            Once submitted, they cannot be changed.
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" id="backbtn"
                                onclick="backButton()">Back</button>
                            <button type="submit" class="btn btn-primary" id="submitbtn">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="deleteConfirmationModal" data-bs-backdrop="static" data-bs-keyboard="false"
            tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Confirmation</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Understood</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const sy_start_value = parseInt(document.getElementById('sy_start').value);
        const sy_end = document.getElementById('sy_end');
        sy_end.value = sy_start_value + 1;

        function formatDate(dateString) {
            const date = new Date(dateString);
            const options = {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };
            return date.toLocaleDateString('en-US', options);
        }

        function updateEndYear() {
            const startYear = parseInt(document.getElementById('sy_start').value);
            const endYearInput = document.getElementById('sy_end');

            const endYear = startYear + 1;
            endYearInput.value = endYear;
        }

        function updateEndDate(type, updateStart = false) {
            if (type === 'kra') {
                if (updateStart) {
                    $('#kra_start').removeClass('is-invalid');
                    $('#kra_start_error').hide();
                    const kraStartDate = new Date($('#kra_start').val());
                    const kraEndInput = $('#kra_end');
                    const minEndDate = new Date(kraStartDate);
                    minEndDate.setDate(kraStartDate.getDate() + 3);
                    const kraMinEndDate = minEndDate.toISOString().split('T')[0];
                    kraEndInput.prop('min', kraMinEndDate);
                    kraEndInput.prop('disabled', false);
                } else {
                    $('#kra_end').removeClass('is-invalid');
                    $('#kra_end_error').hide();
                    const kraEndDate = new Date($('#kra_end').val());
                    const prStartInput = $('#pr_start');
                    const minStartDate = new Date(kraEndDate);
                    minStartDate.setDate(kraEndDate.getDate() + 1);
                    const prMinStartDate = minStartDate.toISOString().split('T')[0];
                    prStartInput.prop('min', prMinStartDate);
                    prStartInput.prop('disabled', false);
                }
            } else if (type === 'pr') {
                if (updateStart) {
                    $('#pr_start').removeClass('is-invalid');
                    $('#pr_start_error').hide();
                    const prStartDate = new Date($('#pr_start').val());
                    const prEndInput = $('#pr_end');
                    const minEndDate = new Date(prStartDate);
                    minEndDate.setDate(prStartDate.getDate() + 3);
                    const prMinEndDate = minEndDate.toISOString().split('T')[0];
                    prEndInput.prop('min', prMinEndDate);
                    prEndInput.prop('disabled', false);
                } else {
                    $('#pr_end').removeClass('is-invalid');
                    $('#pr_end_error').hide();
                    const prEndDate = new Date($('#pr_end').val());
                    const evalStartInput = $('#eval_start');
                    const minStartDate = new Date(prEndDate);
                    minStartDate.setDate(prEndDate.getDate() + 1);
                    const evalMinStartDate = minStartDate.toISOString().split('T')[0];
                    evalStartInput.prop('min', evalMinStartDate);
                    evalStartInput.prop('disabled', false);
                }
            } else {
                if (updateStart) {
                    $('#eval_start').removeClass('is-invalid');
                    $('#eval_start_error').hide();
                    const evalStartDate = new Date($('#eval_start').val());
                    const evalEndInput = $('#eval_end');
                    const minEndDate = new Date(evalStartDate);
                    minEndDate.setDate(evalStartDate.getDate() + 3);
                    const evalMinEndDate = minEndDate.toISOString().split('T')[0];
                    evalEndInput.prop('min', evalMinEndDate);
                    evalEndInput.prop('disabled', false);
                } else {
                    $('#eval_end').removeClass('is-invalid');
                    $('#eval_end_error').hide();
                }
            }
        }

        function loadEvaluationYearTable(page = 1) {
            $.ajax({
                url: '/evaluation-year/displayEvaluationYear',
                type: 'GET',
                data: {
                    page: page // Pass the current page number
                },
                success: function(response) {
                    if (response.success) {
                        var tbody = $('#evalyear_table tbody');
                        tbody.empty();

                        console.log(response.evalyears);

                        $.each(response.evalyears.data, function(index, evalyear) {
                            var row = $('<tr>');
                            row.append($('<td>').addClass('align-middle').text(evalyear.eval_id));
                            row.append($('<td>').addClass('align-middle').text(evalyear.sy_start +
                                ' - ' + evalyear.sy_end));
                            row.append($('<td>').addClass('align-middle').text(formatDate(evalyear
                                .kra_start) + ' - ' + formatDate(evalyear.kra_end)));
                            row.append($('<td>').addClass('align-middle').text(formatDate(evalyear
                                .pr_start) + ' - ' + formatDate(evalyear.pr_end)));
                            row.append($('<td>').addClass('align-middle').text(formatDate(evalyear
                                .eval_start) + ' - ' + formatDate(evalyear.eval_end)));
                            row.append($('<td>').addClass('align-middle').text(evalyear.status));

                            var eval_id = evalyear.eval_id;
                            var school_year = evalyear.sy_start + ' - ' + evalyear.sy_end;
                            console.log(school_year);
                            var buttonGroup = $('<div>').addClass('btn-group').attr('role', 'group');

                            if (evalyear.status == 'inactive') {
                                buttonGroup.append($('<button>').addClass(
                                        'btn btn-outline-primary toggle-status-btn')
                                    .data('eval-id', eval_id)
                                    .text(
                                        'Activate'));
                            } else {
                                buttonGroup.append($('<button>').addClass(
                                        'btn btn-outline-primary toggle-status-btn')
                                    .data('eval-id', eval_id)
                                    .text(
                                        'Deactivate'));
                            }

                            buttonGroup.append($('<button>').addClass(
                                    'btn btn-outline-danger delete-eval-yr-btn')
                                .data('eval-id', eval_id)
                                .data('school-year', school_year)
                                .text('Delete'));
                            row.append($('<td>').addClass('align-middle').append(buttonGroup));

                            tbody.append(row);
                        });


                        var totalPage = response.evalyears.last_page;
                        var currentPage = response.evalyears.current_page;
                        $('#evalyear_pagination').empty();

                        for (var pageCounter = 1; pageCounter <= totalPage; pageCounter++) {
                            (function(pageCounter) {
                                var pageItem = $('<li>').addClass('page-item');
                                if (pageCounter === currentPage) {
                                    pageItem.addClass('active');
                                }
                                var pageButton = $('<button>').addClass('page-link').text(pageCounter);
                                pageButton.click(function() {
                                    loadEvaluationYearTable(pageCounter);
                                });
                                pageItem.append(pageButton);
                                $('#evalyear_pagination').append(pageItem);
                            })(pageCounter);
                        }


                    } else {
                        console.log(response.error);
                    }
                },
                error: function(xhr, status, error) {
                    console.log(error);
                }
            });
        }

        function submitEvalYear(event) {
            event.preventDefault();
            var formData = $('#evalYearForm').serialize();

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('addEvalYear') }}',
                type: 'POST',
                data: formData,
                success: function(response) {
                    console.log('AJAX Success Response:', response);

                    if (response.success) {
                        $('#evalYearForm input, #evalYearForm select').addClass('is-grayed').prop('readonly',
                            true);
                        console.log('success');
                        $('#submitbtn').text('Confirm');
                        $('#confirmationAlert').removeClass('d-none');
                        $('#backbtn').show();

                        $('#evalYearForm').off('submit').on('submit', function(confirmEvent) {
                            confirmEvalYear(confirmEvent, formData);
                        });
                    } else {
                        console.log('fail');
                        $('.text-danger').hide();
                        $('.error-message').text('');

                        if (response.errors) {
                            $.each(response.errors, function(field, messages) {
                                $('#' + field + '_error').show().text(messages[0]);
                                $('#' + field).addClass('is-invalid');
                            });
                        }
                    }
                },
                error: function(xhr) {
                    if (xhr.responseText) {
                        alert('Error: ' + xhr.responseText);
                    } else {
                        alert('An error occurred.');
                    }
                }
            });
        }

        function backButton() {
            $('#evalYearForm input, #evalYearForm select').removeClass('is-grayed').prop('readonly', false);
            $('#evalYearForm').off('submit').on('submit', function(event) {
                submitEvalYear(event);
            });
            $('#confirmationAlert').addClass('d-none');
            $('backbtn').hide();
            $('#submitbtn').text('Submit');
        }

        function confirmEvalYear(event, formData) {
            event.preventDefault();
            $('#submitbtn').prop('disabled', true);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('confirmEvalYear') }}',
                type: 'POST',
                data: formData,
                success: function(response) {
                    console.log('AJAX Confirm Response:', response);

                    if (response.success) {
                        location.reload();
                    } else {}
                },
                error: function(xhr) {
                    if (xhr.responseText) {
                        alert('Error: ' + xhr.responseText);
                    } else {
                        alert('An error occurred.');
                    }
                }
            });
        }

        function toggleEvalYearStatus(eval_id) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('ad.toggleEvalYearStatus') }}',
                type: 'POST',
                data: {
                    eval_id: eval_id
                },
                success: function(response) {
                    if (response.success) {
                        loadEvaluationYearTable();
                        console.log('Successfully toggled');
                    } else {
                        console.log('Error: ' + reponse.error);
                    }
                },
                error: function(xhr) {
                    if (xhr.responseText) {
                        alert('Error: ' + xhr.responseText);
                    } else {
                        alert('An error occurred.');
                    }
                }
            });
        }

        $(document).ready(function() {
            const currentDate = new Date().toISOString().split('T')[0];
            //$('#kra_start').prop('min', currentDate);
            $('#backbtn').hide();
            loadEvaluationYearTable();
        });

        $(document).on('click', '.toggle-status-btn', function() {
            var evalId = $(this).data('eval-id');
            toggleEvalYearStatus(evalId);
        });

        $(document).on('click', '.delete-eval-yr-btn', function() {
            var evalId = $(this).data('eval-id');
            var schoolYear = $(this).data('school-year');
            $('#deleteConfirmationModal').modal('show');
            var message = $('<p>').text('Are you sure you want to delete all of the evaluations of school year ' +
                schoolYear + '?');

            var alertDiv = $('<div>').addClass('alert alert-warning mx-0 my-0').attr('role', 'alert').attr('id',
                'confirmationAlert');
            alertDiv.html(
                '<i class="bx bx-info-circle"></i>This cannot be undone.'
            );

            $('#deleteConfirmationModal .modal-body').empty().append(message, alertDiv);
        });
    </script>

    @if (count($errors) > 0)
        <script>
            $(document).ready(function() {
                $('#startNewEvalYear').modal('show');

                $("input[type='date'], select").each(function() {
                    if ($(this).val() !== '') {
                        $(this).prop("disabled", false);
                    }
                });

                const kra_start = $('#kra_start');
                const kra_end = $('#kra_end');
                const pr_start = $('#pr_start');
                const pr_end = $('#pr_end');
                const eval_start = $('#eval_start');
                const eval_end = $('#eval_end');

                //const currentDate = new Date().toISOString().split('T')[0];
                //kra_start.prop('min', currentDate);

                if (kra_start.val()) {
                    const kraStartDate = new Date($('#kra_start').val());
                    const minEndDate = new Date(kraStartDate);
                    minEndDate.setDate(kraStartDate.getDate() + 3);
                    const kraMinEndDate = minEndDate.toISOString().split('T')[0];
                    kra_end.prop('min', kraMinEndDate);
                    kra_end.prop('disabled', false);
                }

                if (kra_end.val()) {
                    pr_start.prop('disabled', false);
                    const kraEndDate = new Date($('#kra_end').val());
                    const prStartInput = $('#pr_start');
                    const minStartDate = new Date(kraEndDate);
                    minStartDate.setDate(kraEndDate.getDate() + 1);
                    const prMinStartDate = minStartDate.toISOString().split('T')[0];
                    pr_start.prop('min', prMinStartDate);
                }

                if (pr_start.val()) {
                    const prStartDate = new Date($('#pr_start').val());
                    const prEndInput = $('#pr_end');
                    const minEndDate = new Date(prStartDate);
                    minEndDate.setDate(prStartDate.getDate() + 3);
                    const prMinEndDate = minEndDate.toISOString().split('T')[0];
                    pr_end.prop('min', prMinEndDate);
                    pr_end.prop('disabled', false);
                }

                if (pr_end.val()) {
                    const prEndDate = new Date($('#pr_end').val());
                    const evalStartInput = $('#eval_start');
                    const minStartDate = new Date(prEndDate);
                    minStartDate.setDate(prEndDate.getDate() + 1);
                    const evalMinStartDate = minStartDate.toISOString().split('T')[0];
                    eval_start.prop('min', evalMinStartDate);
                    eval_start.prop('disabled', false);
                }

                if (eval_start.val()) {
                    const evalStartDate = new Date($('#eval_start').val());
                    const evalEndInput = $('#eval_end');
                    const minEndDate = new Date(evalStartDate);
                    minEndDate.setDate(evalStartDate.getDate() + 3);
                    const evalMinEndDate = minEndDate.toISOString().split('T')[0];
                    eval_end.prop('min', evalMinEndDate);
                    eval_end.prop('disabled', false);
                }
            });
        </script>
    @endif
@endsection

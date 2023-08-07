@extends('layout.master')

@section('title')
    <h1>Evaluation Years</h1>
@endsection

@section('content')
    <div class="content-container">
        <table class="table table-bordered" id="evalyear_table">
            <thead>
                <tr>
                    <th class='small-column align-middle text-center'>#</th>
                    <th class='medium-column align-middle text-center'>School Year</th>
                    <th class='medium-column align-middle text-center'>KRA Encoding Date</th>
                    <th class='medium-column align-middle text-center'>Performace Review Date</th>
                    <th class='medium-column align-middle text-center'>Employee Review Date</th>
                    <th class='small-column align middle text-center'>Status</th>
                    <th class='small-column align-middle text-center'>Action</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
        <div class="d-flex justify-content-end">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#startNewEvalYear">Start
                New Evaluation Year</button>
        </div>

        <!-- New Eval Year Modal -->
        <div class="modal fade" id="startNewEvalYear" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">Start New Evaluation Year</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST" action="{{ route('check-eval-year') }}" id="evalYearForm">
                        @csrf
                        <div class="modal-body">
                            <p>Fill up the following information to start a new evaluation year:</p>
                            <?php $currentYear = now()->format('Y'); ?>
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
                                    <select class='form-control @error('sy_start') is-invalid @enderror' name="sy_start"
                                        id="sy_start" onchange="updateEndYear()">
                                        <?php $currentYear = now()->format('Y'); ?>
                                        @for ($year = $currentYear; $year <= 2099; $year++)
                                            <option value="{{ $year }}"
                                                @if (old('sy_end') == $year) selected @endif>{{ $year }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col">
                                    <input class='form-control @error('sy_end') is-invalid @enderror' type='number'
                                        name="sy_end" id="sy_end" value="{{ old('sy_end') }}" readonly>
                                </div>
                                <span class="text-danger">
                                    @error('sy_start')
                                        {{ $message }}
                                    @enderror
                                </span>
                                <span class="text-danger">
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
                                    <input class='form-control @error('kra_start') is-invalid @enderror' type='date'
                                        placeholder="KRA starting date" name="kra_start" id="kra_start"
                                        onchange="updateEndDate('kra', true)" value="{{ old('kra_start') }}">
                                </div>
                                <div class="col">
                                    <input class='form-control @error('kra_start') is-invalid @enderror' type='date'
                                        placeholder="KRA ending date" name="kra_end" id="kra_end"
                                        onchange="updateEndDate('kra')" value="{{ old('kra_end') }}">
                                </div>
                                <span class="text-danger">
                                    @error('kra_start')
                                        {{ $message }}
                                    @enderror
                                </span>
                                <span class="text-danger">
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
                                    <input class='form-control @error('pr_start') is-invalid @enderror' type='date'
                                        placeholder="Performance review starting date" name="pr_start" id="pr_start"
                                        onchange="updateEndDate('pr', true)" value="{{ old('pr_start') }}">
                                </div>
                                <div class="col">
                                    <input class='form-control @error('pr_end') is-invalid @enderror' type='date'
                                        placeholder="Performance review ending date" name="pr_end" id="pr_end"
                                        onchange="updateEndDate('pr')" value="{{ old('pr_end') }}">
                                </div>
                                <span class="text-danger">
                                    @error('pr_start')
                                        {{ $message }}
                                    @enderror
                                </span>
                                <span class="text-danger">
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
                                    <input class='form-control @error('eval_start') is-invalid @enderror' type='date'
                                        placeholder="Evaluation starting date" id="eval_start" name="eval_start"
                                        onchange="updateEndDate('eval', true)" value="{{ old('eval_start') }}">
                                </div>
                                <div class="col">
                                    <input class='form-control @error('eval_end') is-invalid @enderror' type='date'
                                        placeholder="Evaluation ending date" id="eval_end" name="eval_end"
                                        onchange="updateEndDate('eval')" value="{{ old('eval_end') }}">
                                </div>
                                <span class="text-danger">
                                    @error('eval_start')
                                        {{ $message }}
                                    @enderror
                                </span>
                                <span class="text-danger">
                                    @error('eval_end')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="submitbtn">Submit</button>
                        </div>
                    </form>
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
                    const kraStartDate = new Date(document.getElementById('kra_start').value);
                    const kraEndInput = document.getElementById('kra_end');
                    const minEndDate = new Date(kraStartDate);
                    minEndDate.setDate(kraStartDate.getDate() + 1);
                    const kraMinEndDate = minEndDate.toISOString().split('T')[0];
                    kraEndInput.min = kraMinEndDate;
                } else {
                    const kraEndDate = new Date(document.getElementById('kra_end').value);
                    const prStartInput = document.getElementById('pr_start');
                    const minStartDate = new Date(kraEndDate);
                    minStartDate.setDate(kraEndDate.getDate() + 1);
                    const prMinStartDate = minStartDate.toISOString().split('T')[0];
                    prStartInput.min = prMinStartDate;
                }
            } else if (type === 'pr') {
                if (updateStart) {
                    const prStartDate = new Date(document.getElementById('pr_start').value);
                    const prEndInput = document.getElementById('pr_end');
                    const minEndDate = new Date(prStartDate);
                    minEndDate.setDate(prStartDate.getDate() + 1);
                    const prMinEndDate = minEndDate.toISOString().split('T')[0];
                    prEndInput.min = prMinEndDate;
                } else {
                    const prEndDate = new Date(document.getElementById('pr_end').value);
                    const evalStartInput = document.getElementById('eval_start');
                    const minStartDate = new Date(prEndDate);
                    minStartDate.setDate(prEndDate.getDate() + 1);
                    const evalMinStartDate = minStartDate.toISOString().split('T')[0];
                    evalStartInput.min = evalMinStartDate;
                }
            } else {
                if (updateStart) {
                    const evalStartDate = new Date(document.getElementById('eval_start').value);
                    const evalEndInput = document.getElementById('eval_end');
                    const minEndDate = new Date(evalStartDate);
                    minEndDate.setDate(evalStartDate.getDate() + 1);
                    const evalMinEndDate = minEndDate.toISOString().split('T')[0];
                    evalEndInput.min = evalMinEndDate;
                }
            }
        }

        function handleSubmitForm(event) {
            event.preventDefault();

            // Serialize form data
            const formData = new FormData(document.getElementById('evalYearForm'));

            // Submit form data using AJAX
            $.ajax({
                url: '{{ route('check-eval-year') }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                success: function(response) {
                    if (response.success) {
                        $('#startNewEvalYear').modal('hide');
                    } else {
                        $('#startNewEvalYear').modal('show');
                        $('#startNewEvalYear .modal-content').scrollTop(0);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error occurred:', error);
                }
            });
        }

        $(document).ready(function() {
            function loadEvaluationYearTable() {
                $.ajax({
                    url: '/evaluation-year/displayEvaluationYear',
                    type: 'GET',
                    success: function(response) {
                        if (response.success) {
                            var tbody = $('#evalyear_table tbody');
                            tbody.empty();

                            $.each(response.evalyears, function(index, evalyear) {
                                var row = '<tr>' +
                                    '<td class="align-middle">' + evalyear.eval_id +
                                    '</td>' +
                                    '<td class="align-middle">' + evalyear.sy_start +
                                    ' - ' +
                                    evalyear.sy_end +
                                    '</td>' +
                                    '<td class="align-middle">' + formatDate(evalyear
                                        .kra_start) + ' - ' + formatDate(evalyear.kra_end) +
                                    '</td>' +
                                    '<td class="align-middle">' + formatDate(evalyear
                                        .pr_start) + ' - ' + formatDate(evalyear.pr_end) +
                                    '</td>' +
                                    '<td class="align-middle">' + formatDate(evalyear
                                        .eval_start) + ' - ' + formatDate(evalyear
                                        .eval_end) + '</td>' +
                                    '<td class="align-middle">' + evalyear.status +
                                    '<td class="align-middle">' +
                                    '<div class="btn-group" role="group" aria-label="Basic example">' +
                                    '<button type="button" class="btn btn-outline-info">Load</button>' +
                                    '<button type="button" class="btn btn-outline-primary">View</button>' +
                                    '<button type="button" class="btn btn-outline-danger">Delete</button></td></div>' +
                                    '</tr>';
                                tbody.append(row);
                            });
                        } else {
                            console.log(response.error);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                });
            }

            loadEvaluationYearTable();
            document.getElementById('evalYearForm').addEventListener('submit', handleSubmitForm);
        });
    </script>
@endsection

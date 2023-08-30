@extends('layout.master')

@section('title')
    <h1>Dashboard</h1>
@endsection

@section('content')
    @if ($first_login == 'true')
        <script>
            $(document).ready(function() {
                $('#firstLoginModal').modal('show');
            });
        </script>
    @endif

    <div class="content-container">
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
            Launch static backdrop modal
        </button>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="firstLoginModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Welcome to HRMDO!</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div>
                        <p>You must first choose your immediate superior and input your job title.</p>
                        <div>
                            <label>Immediate Superior</label>
                            <select class="form-control" name="department" id="department">
                                <option value="" selected>Select Immediate Superior</option>
                                @foreach ($IS as $is)
                                    <option value="{{ $is->account_id }}">
                                        {{ $is->employee->first_name }} {{ $is->employee->last_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mt-3">
                            <label>Your Job Title:</label>
                            <input type="text" class="form-control" value="{{ old('job_title') }}" id="job_title"
                                placeholder="Job Title">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="submit-btn">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $('#job_title').change(function() {
            $('#job_title').removeClass('is-invalid');
        });

        $('#department').change(function() {
            $('#department').removeClass('is-invalid');
        });

        $('#submit-btn').click(function() {
            submitPEFirstLogin();
        });

        function submitPEFirstLogin() {
            var department = $('#department').val();
            var job_title = $('#job_title').val();

            if (job_title.trim() === '') {
                $('#job_title').addClass('is-invalid');
                return;
            } else if (department.trim() === '') {
                $('#department').addClass('is-invalid');
            } else {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{ route('pe.submitFirstLogin') }}',
                    type: 'POST',
                    data: {
                        job_title: job_title,
                    },
                    success: function(response) {
                        if (response.success) {
                            console.log('Job Title Submitted');
                            $('#firstLoginModal').modal('hide');
                        } else {

                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                });
            }
        }
    </script>
@endsection

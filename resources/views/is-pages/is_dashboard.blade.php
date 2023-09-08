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

    <div class="modal fade" id="firstLoginModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Welcome to HRMDO!</h1>
                </div>
                <div class="modal-body">
                    <div>
                        <p>Please enter your following details:</p>
                        <label>Immediate Superior Position:</label>
                        <input type="text" class="form-control" value="{{ old('position') }}" id="position">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="submit-btn">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <div class="content-container">
      <!-- Content -->
    </div>

    <script>
        $('#position').change(function() {
            $('#position').removeClass('is-invalid');
        });

        $('#submit-btn').click(function() {
            submitISPosition();
        });

        function submitISPosition() {
            var position = $('#position').val();

            if (position.trim() === '') {
                $('#position').addClass('is-invalid');
                return;
            } else {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{ route('is.submitISPosition') }}',
                    type: 'POST',
                    data: {
                        position: position,
                    },
                    success: function(response) {
                        if (response.success) {
                            console.log('Position Submitted');
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

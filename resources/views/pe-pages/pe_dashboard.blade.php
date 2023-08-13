@extends('layout.master')

@section('title')
<h1>Dashboard</h1>
@endsection

@section('content')
<div class="content-container">



<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
    Launch static backdrop modal
</button>
</div>

<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
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
                        <select class="form-control" name="department">
                            <option value="" selected>Select Immediate Superior</option>
                            @foreach ($IS as $is)
                            <option value="{{$is->account_id}}">
                                {{$is->employee->first_name}} {{$is->employee->last_name}}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <span class="text-danger">
                        @error('email')
                        {{ $message }}
                        @enderror
                    </span>
                    
                    <div>
                        <label>Immediate Superior</label>
                        <input type="email" class="form-control" value="{{ old('email') }}" name="email">
                    </div>
                    <span class="text-danger">
                        @error('email')
                        {{ $message }}
                        @enderror
                    </span>


                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Understood</button>
            </div>
        </div>
    </div>
</div>
@endsection
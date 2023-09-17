@extends('layout.master')

@section('title')
<h1>Account Settings</h1>
@endsection

@section('content')
<div class="content-container">
    <h3>Basic Information</h3>
    <div class="row g-3 align-items-center mb-3">
            <div class="col-1">
                    <label for="fullName"> Full Name</label>
                </div>
                <div class="col-4">
                <input type="text" class="form-control" value="" readonly>
            </div>
        </div>
        <div class="row g-3 align-items-center mb-3">
            <div class="col-1">
                    <label for="emailAddress">Adamson Mail</label>
                </div>
                <div class="col-4">
                <input type="text" class="form-control" value="" readonly>
            </div>
        </div>
        <div class="row g-3 align-items-center mb-3">
            <div class="col-1">
                    <label for="password">Password  </label>
                </div>
                <div class="col-2">
                    <input type="text" class="form-control" value="" readonly>
                </div>
                <div class="col">
                    <button type="button" class="btn btn-primary">Change Password</button>
                </div>
        </div>
    </div>
</div>
@endsection

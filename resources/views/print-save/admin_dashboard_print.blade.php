@extends('layout.master')

@section('title')
    <h1>Overall Dashboard</h1>
@endsection

@section('content')
    <div class="row g-3 align-items-start mb-3">
        <h2 id="school-year-heading">School Year: -</h2>
    </div>

    <!-- BEHAVIORAL COMPETENCIES -->
    <!-- Sustained Integral Development -->
    <div class="row">
        <div class="col-lg-6 mb-3">
            <div class="content-container h-100">
                <h2 class="text-center">Behavioral Competencies:</h2>
                <h4 class="text-center">Search for Excellence and Sustained Integral Development:</h4>
                <h5 class="text-center" id="sid-school-year">School Year:</h5>
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
        <div class="col-lg-6 mb-3">
            <div class="content-container h-100">
                <h4 class="text-center">Spirit of St. Vincent de Paul and Social Responsibility:</h4>
                <h5 class="text-center" id="sr-school-year">School Year:</h5>
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
        <div class="col-lg-6 mb-3">
            <div class="content-container h-100">
                <h4 class="text-center">Solidarity</h4>
                <h5 class="text-center" id="s-school-year">School Year:</h5>
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
        <div class="col-lg-6 mb-3">
            <div class="content-container h-100">
                <h2 class="text-center">Internal Customers:</h2>
                <h5 class="text-center" id="ic-school-year">School Year:</h5>
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
        
    </script>
@endsection
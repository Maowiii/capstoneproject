@extends('layout.master')

@section('title')
    <h1>Request Overview</h1>
@endsection

@section('content')
<div class="content-container">
    <div class="table-responsive">
        <table class='table table-bordered' id="request_table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Request Note</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="request_body">
                
            </tbody>
        </table>
    </div>
</div>
<script>

</script>
@endsection
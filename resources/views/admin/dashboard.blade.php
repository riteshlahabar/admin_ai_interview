@extends('admin.layouts.app')
@section('title','Dashboard')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-label">Total Interview Sessions</div>
            <div class="stat-value">{{ $interviews }}</div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-label">Today Interviews</div>
            <div class="stat-value">{{ $todayInterviews }}</div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-label">Top Topic</div>
            <div class="stat-value" style="font-size:20px">{{ $topTopic }}</div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-label">Top Role</div>
            <div class="stat-value" style="font-size:20px">{{ $topRole }}</div>
        </div>
    </div>
</div>

<div class="row justify-content-center mt-2">
    <div class="col-md-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-label">Total Students</div>
            <div class="stat-value">{{ $students }}</div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-label">Question Bank</div>
            <div class="stat-value">{{ $questions }}</div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-label">Teacher Answers</div>
            <div class="stat-value">{{ $answers }}</div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-label">Avg Answer Length</div>
            <div class="stat-value">{{ $avgLen }}</div>
        </div>
    </div>
</div>

<div class="card mt-3">
    <div class="card-body">
        <h5 class="card-title mb-2">Admin Notes</h5>
        <p class="text-muted mb-1">This dashboard is now aligned to AI interview operations.</p>
        <ul class="mb-0 text-muted">
            <li>Manage interview categories and question bank from the left menu.</li>
            <li>Track interview activity and top practice areas from the metrics cards.</li>
            <li>Review and improve answer quality using Questions and Answers modules.</li>
        </ul>
    </div>
</div>
@endsection


@extends('admin.layouts.app')
@section('title','Dashboard')
@section('content')
<div class="card">
  <div style="display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:10px">
    <div class="card"><div>Total Students</div><div style="font-size:28px;font-weight:800">{{ $students }}</div></div>
    <div class="card"><div>Total Questions</div><div style="font-size:28px;font-weight:800">{{ $questions }}</div></div>
    <div class="card"><div>Total Answers</div><div style="font-size:28px;font-weight:800">{{ $answers }}</div></div>
    <div class="card"><div>Avg Answer Length</div><div style="font-size:28px;font-weight:800">{{ $avgLen }}</div></div>
  </div>
</div>
@endsection

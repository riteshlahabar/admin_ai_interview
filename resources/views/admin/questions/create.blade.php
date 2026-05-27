@extends('admin.layouts.app')
@section('title','New Question')
@section('content')
<form class="card" method="POST" action="{{ route('admin.questions.store') }}">@csrf
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
    <div>
      <label>Teacher</label>
      <select class="input" name="teacher_id" required>
        @foreach($teachers as $t)<option value="{{ $t->id }}">{{ $t->name }}</option>@endforeach
      </select>
    </div>
    <div>
      <label>Category</label>
      <select class="input" name="category_id" required>
        @foreach($categories as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach
      </select>
    </div>
    <div>
      <label>Difficulty</label>
      <select class="input" name="difficulty">
        <option>Junior</option><option selected>Mid</option><option>Senior</option>
      </select>
    </div>
    <div>
      <label>Active</label>
      <input type="checkbox" name="is_active" value="1" checked>
    </div>
    <div style="grid-column:1/-1">
      <label>Question</label>
      <textarea class="input" name="question" rows="6" required></textarea>
    </div>
  </div>
  <div style="margin-top:10px"><button class="btn">Save</button></div>
</form>
@endsection

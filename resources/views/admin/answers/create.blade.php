@extends('admin.layouts.app')
@section('title','New Answer')

@section('content')
  @if ($errors->any())
    <div class="card" style="border-color:#ef4444">❌ {{ $errors->first() }}</div>
  @endif

  <form class="card" method="POST" action="{{ route('admin.answers.store') }}">
    @csrf
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
      <div>
        <label>Question</label>
        <select class="input" name="question_id" required>
          @foreach($questions as $q)
            <option value="{{ $q->id }}">{{ \Illuminate\Support\Str::limit($q->question, 80) }}</option>
          @endforeach
        </select>
      </div>
      <div>
        <label>Teacher</label>
        <select class="input" name="teacher_id" required>
          @foreach($teachers as $t)
            <option value="{{ $t->id }}">{{ $t->name }}</option>
          @endforeach
        </select>
      </div>
      <div style="grid-column:1 / -1">
        <label>Answer</label>
        <textarea class="input" name="answer" rows="10" required>{{ old('answer') }}</textarea>
      </div>
      <div style="display:flex;align-items:center;gap:8px">
        <input type="checkbox" id="is_approved" name="is_approved" value="1" @checked(old('is_approved'))>
        <label for="is_approved" style="margin:0">Approved</label>
      </div>
    </div>
    <div style="margin-top:10px;display:flex;gap:10px">
      <button class="btn" type="submit">Save</button>
      <a class="btn ghost" href="{{ route('admin.answers.index') }}">Cancel</a>
    </div>
  </form>
@endsection

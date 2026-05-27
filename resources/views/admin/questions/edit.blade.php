@extends('admin.layouts.app')
@section('title','Edit Question')

@section('content')
  @if (session('ok'))
    <div class="card" style="border-color:#22c55e">✅ {{ session('ok') }}</div>
  @endif
  @if ($errors->any())
    <div class="card" style="border-color:#ef4444">❌ {{ $errors->first() }}</div>
  @endif

  <form class="card" method="POST" action="{{ route('admin.questions.update', $q) }}">
    @csrf
    @method('PUT')

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
      <div>
        <label>Teacher</label>
        <select class="input" name="teacher_id" required>
          @foreach($teachers as $t)
            <option value="{{ $t->id }}" @selected(old('teacher_id',$q->teacher_id)==$t->id)>{{ $t->name }}</option>
          @endforeach
        </select>
      </div>
      <div>
        <label>Category</label>
        <select class="input" name="category_id" required>
          @foreach($categories as $c)
            <option value="{{ $c->id }}" @selected(old('category_id',$q->category_id)==$c->id)>{{ $c->name }}</option>
          @endforeach
        </select>
      </div>

      <div>
        <label>Difficulty</label>
        <select class="input" name="difficulty" required>
          @php $d = old('difficulty',$q->difficulty); @endphp
          <option value="Junior" @selected($d==='Junior')>Junior</option>
          <option value="Mid" @selected($d==='Mid')>Mid</option>
          <option value="Senior" @selected($d==='Senior')>Senior</option>
        </select>
      </div>

      <div style="display:flex;align-items:center;gap:8px;margin-top:28px">
        <input type="checkbox" id="is_active" name="is_active" value="1" @checked(old('is_active',$q->is_active))>
        <label for="is_active" style="margin:0">Active</label>
      </div>

      <div style="grid-column:1 / -1">
        <label>Question</label>
        <textarea class="input" name="question" rows="8" required>{{ old('question',$q->question) }}</textarea>
      </div>
    </div>

    <div style="margin-top:10px;display:flex;gap:10px">
      <button class="btn" type="submit">Update</button>
      <a class="btn ghost" href="{{ route('admin.questions.index') }}">Back</a>
    </div>
  </form>
@endsection

@extends('admin.layouts.app')
@section('title','Answers')

@section('content')
  @if (session('ok'))
    <div class="card" style="border-color:#22c55e">✅ {{ session('ok') }}</div>
  @endif
  @if ($errors->any())
    <div class="card" style="border-color:#ef4444">❌ {{ $errors->first() }}</div>
  @endif

  <div class="card" style="display:flex;gap:10px;align-items:center;justify-content:space-between;flex-wrap:wrap">
    <form method="GET" action="{{ route('admin.answers.index') }}" style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
      <input class="input" type="text" name="q" value="{{ request('q') }}" placeholder="Search question/answer/teacher">
      <select class="input" name="category_id" style="min-width:180px">
        <option value="">All Categories</option>
        @foreach(($categories ?? []) as $c)
          <option value="{{ $c->id }}" @selected(request('category_id')==$c->id)>{{ $c->name }}</option>
        @endforeach
      </select>
      <select class="input" name="teacher_id" style="min-width:160px">
        <option value="">All Teachers</option>
        @foreach(($teachers ?? []) as $t)
          <option value="{{ $t->id }}" @selected(request('teacher_id')==$t->id)>{{ $t->name }}</option>
        @endforeach
      </select>
      <select class="input" name="approved" style="min-width:160px">
        <option value="">Any Status</option>
        <option value="1" @selected(request('approved')==='1')>Approved</option>
        <option value="0" @selected(request('approved')==='0')>Pending</option>
      </select>
      <button class="btn" type="submit">Filter</button>
      @if(request()->hasAny(['q','category_id','teacher_id','approved']))
        <a class="btn ghost" href="{{ route('admin.answers.index') }}">Clear</a>
      @endif
    </form>

    <a class="btn" href="{{ route('admin.answers.create') }}">Add Answer</a>
  </div>

  <div class="card">
    <table class="table table-hover align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th style="width:70px">ID</th>
          <th>Question</th>
          <th>Answer</th>
          <th style="width:180px">Category</th>
          <th style="width:160px">Teacher</th>
          <th style="width:110px">Approved</th>
          <th style="width:150px">Created</th>
          <th style="width:220px"></th>
        </tr>
      </thead>
      <tbody>
        @forelse($rows as $a)
          <tr>
            <td>{{ $a->id }}</td>
            <td>{{ \Illuminate\Support\Str::limit($a->question?->question ?? '—', 60) }}</td>
            <td>{{ \Illuminate\Support\Str::limit($a->answer, 60) }}</td>
            <td>{{ $a->question?->category?->name ?? '—' }}</td>
            <td>{{ $a->teacher?->name ?? '—' }}</td>
            <td>
              @if($a->is_approved)
                <span class="badge" style="border-color:#14532d;background:#052e16;color:#bbf7d0">Approved</span>
              @else
                <span class="badge" style="border-color:#581c1c;background:#2a0d0d;color:#fecaca">Pending</span>
              @endif
            </td>
            <td class="muted">{{ $a->created_at?->format('Y-m-d') }}</td>
            <td>
              <a class="btn ghost" href="{{ route('admin.answers.edit', $a) }}">Edit</a>
              <form method="POST" action="{{ route('admin.answers.destroy', $a) }}" style="display:inline">
                @csrf
                @method('DELETE')
                <button class="btn" onclick="return confirm('Delete this answer?')">Delete</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="8" class="muted">No answers found.</td></tr>
        @endforelse
      </tbody>
    </table>

    <div style="margin-top:10px">
      {{ $rows->withQueryString()->links() }}
    </div>
  </div>
@endsection

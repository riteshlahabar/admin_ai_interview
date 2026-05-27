@extends('admin.layouts.app')
@section('title','Questions')

@section('content')
  @if (session('ok'))
    <div class="card" style="border-color:#22c55e">✅ {{ session('ok') }}</div>
  @endif
  @if ($errors->any())
    <div class="card" style="border-color:#ef4444">❌ {{ $errors->first() }}</div>
  @endif

  <div class="card" style="display:flex;gap:10px;align-items:center;justify-content:space-between;flex-wrap:wrap">
    <form method="GET" action="{{ route('admin.questions.index') }}" style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
      <input class="input" type="text" name="q" value="{{ request('q') }}" placeholder="Search question/role/topic/teacher">
      <select class="input" name="category_id" style="min-width:180px">
        <option value="">All Categories</option>
        @foreach(($categories ?? []) as $c)
          <option value="{{ $c->id }}" @selected(request('category_id')==$c->id)>{{ $c->name }}</option>
        @endforeach
      </select>
      <select class="input" name="difficulty" style="min-width:140px">
        <option value="">All Levels</option>
        <option @selected(request('difficulty')==='Junior')>Junior</option>
        <option @selected(request('difficulty')==='Mid')>Mid</option>
        <option @selected(request('difficulty')==='Senior')>Senior</option>
      </select>
      <select class="input" name="active" style="min-width:140px">
        <option value="">Any Status</option>
        <option value="1" @selected(request('active')==='1')>Active</option>
        <option value="0" @selected(request('active')==='0')>Inactive</option>
      </select>
      <button class="btn" type="submit">Filter</button>
      @if(request()->hasAny(['q','category_id','difficulty','active']))
        <a class="btn ghost" href="{{ route('admin.questions.index') }}">Clear</a>
      @endif
    </form>

    <a class="btn" href="{{ route('admin.questions.create') }}">Add Question</a>
  </div>

  <div class="card">
    <table class="table table-hover align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th style="width:70px">ID</th>
          <th>Question</th>
          <th style="width:180px">Category</th>
          <th style="width:110px">Level</th>
          <th style="width:110px">Active</th>
          <th style="width:180px">Teacher</th>
          <th style="width:150px">Created</th>
          <th style="width:220px"></th>
        </tr>
      </thead>
      <tbody>
        @forelse($rows as $q)
          <tr>
            <td>{{ $q->id }}</td>
            <td>{{ \Illuminate\Support\Str::limit($q->question, 80) }}</td>
            <td>{{ $q->category?->name ?? '—' }}</td>
            <td><span class="badge">{{ $q->difficulty }}</span></td>
            <td>
              @if($q->is_active)
                <span class="badge" style="border-color:#14532d;background:#052e16;color:#bbf7d0">Active</span>
              @else
                <span class="badge" style="border-color:#581c1c;background:#2a0d0d;color:#fecaca">Inactive</span>
              @endif
            </td>
            <td>{{ $q->teacher?->name ?? '—' }}</td>
            <td class="muted">{{ $q->created_at?->format('Y-m-d') }}</td>
            <td>
              <a class="btn ghost" href="{{ route('admin.questions.edit', $q) }}">Edit</a>
              <form method="POST" action="{{ route('admin.questions.destroy', $q) }}" style="display:inline">
                @csrf
                @method('DELETE')
                <button class="btn" onclick="return confirm('Delete this question?')">Delete</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="8" class="muted">No questions found.</td></tr>
        @endforelse
      </tbody>
    </table>

    <div style="margin-top:10px">
      {{ $rows->withQueryString()->links() }}
    </div>
  </div>
@endsection

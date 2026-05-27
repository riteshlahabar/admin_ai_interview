@extends('admin.layouts.app')
@section('title','Categories')

@section('content')
  @if (session('ok'))
    <div class="card" style="border-color:#22c55e">✅ {{ session('ok') }}</div>
  @endif
  @if ($errors->any())
    <div class="card" style="border-color:#ef4444">❌ {{ $errors->first() }}</div>
  @endif

  <div class="card" style="display:flex;gap:10px;align-items:center;justify-content:space-between">
    <form method="GET" action="{{ route('admin.categories.index') }}" style="display:flex;gap:10px;align-items:center">
      <input class="input" name="q" value="{{ request('q') }}" placeholder="Search category name">
      <button class="btn" type="submit">Search</button>
      @if(request()->filled('q'))
        <a class="btn ghost" href="{{ route('admin.categories.index') }}">Clear</a>
      @endif
    </form>
    <a class="btn" href="{{ route('admin.categories.create') }}">Add Category</a>
  </div>

  <div class="card">
    <table class="table">
      <thead>
        <tr>
          <th style="width:80px">ID</th>
          <th>Name</th>
          <th>Slug</th>
          <th style="width:160px">Created</th>
          <th style="width:220px"></th>
        </tr>
      </thead>
      <tbody>
        @forelse($categories as $c)
          <tr>
            <td>{{ $c->id }}</td>
            <td>{{ $c->name }}</td>
            <td><span class="badge">{{ $c->slug }}</span></td>
            <td>{{ $c->created_at?->format('Y-m-d') }}</td>
            <td>
              <a class="btn ghost" href="{{ route('admin.categories.edit', $c) }}">Edit</a>
              <form method="POST" action="{{ route('admin.categories.destroy', $c) }}" style="display:inline">
                @csrf
                @method('DELETE')
                <button class="btn" onclick="return confirm('Delete this category?')">Delete</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="5" class="muted">No categories found.</td></tr>
        @endforelse
      </tbody>
    </table>

    <div style="margin-top:10px">
      {{ $categories->withQueryString()->links() }}
    </div>
  </div>
@endsection


@extends('admin.layouts.app')
@section('title','Students')
@section('content')
<div class="card">
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
    <a class="btn btn-primary" href="{{ route('admin.students.create') }}">Add Student</a>

    <form method="GET" action="{{ route('admin.students.index') }}" class="d-flex gap-2 ms-auto" style="min-width:320px;max-width:520px;width:100%">
      <input
        class="form-control"
        type="text"
        name="q"
        value="{{ request('q') }}"
        placeholder="Search by name or email">
      <button class="btn btn-primary" type="submit">Search</button>
      @if(request()->filled('q'))
        <a class="btn btn-outline-secondary" href="{{ route('admin.students.index') }}">Clear</a>
      @endif
    </form>
  </div>
</div>
<div class="card">
  <table class="table table-hover align-middle mb-0">
    <thead class="table-light">
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Joined</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @foreach($students as $s)
      <tr>
        <td>{{ $s->id }}</td>
        <td>{{ $s->name }}</td>
        <td>{{ $s->email }}</td>
        <td>{{ $s->created_at->format('Y-m-d') }}</td>
        <td>
          <a class="btn ghost" href="{{ route('admin.students.edit',$s) }}">Edit</a>
          <form method="POST" action="{{ route('admin.students.destroy',$s) }}" style="display:inline">@csrf @method('DELETE')
            <button class="btn" onclick="return confirm('Delete?')">Delete</button>
          </form>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
  <div style="margin-top:10px">{{ $students->withQueryString()->links() }}</div>
</div>
@endsection

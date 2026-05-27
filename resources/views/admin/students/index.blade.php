@extends('admin.layouts.app')
@section('title','Students')
@section('content')
<div class="card" style="display:flex;gap:10px;align-items:center;justify-content:space-between">
  <form><input class="input" name="q" value="{{ request('q') }}" placeholder="Search name/email"></form>
  <a class="btn" href="{{ route('admin.students.create') }}">Add Student</a>
</div>
<div class="card">
  <table class="table">
    <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Joined</th><th></th></tr></thead>
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

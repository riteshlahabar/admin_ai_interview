@extends('admin.layouts.app')
@section('title','Students')
@section('content')
@if (session('ok'))
  <div class="alert alert-success border-0 shadow-sm">{{ session('ok') }}</div>
@endif

@if ($errors->any())
  <div class="alert alert-danger border-0 shadow-sm">{{ $errors->first() }}</div>
@endif

<div class="card border-0 shadow-sm">
  <div class="card-body">
    <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
      <button type="button" class="btn btn-primary px-4" data-bs-toggle="modal" data-bs-target="#addStudentModal">
        <i class="las la-plus me-1"></i> Add Student
      </button>

      <form method="GET" action="{{ route('admin.students.index') }}" class="ms-lg-auto" style="width:100%;max-width:560px">
        <div class="input-group">
          <span class="input-group-text bg-white"><i class="iconoir-search"></i></span>
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
        </div>
      </form>
    </div>
  </div>
</div>
<div class="card border-0 shadow-sm">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th class="ps-4">ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Joined</th>
            <th class="text-end pe-4">Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($students as $s)
          <tr>
            <td class="ps-4 fw-semibold">#{{ $s->id }}</td>
            <td>{{ $s->name }}</td>
            <td class="text-muted">{{ $s->email }}</td>
            <td>{{ $s->created_at->format('Y-m-d') }}</td>
            <td class="text-end pe-4">
              <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editStudentModal{{ $s->id }}">
                Edit
              </button>
              <form method="POST" action="{{ route('admin.students.destroy',$s) }}" class="d-inline">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete?')">Delete</button>
              </form>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="5" class="text-center text-muted py-4">No students found.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
  <div class="card-footer bg-white border-0">
    {{ $students->withQueryString()->links() }}
  </div>
</div>

@foreach($students as $s)
<div class="modal fade" id="editStudentModal{{ $s->id }}" tabindex="-1" aria-labelledby="editStudentModalLabel{{ $s->id }}" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="POST" action="{{ route('admin.students.update', $s) }}">
        @csrf
        @method('PUT')
        <input type="hidden" name="student_form" value="edit">
        <input type="hidden" name="student_id" value="{{ $s->id }}">
        <div class="modal-header">
          <h5 class="modal-title" id="editStudentModalLabel{{ $s->id }}">Edit Student</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Name</label>
            <input class="form-control" type="text" name="name" value="{{ old('student_id') == $s->id ? old('name', $s->name) : $s->name }}" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input class="form-control" type="email" name="email" value="{{ old('student_id') == $s->id ? old('email', $s->email) : $s->email }}" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Password</label>
            <input class="form-control" type="password" name="password" placeholder="Leave blank to keep current password">
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Phone</label>
              <input class="form-control" type="text" name="phone" value="{{ old('student_id') == $s->id ? old('phone', $s->profile?->phone) : $s->profile?->phone }}">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Date of Birth</label>
              <input class="form-control" type="date" name="dob" value="{{ old('student_id') == $s->id ? old('dob', $s->profile?->dob) : $s->profile?->dob }}">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Update Student</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endforeach

<div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form method="POST" action="{{ route('admin.students.store') }}">
        @csrf
        <input type="hidden" name="student_form" value="create">
        <div class="modal-header">
          <h5 class="modal-title" id="addStudentModalLabel">Add Student</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Name</label>
            <input class="form-control" type="text" name="name" value="{{ old('name') }}" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input class="form-control" type="email" name="email" value="{{ old('email') }}" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Password</label>
            <input class="form-control" type="password" name="password" required>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Phone</label>
              <input class="form-control" type="text" name="phone" value="{{ old('phone') }}">
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Date of Birth</label>
              <input class="form-control" type="date" name="dob" value="{{ old('dob') }}">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Create Student</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('body')
@if ($errors->any() || session('openStudentModal'))
@php
  $studentModalTarget = old('student_form') === 'edit' && old('student_id')
      ? 'editStudentModal'.old('student_id')
      : 'addStudentModal';
@endphp
<script>
document.addEventListener('DOMContentLoaded', function () {
  var modalElement = document.getElementById(@json($studentModalTarget));
  if (modalElement && window.bootstrap) {
    window.bootstrap.Modal.getOrCreateInstance(modalElement).show();
  }
});
</script>
@endif
@endpush

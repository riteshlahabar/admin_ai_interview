@extends('admin.layouts.app')
@section('title','New Category')

@section('content')
  @if ($errors->any())
    <div class="card" style="border-color:#ef4444">❌ {{ $errors->first() }}</div>
  @endif

  <form class="card" method="POST" action="{{ route('admin.categories.store') }}">
    @csrf
    <div style="display:grid;grid-template-columns:1fr;gap:10px">
      <div>
        <label>Name</label>
        <input class="input" type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Flutter State Management">
      </div>
      <div class="muted">
        Slug will be generated automatically from the name. 
      </div>
    </div>
    <div style="margin-top:10px;display:flex;gap:10px">
      <button class="btn" type="submit">Save</button>
      <a class="btn ghost" href="{{ route('admin.categories.index') }}">Cancel</a>
    </div>
  </form>
@endsection

@extends('admin.layouts.app')
@section('title','Edit Category')

@section('content')
  @if (session('ok'))
    <div class="card" style="border-color:#22c55e">✅ {{ session('ok') }}</div>
  @endif
  @if ($errors->any())
    <div class="card" style="border-color:#ef4444">❌ {{ $errors->first() }}</div>
  @endif

  <form class="card" method="POST" action="{{ route('admin.categories.update', $category) }}">
    @csrf
    @method('PUT')
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
      <div style="grid-column:1 / -1">
        <label>Name</label>
        <input class="input" type="text" name="name" value="{{ old('name',$category->name) }}" required>
      </div>
      <div>
        <label>Slug</label>
        <input class="input" type="text" value="{{ $category->slug }}" disabled>
      </div>
      <div>
        <label>Created</label>
        <input class="input" type="text" value="{{ $category->created_at?->format('Y-m-d H:i') }}" disabled>
      </div>
    </div>
    <div style="margin-top:10px;display:flex;gap:10px">
      <button class="btn" type="submit">Update</button>
      <a class="btn ghost" href="{{ route('admin.categories.index') }}">Back</a>
    </div>
  </form>
@endsection

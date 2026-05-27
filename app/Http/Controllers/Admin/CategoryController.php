<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class CategoryController extends Controller
{
  public function index(){
    $categories = \App\Models\Category::latest()->paginate(20);
    return view('admin.categories.index', compact('categories'));
  }
  
  public function create(){ return view('admin.categories.create'); }
  public function store(Request $r){
    $data = $r->validate(['name'=>'required|string|max:100']);
    \App\Models\Category::create([
      'name'=>$data['name'],
      'slug'=>\Str::slug($data['name']).'-'.\Str::random(4),
    ]);
    return redirect()->route('admin.categories.index')->with('ok','Category created');
  }
  public function edit(\App\Models\Category $category){ return view('admin.categories.edit', compact('category')); }
  public function update(Request $r, \App\Models\Category $category){
    $data = $r->validate(['name'=>'required|string|max:100']);
    $category->update(['name'=>$data['name']]);
    return back()->with('ok','Category updated');
  }
  public function destroy(\App\Models\Category $category){
    $category->delete();
    return back()->with('ok','Category deleted');
  }
}


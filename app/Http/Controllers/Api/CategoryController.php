<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // GET /api/categories?per_page=20&search=abc
    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', 20);
        $q = Category::query()->latest();

        if ($search = $request->query('search')) {
            $q->where('name', 'like', "%{$search}%");
        }

        // Returning paginator directly -> JSON with data, links/meta
        return $q->paginate($perPage);
    }

    // GET /api/categories/{category}
    public function show(Category $category)
    {
        // Route-model binding returns a single model as JSON
        return $category;
    }
}

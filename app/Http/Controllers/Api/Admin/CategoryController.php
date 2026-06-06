<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::withCount(['products' => fn($q) => $q->where('is_active', true)]);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where('name', 'like', "%{$s}%");
        }

        $categories = $query->latest()->paginate(10);

        return response()->json([
            'data' => $categories->getCollection()->map(fn($c) => $this->format($c)),
            'meta' => [
                'current_page' => $categories->currentPage(),
                'last_page'    => $categories->lastPage(),
                'from'         => $categories->firstItem(),
                'to'           => $categories->lastItem(),
                'total'        => $categories->total(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'image.image'   => 'El archivo debe ser una imagen.',
            'image.max'     => 'La imagen no debe superar 2MB.',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = cloudinary()->upload($request->file('image')->getRealPath(), ['folder' => 'categories'])->getSecurePath();
        }

        $data['slug'] = Str::slug($data['name']);

        $category = Category::create($data);
        $category->loadCount('products');

        return response()->json([
            'message'  => 'Categoría creada correctamente.',
            'category' => $this->format($category),
        ], 201);
    }

    public function show(string $id)
    {
        $category = Category::withCount('products')->with('products')->findOrFail($id);
        return response()->json($this->format($category));
    }

    public function update(Request $request, string $id)
    {
        $category = Category::findOrFail($id);

        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($category->image && !\Illuminate\Support\Str::startsWith($category->image, 'http')) {
                Storage::disk('public')->delete($category->image);
            }
            $data['image'] = cloudinary()->upload($request->file('image')->getRealPath(), ['folder' => 'categories'])->getSecurePath();
        }

        if (isset($data['name']) && $data['name'] !== $category->name) {
            $data['slug'] = Str::slug($data['name']);
        }

        $category->update($data);
        $category->loadCount('products');

        return response()->json([
            'message'  => 'Categoría actualizada correctamente.',
            'category' => $this->format($category),
        ]);
    }

    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);

        if ($category->image && !\Illuminate\Support\Str::startsWith($category->image, 'http')) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return response()->json(['message' => 'Categoría eliminada correctamente.']);
    }

    private function format(Category $c): array
    {
        return [
            'id'             => $c->id,
            'name'           => $c->name,
            'slug'           => $c->slug,
            'description'    => $c->description,
            'image'          => $c->image,
            'image_url'      => $c->image_url,
            'is_active'      => $c->is_active,
            'products_count' => $c->products_count ?? 0,
            'created_at'     => $c->created_at?->format('d/m/Y'),
        ];
    }
}

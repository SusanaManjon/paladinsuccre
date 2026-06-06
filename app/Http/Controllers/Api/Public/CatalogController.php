<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::active()->with('category');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('category_slug')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category_slug));
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        $products = $query->latest()->paginate(12);

        return response()->json([
            'data' => $products->getCollection()->map(fn($p) => [
                'id'          => $p->id,
                'name'        => $p->name,
                'slug'        => $p->slug,
                'description' => $p->description,
                'price'       => $p->price,
                'weight'      => $p->weight,
                'image_url'   => $p->image_url,
                'stock'       => $p->stock,
                'is_featured' => $p->is_featured,
                'category'    => $p->category ? ['id' => $p->category->id, 'name' => $p->category->name, 'slug' => $p->category->slug] : null,
            ]),
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page'    => $products->lastPage(),
                'from'         => $products->firstItem(),
                'to'           => $products->lastItem(),
                'total'        => $products->total(),
            ],
        ]);
    }

    public function show(string $slug)
    {
        $product = Product::active()->where('slug', $slug)->with('category')->firstOrFail();

        $related = Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with('category')
            ->limit(4)
            ->get()
            ->map(fn($p) => [
                'id'        => $p->id,
                'name'      => $p->name,
                'slug'      => $p->slug,
                'price'     => $p->price,
                'weight'    => $p->weight,
                'image_url' => $p->image_url,
                'stock'     => $p->stock,
            ]);

        return response()->json([
            'id'          => $product->id,
            'name'        => $product->name,
            'slug'        => $product->slug,
            'description' => $product->description,
            'price'       => $product->price,
            'weight'      => $product->weight,
            'image_url'   => $product->image_url,
            'stock'       => $product->stock,
            'is_featured' => $product->is_featured,
            'category'    => $product->category ? ['id' => $product->category->id, 'name' => $product->category->name, 'slug' => $product->category->slug] : null,
            'related'     => $related,
        ]);
    }

    public function categories()
    {
        $categories = Category::active()
            ->withCount(['products' => fn($q) => $q->where('is_active', true)])
            ->get()
            ->map(fn($c) => [
                'id'             => $c->id,
                'name'           => $c->name,
                'slug'           => $c->slug,
                'description'    => $c->description,
                'image_url'      => $c->image_url,
                'is_active'      => $c->is_active,
                'products_count' => $c->products_count,
            ]);

        return response()->json($categories);
    }
}

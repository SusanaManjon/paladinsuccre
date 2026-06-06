<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::active()
            ->featured()
            ->with('category')
            ->limit(8)
            ->get()
            ->map(fn($p) => $this->formatProduct($p));

        $categories = Category::active()
            ->withCount(['products' => fn($q) => $q->where('is_active', true)])
            ->get()
            ->map(fn($c) => [
                'id'             => $c->id,
                'name'           => $c->name,
                'slug'           => $c->slug,
                'description'    => $c->description,
                'image_url'      => $c->image_url,
                'products_count' => $c->products_count,
            ]);

        return response()->json([
            'featured_products' => $featuredProducts,
            'categories'        => $categories,
        ]);
    }

    private function formatProduct($p): array
    {
        return [
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
        ];
    }
}

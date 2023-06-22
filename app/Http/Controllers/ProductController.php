<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Size;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Product $product)
    {
        // This is to avoid N+1 query: will explain later in detail
        $product->load(['productColorSizes.color', 'productColorSizes.size']);

        // This is to populate the columns for the table
        $sizes = Size::pluck('name');

        // We're using groupBy from the relationship's relationship
        $productSizingTable = $product->productColorSizes->groupBy('color.name');

        return view('products.show',
            compact('product', 'productSizingTable', 'sizes'));
    }
}

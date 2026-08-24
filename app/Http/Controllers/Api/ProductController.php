<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\ProductService;

class ProductController extends Controller
{
    public function __construct(private ProductService $productService)
    {
    }

    public function index()
    {
        $products = $this->productService->getAllProducts();

        return ProductResource::collection($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $this->authorize('create', Product::class);

        $validated = $request->validated();
        $validated['vendor_id'] = auth()->user()->vendor_id;

        $product = $this->productService->createProduct($validated);

        return new ProductResource($product);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $this->productService->getProduct($product);

        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $this->authorize('update', $product);

        $updated = $this->productService->updateProduct($product, $request->validated());
        return new ProductResource($updated);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);

        $this->productService->deleteProduct($product);
        return response()->noContent();
    }
}

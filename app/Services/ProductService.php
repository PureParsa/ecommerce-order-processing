<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Vendor;

class ProductService
{
    public function getAllProducts()
    {
        return Product::with('Vendor')->get();
    }

    public function getProduct(Product $product)
    {
        return $product->load('vendor');
    }

    public function createProduct(array $data)
    {
        $product = Product::create($data);
        $product->load('vendor');
        return $product;
    }
    public function updateProduct(Product $product, array $data)
    {
        $product->update($data);
        $product->load('vendor');
        return $product;
    }
    public function deleteProduct(Product $product)
    {
        $product->delete();
    }
}

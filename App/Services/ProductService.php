<?php

namespace App\Services;

use App\Models\ProductModel;
use App\Exceptions\ApiException;

class ProductService
{
    private ProductModel $productModel;

    public function __construct(ProductModel $productModel)
    {
        $this->productModel = $productModel;
    }

    /**
     * @throws ApiException
     */
    public function getProducts(array $data): array
    {
        $token_data = $this->apiController->authorizeRequest();
        $products = $this->productModel->getProducts($data);

        return $products;
    }
}
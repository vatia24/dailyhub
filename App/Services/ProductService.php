<?php

namespace App\Services;

use App\Models\ProductModel;
use App\Models\UserModel;
use App\Exceptions\ApiException;
use App\Controllers\ApiController;

class ProductService
{
    private ProductModel $productModel;
    private ApiController $apiController;

    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->apiController = new ApiController();
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
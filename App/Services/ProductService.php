<?php

namespace App\Services;

use App\Models\ProductModel;
use App\Exceptions\ApiException;

class ProductService
{
    private ProductModel $productModel;
    private AuthService $authService;

    public function __construct(ProductModel $productModel, AuthService $authService)
    {
        $this->productModel = $productModel;
        $this->authService = $authService;
    }

    /**
     * @throws ApiException
     */
    public function getProducts(array $data): array
    {
        // Ensure the request is authorized
        $this->authService->authorizeRequest();

        if (isset($data['id'])) {
            $product = $this->productModel->getProductWithDiscountById((int)$data['id']);
            return $product ? [$product] : [];
        }

        return $this->productModel->getAllProductsWithDiscounts();
    }
}
<?php

namespace App\Services;

use App\Exceptions\ApiException;
use App\Models\DiscountModel;
use App\Models\CompanyModel;

class DiscountService
{
    private DiscountModel $discountModel;
    private AuthService $authService;
    private CompanyModel $companyModel;

    public function __construct(DiscountModel $discountModel, AuthService $authService, CompanyModel $companyModel)
    {
        $this->discountModel = $discountModel;
        $this->authService = $authService;
        $this->companyModel = $companyModel;
    }

    /**
     * @throws ApiException
     */
    public function list(array $data): array
    {
        $this->authService->authorizeRequest();
        return ['discounts' => $this->discountModel->list($data)];
    }

    /**
     * @throws ApiException
     */
    public function upsert(array $data): array
    {
        $token = $this->authService->authorizeRequest();
        $data['user_id'] = $token->data->id;
        // RBAC: allow only Owner/Manager of the company
        $role = $this->companyModel->getUserRoleForCompany((int)$data['user_id'], (int)$data['company_id']);
        if (!in_array($role, ['Owner','Manager'], true)) {
            throw new ApiException(403, 'FORBIDDEN', 'Insufficient permissions');
        }
        // basic validation: one of price or percent must be provided
        if ((empty($data['discount_price']) && empty($data['discount_percent'])) ||
            (!empty($data['discount_price']) && !empty($data['discount_percent']))) {
            throw new ApiException(400, 'BAD_REQUEST', 'Provide either discount_price or discount_percent');
        }
        $id = $this->discountModel->upsert($data);
        return ['id' => $id];
    }

    /**
     * @throws ApiException
     */
    public function bulkStatus(array $data): array
    {
        $this->authService->authorizeRequest();
        $updated = $this->discountModel->bulkSetStatus($data['ids'] ?? [], $data['status'] ?? 'inactive');
        return ['updated' => $updated];
    }
}



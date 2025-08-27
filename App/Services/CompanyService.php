<?php

namespace App\Services;

use App\Exceptions\ApiException;
use App\Models\CompanyModel;

class CompanyService
{
    private CompanyModel $companyModel;
    private AuthService $authService;

    public function __construct(CompanyModel $companyModel, AuthService $authService)
    {
        $this->companyModel = $companyModel;
        $this->authService = $authService;
    }

    /**
     * @throws ApiException
     */
    public function upsertCompany(array $data): array
    {
        $token = $this->authService->authorizeRequest();
        $userId = $token->data->id;
        $data['user_id'] = $userId;
        if (!empty($data['id'])) {
            $role = $this->companyModel->getUserRoleForCompany($userId, (int)$data['id']);
            if (!in_array($role, ['Owner','Manager'], true)) {
                throw new ApiException(403, 'FORBIDDEN', 'Insufficient permissions');
            }
        }
        $id = $this->companyModel->upsertCompany($data);
        return ['id' => $id];
    }

    /**
     * @throws ApiException
     */
    public function getCompany(array $data): array
    {
        $this->authService->authorizeRequest();
        $company = $this->companyModel->getCompanyById((int)$data['id']);
        return ['company' => $company];
    }

    /**
     * @throws ApiException
     */
    public function setStatus(array $data): array
    {
        $token = $this->authService->authorizeRequest();
        $role = $this->companyModel->getUserRoleForCompany($token->data->id, (int)$data['company_id']);
        if (!in_array($role, ['Owner','Manager'], true)) throw new ApiException(403, 'FORBIDDEN', 'Insufficient permissions');
        $this->companyModel->setStatus((int)$data['company_id'], $data['status']);
        return ['ok' => true];
    }

    /**
     * @throws ApiException
     */
    public function setHours(array $data): array
    {
        $token = $this->authService->authorizeRequest();
        $role = $this->companyModel->getUserRoleForCompany($token->data->id, (int)$data['company_id']);
        if (!in_array($role, ['Owner','Manager'], true)) throw new ApiException(403, 'FORBIDDEN', 'Insufficient permissions');
        $this->companyModel->replaceHours((int)$data['company_id'], $data['hours'] ?? []);
        return ['ok' => true];
    }

    public function getHours(array $data): array
    {
        $this->authService->authorizeRequest();
        return ['hours' => $this->companyModel->getHours((int)$data['company_id'])];
    }

    /**
     * @throws ApiException
     */
    public function addSocial(array $data): array
    {
        $token = $this->authService->authorizeRequest();
        $role = $this->companyModel->getUserRoleForCompany($token->data->id, (int)$data['company_id']);
        if (!in_array($role, ['Owner','Manager'], true)) throw new ApiException(403, 'FORBIDDEN', 'Insufficient permissions');
        $id = $this->companyModel->addSocial((int)$data['company_id'], $data['platform'], $data['url']);
        return ['id' => $id];
    }

    public function listSocials(array $data): array
    {
        $this->authService->authorizeRequest();
        return ['socials' => $this->companyModel->listSocials((int)$data['company_id'])];
    }

    /**
     * @throws ApiException
     */
    public function deleteSocial(array $data): array
    {
        $token = $this->authService->authorizeRequest();
        $role = $this->companyModel->getUserRoleForCompany($token->data->id, (int)$data['company_id']);
        if (!in_array($role, ['Owner','Manager'], true)) throw new ApiException(403, 'FORBIDDEN', 'Insufficient permissions');
        $this->companyModel->deleteSocial((int)$data['id']);
        return ['deleted' => true];
    }

    /**
     * @throws ApiException
     */
    public function addGallery(array $data): array
    {
        $this->authService->authorizeRequest();
        $path = \App\Helpers\UploadHelper::saveBase64($data['file_base64'], __DIR__ . '/../../uploads/company');
        $id = $this->companyModel->addGallery((int)$data['company_id'], $path);
        return ['id' => $id, 'path' => $path];
    }

    public function listGallery(array $data): array
    {
        $this->authService->authorizeRequest();
        return ['gallery' => $this->companyModel->listGallery((int)$data['company_id'])];
    }

    /**
     * @throws ApiException
     */
    public function deleteGallery(array $data): array
    {
        $token = $this->authService->authorizeRequest();
        $role = $this->companyModel->getUserRoleForCompany($token->data->id, (int)$data['company_id']);
        if (!in_array($role, ['Owner','Manager'], true)) throw new ApiException(403, 'FORBIDDEN', 'Insufficient permissions');
        $this->companyModel->deleteGallery((int)$data['id']);
        return ['deleted' => true];
    }

    /**
     * @throws ApiException
     */
    public function addDocument(array $data): array
    {
        $token = $this->authService->authorizeRequest();
        $role = $this->companyModel->getUserRoleForCompany($token->data->id, (int)$data['company_id']);
        if (!in_array($role, ['Owner','Manager'], true)) throw new ApiException(403, 'FORBIDDEN', 'Insufficient permissions');
        $path = \App\Helpers\UploadHelper::saveBase64($data['file_base64'], __DIR__ . '/../../uploads/company_docs');
        $id = $this->companyModel->addDocument((int)$data['company_id'], $data['doc_type'], $path);
        return ['id' => $id, 'path' => $path];
    }

    public function listDocuments(array $data): array
    {
        $this->authService->authorizeRequest();
        return ['documents' => $this->companyModel->listDocuments((int)$data['company_id'])];
    }

    /**
     * @throws ApiException
     */
    public function reviewDocument(array $data): array
    {
        $token = $this->authService->authorizeRequest();
        // Reviewer can be internal admin; assuming Owner can also review for now
        $role = $this->companyModel->getUserRoleForCompany($token->data->id, (int)$data['company_id']);
        if (!in_array($role, ['Owner','Manager'], true)) throw new ApiException(403, 'FORBIDDEN', 'Insufficient permissions');
        $this->companyModel->reviewDocument((int)$data['id'], $data['status'], $token->data->id);
        return ['ok' => true];
    }

    /**
     * @throws ApiException
     */
    public function upsertZone(array $data): array
    {
        $token = $this->authService->authorizeRequest();
        $role = $this->companyModel->getUserRoleForCompany($token->data->id, (int)$data['company_id']);
        if (!in_array($role, ['Owner','Manager'], true)) throw new ApiException(403, 'FORBIDDEN', 'Insufficient permissions');
        $id = $this->companyModel->upsertZone($data);
        return ['id' => $id];
    }

    public function listZones(array $data): array
    {
        $this->authService->authorizeRequest();
        return ['zones' => $this->companyModel->listZones((int)$data['company_id'])];
    }

    /**
     * @throws ApiException
     */
    public function deleteZone(array $data): array
    {
        $token = $this->authService->authorizeRequest();
        $role = $this->companyModel->getUserRoleForCompany($token->data->id, (int)$data['company_id']);
        if (!in_array($role, ['Owner','Manager'], true)) throw new ApiException(403, 'FORBIDDEN', 'Insufficient permissions');
        $this->companyModel->deleteZone((int)$data['id']);
        return ['deleted' => true];
    }
}



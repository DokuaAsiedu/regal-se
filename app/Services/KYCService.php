<?php

namespace App\Services;

use App\Enums\Roles;
use App\Exceptions\CustomException;
use App\Models\KYCSubmission;
use App\Notifications\KYCSubmitted;
use App\Repositories\KYCSubmissionRepository;
use App\Services\StatusService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

class KYCService
{
    protected $kycSubmissionRepository;
    protected $statusService;
    protected $userService;
    protected $roleService;

    /**
     * Create a new class instance.
     */
    public function __construct(KYCSubmissionRepository $kycSubmissionRepository, StatusService $statusService, UserService $userService, RoleService $roleService)
    {
        $this->kycSubmissionRepository = $kycSubmissionRepository;
        $this->statusService = $statusService;
        $this->userService = $userService;
        $this->roleService = $roleService;
    }

    public function find($id)
    {
        return $this->kycSubmissionRepository->find($id);
    }

    public function all()
    {
        return $this->kycSubmissionRepository->all();
    }

    public function allQuery($search = [])
    {
        return $this->kycSubmissionRepository->allQuery($search);
    }

    public function store($input)
    {
        return $this->kycSubmissionRepository->create($input);
    }

    public function update($id, $input)
    {
        return $this->kycSubmissionRepository->update($input, $id);
    }

    public function delete($ids)
    {
        $this->kycSubmissionRepository->delete($ids);
    }

    public function validStatuses()
    {
        return collect([
            $this->statusService->pending(),
            $this->statusService->approved(),
        ]);
    }

    public function submitKYC($input, $kyc_submission_id = null)
    {
        if ($kyc_submission_id) {
            $kyc_submission = $this->update($kyc_submission_id, $input);
        } else {
            $kyc_submission = $this->store($input);
        }

        if (!Auth::check()) {
            $user_payload = [
                'name' => $input['customer_name'],
                'email' => $input['customer_email'],
                'phone_prefix' => $input['customer_phone_prefix'],
                'phone' => $input['customer_phone'],
                'phone_country_code' => $input['customer_phone_country_code'],
                'email' => $input['customer_email'],
                'status_id' => $this->statusService->active()->id,
                'role_id' => $this->roleService->customerRole()->id,
                'password' => Hash::make($input['password']),
                'delivery_address' => $input['customer_address'],
                'ghana_card_number' => $input['customer_ghana_card_number'],
                'date_of_birth' => $input['customer_date_of_birth'],
                'company_name' => $input['company_name'],
                'company_email' => $input['company_email'],
                'company_phone_prefix' => $input['company_phone_prefix'],
                'company_phone' => $input['company_phone'],
                'company_phone_country_code' => $input['company_phone_country_code'],
                'company_address' => $input['company_address'],
                'current_position' => $input['customer_current_position'],
                'employment_start_date' => $input['customer_employment_start_date'],
            ];
            $user = $this->userService->storeAndLogin($user_payload);
        } else {
            $user_payload = [
                'name' => $input['customer_name'],
                'email' => $input['customer_email'],
                'phone_prefix' => $input['customer_phone_prefix'],
                'phone' => $input['customer_phone'],
                'phone_country_code' => $input['customer_phone_country_code'],
                'email' => $input['customer_email'],
                'delivery_address' => $input['customer_address'],
                'ghana_card_number' => $input['customer_ghana_card_number'],
                'date_of_birth' => $input['customer_date_of_birth'],
                'company_name' => $input['company_name'],
                'company_email' => $input['company_email'],
                'company_phone_prefix' => $input['company_phone_prefix'],
                'company_phone' => $input['company_phone'],
                'company_phone_country_code' => $input['company_phone_country_code'],
                'company_address' => $input['company_address'],
                'current_position' => $input['customer_current_position'],
                'employment_start_date' => $input['customer_employment_start_date'],
            ];
            $user = $this->userService->update(Auth::id(), $user_payload);
        }

        $kyc_submission->update([
            'user_id' => $user->id,
            'status_id' => $this->statusService->pending()->id,
        ]);

        $this->sendKYCSubmittedNotification($kyc_submission);
    }

    public function sendKYCSubmittedNotification(KYCSubmission $kyc)
    {
        // notify customer
        $kyc->user->notify(new KYCSubmitted($kyc));

        // notify admins
        $admins = $this->userService->admins()->get();
        Notification::send($admins, new KYCSubmitted($kyc, Roles::Admin));
    }

    public function approveKYC($kyc)
    {
        $kyc->status_id = $this->statusService->approved()->id;
        $kyc->save();
    }

    public function rejectKYC($kyc)
    {
        $kyc->status_id = $this->statusService->rejected()->id;
        $kyc->save();
    }
}

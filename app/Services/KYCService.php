<?php

namespace App\Services;

use App\Events\KYCApproved;
use App\Events\KYCSubmitted;
use App\Exceptions\CustomException;
use App\Repositories\KYCRepository;
use App\Services\StatusService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class KYCService
{
    protected $kycRepository;
    protected $statusService;
    protected $userService;
    protected $roleService;
    protected $storeSettingsService;
    protected $companyService;

    /**
     * Create a new class instance.
     */
    public function __construct(KYCRepository $kycRepository, StatusService $statusService, UserService $userService, RoleService $roleService, StoreSettingsService $storeSettingsService, CompanyService $companyService)
    {
        $this->kycRepository = $kycRepository;
        $this->statusService = $statusService;
        $this->userService = $userService;
        $this->roleService = $roleService;
        $this->storeSettingsService = $storeSettingsService;
        $this->companyService = $companyService;
    }

    public function find($id)
    {
        return $this->kycRepository->find($id);
    }

    public function all()
    {
        return $this->kycRepository->all();
    }

    public function allQuery($search = [])
    {
        return $this->kycRepository->allQuery($search);
    }

    public function store($input)
    {
        return $this->kycRepository->create($input);
    }

    public function update($id, $input)
    {
        return $this->kycRepository->update($input, $id);
    }

    public function delete($ids)
    {
        $this->kycRepository->delete($ids);
    }

    public function validStatuses()
    {
        return collect([
            $this->statusService->pending(),
            $this->statusService->approved(),
        ]);
    }

    public function submitKYC($input, $kyc_id = null)
    {
        if ($kyc_id) {
            $kyc = $this->update($kyc_id, $input);
        } else {
            $kyc = $this->store($input);
        }

        // if auto approve is enabled, check if ghana card, staff id, and company id match with what has been set in company staff table
        $auto_approve_enabled = $this->storeSettingsService->autoApproveKyc();
        if ($auto_approve_enabled) {
            $company = $this->companyService->find($input['company_id']);
            $employee = $company->staff()
                ->where('staff_id', $input['staff_id'])
                ->first();
            if (!$employee) {
                throw new CustomException('Oops, it looks like something did not match, we could not verify your details. Please review your information and try submitting again.');
            }

            if ($employee && $employee->user_id !== null) {
                throw new CustomException('Oops, it looks like something did not match, we could not verify your details. Please review your information and try submitting again.');
            }
            $kyc_valid = $employee->staff_id == $input['staff_id'] && $employee->ghana_card_number == $input['ghana_card_number'];

            if (!$kyc_valid) {
                throw new CustomException('Oops, it looks like something did not match, we could not verify your details. Please review your information and try submitting again.');
            }

            // update employee user id on company staff table
            $employee->update([
                'staff_id' => $input['staff_id'],
            ]);
            $kyc->update([
                'status_id' => $this->statusService->approved()->id,
                'rejection_reason' => null,
                'reviewed_by' => null,
            ]);
        }

        if (!Auth::check()) {
            $user_payload = [
                'name' => $input['name'],
                'email' => $input['email'],
                'phone_prefix' => $input['phone_prefix'],
                'phone' => $input['phone'],
                'phone_country_code' => $input['phone_country_code'],
                'email' => $input['email'],
                'status_id' => $this->statusService->active()->id,
                'role_id' => $this->roleService->customerRole()->id,
                'password' => Hash::make($input['password']),
                'delivery_address' => $input['address'],
                'ghana_card_number' => $input['ghana_card_number'],
                'date_of_birth' => $input['date_of_birth'],
                'company_id' => $input['company_id'],
                'staff_id' => $input['staff_id'],
                'current_position' => $input['current_position'],
                'employment_start_date' => $input['employment_start_date'],
            ];
            $user = $this->userService->storeAndLogin($user_payload);
        } else {
            $user_payload = [
                'name' => $input['name'],
                'email' => $input['email'],
                'phone_prefix' => $input['phone_prefix'],
                'phone' => $input['phone'],
                'phone_country_code' => $input['phone_country_code'],
                'email' => $input['email'],
                'delivery_address' => $input['address'],
                'ghana_card_number' => $input['ghana_card_number'],
                'date_of_birth' => $input['date_of_birth'],
                'company_id' => $input['company_id'],
                'staff_id' => $input['staff_id'],
                'current_position' => $input['current_position'],
                'employment_start_date' => $input['employment_start_date'],
            ];
            $user = $this->userService->update(Auth::id(), $user_payload);
        }

        if ($auto_approve_enabled) {
            $status_id = $this->statusService->approved()->id;
        } else {
            $status_id = $this->statusService->pending()->id;
        }

        if (!empty($employee)) {
            $employee->update([
                'user_id' => $user->id,
            ]);
        }

        $kyc_payload = [
            'user_id' => $user->id,
            'status_id' => $status_id,
        ];
        $kyc->update($kyc_payload);

        if (!$auto_approve_enabled) {
            KYCSubmitted::dispatch($kyc);
        } else {
            KYCApproved::dispatch($kyc);
        }
    }

    public function approveKYC($kyc)
    {
        $kyc->status_id = $this->statusService->approved()->id;
        $company = $this->companyService->find($kyc->company_id);
        $employee = $company->staff()
            ->where('staff_id', $kyc->staff_id)
            ->first();

        if ($employee) {
            $employee->update([
                'user_id' => $kyc->user_id,
            ]);
        }
        $kyc->reviewed_by = Auth::id();
        $kyc->rejection_reason = null;
        $kyc->save();

        KYCApproved::dispatch($kyc);
    }

    public function rejectKYC($kyc, $rejection_reason)
    {
        $kyc->status_id = $this->statusService->rejected()->id;
        $kyc->reviewed_by = Auth::id();
        $kyc->rejection_reason = $rejection_reason;
        $kyc->save();

        $this->sendKYCRejectedNotification($kyc);
    }

    public function sendKYCRejectedNotification(KYC $kyc)
    {
        // notify customer
        $kyc->user->notify(new KYCRejected($kyc));
    }
}

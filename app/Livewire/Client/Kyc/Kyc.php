<?php

namespace App\Livewire\Client\Kyc;

use App\Services\KYCService;
use App\Services\StatusService;
use App\Traits\HandlesErrorMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Throwable;

class KYC extends Component
{
    use HandlesErrorMessage;

    public $header;
    public $customer_name = 'Test Customer';
    public $customer_phone_prefix = '+233';
    public $customer_phone = '261222644';
    public $customer_phone_country_code = 'gh';
    public $customer_address = 'Somewhere';
    public $customer_ghana_card_number = 'GHA-0000000000-1';
    public $customer_date_of_birth = '2010-12-12';
    public $customer_email = 'testcustomer@email.com';
    public $password = '123456789';
    public $password_confirmation = '123456789';

    public $company_name = 'Some Company';
    public $company_phone_prefix = '+233';
    public $company_phone = '261222644';
    public $company_phone_country_code = 'gh';
    public $customer_current_position = 'Some position';
    public $company_address = 'Somewhere';
    public $company_email = 'companyemail@email.com';
    public $customer_employment_start_date = '2025-07-21';

    public $kyc_submission;
    public $edit_mode;
    public $kyc_submission_approved;

    protected $kycService;
    protected $statusService;

    protected function rules()
    {
        $rules = [
            'customer_name' => 'required|string',
            'customer_phone' => 'required|string',
            'customer_address' => 'required|string',
            'customer_ghana_card_number' => 'required|string',
            'customer_date_of_birth' => 'required|date',
            'customer_email' => 'required|email',

            'company_name' => 'required|string',
            'company_phone' => 'required|string',
            'customer_current_position' => 'required|string',
            'company_address' => 'required|string',
            'company_email' => 'required|email',
            'customer_employment_start_date' => 'required|date',
        ];

        if (!Auth::check()) {
            $rules['password'] = 'required|confirmed|min:8';
        }

        return $rules;
    }

    public function boot(KYCService $kycService, StatusService $statusService)
    {
        $this->kycService = $kycService;
        $this->statusService = $statusService;
    }

    public function mount()
    {
        try {
            $this->loadData();
        } catch (Throwable $err) {
            $this->handle($err)->message;
            flash()->error(__('Error loading KYC Form'));
        }
    }

    public function loadData()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $this->kyc_submission = $this->kycService
                ->allQuery(['user_id' => $user->id])
                ->get()
                ->last();

            $this->customer_name = $this->kyc_submission->customer_name ?? $user->name ?? '';
            $this->customer_phone = $this->kyc_submission->customer_phone ?? $user->phone ?? '';
            $this->customer_phone_country_code = $this->kyc_submission->customer_phone_country_code ?? $user->phone_country_code ?? 'gh';
            $this->customer_address = $this->kyc_submission->customer_address ?? $user->delivery_address ?? '';
            $this->customer_ghana_card_number = $this->kyc_submission->customer_ghana_card_number ?? $user->ghana_card_number ?? '';
            $this->customer_date_of_birth = $this->kyc_submission->customer_date_of_birth ?? $user->date_of_birth ?? '';
            $this->customer_email = $this->kyc_submission->customer_email ?? $user->email ?? '';

            $this->company_name = $this->kyc_submission->company_name ?? $user->company_name ?? '';
            $this->company_phone = $this->kyc_submission->company_phone ?? $user->company_phone ?? '';
            $this->company_phone_country_code = $this->kyc_submission->company_phone_country_code ?? $user->company_phone_country_code ?? 'gh';
            $this->company_address = $this->kyc_submission->company_address ?? $user->company_address ?? '';
            $this->customer_current_position = $this->kyc_submission->customer_current_position ?? $user->current_position ?? '';
            $this->customer_employment_start_date = $this->kyc_submission->customer_employment_start_date ?? $user->employment_start_date ?? '';
            $this->company_email = $this->kyc_submission->company_email ?? $user->company_email ?? '';
            $this->header = __('Update your KYC');

            $this->kyc_submission_approved = $this->statusService->isApproved($this->kyc_submission->status_id);
            $this->edit_mode = $this->kyc_submission ? false : true;
        } else {
            $this->header = __('Register');
            $this->edit_mode = true;
        }
    }

    public function save()
    {
        $validated_input = $this->validate();
        try {
            DB::beginTransaction();
            $validated_input['customer_phone'] = str_replace(' ', '', $this->customer_phone);
            $validated_input['customer_phone_prefix'] = '+' . $this->customer_phone_prefix;
            $validated_input['customer_phone_country_code'] = $this->customer_phone_country_code;
            $validated_input['company_phone'] = str_replace(' ', '', $this->company_phone);
            $validated_input['company_phone_prefix'] = '+' . $this->company_phone_prefix;
            $validated_input['company_phone_country_code'] = $this->company_phone_country_code;
            $this->kycService->submitKYC($validated_input, $this->kyc_submission->id ?? null);
            $this->loadData();
            DB::commit();
            flash()->success('KYC successfully submitted!');
            $this->dispatch('delay-reload');
        } catch (Throwable $err) {
            DB::rollBack();
            $default_message = __('Error submitting KYC info. Please try again later');
            $message = $this->handle($err, $default_message)->message;
            toastr()->error($message);
        }
    }

    public function edit()
    {
        try {
            $this->edit_mode = true;
        } catch (Throwable $err) {
            DB::rollBack();
            $default_message = __('Error switching to edit mode');
            $this->handle($err, $default_message)->message;
            toastr()->error($default_message);
        }
    }

    public function cancel()
    {
        try {
            if ($this->kyc_submission) {
                $this->edit_mode = false;
            } else {
                redirect()->route('home');
            }
        } catch (Throwable $err) {
            DB::rollBack();
            $default_message = __('Error switching to edit mode');
            $this->handle($err, $default_message)->message;
            redirect()->route('home');
        }
    }

    public function render()
    {
        return view('livewire.client.kyc.kyc');
    }
}

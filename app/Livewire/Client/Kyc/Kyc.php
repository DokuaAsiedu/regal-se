<?php

namespace App\Livewire\Client\Kyc;

use App\Services\CompanyService;
use App\Services\CompanyStaffService;
use App\Services\KYCService;
use App\Services\StatusService;
use App\Traits\HandlesErrorMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Throwable;

class KYC extends Component
{
    use HandlesErrorMessage;

    public $header;
    public $name;
    public $phone_prefix;
    public $phone;
    public $phone_country_code;
    public $address;
    public $ghana_card_number;
    public $date_of_birth;
    public $email;
    public $password;
    public $password_confirmation;

    public $company_name;
    public $current_position;
    public $employment_start_date;
    public $company_id = '';
    public $staff_id;

    public $kyc;
    public $edit_mode;
    public $kyc_approved;

    protected $kycService;
    protected $statusService;
    protected $companyService;
    protected $companyStaffService;

    protected function rules()
    {
        $rules = [
            'name' => 'required|string',
            'phone' => 'required|string',
            'address' => 'required|string',
            'ghana_card_number' => 'required|string',
            'date_of_birth' => 'required|date',
            'email' => 'required|email',

            'company_id' => 'required|exists:companies,id',
            'staff_id' => 'required|string',
            'current_position' => 'required|string',
            'employment_start_date' => 'required|date',
        ];

        if (!Auth::check()) {
            $rules['password'] = 'required|confirmed|min:8';
        }

        return $rules;
    }

    protected function validationAttributes() 
    {
        return [
            'company_id' => 'company',
        ];
    }

    public function boot(KYCService $kycService, StatusService $statusService, CompanyService $companyService, CompanyStaffService $companyStaffService)
    {
        $this->kycService = $kycService;
        $this->statusService = $statusService;
        $this->companyService = $companyService;
        $this->companyStaffService = $companyStaffService;
    }

    public function mount()
    {
        try {
            $this->loadData();
        } catch (Throwable $err) {
            $this->handle($err)->message;
            // flash()->error(__('Error loading KYC Form'));
        }
    }

    public function loadData()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $this->kyc = $this->kycService
                ->allQuery(['user_id' => $user->id])
                ->get()
                ->last();

            $this->name = $this->kyc->name ?? $user->name ?? '';
            $this->phone = $this->kyc->phone ?? $user->phone ?? '';
            $this->phone_country_code = $this->kyc->phone_country_code ?? $user->phone_country_code ?? 'gh';
            $this->address = $this->kyc->address ?? $user->delivery_address ?? '';
            $this->ghana_card_number = $this->kyc->ghana_card_number ?? $user->ghana_card_number ?? '';
            $this->date_of_birth = $this->kyc->date_of_birth ?? $user->date_of_birth ?? '';
            $this->email = $this->kyc->email ?? $user->email ?? '';

            $this->company_id = $this->kyc->company->id ?? '';
            $this->company_name = $this->kyc->company->name ?? '';
            $this->staff_id = $this->kyc->staff_id ?? '';
            $this->current_position = $this->kyc->current_position ?? $user->current_position ?? '';
            $this->employment_start_date = $this->kyc->employment_start_date ?? $user->employment_start_date ?? '';
            $this->header = __('Update your KYC');

            $this->kyc_approved = $this->statusService->isApproved($this->kyc->status_id);
            $this->edit_mode = $this->kyc ? false : true;
        } else {
            $this->header = __('Register');
            $this->edit_mode = true;
        }
    }

    #[Computed()]
    public function companies()
    {
        return $this->companyService
            ->allQuery()
            ->active()
            ->get();
    }

    public function save()
    {
        $validated_input = $this->validate();
        try {
            DB::beginTransaction();
            $validated_input['phone'] = str_replace(' ', '', $this->phone);
            $validated_input['phone_prefix'] = '+' . $this->phone_prefix;
            $validated_input['phone_country_code'] = $this->phone_country_code;
            $this->kycService->submitKYC($validated_input, $this->kyc->id ?? null);
            $this->loadData();
            DB::commit();
            flash()->success('KYC successfully submitted!');
            $this->dispatch('delay-reload');
        } catch (Throwable $err) {
            DB::rollBack();
            $default_message = __('Error submitting KYC info. Please try again later');
            $message = $this->handle($err, $default_message)->message;
            flash()->error($message);
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
            flash()->error($default_message);
        }
    }

    public function cancel()
    {
        try {
            if ($this->kyc) {
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

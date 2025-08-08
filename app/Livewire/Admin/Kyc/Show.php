<?php

namespace App\Livewire\Admin\Kyc;

use App\Services\KYCService;
use App\Services\StatusService;
use App\Traits\HandlesErrorMessage;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;
use Throwable;

class Show extends Component
{
    use HandlesErrorMessage;

    public $kyc_id;
    public $kyc;
    public $customer_name;
    public $customer_phone;
    public $customer_address;
    public $customer_ghana_card_number;
    public $customer_date_of_birth;
    public $customer_email;
    public $company_name;
    public $customer_current_position;
    public $company_phone;
    public $company_address;
    public $company_email;
    public $customer_employment_start_date;

    public $is_pending;

    protected $kycService;
    protected $statusService;

    public function boot(KYCService $kycService, StatusService $statusService)
    {
        $this->kycService = $kycService;
        $this->statusService = $statusService;
    }

    public function mount($kycId)
    {
        try {
            $this->kyc_id = $kycId;
            $this->loadData();
        } catch (Throwable $err) {
            $default_message = __('Error showing KYC');
            $message = $this->handle($err, $default_message)->message;
            flash()->error($message);
        }
    }

    public function loadData()
    {
        $this->kyc = $this->kycService->find($this->kyc_id);
        $this->customer_name = $this->kyc->customer_name ?? 'N/A';
        $this->customer_phone = formatPhone($this->kyc->customer_phone, $this->kyc->customer_phone_prefix) ?? 'N/A';
        $this->customer_address = $this->kyc->customer_address ?? 'N/A';
        $this->customer_ghana_card_number = $this->kyc->customer_ghana_card_number ?? 'N/A';
        $this->customer_date_of_birth = $this->kyc->customer_date_of_birth ?? 'N/A';
        $this->customer_email = $this->kyc->customer_email ?? 'N/A';
        $this->company_name = $this->kyc->company_name ?? 'N/A';
        $this->customer_current_position = $this->kyc->customer_current_position ?? 'N/A';
        $this->company_phone = formatPhone($this->kyc->company_phone, $this->kyc->company_phone_prefix) ?? 'N/A';
        $this->company_address = $this->kyc->company_address ?? 'N/A';
        $this->company_email = $this->kyc->company_email ?? 'N/A';
        $this->customer_employment_start_date = $this->kyc->customer_employment_start_date ?? 'N/A';

        $this->is_pending = $this->statusService->isPending($this->kyc->status_id);
    }

    public function approve()
    {
        try {
            DB::beginTransaction();
            $this->kycService->approveKYC($this->kyc);
            DB::commit();
            $this->loadData();
            flash()->success(__('Successfully approved KYC'));
        } catch (Throwable $err) {
            $default_message = __('Error approving KYC');
            $message = $this->handle($err, $default_message)->message;
            flash()->error($message);
        }
    }

    #[On('reject-kyc')]
    public function reject($id, $reason)
    {
        try {
            DB::beginTransaction();
            $this->kycService->rejectKYC($this->kyc, $reason);
            DB::commit();
            $message = __('KYC rejected');
            flash()->success($message);
            $this->dispatch('closeModal');
            $this->loadData();
        } catch (Throwable $err) {
            DB::rollBack();
            $default_message = __('Error rejecting KYC');
            $message = $this->handle($err, $default_message)->message;
            flash()->error($message);
        }
    }

    public function showRejectionModal($id): void
    {
        $this->dispatch('openModal', component: 'components.confirmation-modal', arguments: [ 
            'event' => 'reject-kyc',
            'id' =>  $id,
            'confirmText' => __('Reject'),
            'showInput' => true,
        ]);
    }

    public function render()
    {
        return view('livewire.admin.kyc.show');
    }
}

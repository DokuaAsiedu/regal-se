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

    public $kyc;
    public $phone;
    public $date_of_birth;
    public $ghana_card_number;
    public $company;

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
            $this->loadData($kycId);
        } catch (Throwable $err) {
            $default_message = __('Error showing KYC');
            $message = $this->handle($err, $default_message)->message;
            flash()->error($message);
        }
    }

    public function loadData($kyc_id)
    {
        $this->kyc = $this->kycService->find($kyc_id);
        $this->phone = formatPhone($this->kyc->phone, $this->kyc->phone_prefix) ?? 'N/A';
        $this->date_of_birth = $this->kyc->date_of_birth ?? 'N/A';
        $this->company = $this->kyc->company;

        $this->is_pending = $this->statusService->isPending($this->kyc->status_id);
    }

    public function approve()
    {
        try {
            DB::beginTransaction();
            $this->kycService->approveKYC($this->kyc);
            DB::commit();
            $this->loadData($this->kyc->id);
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
            $this->loadData($this->kyc->id);
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

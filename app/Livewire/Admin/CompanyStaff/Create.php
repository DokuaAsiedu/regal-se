<?php

namespace App\Livewire\Admin\CompanyStaff;

use App\Services\CompanyService;
use App\Services\CompanyStaffService;
use App\Traits\HandlesErrorMessage;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Throwable;

class Create extends Component
{
    use HandlesErrorMessage;

    public $company_id = '';
    public $staff_id;
    public $ghana_card_number;
    public $employee;

    protected $companyService;
    protected $companyStaffService;

    protected $rules = [
        'company_id' => 'required|exists:companies,id',
        'staff_id' => 'required|string|min:2',
        'ghana_card_number' => 'required|string|min:3',
    ];

    protected function validationAttributes() 
    {
        return [
            'company_id' => 'company',
        ];
    }

    public function boot(CompanyService $companyService, CompanyStaffService $companyStaffService)
    {
        $this->companyService = $companyService;
        $this->companyStaffService = $companyStaffService;
    }

    public function mount()
    {
        try {
            // $this->loadData($id);
        } catch (Throwable $err) {
            $message = $this->handle($err)->message;
            flash()->error(__('Error mounting component') . ': '. $message);
        }
    }

    public function loadData($employee_id)
    {
        $this->employee = $this->companyStaffService->find($employee_id);
        if ($this->employee) {
            $this->staff_id = $this->employee->staff_id;
            $this->ghana_card_number = $this->employee->ghana_card_number;
            $this->company_id = $this->employee->company_id;
        }
    }

    #[Computed]
    public function companies()
    {
        return $this->companyService->all();
    }

    public function updated($prop, $value)
    {
        $this->dispatch('companyUpdated', $value);
        $this->dispatch('$refresh');
    }

    #[On('edit-staff')] 
    public function updateStaff($id)
    {
        try {
            $this->loadData($id);
        } catch (Throwable $err) {
            $message = $this->handle($err)->message;
            flash()->error(__('Error loading employee info') . ': '. $message);
        }
    }

    public function save()
    {
        $this->validate();
        try {
            $payload = [
                'staff_id' => $this->staff_id,
                'ghana_card_number' => $this->ghana_card_number,
                'company_id' => $this->company_id,
            ];
            DB::beginTransaction();
            if ($this->employee) {
                $this->companyStaffService->update($this->employee->id, $payload);
                $message = 'Employee successfully updated';
            } else {
                $this->companyStaffService->store($payload);
                $message = 'Employee successfully added';
            }
            DB::commit();
            flash()->success($message);
            $this->dispatch('refresh');
        } catch (Throwable $err) {
            DB::rollBack();
            $message = $this->handle($err)->message;
            flash()->error($message);
        }
    }

    public function render()
    {
        return view('livewire.admin.company-staff.create');
    }
}

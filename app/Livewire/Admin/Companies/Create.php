<?php

namespace App\Livewire\Admin\Companies;

use App\Services\CompanyService;
use App\Traits\HandlesErrorMessage;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Throwable;

class Create extends Component
{
    use HandlesErrorMessage;

    public $company;
    public $name;
    public $code;
    public $phone;
    public $phone_prefix;
    public $phone_country_code;
    public $email;
    public $address;
    public $status = '';

    public $header;
    public $success_message;

    protected $rules = [
        'name' => 'required|string|min:2',
        'code' => 'nullable|string|min:2',
        'phone' => 'required|string',
        'email' => 'required|email',
        'address' => 'required|string',
        'status' => 'required|exists:status,id',
    ];

    protected $companyService;

    public function mount($id = null)
    {
        try {
            $this->loadData($id);
        } catch (Throwable $err) {
            $message = $this->handle($err)->message;
            flash()->error(__('Error mounting component') . ': '. $message);
        }
    }

    public function boot(CompanyService $companyService)
    {
        $this->companyService = $companyService;
    }

    #[Computed()]
    public function validStatuses()
    {
        return $this->companyService->validStatuses();
    }

    public function loadData($id)
    {
        if (isset($id)) {
            $this->company = $this->companyService->find($id);
            $this->name = $this->company->name ?? '';
            $this->code = $this->company->code ?? '';
            $this->email = $this->company->email ?? '';
            $this->address = $this->company->address ?? '';
            $this->phone = $this->company->phone ?? '';
            $this->phone_country_code = $this->company->phone_country_code ?? '';
            $this->phone_prefix = $this->company->phone_prefix ?? '';
            $this->status = $this->company->status->id ?? '';

            $this->header = __('Edit Company');
            $this->success_message = __('Company successfully updated');
        } else {
            $this->header = __('Create New Company');
            $this->success_message = __('Company successfully created');
        }
    }

    public function save()
    {
        $this->validate();
        try {
            DB::beginTransaction();
            $phone = str_replace(' ', '', $this->phone);
            $phone_prefix = '+' . $this->phone_prefix;
            $phone_country_code = $this->phone_country_code;
            $payload = [
                'name' => $this->name,
                'code' => $this->code,
                'email' => $this->email,
                'address' => $this->address,
                'status_id' => $this->status,
                'phone' => $phone,
                'phone_prefix' => $phone_prefix,
                'phone_country_code' => $phone_country_code,
            ];
            if ($this->company) {
                $this->companyService->update($this->company->id, $payload);
            } else {
                $this->companyService->store($payload);
            }
            DB::commit();
            flash()->success($this->success_message);
            return redirect()->route('companies.index');
        } catch (Throwable $err) {
            DB::rollBack();
            $message = $this->handle($err)->message;
            flash()->error($message);
        }
    }

    public function render()
    {
        return view('livewire.admin.companies.create');
    }
}

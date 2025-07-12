<?php

namespace App\Livewire\Admin\StoreSettings;

use App\Services\StoreSettingsService;
use App\Traits\HandlesErrorMessage;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Throwable;

class Edit extends Component
{
    use HandlesErrorMessage;

    public $currency_code;
    public $currency_name;
    public $currency_symbol;
    public $repayment_months;
    public $down_payment_percentage;

    protected $storeSettingsService;

    protected $rules = [
        'currency_code' => 'required|string|min:1',
        'currency_name' => 'required|string|min:1',
        'currency_symbol' => 'required|string|min:1',
        'repayment_months' => 'required|integer|min:1',
        'down_payment_percentage' => 'required|numeric|min:1|max:99',
    ];

    public function boot(StoreSettingsService $storeSettingsService)
    {
        $this->storeSettingsService = $storeSettingsService;
    }

    public function mount()
    {
        try {
            $this->loadData();
        } catch (Throwable $err) {
            $message = $this->handle($err)->message;
            toastr()->error(__('Error mounting component') . ': '. $message);
        }
    }

    public function loadData()
    {
        $this->currency_name = $this->storeSettingsService
            ->allQuery(['code' => 'currency_name'])
            ->first()
            ->value;
        $this->currency_code = $this->storeSettingsService
            ->allQuery(['code' => 'currency_code'])
            ->first()
            ->value;
        $this->currency_symbol = $this->storeSettingsService
            ->allQuery(['code' => 'currency_symbol'])
            ->first()
            ->value;
        $this->repayment_months = $this->storeSettingsService
            ->allQuery(['code' => 'repayment_months'])
            ->first()
            ->value;
        $this->down_payment_percentage = $this->storeSettingsService
            ->allQuery(['code' => 'down_payment_percentage'])
            ->first()
            ->value;
    }

    public function save()
    {
        $this->validate();
        try {
            DB::beginTransaction();
            $updates = [
                'currency_code' => $this->currency_code,
                'currency_name' => $this->currency_name,
                'currency_symbol' => $this->currency_symbol,
                'repayment_months' => $this->repayment_months,
                'down_payment_percentage' => $this->down_payment_percentage,
            ];
            foreach ($updates as $code => $value) {
                $this->storeSettingsService
                    ->allQuery(['code' => $code])
                    ->update([
                        'value' => $value,
                    ]);
            }
            DB::commit();
            toastr()->success('Store settings successfully updated');
        } catch (Throwable $err) {
            DB::rollBack();
            $message = $this->handle($err)->message;
            toastr()->error($message);
        }
    }

    public function render()
    {
        return view('livewire.admin.store-settings.edit');
    }
}

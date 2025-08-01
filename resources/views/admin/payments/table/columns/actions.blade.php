<div class="flex gap-2">
    <x-button :name="__('View')" icon:trailing="eye" :href="route('payments.show', ['payment' => $row->id])" />
</div>

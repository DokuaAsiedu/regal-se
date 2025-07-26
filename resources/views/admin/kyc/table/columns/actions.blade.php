<div class="flex gap-2">
    <x-button :name="__('View')" icon:trailing="eye" :href="route('kyc.show', ['kyc' => $row->id])" />
</div>
<!-- We must ship. - Taylor Otwell -->

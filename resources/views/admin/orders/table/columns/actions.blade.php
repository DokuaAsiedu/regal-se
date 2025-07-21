<div class="flex gap-2">
    <x-button :name="__('View')" icon:trailing="eye" :href="route('orders.show', ['order' => $row->id])" />
</div>
<!-- Very little is needed to make a happy life. - Marcus Aurelius -->

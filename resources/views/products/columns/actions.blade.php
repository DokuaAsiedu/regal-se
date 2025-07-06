<div class="flex gap-2">
    @include('products.actions.edit-button', ['value' => $row->id])
    @include('products.actions.delete-button', ['value' => $row->id])
</div>
<x-layouts.admin :title="__('Edit Product')">
    <div id="app"></div>

    <script>
        window.product = @json($product);
        window.statuses = @json($statuses);
        window.productCategories = @json($product_categories);
        window.availableCategories = @json($available_categories);
        window.productImages = @json($product_images);
    </script>
</x-layouts.admin>
<x-layouts.admin :title="__('Create New Product')">
    <div id="app"></div>
    <script>
        window.statuses = @json($statuses);
        window.availableCategories = @json($available_categories);
    </script>
</x-layouts.admin>
<!-- No surplus words or unnecessary actions. - Marcus Aurelius -->

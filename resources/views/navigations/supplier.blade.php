<div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
    <x-nav-link :href="route('supplierDashboardShow')" :active="request()->routeIs('supplierDashboardShow')">
        {{ __('Головна') }}
    </x-nav-link>
</div>
<div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
    <x-nav-link :href="route('supplierOrders')" :active="request()->routeIs('supplierOrders')">
        {{ __('Історія замовлень') }}
    </x-nav-link>
</div>

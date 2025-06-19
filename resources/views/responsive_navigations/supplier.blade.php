<div class="pt-2 pb-3 space-y-1">
    <x-responsive-nav-link :href="route('dispatcherDashboardShow')" :active="request()->routeIs('dispatcherDashboardShow')">
        {{ __('Головна') }}
    </x-responsive-nav-link>
</div>
<div class="pt-2 pb-3 space-y-1">
    <x-responsive-nav-link :href="route('supplierOrders')" :active="request()->routeIs('supplierOrders')">
        {{ __('Історія замовлень') }}
    </x-responsive-nav-link>
</div>

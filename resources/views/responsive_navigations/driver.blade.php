
<div class="pt-2 pb-3 space-y-1">
    <x-responsive-nav-link :href="route('driverDashboardShow')" :active="request()->routeIs('driverDashboardShow')">
        {{ __('Головна') }}
    </x-responsive-nav-link>
</div>
<div class="pt-2 pb-3 space-y-1">
    <x-responsive-nav-link :href="route('driverOrder')" :active="request()->routeIs('driverOrder')">
        {{ __('Поточне замовлення') }}
    </x-responsive-nav-link>
</div>
<div class="pt-2 pb-3 space-y-1">
    <x-responsive-nav-link :href="route('driverOrders')" :active="request()->routeIs('driverOrders')">
        {{ __('Список замовлень') }}
    </x-responsive-nav-link>
</div>

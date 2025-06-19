<div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
    <x-nav-link :href="route('driverDashboardShow')" :active="request()->routeIs('driverDashboardShow')">
        {{ __('Головна') }}
    </x-nav-link>
</div>
<div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
    <x-nav-link :href="route('driverOrder')" :active="request()->routeIs('driverOrder')">
        {{ __('Поточне замовлення') }}
    </x-nav-link>
</div>
<div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
    <x-nav-link :href="route('driverOrders')" :active="request()->routeIs('driverOrders')">
        {{ __('Список замовлень') }}
    </x-nav-link>
</div>

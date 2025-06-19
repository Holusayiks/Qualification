<div class="pt-2 pb-3 space-y-1">
    <x-responsive-nav-link :href="route('dispatcherDashboardShow')" :active="request()->routeIs('dispatcherDashboardShow')">
        {{ __('Головна') }}
    </x-responsive-nav-link>
</div>
<div class="pt-2 pb-3 space-y-1">
    <x-responsive-nav-link :href="route('driversDashboard')" :active="request()->routeIs('driversDashboard')">
        {{ __('Водії на станції') }}
    </x-responsive-nav-link>
</div>
<div class="pt-2 pb-3 space-y-1">
    <x-responsive-nav-link :href="route('driversInVacationDashboard')" :active="request()->routeIs('driversInVacationDashboard')">
        {{ __('Водії у відпусці') }}
    </x-responsive-nav-link>
</div>

<div class="pt-2 pb-3 space-y-1">
    <x-responsive-nav-link :href="route('dispatcherDashboardRides')" :active="request()->routeIs('dispatcherDashboardRides')">
        {{ __('Замовлення') }}
    </x-responsive-nav-link>
</div>
<div class="pt-2 pb-3 space-y-1">
    <x-responsive-nav-link :href="route('autosDashboard')" :active="request()->routeIs('autosDashboard')">
        {{ __('Авто на станції') }}
    </x-responsive-nav-link>
</div>
<div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
    <x-nav-link :href="route('dispatcherDashboardStations')" :active="request()->routeIs('dispatcherDashboardStations')">
        {{ __('Список станцій') }}
    </x-nav-link>
</div>


<div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
    <x-nav-link :href="route('dispatcherDashboardShow')" :active="request()->routeIs('dispatcherDashboardShow')">
        {{ __('Головна') }}
    </x-nav-link>
</div>
<div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
    <x-nav-link :href="route('driversDashboard')" :active="request()->routeIs('driversDashboard')">
        {{ __('Водії на станції') }}
    </x-nav-link>
</div>
<div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
    <x-nav-link :href="route('driversInVacationDashboard')" :active="request()->routeIs('driversInVacationDashboard')">
        {{ __('Водії у відпусці') }}
    </x-nav-link>
</div>

<div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
    <x-nav-link :href="route('dispatcherDashboardRides')" :active="request()->routeIs('dispatcherDashboardRides')">
        {{ __('Замовлення') }}
    </x-nav-link>
</div>
<div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
    <x-nav-link :href="route('autosDashboard')" :active="request()->routeIs('autosDashboard')">
        {{ __('Авто на станції') }}
    </x-nav-link>
</div>
<div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
    <x-nav-link :href="route('dispatcherDashboardStations')" :active="request()->routeIs('dispatcherDashboardStations')">
        {{ __('Список станцій') }}
    </x-nav-link>
</div>



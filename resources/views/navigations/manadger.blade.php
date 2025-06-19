<div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
    <x-nav-link :href="route('menedgerDashboardShow')" :active="request()->routeIs('menedgerDashboardShow')">
        {{ __('Головна') }}
    </x-nav-link>
</div>

<div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
    <x-nav-link :href="route('menedgerDashboardAutos')" :active="request()->routeIs('menedgerDashboardAutos')">
        {{ __('Авто на станції') }}
    </x-nav-link>
</div>


<div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
    <x-nav-link :href="route('menedgerDashboardRides')" :active="request()->routeIs('menedgerDashboardRides')">
        {{ __('Замовлення') }}
    </x-nav-link>
</div>


<div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
    <x-nav-link :href="route('menedgerDashboardFinanse')" :active="request()->routeIs('menedgerDashboardFinanse')">
        {{ __('Фінанси') }}
    </x-nav-link>
</div>
<div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
    <x-nav-link :href="route('menedgerDashboardOrders')" :active="request()->routeIs('menedgerDashboardOrders')">
        {{ __('Cтворення пакетів замовлень') }}
    </x-nav-link>
</div>
<div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
    <x-nav-link :href="route('menedgerDashboardStations')" :active="request()->routeIs('menedgerDashboardStations')">
        {{ __('Список станцій') }}
    </x-nav-link>
</div>
<div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
    <x-nav-link :href="route('menedgerDashboardJournal')" :active="request()->routeIs('menedgerDashboardJournal')">
        {{ __('Страховий фонд') }}
    </x-nav-link>
</div>

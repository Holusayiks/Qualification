<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            @yield('title')
        </h2>
    </x-slot>

    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @yield('before-content')
            <?php
            if(!isset($hide_main)){
            ?>
            <div class="bg-white dark:bg-gray-800 overflow-auto shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                       @yield('content')
                </div>
            </div>
            <?php
            }
            ?>
            @yield('after-content')
        </div>
    </div>
</x-app-layout>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- JQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<!-- <img class="opacity-5 h-16 w-16" src="{{ url('images/empty_box.png') }}" alt="empty_box"> -->
<body >
    <div class="absolute h-full w-full bg-center" style="background-image: url('{{ asset('/images/road.jpg')}}')">
        <div class="relative h-full w-full ">
            @if (Route::has('login'))
            <div class="absolute items-center h-50 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 flex flex-col gap-2 bg-gray-300/75 p-5 rounded-lg">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>
                @auth
                <a
                    href="{{ url('/dashboard') }}"
                    class="text-center w-36 text-white bg-green-700 hover:bg-green-800 focus:ring-2 focus:ring-green-700 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800">
                    Головна
                </a>
                @else
                <a
                    href="{{ route('login') }}"
                    class="text-center w-36 text-white bg-green-700 hover:bg-green-800 focus:ring-2 focus:ring-green-700 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800">
                    Увійти
                </a>

                @if (Route::has('register'))
                <a
                    href="{{ route('register') }}"
                    class="text-center w-36 text-white bg-green-700 hover:bg-green-800 focus:ring-2 focus:ring-green-700 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800">
                    Реєстрація
                </a>
                @endif
                @endauth
            </div>
            @endif

        </div>
    </div>
</body>

</html>

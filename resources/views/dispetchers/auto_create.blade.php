@extends('layouts.main')
@section('title','Додавання авто')


@section('navlinks')
@include('navigations.dispatcher')
@endsection
@section('responsive_navlinks')
@include('responsive_navigations.dispatcher')
@endsection

@section('content')
<form class="mx-44" method="POST" action="/dispetcher/dashboard/autos/store">
    @csrf

    <div class="mt-10 grid grid-cols-1">
        @if ($errors->any())
        @foreach ($errors->all() as $error)
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 mt-3 py-3 rounded relative" role="alert">
            <strong class="font-bold">Помилка</strong>
            <span class="block sm:inline">{{ $error }}</span>
            <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <title>Close</title>
                    <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                </svg>
            </span>
        </div>
        @endforeach
        @endif
        <label class="block text-sm font-medium leading-6 text-gray-900" for="nomer">Номер</label>
        <input class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm
            ring-1 ring-inset ring-gray-300 placeholder:text-gray-400
            focus:ring-2 focus:ring-inset focus:ring-indigo-600
            sm:text-sm sm:leading-6" type="text" name="nomer" id="nomer">
        <label class="block text-sm font-medium leading-6 text-gray-900" for="lifting_weight">Підйомна вага</label>
        <input class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm
            ring-1 ring-inset ring-gray-300 placeholder:text-gray-400
            focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
            type="number" min="0" step="10" name="lifting_weight" id="lifting_weight">

        <label class="block text-sm font-medium leading-6 text-gray-900" for="type">Тип авто</label>
        <select name="type" id="type" class="block w-full rounded-md
            border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300
            focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6">
            <option value="">Обрати</option>
            <option value="Цистерна">Цистерна</option>
            <option value="Рефрежиратор">Рефрежиратор</option>
            <option value="Контейнер">Контейнер</option>
        </select>
    </div>
    <div class="relative w-full h-10">
        <button class="btn bg-green-400 m-2 p-2 rounded-lg text-white hover:bg-green-600 transition absolute inset-y-0 right-0 h-10" role="submit">Додати авто</button>
    </div>
</form>
@endsection

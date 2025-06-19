@extends('layouts.main')
@section('title','Редагувати дані')

@section('navlinks')
@include('navigations.driver')
@endsection
@section('responsive_navlinks')
@include('responsive_navigations.driver')
@endsection

@section('content')
<form method="POST" action="/driver/dashboard/{{$driver->id}}/update">
    @csrf
    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
        <div class="sm:col-span-3">
            <label class="block text-sm font-medium leading-6 text-gray-900" for="name">Ім'я</label>
            <input class="block w-full rounded-md border-0 py-1.5 text-gray-900
            shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400
            focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                type="text" name="name" id="name" value="{{$driver->full_name}}">
        </div>
        <div class="sm:col-span-3">
            <label class="block text-sm font-medium leading-6 text-gray-900" for="name">Телефон</label>
            <input class="block w-full rounded-md border-0 py-1.5 text-gray-900
            shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400
            focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                type="text" name="phone" id="phone" value="{{$driver->phone}}">
        </div>
    </div>
    @if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 mt-3 py-3 rounded relative" role="alert">
        <strong class="font-bold">Помилка</strong>
        <span class="block sm:inline">

            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </span>
        <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
            <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                <title>Close</title>
                <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
            </svg>
        </span>
    </div>

    @endif
    <div class="relative w-full h-10">
        <button class="btn bg-green-400 m-2 p-2 rounded-lg text-white hover:bg-green-600 transition absolute inset-y-0 right-0 w-32 h-10" role="submit">Submit</button>
    </div>
</form>
@endsection

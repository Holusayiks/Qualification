@extends('layouts.main')
@section('title','Edit order')


@section('navlinks')
@include('navigations.supplier')
@endsection
@section('responsive_navlinks')
@include('responsive_navigations.supplier')
@endsection


@section('content')
<form class="mx-44" method="POST" action="/supplier/dashboard/orders/{{$order->id}}/update">
    @csrf


    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
        <div class="sm:col-span-3">
            <label class="block text-sm font-medium leading-6 text-gray-900" for="supplier_id">Supplier</label>
            <select multiple name="orders" id="order" class="block w-full rounded-md
            border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300
            focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6">
                <option value="">Choose</option>
                @foreach ($suppliers as $item)
                    <option {{ $supplier->id==$item->id?"selected":""}} value="{{ $item->id }}">{{ $item->full_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="sm:col-span-3">
            <label class="block text-sm font-medium leading-6 text-gray-900" for="car_type">Car type</label>
            <input class="block w-full rounded-md border-0 py-1.5 text-gray-900
            shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400
            focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
            type="text" name="car_type" id="car_type" value='{{$order->auto_type}}'>
        </div>
    </div>
    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
        <div class="sm:col-span-2">
            <label class="block text-sm font-medium leading-6 text-gray-900" for="point_A">Point A</label>
            <input class="block w-full rounded-md border-0 py-1.5 text-gray-900
            shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400
            focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
            type="text" name="point_A" id="point_A" value='{{$order->point_A}}'>
        </div>
        <div class="sm:col-span-2">
            <label class="block text-sm font-medium leading-6 text-gray-900" for="point_B">Point B</label>
            <input class="block w-full rounded-md border-0 py-1.5 text-gray-900
            shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400
            focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
            type="text" name="point_B" id="point_B" value='{{$order->point_B}}'>
        </div>
        <div class="sm:col-span-2">
            <label class="block text-sm font-medium leading-6 text-gray-900" for="date_of_start">Date of start</label>
            <input class="block w-full rounded-md border-0 py-1.5 text-gray-900
            shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400
            focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
            type="date" name="date_of_start" id="date_of_start" value='{{$order->date_of_start}}'>
        </div>
    </div>
    <div class="relative w-full h-10">
        <button class="btn bg-green-400 m-2 p-2 rounded-lg text-white hover:bg-green-600 transition absolute inset-y-0 right-0 w-32 h-10" role="submit">Submit</button>
    </div>
</form>
@endsection


@extends('layouts.main')
@section('title', 'Головна')

@section('navlinks')
    @include('navigations.supplier')
@endsection

@section('responsive_navlinks')
    @include('responsive_navigations.supplier')
@endsection

@section('before-content')
    <button class="btn bg-green-400 m-2 p-2 rounded-lg text-white hover:bg-green-600 transition"
        onclick="location.href='/supplier/dashboard/orders/add'">Створити замовлення</button>
    <div class="bg-white dark:bg-gray-800 overflow-auto shadow-sm sm:rounded-lg mb-10">
        <div class="p-6 text-gray-900 dark:text-gray-100">
    <h4>{{ $supplier->full_name }}</h4>

    <p><b>Телефон:</b> {{ $supplier->phone }}</p>
    <p><b>Адреса:</b> {{ $supplier->address }}</p>
    <button class="btn bg-sky-400 m-1 p-1 rounded-sm text-white"
        onclick="location.href='/supplier/dashboard/{{ $supplier->id }}/edit'">Змінити дані</button>
    <!-- <button class="btn bg-red-400 m-1 p-1 rounded-sm text-white" onclick="location.href='/suppliers/{{ $supplier->id }}/delete'">Видалити профіль</button> -->


        </div>
    </div>
@endsection

@section('content')
            <b>Останні замовлення:</b>
            @foreach ($orders as $order)
                <p>{{ $order->product }} <i>{{ $order->status }}</i> ({{ date('d/m/Y', strtotime($order->date_of_start)) }})
                </p>
            @endforeach
@endsection

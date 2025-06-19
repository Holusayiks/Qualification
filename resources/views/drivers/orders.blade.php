@extends('layouts.main')
@section('title','Список замовлень')

@section('navlinks')

@include('navigations.driver')
@endsection

@section('content')
@if(count($rides)!=0)
<table class="w-full">
    <thead>
        <tr>
            {{-- <th class="border border-slate-300 text-center p-2">#</th> --}}
            <th class="border border-slate-300 text-center p-2">Дата відправки</th>
            <th class="border border-slate-300 text-center p-2">Маршрут</th>
            <th class="border border-slate-300 text-center p-2">Заробіток</th>
            <th class="border border-slate-300 text-center p-2">Відстань</th>
            <th class="border border-slate-300 text-center p-2">Вага</th>

            <th class="border border-slate-300 text-center p-2">Замовлення</th>
            <th class="border border-slate-300 text-center p-2">Замовник</th>
            <th class="border border-slate-300 text-center p-2">Вага</th>
            <th class="border border-slate-300 text-center p-2">Адреси</th>

            <th class="border border-slate-300 text-center p-2">Початкова і кінцева станція</th>
        </tr>
    </thead>
    <tbody class="text-xs">

        @foreach($rides as $ride)
        <tr>
            <?php
            $order_count = count($ride->orders);

            ?>
            {{-- <td rowspan="{{$order_count}}" class="border border-slate-300 text-center p-2">{{ $ride->id }}</td> --}}
            <td rowspan="{{$order_count}}" class="border border-slate-300 text-center p-2">{{ date('d/m/y',strtotime($ride->date)) }}</td>
            <td rowspan="{{$order_count}}" class="border border-slate-300 text-center p-2">{{ $ride->routes }}</td>
            <td rowspan="{{$order_count}}" class="border border-slate-300 text-center p-2">{{ ($ride->vutratu)*0.4 }} грн</td>
            <td rowspan="{{$order_count}}" class="border border-slate-300 text-center p-2">{{ $ride->distance }} км</td>
            <td rowspan="{{$order_count}}" class="border border-slate-300 text-center p-2">{{ $ride->weigth }} кг</td>
            <td class="border border-slate-300 text-center p-2">
                {{ $ride->orders[0]->product }}
            </td>
            <td class="border border-slate-300 text-center p-2">
                {{ $ride->orders[0]->supplier->full_name }} ({{ $ride->orders[0]->supplier->phone }})
            </td>
            <td class="border border-slate-300 text-center p-2">
                {{ $ride->orders[0]->weigth}} кг
            </td>
            <td class="border border-slate-300 text-center p-2">
                {{ $ride->orders[0]->point_A}} - {{ $ride->orders[0]->point_B}}
            </td>
            <td rowspan="{{$order_count}}" class="border border-slate-300 text-center p-2">{{ $ride->pointA }} - {{ $ride->pointB }}</td>

        </tr>
        @if($order_count>1)
        @foreach($ride->orders as $key=>$order)
        @if($key!=0)
        <tr>
            <td class="border border-slate-300 text-center p-2">
                {{ $order->product }}
            </td>
            <td class="border border-slate-300 text-center p-2">
                {{ $order->supplier->full_name }} ({{ $ride->orders[0]->supplier->phone }})
            </td>
            <td class="border border-slate-300 text-center p-2">
                {{ $order->weigth}} кг
            </td>
            <td class="border border-slate-300 text-center p-2">
                {{ $order->point_A}} - {{ $ride->orders[0]->point_B}}
            </td>
        </tr>
        @endif
        @endforeach
        @endif
        @endforeach
    </tbody>
</table>
@else
<div class="flex justify-center flex-col items-center">
    <img class="opacity-5 h-16 w-16" src="{{ url('images/empty_history.png') }}" alt="empty_box">
    <h2 class="opacity-25 ">Історія замовлень пуста</h2>

</div>
@endif
@endsection

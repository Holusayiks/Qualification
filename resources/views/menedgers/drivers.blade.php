@extends('layouts.main')
@section('title','Список водіїв на станції')


@section('navlinks')
@include('navigations.manadger')
@endsection
@section('responsive_navlinks')
@include('responsive_navigations.manadger')
@endsection

@section('content')
@if(count($drivers)!=0)

<table class="w-full">
    <thead>
        <tr>
            <th class="border border-slate-300">Ім'я</th>
            <th class="border border-slate-300">Телефон</th>
            <th class="border border-slate-300">Статус</th>
            <th class="border border-slate-300">Авто</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($drivers as $driver)
        <tr>
            <td class="border border-slate-300 text-center p-2">{{ $driver->full_name }}</td>
            <td class="border border-slate-300 text-center p-2">{{ $driver->phone }}</td>
            <?php
            $status_class = "";
            if($driver->status=='Хочу в відпустку'){
                $status_class="text-orange-500 bg-orange-100";
            }
            else if($driver->status=='В відпусці'){
                $status_class="text-yellow-500 bg-yellow-100";
            }
            else if ($driver->status == 'Аварійна ситуація') {
                $status_class = "text-red-500 bg-red-100";
            }
            ?>
            <td class="border border-slate-300 text-center p-2 {{$status_class}}">

                @if($driver->status=='В відпусці')
                {{'У відпусці з ' . date("d/m/Y", strtotime($driver->date_of_vacation)) . ' на ' .  $driver->days_in_vacation . ' днів'}}
                @elseif($driver->status=='Хочу в відпустку')
                {{ 'Очікує дозволу на відпустку з ' . date("d/m/Y", strtotime($driver->date_of_vacation)) . ' на ' .  $driver->days_in_vacation . ' днів' }}
                @else
                {{ $driver->status }}
                @endif
            </td>
            <td class="border border-slate-300 text-center p-2">{{ $driver->auto==null?' - ':$driver->auto->nomer . '(' . $driver->auto->type . ')' }}</td>
            <!-- <td class="border border-slate-300 flex justify-end">

            </td> -->
        </tr>
        @endforeach
    </tbody>
</table>
@else
<div class="flex justify-center flex-col items-center">
    <img class="opacity-5 h-16 w-16" src="{{ url('images/only_tires.png') }}" alt="empty_box">
    <h2 class="opacity-25 ">Станція не має водіїв</h2>

</div>
@endif
@endsection

@extends('layouts.main')
@section('title','Водії на станції')

@section('navlinks')
@include('navigations.dispatcher')
@endsection
@section('responsive_navlinks')
@include('responsive_navigations.dispatcher')
@endsection

@section('content')

    @if(count($drivers)!=0)
    <table class="w-full">
        <thead>
            <tr>
                <th class="border border-slate-300">Ім'я</th>
                <th class="border border-slate-300">Телефон</th>
                <th class="border border-slate-300">Відпустка</th>
                <th class="border border-slate-300">Дії</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($drivers as $driver)
            <tr>
                <td class="border border-slate-300 text-center p-2">{{ $driver->full_name }}</td>
                <td class="border border-slate-300 text-center p-2">{{ $driver->phone }}</td>
                <td class="border border-slate-300 text-center p-2">
                    {{'З ' . date("d/m/Y", strtotime($driver->date_of_vacation)) . ' на ' .  $driver->days_in_vacation . ' днів'}}
                </td>
                <td class="border border-slate-300 flex justify-center">
                    <button class="btn bg-green-400 m-1 p-1 text-sm rounded-sm text-white" onclick="location.href='/dispetcher/dashboard/drivers/{{$driver->id}}/status/returnfromvacation'">Повернути з відпустки</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="flex justify-center flex-col items-center">
    <img class="opacity-5 h-16 w-16" src="{{ url('images/vacation.png') }}" alt="empty_box">
    <h2 class="opacity-25 ">Водіїв у відпусці немає</h2>

</div>
    @endif
@endsection


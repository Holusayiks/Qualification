@extends('layouts.main')
@section('title','Авто на станції')

@section('navlinks')
@include('navigations.dispatcher')
@endsection
@section('responsive_navlinks')
@include('responsive_navigations.dispatcher')
@endsection


@section('before-content')
<button onclick="location.href='/dispetcher/dashboard/autos/create'" class="btn bg-blue-400 m-1 p-1 text-sm rounded-sm text-white my-3 px-2">Додати авто</button>
@endsection

@section('content')

@if(count($autos)!=0)
<table class="w-full">
    <thead>
        <tr>
            <th class="border border-slate-300">Номер</th>
            <th class="border border-slate-300">Тип</th>
            <th class="border border-slate-300">Водій</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($autos as $auto)
        <tr>
            <td class="border border-slate-300 text-center p-2">{{ $auto->nomer }}</td>
            <td class="border border-slate-300 text-center p-2">{{ $auto->type }}</td>
            <td class="border border-slate-300 text-center p-2">{{ $auto->driver!=null?$auto->driver->full_name . ' ('. $auto->driver->phone .')':' - ' }}</td>

        </tr>
        @endforeach
    </tbody>
</table>
@else
<div class="flex justify-center flex-col items-center">
    <img class="opacity-5 h-16 w-16" src="{{ url('images/only_tires.png') }}" alt="empty_box">
    <h2 class="opacity-25 ">Станція не має авто</h2>

</div>
@endif
@endsection

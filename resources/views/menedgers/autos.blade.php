@extends('layouts.main')
@section('title','Список авто на станції')

@section('navlinks')
@include('navigations.manadger')
@endsection
@section('responsive_navlinks')
@include('responsive_navigations.manadger')
@endsection

@section('content')
@if(count($autos)!=0)

<table class="w-full">
    <thead>
        <tr>
            <th class="border border-slate-300">Номер</th>
            <th class="border border-slate-300">Водій</th>
            <th class="border border-slate-300">Статус</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($autos as $auto)
        <tr>
            <td class="border border-slate-300 text-center p-2">{{ $auto->nomer }}</td>
            @if ( $auto->driver!=null)
            <td class="border border-slate-300 text-center p-2">{{ $auto->driver->full_name }}<br>{{ ' (' . $auto->driver->phone . ')'}}</td>
            <td class="border border-slate-300 text-center p-2">{{ $auto->driver->status}}</td>
            @else
            <td class="border border-slate-300 text-center p-2"> - </td>
            <td class="border border-slate-300 text-center p-2"> - </td>
            @endif
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

@extends('layouts.main')
@section('title','Головна')

@section('navlinks')
@include('navigations.dispatcher')
@endsection
@section('responsive_navlinks')
@include('responsive_navigations.dispatcher')
@endsection

@section('content')
<table class="w-full text-sm">
    <thead>
        <tr>
            <th class="border border-slate-300">Станція</th>
            <th class="border border-slate-300">Менеджер</th>
            <th class="border border-slate-300">Диспетчер</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($stations as $station)
        <tr>
            <td class="border border-slate-300 p-1">№{{ $station->nomer }} {{ $station->address }}</td>
            <td class="border border-slate-300 p-1">{{ $station->menedger->full_name }} ({{ $station->menedger->phone }})</td>
            <td class="border border-slate-300 p-1">{{ $station->dispetcher->full_name }} ({{ $station->dispetcher->phone }})</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection

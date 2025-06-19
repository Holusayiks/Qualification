@extends('layouts.main')
@section('title','Замовлення')


@section('navlinks')
@include('navigations.manadger')
@endsection
@section('responsive_navlinks')
@include('responsive_navigations.manadger')
@endsection

@section('content')
@if(count($rides)!=0)
<table class="w-full">
    <thead>
        <tr>
            <th class="border border-slate-300 text-center p-2">Статус замовлення</th>
            <th class="border border-slate-300 text-center p-2">Відстань замовлення</th>
            <th class="border border-slate-300 text-center p-2">Витрати на замовлення</th>
            <th class="border border-slate-300 text-center p-2">Чистий прибуток замовлення</th>
            <th class="border border-slate-300 text-center p-2">Зарплата водія</th>
            <th class="border border-slate-300 text-center p-2">Товар підзамовлення</th>
            <th class="border border-slate-300 text-center p-2">Відстань підзамовлення</th>
            <th class="border border-slate-300 text-center p-2">Витрати на підзамовлення</th>
            <th class="border border-slate-300 text-center p-2">Водій замовлення</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rides as $ride)
        <tr>
            <?php
            $status_style = '';
            if ($ride->status == 'Виконано') {
                $status_style = 'text-green-500 bg-green-100';
            } else if ($ride->status == 'Аварійна ситуація' || $ride->status == 'Відмовлено водієм') {
                $status_style = 'text-rose-500 bg-rose-100';
            }
            ?>
            <td rowspan="{{count($ride->orders)}}" class="border border-slate-300 text-center p-2 {{$status_style}}">{{ $ride->status }}</td>
            <td rowspan="{{count($ride->orders)}}" class="border border-slate-300 text-center p-2">{{$ride->distance}} км</td>
            <td rowspan="{{count($ride->orders)}}" class="border border-slate-300 text-center p-2">{{$ride->vutratu}} грн</td>
            <td rowspan="{{count($ride->orders)}}" class="border border-slate-300 text-center p-2">{{$ride->vuruchka}} грн</td>
            <td rowspan="{{count($ride->orders)}}" class="border border-slate-300 text-center p-2">{{$ride->driver_zarplata}} грн</td>
            <td class="border border-slate-300 text-center p-2">
                {{ $ride->orders[0]->product }}<br><small> ({{ date('d.m.Y',strtotime($ride->orders[0]->date_of_start)) }})</small>
            </td>
            <td class="border border-slate-300 text-center p-2">{{ round($ride->orders[0]->distance/1000) }} км</td>
            <td class="border border-slate-300 text-center p-2">{{  $ride->orders[0]->vutratu }} грн</td>
            <td rowspan="{{count($ride->orders)}}" class="border border-slate-300 text-center p-2">
                @if ($ride->driver!=null)
                {{$ride->driver->full_name}}
                @else
                {{' - '}}
                @endif
            </td>

        </tr>
        @if(count($ride->orders)>1)
        @foreach($ride->orders as $key=>$order)
        @if($key!=0)
        <tr>
            <td class="border border-slate-300 text-center p-2">
                {{ $order->product }}<br><small> ({{ date('d.m.Y',strtotime($order->date_of_start)) }})</small>
            </td>
            <td class="border border-slate-300 text-center p-2">{{ round($order->distance/1000) }} км</td>
            <td class="border border-slate-300 text-center p-2">{{  $order->vutratu }} грн</td>
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


@section('script')
<script>
    const apiKey = document.querySelector('meta[name="google-maps-api-key"]').getAttribute('content');

    async function LoadDistancies() {
        let trds = document.getElementsByClassName('tr-vutratu');
        let trv = document.getElementsByClassName('tr-prubutok');
        console.log("trds: ", trds);
        let distancies = [];
        for (let i = 0; i < trds.length; i++) {
            const element = trds[i];
            const a = element.getAttribute('point_A');
            const b = element.getAttribute('point_B');
            const o = await getDistancies(a, b);
            const d = o.distance/1000;
            console.log(`${a} and ${b}: ${d}`);
            distancies.push(d);
            trds[i].innerHTML = Math.round(d * 5.1, 2) + " грн";
            trv[i].innerHTML = Math.round(d * 5.1 * 1.4,2) + " грн";
        }


    }

    LoadDistancies();


    async function getDistancies(v, w) {
        const originCoords = await getCoordinates(v);
        const destinationCoords = await getCoordinates(w);

        if (!originCoords || !destinationCoords) {
            console.error("Could not fetch coordinates for the given addresses.");
            return;
        }

        const requestBody = {
            "origin": {
                "location": {
                    "latLng": originCoords
                }
            },
            "destination": {
                "location": {
                    "latLng": destinationCoords
                }
            },
            "travelMode": "DRIVE",
            "routingPreference": "TRAFFIC_AWARE",
            "computeAlternativeRoutes": true
        };
        return new Promise((resolve, reject) => {
            fetch(`https://routes.googleapis.com/directions/v2:computeRoutes?key=${apiKey}`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-Goog-FieldMask": "*"
                    },
                    body: JSON.stringify(requestBody)
                })
                .then(response => response.json())
                .then(data => {
                    const res = {
                        point_A: v,
                        point_B: w,
                        distance: data.routes[0].legs[0].distanceMeters // Приклад обробки результату
                    };
                    resolve(res);
                })
                .catch(error => {
                    console.error("Error fetching route:", error);
                });
        });
    }

    async function getCoordinates(address) {
        const url = `https://cors-anywhere.herokuapp.com/https://maps.googleapis.com/maps/api/geocode/json?address=${encodeURIComponent(address)}&key=${apiKey}`;

        try {
            const response = await fetch(url);
            const data = await response.json();
            if (data.status == "OK") {
                const location = data.results[0].geometry.location;
                return {
                    latitude: location.lat,
                    longitude: location.lng
                };
            } else {
                throw new Error(`Geocoding error: ${data.status}`);
            }
        } catch (error) {
            console.error("Error fetching coordinates:", error);
            return null;
        }
    }
</script>
@endsection

@extends('layouts.main')
@section('title', 'Поточне замовлення')

@section('style')
    <style>
        .dropbtn {
            background-color: #3498DB;
            color: white;
            padding: 16px;
            font-size: 16px;
            border: none;
            cursor: pointer;
        }

        .dropbtn:hover,
        .dropbtn:focus {
            background-color: #2980B9;
        }

        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f1f1f1;
            min-width: 160px;
            overflow: auto;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 1;
            height: 170px;
            overflow-y: scroll;
        }

        .dropdown-content a {
            color: black;
            padding: 2px;
            line-height: normal;
            text-decoration: none;
            display: block;
            border-bottom: .5px solid gray;
        }

        .dropdown a:hover {
            background-color: #ddd;
        }

        .show {
            display: block;
        }
    </style>
@endsection

@section('navlinks')
    @include('navigations.driver')
@endsection
@section('responsive_navlinks')
    @include('responsive_navigations.driver')
@endsection

@section('content')

    @if (isset($ride))
        <div class="mt-0 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6" style="height: 670px;">
            <div class="sm:col-span-3 h-100">
                <!-- <h4>{{ $ride->product }}</h4> -->
                <p class="{{ $ride->status == 'Аварійна ситуація' ? 'text-orange-400' : '' }}"><b
                        class="{{ $ride->status == 'Аварійна ситуація' ? 'text-black' : '' }}">Статус:</b>
                    {{ $ride->status }}
                    <?php
                    if($ride->status=='Аварійна ситуація'){
                        switch ($ride->driver->accident_type) {
                            case 'zaminakolesa':
                                echo " (Заміна колеса)";
                                break;
                            case 'easypolomka':
                                echo " (Легка поломка)";
                                break;
                            case 'dtp':
                                echo " (ДТП)";
                                break;
                            case 'hardpolomka':
                                echo " (Складна поломка)";
                                break;

                            default:
                                echo "";
                                break;
                        }
                    }
                    ?>
                    @if ($ride->status == 'Аварійна ситуація'&&in_array($ride->accident_type,["zaminakolesa","easypolomka"]))
                        <button class="button text-white py-0 px-2 rounded bg-orange-400"
                            onclick="location.href='/driver/dashboard/accident/disable'">Вирішено</button>
                    @endif
                </p>
                <p><b>Станція початкова:</b> {{ $ride->station_start->address }} </p>
                <p><b>Станція кінцева:</b> {{ $ride->station_end->address }} </p>
                <p><b>Вага:</b> {{ $ride->weigth }} кг</p>
                <p><b>Загальна відстань:</b> {{ $ride->distance }} км</p>
                <p><b>Витрати:</b> {{ $ride->vutratu }} грн</p>
                <p><b>Заробіток:</b> {{ $ride->driver_zarplata<0?0:$ride->driver_zarplata }} грн</p>
                @if ($ride->status == 'Призначено')
                    <button onclick="location.href='/driver/dashboard/rides/{{ $ride->id }}/confirm'"
                        class="button text-white py-0 px-2 rounded bg-green-400">Прийняти</button>
                    <button onclick="location.href='/driver/dashboard/rides/{{ $ride->id }}/return/{{ $driver->id }}'"
                        class="button text-white py-0 px-2 rounded bg-rose-400">Відмовити</button>
                @endif
                <br><!-- <p>Кінцева станція: {{ $ride->status }}</p> -->
                <b>Замовлення:</b>
                <ul>
                    @foreach ($orders as $order)
                        <li>{{ $order->product }} - {{ $order->supplier->full_name }} ({{ $order->supplier->phone }})
                        </li>
                    @endforeach
                </ul>
                <h4><b>Маршрут:</b></h4>
                @if ($ride->status == 'Прийнято' && $driver->status != 'В дорозі')
                    <button onclick="location.href='/driver/dashboard/ridestart'"
                        class="button text-white py-0 px-2 rounded bg-green-400">Виїхав</button>
                @endif
                <ol>
                    @foreach ($points as $point)
                        <li class="{{ $ride->current_point >= $point['index'] ? 'text-gray-300' : '' }}">
                            {{ $point['index'] . '. ' . $point['title'] . ' (' . $point['product'] . ') ' }} <i
                                class="text-{{ $point['type'] == 'Забрати' ? 'yellow' : 'green' }}-300">{{ $point['type'] }}</i>

                            <?php
                            $key = 'index';
                            ?>
                            @if (in_array($ride->status,['Прийнято','Аварійна ситуація']) && $driver->status == 'В дорозі'&&$ride->current_point + 1 == $point['index'])
                                <button class="button text-white  py-0 px-2 rounded bg-green-400"
                                    {{ $ride->status == 'Аварійна ситуація' ? 'disabled' : '' }}
                                    onclick='location.href="/driver/dashboard/rides/update/{{ $point[$key] }}"'>Відмітити
                                    як пройдену</a>

                                    <!-- TODO: -->
                            @endif
                        </li>
                    @endforeach
                </ol>

                <div class="dropdown">
                    {{-- <button
                        class="btn bg-{{ $ride->driver->status == 'Аварійна ситуація' ? 'gray' : 'orange' }}-400 m-1 p-1 rounded-sm text-white"
                        onclick="location.href='/driver/dashboard/accident'">Аварійна ситуація</button> --}}
                    <button
                        class="btn bg-{{ $ride->driver->status == 'Аварійна ситуація' ? 'gray' : 'orange' }}-400 m-1 p-1 rounded-sm text-white dropbtn"
                        onclick="chooseTypeOfAccidentSituation()">Аварійна ситуація</button>
                    <div id="myDropdown" class="dropdown-content w-1/3">
                        <b class="p-4">Прості</b>
                        <a class="p-0 m-1 text-sm" onclick="AccidentSituation('zaminakolesa')">Заміна колеса</a>
                        <a class="p-0 m-1 text-sm" onclick="AccidentSituation('easypolomka')">Легка поломка</a>
                        <b class="p-4">Складні</b>
                        <a class="p-0 m-1 text-sm" onclick="AccidentSituation('dtp')">ДТП</a>
                        <a class="p-0 m-1 text-sm" onclick="AccidentSituation('hardpolomka')">Важка поломка</a>
                    </div>
                </div>


            </div>
            <div class="sm:col-span-3 h-80">
                <div id="map" style="height: 320px; width: 450px;"></div>
            </div>
        </div>
    @else
        <div class="flex justify-center flex-col items-center">
            <img class="opacity-5 h-16 w-16" src="{{ url('images/empty_box.png') }}" alt="empty_box">
            <h2 class="opacity-25 ">Поточного замовлення немає</h2>

        </div>
    @endif
@endsection

@section('script')

<script>
    function AccidentSituation(situation)
    {
        let res=confirm('Ви справді хочете повідомити про аварійну ситуацію?');
        if(res==true) location.href="/driver/dashboard/accident/"+situation;
    }
</script>
    <script
        src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&sensor=false&libraries=drawing,geometry,places,marker">
    </script>
    <script>
        function chooseTypeOfAccidentSituation() {
            document.getElementById("myDropdown").classList.toggle("show");
        }

        // Close the dropdown if the user clicks outside of it
        window.onclick = function(event) {
            if (!event.target.matches('.dropbtn')) {
                var dropdowns = document.getElementsByClassName("dropdown-content");
                var i;
                for (i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }
    </script>
    <script>
        //KEY
        const apiKey = document.querySelector('meta[name="google-maps-api-key"]').getAttribute('content');

        fetch("http://localhost:8000/api/drivers/routes/current/{{ $driver->id }}")
            .then((response) => {
                return response.json();
            })
            .then((json) => {
                console.log("json", json)
                PrintMap(json.routes, json.station_start)
            })
            .catch((error) => {
                console.error("Error fetching data:", error);
            });

        async function getCoordinates(address) {
            const url =
                `https://cors-anywhere.herokuapp.com/https://maps.googleapis.com/maps/api/geocode/json?address=${encodeURIComponent(address)}&key=${apiKey}`;

            try {
                const response = await fetch(url);
                const data = await response.json();

                if (data.status === "OK") {
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

        async function PrintMap(routes, station_start) {
            let stations_start_coordinations = await getCoordinates(station_start);

            let map = new google.maps.Map(document.getElementById('map'), {
                zoom: 5,
                center: new google.maps.LatLng(stations_start_coordinations.latitude,
                    stations_start_coordinations.longitude),
                    mapId:"949e9f51ddfb584a"
            });

            let waypoints = [];
            for (let route of routes.slice(1, -1)) {
                let route_ = await getCoordinates(route);
                waypoints.push({
                    location: {
                        latLng: {
                            latitude: route_.latitude,
                            longitude: route_.longitude
                        }
                    }
                });
            }

            const first_point = await getCoordinates(routes[0]);
            const last_point = await getCoordinates(routes[routes.length - 1]);
            let requestBody = {
                origin: {
                    location: {
                        latLng: {
                            latitude: first_point.latitude,
                            longitude: first_point.longitude
                        }
                    }
                },
                destination: {
                    location: {
                        latLng: {
                            latitude: last_point.latitude,
                            longitude: last_point.longitude
                        }
                    }
                },
                intermediates: waypoints,
                travelMode: "DRIVE"
            };

            let url = "https://cors-anywhere.herokuapp.com/https://routes.googleapis.com/directions/v2:computeRoutes";

            try {
                let response = await fetch(url, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-Goog-Api-Key": apiKey,
                        "X-Goog-FieldMask": "routes.duration,routes.distanceMeters,routes.polyline.encodedPolyline"
                    },
                    body: JSON.stringify(requestBody)
                });

                let data = await response.json();

                if (data.routes && data.routes.length > 0 && data.routes[0].polyline) {
                    let decodedPath = google.maps.geometry.encoding.decodePath(data.routes[0].polyline.encodedPolyline);
                    let polyline = new google.maps.Polyline({
                        path: decodedPath,
                        geodesic: true,
                        strokeColor: "blue",
                        strokeOpacity: 0.8, // Трохи прозорості
                        strokeWeight: 3
                    });
                    polyline.setMap(map);

                    const {
                        AdvancedMarkerElement
                    } = google.maps.marker;
                    const fixPoint = (point) => {
                        if (!point || typeof point.latitude !== "number" || typeof point.longitude !== "number") {
                            console.error("Некоректна точка:", point);
                            return null;
                        }
                        return point;
                    };

                    const allPoints = [
                        fixPoint(first_point),
                        ...waypoints.map(wp => fixPoint(wp.location.latLng)),
                        fixPoint(last_point)
                    ].filter(p => p !== null);

                    console.log("first_point:", first_point);
                    console.log("waypoints:", waypoints);
                    console.log("last_point:", last_point);
                    allPoints.forEach((point, index) => {
                        new AdvancedMarkerElement({
                            position: new google.maps.LatLng(point.latitude, point.longitude),
                            map: map,
                            title: `Точка ${index + 1}`
                        });
                    });
                } else {
                    console.error("Маршрут не знайдено");
                }
            } catch (error) {
                console.error("Помилка отримання маршруту:", error);
            }
        }
    </script>
@endsection

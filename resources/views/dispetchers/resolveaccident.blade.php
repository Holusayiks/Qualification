@extends('layouts.main')
@section('title', 'Аварійна ситуація')

@section('navlinks')
    @include('navigations.dispatcher')
@endsection
@section('responsive_navlinks')
    @include('responsive_navigations.dispatcher')
@endsection

@section('content')
    <h2>{{ $driver->full_name }} {{ $driver->phone }} </h2>
    <p>(Тип авто: {{ $driver->auto->type }})</p>
    <p>Остання пройдена точка: {{ $ride->current_point->title }}</p>
    <p>Замовлення №{{ $ride->id }}</p>
    <p>Початкова станція: №{{ $ride->station_start->nomer }}({{ $ride->station_start->address }})</p>
    <p>Кінцева станція: №{{ $ride->station_end->nomer }}({{ $ride->station_end->address }})</p>
    <p>Тип аварійної ситуації:
        <?php
        switch ($driver->accident_type) {
            case 'zaminakolesa':
                echo 'Заміна колеса';
                break;
            case 'easypolomka':
                echo 'Легка поломка';
                break;
            case 'dtp':
                echo 'ДТП';
                break;
            case 'hardpolomka':
                echo 'Складна поломка';
                break;

            default:
                echo '';
                break;
        }
        ?>
    </p>

    <div>
        <div id="HardLevel" class="h-auto w-100 transition ease-in delay-1000 duration-1000">
            <h2>Вирішення ситуації:</h2>
            <form class="flex flex-col" action="/dispetcher/dashboard/drivers/{{ $driver->id }}/resolveaccident/hard"
                method="POST">
                @csrf
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 mt-3 py-3 rounded relative" role="alert">
                        <strong class="font-bold">Помилка</strong>
                        <span class="block sm:inline">

                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </span>
                        <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                            <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20">
                                <title>Close</title>
                                <path
                                    d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                            </svg>
                        </span>
                    </div>

                @endif
                <label for="station_id">Станція</label>
                <select name="station_id" id="station_id" onchange="LoadDrivers(this)">
                    <option value="">Оберіть</option>
                </select>
                <label for="driver_id">Водій</label>
                <select name="driver_id" id="driver_id">
                    <option value="">Оберіть</option>
                </select>
                <label for="need_to_get_order"> <input class="mr-3" type="checkbox" name="need_to_get_order"
                        id="need_to_get_order" onchange="ToogleAddressInput()" />Потрібно забрати товар</label>

                <label for="accident_address" id="accident_address_label">Адреса аварії</label>
                <input type="text" name="accident_address" id="accident_address" disabled='true' />
                <input type="text" id="new_distance" name="new_distance" class="hidden">
                <?php

                $points = json_decode($ride->points, true);
                ?>
                <input type="text" id="distance_for_old_driver" name="distance_for_old_driver" class="hidden">
                <button class="btn bg-green-400 text-white rounded p-2 m-2" type='submit'>Передати замовлення іншому
                    водію</button>

                <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places"></script>
            </form>
        </div>
    </div>
@endsection
@section('script')
    <script>
        function ToogleAddressInput() {
            const accident_address = document.getElementById('accident_address');
            accident_address.disabled = !accident_address.disabled;
            const accident_address_label = document.getElementById('accident_address_label');
            accident_address_label.style.col;
            accident_address.style.borderColor = accident_address.disabled ? 'LightGray' : "grey";
        }

        let points_array = JSON.parse(@json($ride->points));
        let station_end = "{{ $ride->station_end->address }}";
        async function calcNewDistance(first_distance) {
            let points = [];
            points_array.forEach(point => {
                points.push(point.title);
            });
            points.push(station_end);
            console.log('points',points)
            let res_distance = first_distance;
            for (let i = 0; i < points.length - 1; i++) {
                console.log("calc:",points[i]+" "+points[i+1]);
                let d = await getDistance(points[i], points[i + 1]);
                console.log("d",d.distance)
                res_distance += d.distance;
            }
            document.getElementById('new_distance').value = res_distance;
        }
    </script>
    <script>
        const apiKey = document.querySelector('meta[name="google-maps-api-key"]').getAttribute('content');

        function initialize() {
            const pickupInput = document.getElementById('accident_address');

            const pickupAutocomplete = new google.maps.places.Autocomplete(pickupInput);

        }
        google.maps.event.addDomListener(window, 'load', initialize);

        let current_point = '{{ $ride->current_point->title }}';
        let start_point = "{{ $points[0]['title'] }}";
        LoadHardLevelForm(start_point, current_point);


        async function LoadDrivers(e) {
            document.getElementById('driver_id').innerHTML =
                "<option class='text-gray-300' value=''>Loading...</option>";
            const station_id = e.value;
            calcNewDistance(e.getAttribute('distance'))
            const drivers_data = await getDrivers(station_id);
            let html = "<option value='0'>Оберіть</option>";
            drivers_data.drivers_on_station.forEach(driver => {
                let driver_auto = driver.auto == null ? "" : " (" + driver.auto.type + ")";
                html += "<option value='" + driver.id + "'>" + driver.full_name + " із станції #" + driver
                    .station.nomer + " - " + driver.station.address + driver_auto + "</option>";
            });
            drivers_data.drivers.forEach(driver => {
                let driver_auto = driver.auto == null ? "" : " (" + driver.auto.type + ")";
                html += "<option value='" + driver.id + "'>" + driver.full_name + driver_auto + "</option>";
            });
            document.getElementById('driver_id').innerHTML = html;

        }

        async function getDistance(v, w) {
            if (v == w) return {
                point_A: v,
                point_B: w,
                distance: 0
            };
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
                            distance: data.routes[0].legs[0]
                                .distanceMeters // Приклад обробки результату
                        };
                        resolve(res);
                    })
                    .catch(error => {
                        console.error("Error fetching route:", error);
                    });
            });
        }
        async function getCoordinates(address) {
            const url =
                `https://cors-anywhere.herokuapp.com/https://maps.googleapis.com/maps/api/geocode/json?address=${encodeURIComponent(address)}&key=${apiKey}`;

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
                    console.warn(`Geocoding error: ${data.status}`);
                    return await getCoordinatesOSM(address)
                }
            } catch (error) {
                console.error("Error fetching coordinates:", error);
                return null;
            }
        }

        async function getCoordinatesOSM(address) {

            const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}`;

            try {
                const response = await fetch(url, {
                    headers: {
                        'Accept-Language': 'uk', // щоб результати були локалізовані
                        'User-Agent': 'MyCargoApp/1.0 (mykhaylenkooleksandr208@gmail.com', // обов'язковий для Nominatim
                    }
                });
                const data = await response.json();

                if (data.length > 0) {
                    const location = data[0];
                    return {
                        latitude: location.lat,
                        longitude: location.lon
                    };
                } else {
                    console.error("Адресу не знайдено");
                    return null;
                }
            } catch (error) {
                console.error("Помилка запиту:", error);
                return null;
            }
        }
        async function LoadHardLevelForm(start_point, current_point) {
            let distance_from_start_point_to_accident_point = await getDistance(current_point, start_point);
            document.getElementById('distance_for_old_driver').value = distance_from_start_point_to_accident_point
                .distance;
            document.getElementById('station_id').innerHTML =
                "<option class='text-gray-300' value=''>Loading...</option>";
            let stations = await getStations();
            for (const station of stations) {
                const a = await getDistance(station.address, current_point);
                station.distance = a.distance / 1000;
            }
            stations = stations.sort((a, b) => a.distance - b.distance)
            let html = "<option value='0'>Оберіть</option>";
            stations.forEach(station => {
                html += "<option value='" + station.id + "' distance='" + station.distance + "'>#" + station
                    .nomer + " (" + station.address +
                    ") - " + station.distance + "км</option>";
            });
            document.getElementById('station_id').innerHTML = html;

        }

        function getStations() {
            return new Promise((resolve, reject) => {
                return fetch("http://localhost:8000/api/stations")
                    .then((response) => {
                        return response.json();
                    })
                    .then((json) => {
                        resolve(json);
                    })
                    .catch((error) => {
                        console.error("Error fetching data:", error);
                        alert(
                            "При завантажені виникла помилка. Прейдіть за посиланням: https://cors-anywhere.herokuapp.com/corsdemo, та натисніть кнопку."
                            );
                    });
            });
        }

        function getDrivers(station_id) {
            let type="{{ $driver->auto->type }}";
            return new Promise((resolve, reject) => {
                return fetch("http://localhost:8000/api/stations/" + station_id +"/" +type+"/drivers")
                    .then((response) => {
                        return response.json();
                    })
                    .then((json) => {
                        resolve(json);
                    })
                    .catch((error) => {
                        console.error("Error fetching data:", error);
                    });
            });
        }
    </script>
@endsection

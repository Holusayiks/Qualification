@extends('layouts.main')
@section('title', 'Створення замовлення')


@section('navlinks')
    @include('navigations.supplier')
@endsection
@section('responsive_navlinks')
    @include('responsive_navigations.supplier')
@endsection

@section('content')
    <form class="mx-44" method="POST" action="/supplier/dashboard/orders/store">
        @csrf

        <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
            <div class="sm:col-span-3">
                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 mt-3 py-3 rounded relative"
                            role="alert">
                            <strong class="font-bold">Помилка</strong>
                            <span class="block sm:inline">{{ $error }}</span>
                            <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                                <svg class="fill-current h-6 w-6 text-red-500" role="button"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <title>Close</title>
                                    <path
                                        d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                                </svg>
                            </span>
                        </div>
                    @endforeach
                @endif
                <label class="block text-sm font-medium leading-6 text-gray-900" for="product">Продукт</label>
                <input
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm
            ring-1 ring-inset ring-gray-300 placeholder:text-gray-400
            focus:ring-2 focus:ring-inset focus:ring-indigo-600
            sm:text-sm sm:leading-6"
                    type="text" name="product" id="product">
                <label class="block text-sm font-medium leading-6 text-gray-900" for="weigth">Вага</label>
                <input
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm
            ring-1 ring-inset ring-gray-300 placeholder:text-gray-400
            focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                    type="number" min="0" name="weigth" id="weigth">

                <label class="block text-sm font-medium leading-6 text-gray-900" for="car_type">Тип авто</label>
                <select name="car_type" id="car_type"
                    class="block w-full rounded-md
            border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300
            focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6">
                    <option value="">Обрати</option>
                    <option value="Цистерна">Цистерна</option>
                    <option value="Рефрежиратор">Рефрежиратор</option>
                    <option value="Контейнер">Контейнер</option>
                </select>

                <label class="block text-sm font-medium leading-6 text-gray-900" for="point_A">Точка відправлення</label>
                <input
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900
            shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400
            focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                    type="text" name="point_A" id="point_A">
                <label class="block text-sm font-medium leading-6 text-gray-900" for="point_B">Точка для доставки</label>
                <input
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900
            shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400
            focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                    type="text" name="point_B" id="point_B">
                <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places"></script>
                <button type="button"
                    class="btn bg-blue-400 m-2 rounded text-white hover:bg-blue-600 transition inset-y-0 right-0 p-1.5 text-sm"
                    onclick="updateMapRoute()">Показати маршрут на карті</button>
                <label class="block text-sm font-medium leading-6 text-gray-900" for="date_of_start">Дати відправки</label>
                <input
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900
            shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400
            focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                    type="date" min="{{ date('Y-m-d') }}" name="date_of_start" id="date_of_start">


                    <input type="number" class="focus:ring-2 focus:ring-inset focus:ring-indigo-600 hidden" name="distance" id="distance_input"/>
                    <input type="number" class="focus:ring-2 focus:ring-inset focus:ring-indigo-600 hidden" name="vutratu" id="vutratu_input"/>
            </div>

            <div class="sm:col-span-3">
                <iframe id="map-frame" width="400" height="350" style="border:0" loading="lazy" allowfullscreen
                    referrerpolicy="no-referrer-when-downgrade"
                    src="https://www.google.com/maps/embed/v1/directions?key={{ env('GOOGLE_MAPS_API_KEY') }}&origin=&destination=">
                </iframe>
                <p id="distance_text" style="display: none;"><b>Відстань: </b><span id="distance_span"></span> км</p>
                <p id="vutratu_text" style="display:none;"><b>Витрати:</b><span id="vutratu_span"></span> грн  +5% (<span id="strahovka_span"></span> грн )страховка </p>
            </div>
        </div>
        <div class="relative w-full h-10">

            <button
                class="btn bg-green-400 m-2 p-2 rounded-lg text-white hover:bg-green-600 transition absolute inset-y-0 right-0 h-10"
                role="submit">Зберегти замовлення</button>
        </div>
    </form>
@endsection



@section('script')

    <script>
        const apiKey = document.querySelector('meta[name="google-maps-api-key"]').getAttribute('content');

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
                    throw new Error(`Geocoding error: ${data.status}`);
                }
            } catch (error) {
                console.error("Error fetching coordinates:", error);
                return null;
            }
        }

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
                        const res =  data.routes[0].legs[0].distanceMeters;
                        resolve(res);
                    })
                    .catch(error => {
                        console.error("Error fetching route:", error);
                    });
            });
        }
    </script>
    <script>
        function initialize() {
            const pickupInput = document.getElementById('point_A');
            const deliveryInput = document.getElementById('point_B');

            const pickupAutocomplete = new google.maps.places.Autocomplete(pickupInput);
            const deliveryAutocomplete = new google.maps.places.Autocomplete(deliveryInput);
        }
        google.maps.event.addDomListener(window, 'load', initialize);

        window.updateMapRoute = async function() {
            const pickupAddress = document.getElementById('point_A').value;
            const deliveryAddress = document.getElementById('point_B').value;
            console.log("pickupAddress", pickupAddress);
            console.log("deliveryAddress", deliveryAddress);

            let weight=document.getElementById('weigth').value;
            let distance_in_metres = await getDistancies(pickupAddress, deliveryAddress);
            let distance=distance_in_metres/1000;

            let vutratu=0;
            if(weight<=2000){
                vutratu=distance*5.1+distance*3.1+distance*10;
            }else{
                vutratu=distance*5.1+distance*4+distance*12;
            }
            let strachovka=weight<=2000?Math.round(distance*10*0.05):Math.round(distance*12*0.05);

            document.getElementById('distance_text').style.display = 'block';
            document.getElementById('vutratu_text').style.display = 'block';

            document.getElementById('distance_span').innerText = distance;
            document.getElementById('vutratu_span').innerText = vutratu;
            document.getElementById('strahovka_span').innerText = strachovka;

            document.getElementById('distance_input').value = distance_in_metres;
            document.getElementById('vutratu_input').value = vutratu+strachovka;

            const mapFrame = document.getElementById('map-frame');

            mapFrame.src = `https://www.google.com/maps/embed/v1/directions?key=` + apiKey +
                `&origin=${encodeURIComponent(pickupAddress)}&destination=${encodeURIComponent(deliveryAddress)}`;
        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&sensor=false&libraries=drawing,geometry,places,marker"></script>

@endsection

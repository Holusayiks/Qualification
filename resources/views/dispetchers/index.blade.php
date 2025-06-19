@extends('layouts.main')
@section('title', 'Головна')

@section('navlinks')
    @include('navigations.dispatcher')
@endsection
@section('responsive_navlinks')
    @include('responsive_navigations.dispatcher')
@endsection
<?=
$hide_main=true;
?>
@section('before-content')
    <main class="flex-1 p-6 space-y-6">

        <section class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded shadow">
                <h2 class="text-xl font-semibold mb-2">Водії у рейсі</h2>
                <p class="text-3xl font-bold">{{ $drivers_in_ride_now }}</p>
            </div>
            <div class="bg-white p-6 rounded shadow">
                <h2 class="text-xl font-semibold mb-2">Витрати на бензин (за останній місяць)</h2>
                <p class="text-3xl font-bold">{{ round($vutratu_by_last_month / 1000) }} тис. грн</p>
            </div>
            <div class="bg-white p-6 rounded shadow">
                <h2 class="text-xl font-semibold mb-2">Аварійні ситуації</h2>
                <p class="text-3xl font-bold">{{ $accidents_number }}</p>
            </div>
        </section>

        <section class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="flex flex-col">
                    <div class="bg-white p-6 rounded shadow flex flex-col justify-between mb-5">
                        <div class="h-auto w-full">
                            <h2 class="text-xl font-semibold mb-2">{{ $dispatcher->full_name }}</h2>
                            <p><b>Телефон:</b>{{ $dispatcher->phone }}</p>
                            <p><b>Станція:</b> №{{ $dispatcher->station->nomer }}, {{ $dispatcher->station->address }}</p>
                        </div>
                        <button class="btn bg-sky-400 m-1 p-1 rounded-sm text-white"
                            onclick="location.href='/dispetcher/dashboard/{{ $dispatcher->id }}/edit'">Змінити дані</button>

                    </div>
                    <div class="bg-white p-6 rounded shadow flex flex-col justify-between mt-3">
                        <div class="h-auto w-full text-xl">
                            <p><b>Загальний страховий фонд:</b><br> {{ $insurance }} грн</p>
                        </div>
                    </div>


                </div>
                <div class="bg-white p-6 rounded shadow">
                    <h2 class="text-xl font-semibold mb-2">Авто на станції</h2>
                    <div id="chart">

                    </div>
                </div>
                <div class="bg-white p-6 rounded shadow">
                    <h2 class="text-xl font-semibold mb-2">Авто на станції за типом</h2>
                    <table class="w-full">
                        <thead>
                            <tr>
                                <th class="border border-slate-300">Тип</th>
                                <th class="border border-slate-300">Кількість</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="border border-slate-300 text-center p-2">Контейнер</td>
                                <td class="border border-slate-300 text-center p-2">{{ $auto_type['Контейнер'] }}</td>
                            </tr>
                            <tr>
                                <td class="border border-slate-300 text-center p-2">Рефрежиратор</td>
                                <td class="border border-slate-300 text-center p-2">{{ $auto_type['Рефрежиратор'] }}</td>
                            </tr>
                            <tr>
                                <td class="border border-slate-300 text-center p-2">Цистерна</td>
                                <td class="border border-slate-300 text-center p-2">{{ $auto_type['Цистерна'] }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
        </section>

    </main>
@endsection
@section('content')

@endsection

@section('script')
    <script>
        function getAutoData() {
            return new Promise((resolve, reject) => {
                return fetch("http://localhost:8000/api/stations/" + <?php echo $dispatcher->station->id; ?> + "/autodata")
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


        function FillApexChart(data) {
            var options = {
                series: data.series,
                chart: {
                    width: 350,
                    type: 'pie',
                },
                labels: data.labels,
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 180
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            };

            var chart = new ApexCharts(document.querySelector("#chart"), options);
            chart.render();
        }
        async function Start() {
            const data = await getAutoData();
            FillApexChart(data)
        }
        Start();
    </script>
@endsection

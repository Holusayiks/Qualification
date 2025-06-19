@extends('layouts.main')
@section('title', 'Водії на станції')

@section('navlinks')
    @include('navigations.dispatcher')
@endsection
@section('responsive_navlinks')
    @include('responsive_navigations.dispatcher')
@endsection

@section('content')
    @if (count($drivers) != 0)
        <table class="w-full">
            <thead>
                <tr>
                    <th class="border border-slate-300">Ім'я</th>
                    <th class="border border-slate-300">Телефон</th>
                    <th class="border border-slate-300">Статус</th>
                    <th class="border border-slate-300">Авто</th>
                    <th class="border border-slate-300">Дії</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($drivers as $driver)
                    <tr>
                        <td class="border border-slate-300 text-center p-2">{{ $driver->full_name }}</td>
                        <td class="border border-slate-300 text-center p-2">{{ $driver->phone }}</td>
                        <?php
                        $status_class = '';
                        if ($driver->status == 'Хочу в відпустку') {
                            $status_class = 'text-orange-500 bg-orange-100';
                        } elseif ($driver->status == 'В відпусці') {
                            $status_class = 'text-yellow-500 bg-yellow-100';
                        } elseif ($driver->status == 'Аварійна ситуація') {
                            $status_class = 'text-red-500 bg-red-100';
                        }
                        ?>
                        <td class="border border-slate-300 text-center p-2 {{ $status_class }}">

                            @if ($driver->status == 'В відпусці')
                                {{ 'У відпусці з ' . date('d/m/Y', strtotime($driver->date_of_vacation)) . ' на ' . $driver->days_in_vacation . ' днів' }}
                            @elseif($driver->status == 'Хочу в відпустку')
                                {{ 'Очікує дозволу на відпустку з ' . date('d/m/Y', strtotime($driver->date_of_vacation)) . ' на ' . $driver->days_in_vacation . ' днів' }}
                            @elseif ($driver->status == 'Аварійна ситуація')
                                {{ $driver->status }}
                                <?php
                                switch ($driver->accident_type) {
                                    case 'zaminakolesa':
                                        echo ' (Заміна колеса)';
                                        break;
                                    case 'easypolomka':
                                        echo ' (Легка поломка)';
                                        break;
                                    case 'dtp':
                                        echo ' (ДТП)';
                                        break;
                                    case 'hardpolomka':
                                        echo ' (Складна поломка)';
                                        break;

                                    default:
                                        echo '';
                                        break;
                                }
                                ?>
                            @else
                                {{ $driver->status }}
                            @endif
                        </td>
                        <td class="border border-slate-300 text-center p-2">
                            {{ $driver->auto == null ? ' - ' : $driver->auto->nomer . '(' . $driver->auto->type . ')' }}</td>
                        <td class="border border-slate-300 flex justify-center h-12">
                            @if ($driver->status == 'Хочу в відпустку')
                                <button class="btn bg-green-400 m-1 p-1 text-sm rounded-sm text-white"
                                    onclick="location.href='/dispetcher/dashboard/drivers/{{ $driver->id }}/status/yes'">Відправити
                                    у відпустку</button>
                                <button class="btn bg-orange-400 m-1 p-1 text-sm rounded-sm text-white"
                                    onclick="location.href='/dispetcher/dashboard/drivers/{{ $driver->id }}/status/no'">Відмовити
                                    у відпустці</button>
                            @endif
                            <!-- <button class="btn bg-red-400 m-1 p-1 text-sm rounded-sm text-white" onclick="location.href='.menedger/dashboard/drivers/{{ $driver->id }}/delete'">Звільнити</button> -->
                        @if ($driver->status == 'Після аварії')
                            <button class="btn bg-green-400 m-1 p-1 text-sm rounded-sm text-white"
                                onclick="location.href='/dispetcher/dashboard/drivers/{{ $driver->id }}/atstation'">Прибув</button>
                            @elseif ($driver->status != 'В дорозі' && $driver->status != 'Аварійна ситуація')
                                <button class="btn bg-green-400 m-1 p-1 text-sm rounded-sm text-white"
                                    onclick="AddAuto('{{ $driver->id }}')">{{ $driver->auto == null ? 'Надати' : 'Змінити' }}
                                    авто</button>
                            @elseif ($driver->status == 'Аварійна ситуація')
                                <button class="btn bg-rose-400 m-1 p-1 text-sm rounded-sm text-white"
                                    onclick="location.href='/dispetcher/dashboard/drivers/{{ $driver->id }}/resolveaccident'">Вирішити</button>
                            @endif

                        </td>
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
@section('script')
    <script>
        let driver = null;

        function getDriver(id) {
            return new Promise((resolve, reject) => {
                return fetch("http://localhost:8000/api/drivers/show/" + id)
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

        function getAuto(id) {
            return new Promise((resolve, reject) => {
                return fetch("http://localhost:8000/api/autos/show/" + id)
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
        async function AddAuto(id) {
            driver = await getDriver(id);
            console.log('get driver: ', driver);
            OpenModal();

            document.getElementById('driver_name').innerText = driver.full_name;

            if (driver.auto_id != null) {
                const auto = driver.auto;
                console.log('auto',auto)
                console.log('auto nomer',auto.nomer)
                document.getElementById('driver_auto').innerText = auto.nomer + " (Тип авто: " + auto.type + ")";
            } else {
                document.getElementById('modal_btn_remove_auto').classList.add('hidden');
                document.getElementById('driver_auto').parentElement.remove();
            }
        }

        function SubmitAuto() {
            const auto_id = document.getElementById('auto').value;
            location.href = '/dispetcher/dashboard/changedriver/' + driver.id + '/' + auto_id;
        }

        function RemoveAuto() {
            location.href = '/dispetcher/dashboard/changedriver/' + driver.id + '/n';
        }

        //MODAL
        let openModal = false;
        let modalId = 'modalWindowAddAuto';

        function OpenModal() {
            document.getElementById(modalId).classList.remove('hidden');
            openModal = !openModal;
        }

        function CloseModal() {
            document.getElementById(modalId).classList.add('hidden');
            openModal = !openModal;
        }
    </script>
@endsection

@section('modal')<!-- Main modal -->
    <div id="modalWindowAddAuto" tabindex="-1" aria-hidden="true"
        class="{{ $errors->all() ? '' : 'hidden' }} overflow-y-auto overflow-x-hidden fixed inset-0 z-50 flex justify-center items-center bg-gray-500 bg-opacity-50">
        <div class="relative p-4 w-full max-w-full h-full max-h-full bg-white">

            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Надання авто
                    </h3>
                    <button onclick="CloseModal()" type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-4 md:p-5 space-y-4" id="modalContent">
                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-3 h-15">
                            @if ($errors->any())
                                <div class="bg-red-100 border border-red-400 text-red-700 px-4 mt-3 py-3 rounded relative"
                                    role="alert">
                                    <strong class="font-bold">Помилка</strong>
                                    <span class="block sm:inline">

                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </span>
                                    <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                                        <svg class="fill-current h-6 w-6 text-red-500" role="button"
                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <title>Close</title>
                                            <path
                                                d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                                        </svg>
                                    </span>
                                </div>

                            @endif
                            <p>Водій: <i id="driver_name"></i></p>
                            <p>Поточне авто: <i id="driver_auto"></i></p>
                            <label for="auto">Авто:</label>
                            <select name="auto" id="auto">
                                <option value="">Оберіть...</option>
                                @foreach ($autos as $auto)
                                    <option value="{{ $auto->id }}">{{ $auto->nomer }} (Тип авто:
                                        {{ $auto->type }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                    <button onclick="SubmitAuto()" type="button"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Зберегти</button>
                    <button onclick="RemoveAuto()" id="modal_btn_remove_auto" type="button"
                        class="mx-5 text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">Прибрати
                        авто</button>
                    <button onclick="CloseModal()" type="button"
                        class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Повернутись</button>
                </div>
            </div>
        </div>
    </div>
@endsection

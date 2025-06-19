<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .progress-bar {
            width: 100%;
            height: 30px;
            background-color: #f1f1f1;
            position: relative;
        }

        .progress-bar #modal_progress {
            height: 100%;
            text-align: center;
            line-height: 30px;
            color: white;
            background: #14bb1a50;
            position: absolute;
            left: 0;
            top: 0;
            z-index: 1;
        }

        .progress-bar #modal_progress2 {
            height: 100%;
            text-align: center;
            line-height: 30px;
            color: white;
            background: #14bb1a50;
            left: 0;
            top: 0;
            z-index: 2;
        }

        .w-full {
            width: 100%;
        }

        .h-auto {
            height: auto;
        }

        .hidden {
            display: none;
        }
    </style>
    <title>Document</title>
</head>

<body>
    <!-- <div id="modal_progress_bar" class="w-full h-auto">
        <div class="progress-bar">
            <div id="modal_progress"></div>
            <div id="modal_progress2"></div>
        </div>
    </div> -->
    <!-- <div class="text-center w-full">
        <h4>Формування маршруту</h4>
        <table>
            <thead>
                <tr>
                    <th>Адреса 1</th>
                    <th>Адреса 2</th>
                    <th>Відстань</th>
                </tr>
            </thead>
            <tbody id="disctancies_list"></tbody>
        </table>
        <ul id="combinations"></ul>
    </div> -->
    <!-- <script>
        const progressBarModal = document.getElementById('modal_progress');
        const progressBarModal2 = document.getElementById('modal_progress2');
        let progress_bar_data_modal = {
            value: 0,
            max: 0
        };
        let progress_bar_data_modal2 = {
            value: 0,
            max: 0
        };

        function ProgressBarModalUpdate(value) {
            progress_bar_data_modal.value += value;
            ProgressBarModalPrint();
        }

        function ProgressBarModal2Update(value) {
            progress_bar_data_modal2.value += value;
            ProgressBarModal2Print();
        }

        function ProgressBarModalPrint() {
            let value = (progress_bar_data_modal.value * 100) / progress_bar_data_modal.max;
            progressBarModal.style.width = value + '%';
        }

        function ProgressBarModal2Print() {
            let value = (progress_bar_data_modal2.value * 100) / progress_bar_data_modal2.max;
            progressBarModal2.style.width = value + '%';
        }

        function printDistancies() {

            let html = '';
            distancies.forEach(element => {
                html += "<tr>";
                html += `<td>${element.point_A}</td>`;
                html += `<td>${element.point_B}</td>`;
                html += `<td>${element.distance/1000} км</td>`;
                html += "</tr>";

            });
            document.getElementById('disctancies_list').innerHTML = html;
        }

        function printCombinations() {
            let html = '';
            combinations.forEach(element => {
                if (element.is_used) {
                    html += "<li>";
                } else {
                    html += "<li style='color: red'>";
                }

                html += `<span>${element.points[0]} - ${element.points[1]}</span>`;
                html += "<span> - </span>";
                html += `<span>${element.is_used ? 'used' : 'not used'}</span>`;
                html += "</li>";

            });
            document.getElementById('combinations').innerHTML = html;
        }

        function addPrintDistancies(element) {

            let html = '';
            html += "<tr>";
            html += `<td>${element.point_A}</td>`;
            html += `<td>${element.point_B}</td>`;
            html += `<td>${element.distance/1000} км</td>`;
            html += "</tr>";
            document.getElementById('disctancies_list').innerHTML += html;
        }

        function sFact(num) {
            var rval = 1;
            for (var i = 2; i <= num; i++)
                rval = rval * i;
            return rval;
        }

        const apiKey = 'AIzaSyBIMmRuwPb7B2I5l_Sx05zDsUuPQxzd1KU';
        // optimise code

        let points = [
            "Київ, вул. Хрещатик, 1",
            "Львів, вул. Шевченка, 23",
            "Одеса, вул. Дерибасівська, 10",
            "Чернівці, вул. Героїв Майдану, 15",
            "Дніпро, пр. Дмитра Яворницького, 28",
            "Івано-Франківськ, вул. Незалежності, 24",
            "Харків, вул. Сумська, 45",
            "Запоріжжя, пр. Соборний, 12",
            "Полтава, вул. Пушкіна, 17",
            "Суми, вул. Горького, 8",
            "Київ, вул. Лесі Українки, 7",
            "Хмельницький, вул. Проскурівська, 14",
            "Львів, вул. Франка, 5",
            "Черкаси, бульв. Шевченка, 10",
            "Одеса, вул. Рішельєвська, 18",
            "Кривий Ріг, вул. Михайла Грушевського, 22",
            "Дніпро, вул. Леніна, 15",
            "Миколаїв, вул. Радянська, 3",
            "Харків, пр. Гагаріна, 21",
            "Житомир, вул. Михайлівська, 11",
            // "Полтава, вул. Котляревського, 12",
            // "Київ, вул. Володимирська, 10",
            // "Львів, вул. Сахарова, 4",
            // "Луцьк, вул. Карпенка-Карого, 16",
            // "Одеса, вул. Артилерійська, 6",
            // "Запоріжжя, вул. Таганрозька, 9",
            // "Дніпро, вул. Космічна, 5",
            // "Чернівці, вул. Гагаріна, 32",
            // "Харків, вул. Московський проспект, 20",
            // "Суми, вул. Миколи Лисенка, 21",
            // "Полтава, вул. Степана Бандери, 8",
            // "Івано-Франківськ, вул. Січових Стрільців, 30",
            // "Львів, вул. Леонтовича, 3",
            // "Миколаїв, вул. Миколи Лемешева, 9",
            // "Одеса, вул. Балківська, 15",
            // "Кривий Ріг, вул. Саксаганського, 11",
            // "Дніпро, вул. Шевченка, 45",
            // "Черкаси, вул. Пастернака, 8",
            // "Харків, вул. Полтавський шлях, 12",
            // "Київ, вул. Тараса Шевченка, 6",
            // "Полтава, вул. Соборності, 22",
            // "Житомир, вул. Лесі Українки, 19",
            // "Львів, вул. Кирила і Мефодія, 4",
            // "Луцьк, вул. Набережна, 6",
            // "Одеса, вул. Пушкінська, 21",
            // "Суми, вул. 1 Травня, 5",
            // "Дніпро, вул. Петра Сагайдачного, 3",
            // "Миколаїв, вул. Водопійна, 12",
            // "Харків, вул. Дарвіна, 18",
            // "Кривий Ріг, вул. Бульвар Шевченка, 9",
            // "Полтава, вул. Котляревського, 8",
            // "Київ, вул. Лесі Українки, 25",
            // "Львів, вул. Січових Стрільців, 7",
            // "Івано-Франківськ, вул. Степана Бандери, 17",
            // "Одеса, вул. Ванцетті, 15",
            // "Хмельницький, вул. Бандери, 14",
            // "Дніпро, вул. Миколи Амосова, 21",
            // "Кривий Ріг, вул. Київська, 10",
            // "Харків, вул. Спортивна, 4",
            // "Чернівці, вул. Шевченка, 20",
            // "Полтава, вул. Чорновола, 19",
            // "Миколаїв, вул. Горького, 11",
            // "Львів, вул. Волошкова, 10",
            // "Івано-Франківськ, вул. Гречка, 12",
            // "Одеса, вул. Радісна, 4",
            // "Черкаси, вул. Героїв Майдану, 18",
            // "Дніпро, вул. Генерала Петрова, 3",
            // "Запоріжжя, вул. Петра Могили, 16",
            // "Харків, вул. Барабашова, 22",
            // "Житомир, вул. Бернарда, 5",
            // "Львів, вул. Шевченка, 21",
            // "Київ, вул. Велика Васильківська, 33",
            // "Одеса, вул. Середня, 8",
            // "Харків, вул. Плеханівська, 15",
            // "Дніпро, вул. Шевченка, 35",
            // "Миколаїв, вул. Миколаївська, 6",
            // "Полтава, вул. Шевченка, 23",
            // "Кривий Ріг, вул. Петра Сагайдачного, 17",
            // "Харків, вул. Одеська, 14",
            // "Івано-Франківськ, вул. Грушевського, 5",
            // "Львів, вул. Володимира Великого, 3",
            // "Чернівці, вул. Лесі Українки, 19",
            // "Одеса, вул. Ляпис, 14",
            // "Хмельницький, вул. Грушевського, 8",
            // "Дніпро, вул. Гоголя, 22",
            // "Суми, вул. Київська, 27",
            // "Полтава, вул. Карла Маркса, 10",
            // "Запоріжжя, вул. Степана Бандери, 5",
            // "Харків, вул. Чернишова, 19",
            // "Житомир, вул. Михайлівська, 7",
            // "Львів, вул. Жовківська, 13",
            // "Черкаси, вул. Перемоги, 23",
            // "Одеса, вул. Приморська, 5",
            // "Миколаїв, вул. Космонавтів, 8",
            // "Дніпро, вул. Театральна, 11",
            // "Хмельницький, вул. Леніна, 3",
            // "Полтава, вул. Горького, 14",
            // "Київ, вул. Гетьмана, 13",
            // "Харків, вул. 23 Серпня, 22",
            // "Львів, вул. Університетська, 8",
            // "Львів, вул. Лесі Українки, 5",
            // "Черкаси, вул. Івана Франка, 12",
            // "Одеса, вул. Черноморська, 6",
            // "Запоріжжя, вул. Маяковського, 4",
            // "Дніпро, пр. Маяковського, 16",
            // "Кривий Ріг, вул. Промислова, 18",
            // "Полтава, вул. Воскресенська, 22",
            // "Чернівці, вул. Першотравнева, 4",
            // "Харків, вул. Миколаївська, 3",
            // "Київ, вул. Старонаводницька, 10",
            // "Львів, вул. Грінченка, 4",
            // "Дніпро, вул. Маяковського, 9",
            // "Одеса, вул. Степова, 3",
            // "Івано-Франківськ, вул. Довженка, 20",
            // "Дніпро, вул. Князя Святослава, 12",
            // "Запоріжжя, вул. Ворошилова, 13",
            // "Полтава, вул. Мічуріна, 5",
            // "Кривий Ріг, вул. Мала Садова, 2",
            // "Харків, вул. Лермонтова, 2",
            // "Черкаси, вул. Віктора Ющенка, 11",
        ];


        let distancies = [];


        progress_bar_data_modal.max = sFact(points.length);

        let combinations = JSON.parse(localStorage.getItem('combinations')) || makeAllCombination();
        progress_bar_data_modal.max = combinations.length;
        progress_bar_data_modal.value = 0;
        ProgressBarModalPrint();

        function makeAllCombination() {
            let result = [];
            for (let i = 0; i < points.length; i++) {
                for (let j = i + 1; j < points.length; j++) {
                    result.push({
                        points: [points[i], points[j]],
                        is_used: false
                    });
                }
            }
            localStorage.setItem('combinations', JSON.stringify(result));
            return result;
        }


        async function FindDistancies() {
            const maxConcurrentRequests = 5;
            let currentRequests = 0;
            let delayBetweenRequests = 1000; // Затримка в мілісекундах (1 секунда)
            const maxDelay = 10000; // Максимальна затримка в мілісекундах (10 секунд)

            distancies = JSON.parse(localStorage.getItem('distancies')) || [];
            printDistancies();
            progress_bar_data_modal2.max = progress_bar_data_modal.max;
            ProgressBarModalUpdate(distancies.length);
            ProgressBarModal2Print();

            for (const combination of combinations) {
            console.clear();
            printCombinations();
            console.log(progress_bar_data_modal.value + '/' + progress_bar_data_modal.max);
            console.log('checked: ' + progress_bar_data_modal2.value + '/' + progress_bar_data_modal2.max);

            const [v, w] = combination.points;
            if (!combination.is_used) {
                currentRequests++;
                while (currentRequests >= maxConcurrentRequests) {
                await new Promise(resolve => setTimeout(resolve, 5000));
                }
                await new Promise(resolve => setTimeout(resolve, delayBetweenRequests));
                await console.log(` '${v}' та '${w}' - запит на сервер` + ` поточне ` + currentRequests);

                try {
                const res = await getDistancies(v, w);
                addPrintDistancies(res);
                distancies.push(res);
                ProgressBarModalUpdate(1);
                localStorage.setItem('distancies', JSON.stringify(distancies));
                delayBetweenRequests = 1000; // Reset delay on success
                combination.is_used = true;
                localStorage.setItem('combinations', JSON.stringify(combinations));
                console.log('combination', combination);
                } catch (error) {
                delayBetweenRequests = Math.min(delayBetweenRequests * 2, maxDelay); // Increase delay on error
                } finally {
                currentRequests--;
                await new Promise(resolve => setTimeout(resolve, delayBetweenRequests));
                }
            }
            ProgressBarModal2Update(1);
            }
            console.log('distancies', distancies);
        };

        function getDistancies(v, w) {
            return new Promise((resolve, reject) => {
                return fetch("https:/cors-anywhere.herokuapp.com/https://maps.googleapis.com/maps/api/distancematrix/json?key=" + apiKey + "&origins=" + w + "&destinations=" + v, {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'Access-Control-Allow-Origin': '*'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! Status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(distanceData => {
                        console.log(` '${v}' та '${w}' - ${distanceData.rows[0].elements[0].distance.value}`)
                        resolve({
                            point_A: v,
                            point_B: w,
                            distance: distanceData.rows[0].elements[0].distance.value // Приклад обробки результату
                        });
                    })
                    .catch((error) => {
                        console.error("Error fetching distance:", error);
                        reject(error);
                    });
            });
        }
        // console.log('combinations', combinations);
        // FindDistancies();

        // FindNumberOfDistanciesForEveryCombination();

        // distancies = JSON.parse(localStorage.getItem('distancies')) || [];
        // console.log('distancies', distancies);

        async function FindNumberOfDistanciesForEveryCombination() {
            const maxConcurrentRequests = 5;
            let currentRequests = 0;
            let delayBetweenRequests = 1000; // Затримка в мілісекундах (1 секунда)
            const maxDelay = 10000; // Максимальна затримка в мілісекундах (10 секунд)


            let distancies = JSON.parse(localStorage.getItem('distancies')) || [];
            let combinations = JSON.parse(localStorage.getItem('combinations')) || makeAllCombination();

            let distancies_new = [];
            ProgressBarModalUpdate(distancies.length);

            for (const combination of combinations) {

                let count = 0;
                distancies.forEach(distancy => {
                    if (distancy.point_A === combination[0] && distancy.point_B === combination[1]) {
                        count++;
                    }
                });
                if (count != 0) {
                    distancies_new.push({
                        points: combination,
                        count: count
                    });
                    combination.is_used = true;
                }
            };

            localStorage.setItem('distancies', JSON.stringify(distancies_new));
            localStorage.setItem('combinations', JSON.stringify(combinations));
        }
        // localStorage.removeItem('distancies');
        // localStorage.removeItem('combinations');


    </script> -->
    <?php

    $station_addresses = ["Київ", "Львів", "Одеса", "Чернівці", "Дніпро", "Івано-Франківськ", "Харків", "Запоріжжя", "Полтава", "Суми", "Миколаїв", "Черкаси", "Кривий Ріг", "Хмельницький", "Житомир"];

    $points = [
        "Київ, вул. Хрещатик, 1",
        "Львів, вул. Шевченка, 23",
        "Одеса, вул. Дерибасівська, 10",
        "Чернівці, вул. Героїв Майдану, 15",
        "Дніпро, пр. Дмитра Яворницького, 28",
        "Івано-Франківськ, вул. Незалежності, 24",
        "Харків, вул. Сумська, 45",
        "Запоріжжя, пр. Соборний, 12",
        "Полтава, вул. Пушкіна, 17",
        "Суми, вул. Горького, 8",
        "Київ, вул. Лесі Українки, 7",
        "Хмельницький, вул. Проскурівська, 14",
        "Львів, вул. Франка, 5",
        "Черкаси, бульв. Шевченка, 10",
        "Одеса, вул. Рішельєвська, 18",
        "Кривий Ріг, вул. Михайла Грушевського, 22",
        "Дніпро, вул. Леніна, 15",
        "Миколаїв, вул. Радянська, 3",
        "Харків, пр. Гагаріна, 21",
        "Житомир, вул. Михайлівська, 11"
    ];
    $distancies = [
        [
            "point_A" => "Львів, вул. Шевченка, 23",
            "point_B" =>  "Полтава, вул. Пушкіна, 17",
            "distance" =>  882709
        ],
        [
            "point_A" => "Львів, вул. Шевченка, 23",
            "point_B" =>  "Суми, вул. Горького, 8",
            "distance" =>  876226
        ],
        [
            "point_A" => "Львів, вул. Шевченка, 23",
            "point_B" =>  "Київ, вул. Лесі Українки, 7",
            "distance" =>  542117
        ],
        [
            "point_A" => "Львів, вул. Шевченка, 23",
            "point_B" =>  "Хмельницький, вул. Проскурівська, 14",
            "distance" =>  250517
        ],
        [
            "point_A" => "Львів, вул. Шевченка, 23",
            "point_B" =>  "Львів, вул. Франка, 5",
            "distance" =>  3445
        ],
        [
            "point_A" => "Львів, вул. Шевченка, 23",
            "point_B" =>  "Черкаси, бульв. Шевченка, 10",
            "distance" =>  735613
        ],
        [
            "point_A" => "Львів, вул. Шевченка, 23",
            "point_B" =>  "Одеса, вул. Рішельєвська, 18",
            "distance" =>  804286
        ],
        [
            "point_A" => "Львів, вул. Шевченка, 23",
            "point_B" =>  "Кривий Ріг, вул. Михайла Грушевського, 22",
            "distance" =>  824867
        ],
        [
            "point_A" => "Львів, вул. Шевченка, 23",
            "point_B" =>  "Дніпро, вул. Леніна, 15",
            "distance" =>  950552
        ],
        [
            "point_A" => "Львів, вул. Шевченка, 23",
            "point_B" =>  "Миколаїв, вул. Радянська, 3",
            "distance" =>  810682
        ],
        [
            "point_A" => "Львів, вул. Шевченка, 23",
            "point_B" =>  "Харків, пр. Гагаріна, 21",
            "distance" =>  1021286
        ],
        [
            "point_A" => "Львів, вул. Шевченка, 23",
            "point_B" =>  "Житомир, вул. Михайлівська, 11",
            "distance" =>  404471
        ],
        [
            "point_A" => "Одеса, вул. Дерибасівська, 10",
            "point_B" =>  "Чернівці, вул. Героїв Майдану, 15",
            "distance" =>  719905
        ],
        [
            "point_A" => "Одеса, вул. Дерибасівська, 10",
            "point_B" =>  "Дніпро, пр. Дмитра Яворницького, 28",
            "distance" =>  456425
        ],
        [
            "point_A" => "Одеса, вул. Дерибасівська, 10",
            "point_B" =>  "Івано-Франківськ, вул. Незалежності, 24",
            "distance" =>  799155
        ],
        [
            "point_A" => "Одеса, вул. Дерибасівська, 10",
            "point_B" =>  "Харків, вул. Сумська, 45",
            "distance" =>  676721
        ],
        [
            "point_A" => "Одеса, вул. Дерибасівська, 10",
            "point_B" =>  "Запоріжжя, пр. Соборний, 12",
            "distance" =>  529063
        ],
        [
            "point_A" => "Одеса, вул. Дерибасівська, 10",
            "point_B" =>  "Полтава, вул. Пушкіна, 17",
            "distance" =>  562201
        ],
        [
            "point_A" => "Одеса, вул. Дерибасівська, 10",
            "point_B" =>  "Суми, вул. Горького, 8",
            "distance" =>  807307
        ],
        [
            "point_A" => "Одеса, вул. Дерибасівська, 10",
            "point_B" =>  "Київ, вул. Лесі Українки, 7",
            "distance" =>  493675
        ],
        [
            "point_A" => "Одеса, вул. Дерибасівська, 10",
            "point_B" =>  "Хмельницький, вул. Проскурівська, 14",
            "distance" =>  555083
        ],
        [
            "point_A" => "Одеса, вул. Дерибасівська, 10",
            "point_B" =>  "Львів, вул. Франка, 5",
            "distance" =>  797790
        ],
        [
            "point_A" => "Одеса, вул. Дерибасівська, 10",
            "point_B" =>  "Черкаси, бульв. Шевченка, 10",
            "distance" =>  448446
        ],
        [
            "point_A" => "Одеса, вул. Дерибасівська, 10",
            "point_B" =>  "Одеса, вул. Рішельєвська, 18",
            "distance" =>  2085
        ],
        [
            "point_A" => "Одеса, вул. Дерибасівська, 10",
            "point_B" =>  "Кривий Ріг, вул. Михайла Грушевського, 22",
            "distance" =>  309571
        ],
        [
            "point_A" => "Одеса, вул. Дерибасівська, 10",
            "point_B" =>  "Дніпро, вул. Леніна, 15",
            "distance" =>  454979
        ],
        [
            "point_A" => "Одеса, вул. Дерибасівська, 10",
            "point_B" =>  "Миколаїв, вул. Радянська, 3",
            "distance" =>  134617
        ],
        [
            "point_A" => "Одеса, вул. Дерибасівська, 10",
            "point_B" =>  "Харків, пр. Гагаріна, 21",
            "distance" =>  676683
        ],
        [
            "point_A" => "Одеса, вул. Дерибасівська, 10",
            "point_B" =>  "Житомир, вул. Михайлівська, 11",
            "distance" =>  504461
        ],
        [
            "point_A" => "Чернівці, вул. Героїв Майдану, 15",
            "point_B" =>  "Дніпро, пр. Дмитра Яворницького, 28",
            "distance" =>  866403
        ],
        [
            "point_A" => "Чернівці, вул. Героїв Майдану, 15",
            "point_B" =>  "Івано-Франківськ, вул. Незалежності, 24",
            "distance" =>  136633
        ],
        [
            "point_A" => "Чернівці, вул. Героїв Майдану, 15",
            "point_B" =>  "Харків, вул. Сумська, 45",
            "distance" =>  1011258
        ],
        [
            "point_A" => "Чернівці, вул. Героїв Майдану, 15",
            "point_B" =>  "Запоріжжя, пр. Соборний, 12",
            "distance" =>  939041
        ],
        [
            "point_A" => "Чернівці, вул. Героїв Майдану, 15",
            "point_B" =>  "Полтава, вул. Пушкіна, 17",
            "distance" =>  872642
        ],
        [
            "point_A" => "Чернівці, вул. Героїв Майдану, 15",
            "point_B" =>  "Суми, вул. Горького, 8",
            "distance" =>  866159
        ],
        [
            "point_A" => "Чернівці, вул. Героїв Майдану, 15",
            "point_B" =>  "Київ, вул. Лесі Українки, 7",
            "distance" =>  532050
        ],
        [
            "point_A" => "Чернівці, вул. Героїв Майдану, 15",
            "point_B" =>  "Хмельницький, вул. Проскурівська, 14",
            "distance" =>  190004
        ],
        [
            "point_A" => "Чернівці, вул. Героїв Майдану, 15",
            "point_B" =>  "Львів, вул. Франка, 5",
            "distance" =>  276224
        ],
        [
            "point_A" => "Чернівці, вул. Героїв Майдану, 15",
            "point_B" =>  "Черкаси, бульв. Шевченка, 10",
            "distance" =>  633993
        ],
        [
            "point_A" => "Чернівці, вул. Героїв Майдану, 15",
            "point_B" =>  "Одеса, вул. Рішельєвська, 18",
            "distance" =>  718691
        ],
        [
            "point_A" => "Чернівці, вул. Героїв Майдану, 15",
            "point_B" =>  "Кривий Ріг, вул. Михайла Грушевського, 22",
            "distance" =>  739273
        ],
        [
            "point_A" => "Чернівці, вул. Героїв Майдану, 15",
            "point_B" =>  "Дніпро, вул. Леніна, 15",
            "distance" =>  864957
        ],
        [
            "point_A" => "Чернівці, вул. Героїв Майдану, 15",
            "point_B" =>  "Миколаїв, вул. Радянська, 3",
            "distance" =>  725087
        ],
        [
            "point_A" => "Чернівці, вул. Героїв Майдану, 15",
            "point_B" =>  "Харків, пр. Гагаріна, 21",
            "distance" =>  1011220
        ],
        [
            "point_A" => "Чернівці, вул. Героїв Майдану, 15",
            "point_B" =>  "Житомир, вул. Михайлівська, 11",
            "distance" =>  391199
        ],
        [
            "point_A" => "Дніпро, пр. Дмитра Яворницького, 28",
            "point_B" =>  "Івано-Франківськ, вул. Незалежності, 24",
            "distance" =>  946706
        ],
        [
            "point_A" => "Дніпро, пр. Дмитра Яворницького, 28",
            "point_B" =>  "Харків, вул. Сумська, 45",
            "distance" =>  219113
        ],
        [
            "point_A" => "Дніпро, пр. Дмитра Яворницького, 28",
            "point_B" =>  "Запоріжжя, пр. Соборний, 12",
            "distance" =>  94353
        ],
        [
            "point_A" => "Дніпро, пр. Дмитра Яворницького, 28",
            "point_B" =>  "Полтава, вул. Пушкіна, 17",
            "distance" =>  196992
        ],
        [
            "point_A" => "Дніпро, пр. Дмитра Яворницького, 28",
            "point_B" =>  "Суми, вул. Горького, 8",
            "distance" =>  376300
        ],
        [
            "point_A" => "Харків, вул. Сумська, 45",
            "point_B" =>  "Черкаси, бульв. Шевченка, 10",
            "distance" =>  396218
        ],
        [
            "point_A" => "Харків, вул. Сумська, 45",
            "point_B" =>  "Одеса, вул. Рішельєвська, 18",
            "distance" =>  675753
        ],
        [
            "point_A" => "Харків, вул. Сумська, 45",
            "point_B" =>  "Кривий Ріг, вул. Михайла Грушевського, 22",
            "distance" =>  367373
        ],
        [
            "point_A" => "Харків, вул. Сумська, 45",
            "point_B" =>  "Дніпро, вул. Леніна, 15",
            "distance" =>  218649
        ],
        [
            "point_A" => "Харків, вул. Сумська, 45",
            "point_B" =>  "Миколаїв, вул. Радянська, 3",
            "distance" =>  543478
        ],
        [
            "point_A" => "Харків, вул. Сумська, 45",
            "point_B" =>  "Харків, пр. Гагаріна, 21",
            "distance" =>  3671
        ],
        [
            "point_A" => "Харків, вул. Сумська, 45",
            "point_B" =>  "Житомир, вул. Михайлівська, 11",
            "distance" =>  632177
        ],
        [
            "point_A" => "Запоріжжя, пр. Соборний, 12",
            "point_B" =>  "Полтава, вул. Пушкіна, 17",
            "distance" =>  275540
        ],
        [
            "point_A" => "Запоріжжя, пр. Соборний, 12",
            "point_B" =>  "Суми, вул. Горького, 8",
            "distance" =>  454848
        ],
        [
            "point_A" => "Запоріжжя, пр. Соборний, 12",
            "point_B" =>  "Київ, вул. Лесі Українки, 7",
            "distance" =>  573106
        ],
        [
            "point_A" => "Запоріжжя, пр. Соборний, 12",
            "point_B" =>  "Хмельницький, вул. Проскурівська, 14",
            "distance" =>  769620
        ],
        [
            "point_A" => "Запоріжжя, пр. Соборний, 12",
            "point_B" =>  "Львів, вул. Франка, 5",
            "distance" =>  1012327
        ],
        [
            "point_A" => "Запоріжжя, пр. Соборний, 12",
            "point_B" =>  "Черкаси, бульв. Шевченка, 10",
            "distance" =>  390712
        ],
        [
            "point_A" => "Запоріжжя, пр. Соборний, 12",
            "point_B" =>  "Одеса, вул. Рішельєвська, 18",
            "distance" =>  522740
        ],
        [
            "point_A" => "Запоріжжя, пр. Соборний, 12",
            "point_B" =>  "Кривий Ріг, вул. Михайла Грушевського, 22",
            "distance" =>  214360
        ],
        [
            "point_A" => "Запоріжжя, пр. Соборний, 12",
            "point_B" =>  "Дніпро, вул. Леніна, 15",
            "distance" =>  90495
        ],
        [
            "point_A" => "Запоріжжя, пр. Соборний, 12",
            "point_B" =>  "Миколаїв, вул. Радянська, 3",
            "distance" =>  390464
        ],
        [
            "point_A" => "Запоріжжя, пр. Соборний, 12",
            "point_B" =>  "Харків, пр. Гагаріна, 21",
            "distance" =>  297622
        ],
        [
            "point_A" => "Запоріжжя, пр. Соборний, 12",
            "point_B" =>  "Житомир, вул. Михайлівська, 11",
            "distance" =>  695527
        ],
        [
            "point_A" => "Полтава, вул. Пушкіна, 17",
            "point_B" =>  "Суми, вул. Горького, 8",
            "distance" =>  177885
        ],
        [
            "point_A" => "Полтава, вул. Пушкіна, 17",
            "point_B" =>  "Київ, вул. Лесі Українки, 7",
            "distance" =>  362082
        ],
        [
            "point_A" => "Полтава, вул. Пушкіна, 17",
            "point_B" =>  "Хмельницький, вул. Проскурівська, 14",
            "distance" =>  684208
        ],
        [
            "point_A" => "Полтава, вул. Пушкіна, 17",
            "point_B" =>  "Львів, вул. Франка, 5",
            "distance" =>  892835
        ],
        [
            "point_A" => "Полтава, вул. Пушкіна, 17",
            "point_B" =>  "Черкаси, бульв. Шевченка, 10",
            "distance" =>  257991
        ],
        [
            "point_A" => "Полтава, вул. Пушкіна, 17",
            "point_B" =>  "Одеса, вул. Рішельєвська, 18",
            "distance" =>  560286
        ],
        [
            "point_A" => "Полтава, вул. Пушкіна, 17",
            "point_B" =>  "Кривий Ріг, вул. Михайла Грушевського, 22",
            "distance" =>  283202
        ],
        [
            "point_A" => "Полтава, вул. Пушкіна, 17",
            "point_B" =>  "Дніпро, вул. Леніна, 15",
            "distance" =>  198466
        ],
        [
            "point_A" => "Полтава, вул. Пушкіна, 17",
            "point_B" =>  "Миколаїв, вул. Радянська, 3",
            "distance" =>  428582
        ],
        [
            "point_A" => "Полтава, вул. Пушкіна, 17",
            "point_B" =>  "Харків, пр. Гагаріна, 21",
            "distance" =>  143248
        ],
        [
            "point_A" => "Полтава, вул. Пушкіна, 17",
            "point_B" =>  "Житомир, вул. Михайлівська, 11",
            "distance" =>  493950
        ],
        [
            "point_A" => "Суми, вул. Горького, 8",
            "point_B" =>  "Київ, вул. Лесі Українки, 7",
            "distance" =>  344402
        ],
        [
            "point_A" => "Суми, вул. Горького, 8",
            "point_B" =>  "Хмельницький, вул. Проскурівська, 14",
            "distance" =>  666528
        ],
        [
            "point_A" => "Суми, вул. Горького, 8",
            "point_B" =>  "Львів, вул. Франка, 5",
            "distance" =>  875155
        ],
        [
            "point_A" => "Суми, вул. Горького, 8",
            "point_B" =>  "Черкаси, бульв. Шевченка, 10",
            "distance" =>  324887
        ],
        [
            "point_A" => "Суми, вул. Горького, 8",
            "point_B" =>  "Одеса, вул. Рішельєвська, 18",
            "distance" =>  805149
        ],
        [
            "point_A" => "Суми, вул. Горького, 8",
            "point_B" =>  "Кривий Ріг, вул. Михайла Грушевського, 22",
            "distance" =>  526022
        ],
        [
            "point_A" => "Суми, вул. Горького, 8",
            "point_B" =>  "Дніпро, вул. Леніна, 15",
            "distance" =>  377298
        ],
        [
            "point_A" => "Суми, вул. Горького, 8",
            "point_B" =>  "Миколаїв, вул. Радянська, 3",
            "distance" =>  607921
        ],
        [
            "point_A" => "Суми, вул. Горького, 8",
            "point_B" =>  "Харків, пр. Гагаріна, 21",
            "distance" =>  189263
        ],
        [
            "point_A" => "Суми, вул. Горького, 8",
            "point_B" =>  "Житомир, вул. Михайлівська, 11",
            "distance" =>  476270
        ],
        [
            "point_A" => "Київ, вул. Лесі Українки, 7",
            "point_B" =>  "Хмельницький, вул. Проскурівська, 14",
            "distance" =>  333588
        ],
        [
            "point_A" => "Київ, вул. Лесі Українки, 7",
            "point_B" =>  "Львів, вул. Франка, 5",
            "distance" =>  542215
        ],
        [
            "point_A" => "Київ, вул. Лесі Українки, 7",
            "point_B" =>  "Черкаси, бульв. Шевченка, 10",
            "distance" =>  199497
        ],
        [
            "point_A" => "Київ, вул. Лесі Українки, 7",
            "point_B" =>  "Одеса, вул. Рішельєвська, 18",
            "distance" =>  492059
        ],
        [
            "point_A" => "Київ, вул. Лесі Українки, 7",
            "point_B" =>  "Кривий Ріг, вул. Михайла Грушевського, 22",
            "distance" =>  427058
        ],
        [
            "point_A" => "Київ, вул. Лесі Українки, 7",
            "point_B" =>  "Дніпро, вул. Леніна, 15",
            "distance" =>  497720
        ],
        [
            "point_A" => "Київ, вул. Лесі Українки, 7",
            "point_B" =>  "Миколаїв, вул. Радянська, 3",
            "distance" =>  497114
        ],
        [
            "point_A" => "Київ, вул. Лесі Українки, 7",
            "point_B" =>  "Харків, пр. Гагаріна, 21",
            "distance" =>  498704
        ],
        [
            "point_A" => "Київ, вул. Лесі Українки, 7",
            "point_B" =>  "Житомир, вул. Михайлівська, 11",
            "distance" =>  143330
        ],
        [
            "point_A" => "Хмельницький, вул. Проскурівська, 14",
            "point_B" =>  "Львів, вул. Франка, 5",
            "distance" =>  239918
        ],
        [
            "point_A" => "Київ, вул. Хрещатик, 1",
            "point_B" =>  "Львів, вул. Шевченка, 23",
            "distance" =>  541278
        ],
        [
            "point_A" => "Київ, вул. Хрещатик, 1",
            "point_B" =>  "Одеса, вул. Дерибасівська, 10",
            "distance" =>  476318
        ],
        [
            "point_A" => "Київ, вул. Хрещатик, 1",
            "point_B" =>  "Чернівці, вул. Героїв Майдану, 15",
            "distance" =>  525587
        ],
        [
            "point_A" => "Київ, вул. Хрещатик, 1",
            "point_B" =>  "Дніпро, пр. Дмитра Яворницького, 28",
            "distance" =>  478364
        ],
        [
            "point_A" => "Київ, вул. Хрещатик, 1",
            "point_B" =>  "Івано-Франківськ, вул. Незалежності, 24",
            "distance" =>  604607
        ],
        [
            "point_A" => "Київ, вул. Хрещатик, 1",
            "point_B" =>  "Харків, вул. Сумська, 45",
            "distance" =>  481758
        ],
        [
            "point_A" => "Київ, вул. Хрещатик, 1",
            "point_B" =>  "Запоріжжя, пр. Соборний, 12",
            "distance" =>  566853
        ],
        [
            "point_A" => "Київ, вул. Хрещатик, 1",
            "point_B" =>  "Полтава, вул. Пушкіна, 17",
            "distance" =>  343141
        ],
        [
            "point_A" => "Київ, вул. Хрещатик, 1",
            "point_B" =>  "Суми, вул. Горького, 8",
            "distance" =>  336519
        ],
        [
            "point_A" => "Київ, вул. Хрещатик, 1",
            "point_B" =>  "Київ, вул. Лесі Українки, 7",
            "distance" =>  11919
        ],
        [
            "point_A" => "Київ, вул. Хрещатик, 1",
            "point_B" =>  "Хмельницький, вул. Проскурівська, 14",
            "distance" =>  330930
        ],
        [
            "point_A" => "Київ, вул. Хрещатик, 1",
            "point_B" =>  "Львів, вул. Франка, 5",
            "distance" =>  539557
        ],
        [
            "point_A" => "Київ, вул. Хрещатик, 1",
            "point_B" =>  "Черкаси, бульв. Шевченка, 10",
            "distance" =>  190278
        ],
        [
            "point_A" => "Київ, вул. Хрещатик, 1",
            "point_B" =>  "Одеса, вул. Рішельєвська, 18",
            "distance" =>  475880
        ],
        [
            "point_A" => "Київ, вул. Хрещатик, 1",
            "point_B" =>  "Кривий Ріг, вул. Михайла Грушевського, 22",
            "distance" =>  418018
        ],
        [
            "point_A" => "Київ, вул. Хрещатик, 1",
            "point_B" =>  "Дніпро, вул. Леніна, 15",
            "distance" =>  480736
        ],
        [
            "point_A" => "Київ, вул. Хрещатик, 1",
            "point_B" =>  "Миколаїв, вул. Радянська, 3",
            "distance" =>  488074
        ],
        [
            "point_A" => "Київ, вул. Хрещатик, 1",
            "point_B" =>  "Харків, пр. Гагаріна, 21",
            "distance" =>  481719
        ],
        [
            "point_A" => "Київ, вул. Хрещатик, 1",
            "point_B" =>  "Житомир, вул. Михайлівська, 11",
            "distance" =>  140672
        ],
        [
            "point_A" => "Львів, вул. Шевченка, 23",
            "point_B" =>  "Одеса, вул. Дерибасівська, 10",
            "distance" =>  804724
        ],
        [
            "point_A" => "Львів, вул. Шевченка, 23",
            "point_B" =>  "Чернівці, вул. Героїв Майдану, 15",
            "distance" =>  279843
        ],
        [
            "point_A" => "Львів, вул. Шевченка, 23",
            "point_B" =>  "Дніпро, пр. Дмитра Яворницького, 28",
            "distance" =>  951997
        ],
        [
            "point_A" => "Львів, вул. Шевченка, 23",
            "point_B" =>  "Івано-Франківськ, вул. Незалежності, 24",
            "distance" =>  136280
        ],
        [
            "point_A" => "Львів, вул. Шевченка, 23",
            "point_B" =>  "Харків, вул. Сумська, 45",
            "distance" =>  1021325
        ],
        [
            "point_A" => "Львів, вул. Шевченка, 23",
            "point_B" =>  "Запоріжжя, пр. Соборний, 12",
            "distance" =>  1024636
        ],
        [
            "point_A" => "Дніпро, пр. Дмитра Яворницького, 28",
            "point_B" =>  "Київ, вул. Лесі Українки, 7",
            "distance" =>  496009
        ],
        [
            "point_A" => "Дніпро, пр. Дмитра Яворницького, 28",
            "point_B" =>  "Хмельницький, вул. Проскурівська, 14",
            "distance" =>  702634
        ],
        [
            "point_A" => "Дніпро, пр. Дмитра Яворницького, 28",
            "point_B" =>  "Львів, вул. Франка, 5",
            "distance" =>  945341
        ],
        [
            "point_A" => "Дніпро, пр. Дмитра Яворницького, 28",
            "point_B" =>  "Черкаси, бульв. Шевченка, 10",
            "distance" =>  323725
        ],
        [
            "point_A" => "Дніпро, пр. Дмитра Яворницького, 28",
            "point_B" =>  "Одеса, вул. Рішельєвська, 18",
            "distance" =>  455754
        ],
        [
            "point_A" => "Дніпро, пр. Дмитра Яворницького, 28",
            "point_B" =>  "Кривий Ріг, вул. Михайла Грушевського, 22",
            "distance" =>  147374
        ],
        [
            "point_A" => "Дніпро, пр. Дмитра Яворницького, 28",
            "point_B" =>  "Дніпро, вул. Леніна, 15",
            "distance" =>  2191
        ],
        [
            "point_A" => "Дніпро, пр. Дмитра Яворницького, 28",
            "point_B" =>  "Миколаїв, вул. Радянська, 3",
            "distance" =>  323478
        ],
        [
            "point_A" => "Дніпро, пр. Дмитра Яворницького, 28",
            "point_B" =>  "Харків, пр. Гагаріна, 21",
            "distance" =>  219074
        ],
        [
            "point_A" => "Дніпро, пр. Дмитра Яворницького, 28",
            "point_B" =>  "Житомир, вул. Михайлівська, 11",
            "distance" =>  628541
        ],
        [
            "point_A" => "Івано-Франківськ, вул. Незалежності, 24",
            "point_B" =>  "Харків, вул. Сумська, 45",
            "distance" =>  1084880
        ],
        [
            "point_A" => "Івано-Франківськ, вул. Незалежності, 24",
            "point_B" =>  "Запоріжжя, пр. Соборний, 12",
            "distance" =>  1018539
        ],
        [
            "point_A" => "Івано-Франківськ, вул. Незалежності, 24",
            "point_B" =>  "Полтава, вул. Пушкіна, 17",
            "distance" =>  946264
        ],
        [
            "point_A" => "Івано-Франківськ, вул. Незалежності, 24",
            "point_B" =>  "Суми, вул. Горького, 8",
            "distance" =>  939782
        ],
        [
            "point_A" => "Івано-Франківськ, вул. Незалежності, 24",
            "point_B" =>  "Київ, вул. Лесі Українки, 7",
            "distance" =>  605672
        ],
        [
            "point_A" => "Івано-Франківськ, вул. Незалежності, 24",
            "point_B" =>  "Хмельницький, вул. Проскурівська, 14",
            "distance" =>  244421
        ],
        [
            "point_A" => "Івано-Франківськ, вул. Незалежності, 24",
            "point_B" =>  "Львів, вул. Франка, 5",
            "distance" =>  132298
        ],
        [
            "point_A" => "Івано-Франківськ, вул. Незалежності, 24",
            "point_B" =>  "Черкаси, бульв. Шевченка, 10",
            "distance" =>  713491
        ],
        [
            "point_A" => "Івано-Франківськ, вул. Незалежності, 24",
            "point_B" =>  "Одеса, вул. Рішельєвська, 18",
            "distance" =>  798189
        ],
        [
            "point_A" => "Івано-Франківськ, вул. Незалежності, 24",
            "point_B" =>  "Кривий Ріг, вул. Михайла Грушевського, 22",
            "distance" =>  818771
        ],
        [
            "point_A" => "Івано-Франківськ, вул. Незалежності, 24",
            "point_B" =>  "Дніпро, вул. Леніна, 15",
            "distance" =>  944455
        ],
        [
            "point_A" => "Івано-Франківськ, вул. Незалежності, 24",
            "point_B" =>  "Миколаїв, вул. Радянська, 3",
            "distance" =>  804585
        ],
        [
            "point_A" => "Івано-Франківськ, вул. Незалежності, 24",
            "point_B" =>  "Харків, пр. Гагаріна, 21",
            "distance" =>  1084842
        ],
        [
            "point_A" => "Івано-Франківськ, вул. Незалежності, 24",
            "point_B" =>  "Житомир, вул. Михайлівська, 11",
            "distance" =>  418350
        ],
        [
            "point_A" => "Харків, вул. Сумська, 45",
            "point_B" =>  "Запоріжжя, пр. Соборний, 12",
            "distance" =>  299507
        ],
        [
            "point_A" => "Харків, вул. Сумська, 45",
            "point_B" =>  "Полтава, вул. Пушкіна, 17",
            "distance" =>  143897
        ],
        [
            "point_A" => "Харків, вул. Сумська, 45",
            "point_B" =>  "Суми, вул. Горького, 8",
            "distance" =>  189413
        ],
        [
            "point_A" => "Харків, вул. Сумська, 45",
            "point_B" =>  "Київ, вул. Лесі Українки, 7",
            "distance" =>  500308
        ],
        [
            "point_A" => "Харків, вул. Сумська, 45",
            "point_B" =>  "Хмельницький, вул. Проскурівська, 14",
            "distance" =>  822435
        ],
        [
            "point_A" => "Харків, вул. Сумська, 45",
            "point_B" =>  "Львів, вул. Франка, 5",
            "distance" =>  1031062
        ],
        [
            "point_A" => "Хмельницький, вул. Проскурівська, 14",
            "point_B" =>  "Черкаси, бульв. Шевченка, 10",
            "distance" =>  469056
        ],
        [
            "point_A" => "Хмельницький, вул. Проскурівська, 14",
            "point_B" =>  "Одеса, вул. Рішельєвська, 18",
            "distance" =>  553754
        ],
        [
            "point_A" => "Хмельницький, вул. Проскурівська, 14",
            "point_B" =>  "Кривий Ріг, вул. Михайла Грушевського, 22",
            "distance" =>  574336
        ],
        [
            "point_A" => "Хмельницький, вул. Проскурівська, 14",
            "point_B" =>  "Дніпро, вул. Леніна, 15",
            "distance" =>  700020
        ],
        [
            "point_A" => "Хмельницький, вул. Проскурівська, 14",
            "point_B" =>  "Миколаїв, вул. Радянська, 3",
            "distance" =>  560150
        ],
        [
            "point_A" => "Хмельницький, вул. Проскурівська, 14",
            "point_B" =>  "Харків, пр. Гагаріна, 21",
            "distance" =>  810908
        ],
        [
            "point_A" => "Хмельницький, вул. Проскурівська, 14",
            "point_B" =>  "Житомир, вул. Михайлівська, 11",
            "distance" =>  193370
        ],
        [
            "point_A" => "Львів, вул. Франка, 5",
            "point_B" =>  "Черкаси, бульв. Шевченка, 10",
            "distance" =>  711802
        ],
        [
            "point_A" => "Львів, вул. Франка, 5",
            "point_B" =>  "Одеса, вул. Рішельєвська, 18",
            "distance" =>  796501
        ],
        [
            "point_A" => "Львів, вул. Франка, 5",
            "point_B" =>  "Кривий Ріг, вул. Михайла Грушевського, 22",
            "distance" =>  817082
        ],
        [
            "point_A" => "Львів, вул. Франка, 5",
            "point_B" =>  "Дніпро, вул. Леніна, 15",
            "distance" =>  942767
        ],
        [
            "point_A" => "Львів, вул. Франка, 5",
            "point_B" =>  "Миколаїв, вул. Радянська, 3",
            "distance" =>  802897
        ],
        [
            "point_A" => "Львів, вул. Франка, 5",
            "point_B" =>  "Харків, пр. Гагаріна, 21",
            "distance" =>  1020498
        ],
        [
            "point_A" => "Львів, вул. Франка, 5",
            "point_B" =>  "Житомир, вул. Михайлівська, 11",
            "distance" =>  403683
        ],
        [
            "point_A" => "Черкаси, бульв. Шевченка, 10",
            "point_B" =>  "Одеса, вул. Рішельєвська, 18",
            "distance" =>  446199
        ],
        [
            "point_A" => "Черкаси, бульв. Шевченка, 10",
            "point_B" =>  "Кривий Ріг, вул. Михайла Грушевського, 22",
            "distance" =>  247063
        ],
        [
            "point_A" => "Черкаси, бульв. Шевченка, 10",
            "point_B" =>  "Дніпро, вул. Леніна, 15",
            "distance" =>  321427
        ],
        [
            "point_A" => "Черкаси, бульв. Шевченка, 10",
            "point_B" =>  "Миколаїв, вул. Радянська, 3",
            "distance" =>  317119
        ],
        [
            "point_A" => "Черкаси, бульв. Шевченка, 10",
            "point_B" =>  "Харків, пр. Гагаріна, 21",
            "distance" =>  381535
        ],
        [
            "point_A" => "Черкаси, бульв. Шевченка, 10",
            "point_B" =>  "Житомир, вул. Михайлівська, 11",
            "distance" =>  330907
        ],
        [
            "point_A" => "Одеса, вул. Рішельєвська, 18",
            "point_B" =>  "Кривий Ріг, вул. Михайла Грушевського, 22",
            "distance" =>  308892
        ],
        [
            "point_A" => "Одеса, вул. Рішельєвська, 18",
            "point_B" =>  "Дніпро, вул. Леніна, 15",
            "distance" =>  454299
        ],
        [
            "point_A" => "Одеса, вул. Рішельєвська, 18",
            "point_B" =>  "Миколаїв, вул. Радянська, 3",
            "distance" =>  133937
        ],
        [
            "point_A" => "Одеса, вул. Рішельєвська, 18",
            "point_B" =>  "Харків, пр. Гагаріна, 21",
            "distance" =>  676003
        ],
        [
            "point_A" => "Одеса, вул. Рішельєвська, 18",
            "point_B" =>  "Житомир, вул. Михайлівська, 11",
            "distance" =>  502985
        ],
        [
            "point_A" => "Кривий Ріг, вул. Михайла Грушевського, 22",
            "point_B" =>  "Дніпро, вул. Леніна, 15",
            "distance" =>  145363
        ],
        [
            "point_A" => "Кривий Ріг, вул. Михайла Грушевського, 22",
            "point_B" =>  "Миколаїв, вул. Радянська, 3",
            "distance" =>  176285
        ],
        [
            "point_A" => "Кривий Ріг, вул. Михайла Грушевського, 22",
            "point_B" =>  "Харків, пр. Гагаріна, 21",
            "distance" =>  367067
        ],
        [
            "point_A" => "Кривий Ріг, вул. Михайла Грушевського, 22",
            "point_B" =>  "Житомир, вул. Михайлівська, 11",
            "distance" =>  523880
        ],
        [
            "point_A" => "Дніпро, вул. Леніна, 15",
            "point_B" =>  "Миколаїв, вул. Радянська, 3",
            "distance" =>  321289
        ],
        [
            "point_A" => "Дніпро, вул. Леніна, 15",
            "point_B" =>  "Харків, пр. Гагаріна, 21",
            "distance" =>  219349
        ],
        [
            "point_A" => "Дніпро, вул. Леніна, 15",
            "point_B" =>  "Житомир, вул. Михайлівська, 11",
            "distance" =>  626352
        ],
        [
            "point_A" => "Миколаїв, вул. Радянська, 3",
            "point_B" =>  "Харків, пр. Гагаріна, 21",
            "distance" =>  542214
        ],
        [
            "point_A" => "Миколаїв, вул. Радянська, 3",
            "point_B" =>  "Житомир, вул. Михайлівська, 11",
            "distance" =>  509415
        ],
        [
            "point_A" => "Харків, пр. Гагаріна, 21",
            "point_B" =>  "Житомир, вул. Михайлівська, 11",
            "distance" =>  632626
        ]
    ];
    function getRandPoint($array)
    {
        return $array[rand(0, count($array) - 1)];
    }

    function vardump($content): void
    {
        echo "<pre>";
        var_dump($content);
        echo "</pre>";
    }

    function vardump2($content): void
    {
        foreach ($content as $item) {
            echo $item[0] . " - " . $item[1] . "<br>";
        }
    }
    $orders = [];
    for ($i = 0; $i < 0; $i++) {
        $orders[] = (object)[
            "point_A" => getRandPoint($points),
            "point_B" => getRandPoint($points)
        ];
    }
    //test all methods
    $res = getRidePoints($orders, $distancies);

    echo 'result:';
    vardump($res);


    function getRidePoints($orders, $distancies)
    {
        if (!is_array($orders)||empty($orders)) {
            return null;
        }

        if (count($orders) == 1) {
            return [$orders[0]->point_A, $orders[0]->point_B];
        }

        // Find best route
        $points = [];
        foreach ($orders as $order) {
            $points[] = $order->point_A;
            $points[] = $order->point_B;
        }
        // $points = array_unique($points);

        $COMB = new Combination($points, 0, count($points) - 1);
        $combinations = $COMB->getResult();

        if (!is_array($combinations) || empty($combinations)) {
            throw new Exception('Combinations not found');
        }

        $best_route = $combinations[0];
        $best_route_distance = getDistanceByPoints($best_route, $distancies);

        foreach ($combinations as $combination) {
            $route = getDistanceByPoints($combination, $distancies);
            if ($route < $best_route_distance) {
                $best_route = $combination;
                $best_route_distance = $route;
            }
        }

        return $best_route;
    }

    function getDistance($point_A, $point_B, $distancies)
    {
        foreach ($distancies as $distance) {
            if ($point_A == $point_B) return 0;
            if ($distance['point_A'] == $point_A && $distance['point_B'] == $point_B) return $distance['distance'];
            if ($distance['point_A'] == $point_B && $distance['point_B'] == $point_A) return $distance['distance'];
        }
        return 0;
    }
    class Combination
    {
        private $result;

        //add property
        public function getResult()
        {
            return $this->result;
        }

        function __construct(array $array)
        {
            $this->result = [];

            $this->getCombinations($array, 0, count($array) - 1);
        }
        private function getCombinations($a, $i, $n)
        {
            if ($i == $n) {
                $this->result[] = $a;
            } else {
                for ($j = $i; $j <= $n; $j++) {
                    $this->swap($a, $i, $j);
                    $this->getCombinations($a, $i + 1, $n);
                    $this->swap($a, $i, $j); // backtrack
                }
            }
        }

        private function swap(&$a, $i, $j)
        {
            $temp = $a[$i];
            $a[$i] = $a[$j];
            $a[$j] = $temp;
        }
    }
    function getDistanceByPoints($points, $distancies)
    {
        $distance = 0;
        if (is_array($points)) {
            for ($i = 0; $i < count($points) - 1; $i++) {
                $distance += getDistance($points[$i], $points[$i + 1], $distancies);
            }
        }
        return $distance;
    }




    ?>
</body>

</html>

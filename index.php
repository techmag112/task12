<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Практика работа с массивами и строками в php</title>
</head>
<body>
    <?php
        $example_persons_array = [
            [
                'fullname' => 'Иванов Иван Иванович',
                'job' => 'tester',
            ],
            [
                'fullname' => 'Степанова Наталья Степановна',
                'job' => 'frontend-developer',
            ],
            [
                'fullname' => 'Пащенко Владимир Александрович',
                'job' => 'analyst',
            ],
            [
                'fullname' => 'Громов Александр Иванович',
                'job' => 'fullstack-developer',
            ],
            [
                'fullname' => 'Славин Семён Сергеевич',
                'job' => 'analyst',
            ],
            [
                'fullname' => 'Цой Владимир Антонович',
                'job' => 'frontend-developer',
            ],
            [
                'fullname' => 'Быстрая Юлия Сергеевна',
                'job' => 'PR-manager',
            ],
            [
                'fullname' => 'Шматко Антонина Сергеевна',
                'job' => 'HR-manager',
            ],
            [
                'fullname' => 'аль-Хорезми Мухаммад ибн-Муса',
                'job' => 'analyst',
            ],
            [
                'fullname' => 'Бардо Жаклин Фёдоровна',
                'job' => 'android-developer',
            ],
            [
                'fullname' => 'Шварцнегер Арнольд Густавович',
                'job' => 'babysitter',
            ],
        ];

        /* Генерируем случайный индекс */    
        $index = mt_rand(0, 10);

        function getPartsFromFullname($fio)
        {
            $parts = explode(' ', $fio);
            return ['surname' => $parts[0], 'name' => $parts[1], 'patronomyc' => $parts[2]];
        
        }

        function getFullnameFromParts($str1, $srt2, $srt3) {
            
            return $str1.' '.$srt2.' '.$srt3;
        }

        echo '<h3>Преобразуем рандомное ФИО в массив:</h3>';
        echo "<br>Запускаем getPartsFromFullname(example_persons_array[index]['fullname'])<br>Результат:<br>";
        var_dump(getPartsFromFullname($example_persons_array[$index]['fullname']));
        echo '<br>===============================================================<br>';

        function getShortName($fio)
        {   
            $arr = getPartsFromFullname($fio);
            return $arr['surname'].' '.mb_substr($arr['name'], 0, 1).'.';
        }
        
        echo '<h3>Преобразуем рандомное ФИО в фамилию и первую букву имени с точкой:</h3>';
        echo "Запускаем getShortName(example_persons_array[index]['fullname'])<br>Результат:<br>";
        echo getShortName($example_persons_array[$index]['fullname']);
        echo '<br>===============================================================<br>';

        function getGenderFromName($str) {
            $gender = 0;
            $arr = getPartsFromFullname($str);
            if (mb_substr($arr['surname'], -2) === 'ва') {
                $gender--;
            } elseif (mb_substr($arr['patronomyc'], -3) === 'вна')  {
                $gender--;
            } elseif (mb_substr($arr['name'], -1) === 'а') {
                $gender--;
            }

            if (mb_substr($arr['surname'], -1) === 'в') {
                $gender++;
            } elseif (mb_substr($arr['patronomyc'], -2) === 'ич')  {
                $gender++;
            } elseif ((mb_substr($arr['name'], -1) === 'н') or (mb_substr($arr['name'], -1) === 'й')) {
                $gender++;
            }

            if ($gender >=1) {
                $gender = 1;
            } elseif ($gender >=0) {
                $gender = 0;
            } else {
                $gender = -1;
            }

            return $gender;
        }

        echo '<h3>Определяем пол по ФИО (1 - мужчина, -1 - женщина, 0 - не удалось):</h3>';
        echo "Запускаем getGenderFromName(example_persons_array[index]['fullname'])<br>Результат:<br>";
        echo getGenderFromName($example_persons_array[$index]['fullname']);
        echo '<br>===============================================================<br>';

        function getGenderDescription($arr) {
            $text = "Гендерный состав аудитории:<br>---------------------------<br>Мужчины - ";
            $men = 0;
            $women = 0;
            $i = 0;
            foreach($arr as $value) {
                $gender = getGenderFromName($value['fullname']);
                if ($gender > 0) {
                    $men++;
                } elseif ($gender < 0) {
                    $women++;
                }
                $i++;
            }
            
            $text .= round(($men*100)/$i, 1).'% <br>';
            $text .= 'Женщины - '.round(($women*100)/$i, 1).'% <br>';
            $text .= 'Не удалось определить - '.round((($i-$men-$women)*100)/$i, 1).'%';
            return $text;
        }

        echo '<h3>Определяем гендерный состав массива:</h3>';
        echo "Запускаем getGenderDescription(example_persons_array)<br>Результат:<br>";
        echo getGenderDescription($example_persons_array);
        echo '<br>===============================================================<br>';

        function getPerfectPartner($str1, $str2, $str3, $arr) {
            $str1 = transformString($str1);
            $str2 = transformString($str2);
            $str3 = transformString($str3);
            $fio = getFullnameFromParts($str1, $str2, $str3);
            $gender1 = getGenderFromName($fio);
            $people1 = getShortName($fio);
            do {
                $index = mt_rand(0, 10);
                $gender2 = getGenderFromName($arr[$index]['fullname']);
                $people2 = getShortName($arr[$index]['fullname']);
            } while ($gender1 === $gender2);
            
            $text = $people1.' + '.$people2.' =<br>♡ Идеально на '.(mt_rand(5000, 10000)/100).'% ♡';
            return $text;
        }
   
        function transformString($str) {
            $str = mb_strtoupper(mb_substr($str, 0, 1)).mb_strtolower(mb_substr($str, 1, mb_strlen($str)));
            return $str;
        }

        echo '<h3>Строим таблицу совместимости по рандомному ФИО</h3>';
        echo "Запускаем getPerfectPartner('ИВАнов', 'иваН', 'иваНОВич', example_persons_array)<br>Результат:<br>";
        echo getPerfectPartner('ИВАнов', 'иваН', 'иваНОВич', $example_persons_array);
        echo '<br>===============================================================<br>';


    ?> 
</body>
</html>
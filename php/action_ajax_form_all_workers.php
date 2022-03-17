<?php

include "Workers.php";

$date = $_POST["date"];

$worler = new Workers();
$out_table_data = $worler->output_all_workers($date);


// Формируем массив для JSON ответа
$result = array(
    'date' => $out_table_data

);

// Переводим массив в JSON
echo json_encode($result);



<?php
    include "Workers.php";

    $date = $_POST["date"];
    $id_worker = $_POST["id"];

    $worler = new Workers();
    $out_table_data = $worler->table_worker($date,$id_worker);


    // Формируем массив для JSON ответа
   $result = array(
        'date' => $out_table_data


    );

    // Переводим массив в JSON
    echo json_encode($result);


?>
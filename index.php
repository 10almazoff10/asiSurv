<?php
include "php/Workers.php";

?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">


    <meta name="description" content="Article FRUCTCODE.COM. How to send ajax form.">
    <meta name="author" content="fructcode.com">

    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
    <script src="js/ajax.js"></script>

    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="/js/HideSeek-master/jquery.hideseek.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <link rel="icon" class="fav" href="img/favicon.svg" type="image/svg+xml">
    <link rel="icon" href="img/favicon.ico" type="image/x-icon">
    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300&family=Roboto:wght@100;300&display=swap" rel="stylesheet">
    <script src="https://momentjs.com/downloads/moment.js"></script>
    <script src="https://momentjs.com/downloads/moment-with-locales.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>

    <title>СУРВ</title>
</head>

<body>

<div class="main_box">


    <div class="left_panel box3d">
        <div class="workers_list">
            <div class="navbar">
                <input placeholder="Начните писать фамилию..." class="form-control" id="search" name="search" type="text" data-toggle="hideseek" data-list=".form_radio_btn" autofocus>
            </div>

            <form method="POST" id="ajax_form" action="" >

            <ul class="list-group" >

                <div class="form_radio_btn">

                    <?php
                    $a= new Workers();
                    $a->list_workers();
                    ?>

                </div>
                



                
                
            </ul>
        </div>
    </div>
    <div class="mid_pannel box3d">
        <div class="top_picker box3d">




                <div class='input-group date' id='datetimepicker10'>
                    <input placeholder="Выберите дату" name="date" type='text' class="form-control" />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar">
                        </span>

                    </span>
                    
                </div>
                <input class="btn btn-light btn_date" type="button" id="btn" value="Выбрать" />
                <button class="btn btn-light btn_date" id="print" onclick="printContent('result_form');" >Печать</button>
                
            </form>

            <div class="result_form1" id="result_form1"></div>


        </div>



        <br>


            <div class="worker_table box3d" id="result_form"></div>

            </div>




<script type="text/javascript">
    $(function () {
        $('#datetimepicker10').datetimepicker({
            locale: 'ru',
            viewMode: 'years',
            format: 'MM/YYYY'

        });
    });
    //Функция печати дива "result_form"
    function printContent(el){
        var restorepage = $('body').html();
        var printcontent = $('#' + el).clone();
        var enteredtext = $('#text').val();
        $('body').empty().html(printcontent);
        window.print();
        $('body').html(restorepage);
        $('#text').html(enteredtext);
    }
</script>














</body>
</html>
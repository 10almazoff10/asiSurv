<?php

class Workers
{
    public $url = "localhost";
    public $user = "tex";
    public $pass = "Te99874566";
    public $db = "timesheet";
    public $table_workers = "workers";
    public $table_timeing = "timeing";

    function outputWorkers(){

    }

    function list_workers(){
        $arr_workers = $this->querySql( "SELECT * FROM $this->table_workers");
        foreach ($arr_workers as $key => $value)
        {
            $id = $value['id'];
            $first_name= $value['first_name'];
            $mid_name = $value['mid_name'];
            $last_name = $value['last_name'];
            $date_end = $value['date_end'];
            if ($date_end == '')
            {
                echo '<input id="id'.$key.'" type="radio" name="id" value="'.$id.'" >
                      <label  for="id'.$key.'">'."$first_name $mid_name $last_name" . '</label>';

                //echo '<li><a href="?id='.$id.'" class="list-group-item list-group-item-action">'."$first_name $mid_name $last_name" . '</a></li>';
            }
            /*      Вывод вместе с уволенными
             *     if ($date_end != '') {
                    echo '<li><a href="?id='.$id.'" class="list-group-item list-group-item-action">'. '❌ '."$first_name $mid_name $last_name" . '</a></li>';
                }else{
                    echo '<li><a href="?id='.$id.'" class="list-group-item list-group-item-action">'. '✅ '."$first_name $mid_name $last_name" . '</a></li>';
                }
    */
        }
    }

    function querySql($query){
        $mysqli = new mysqli($this->url, $this->user, $this->pass, $this->db);

        foreach ($mysqli->query($query) as $key => $value) {

            $first_name = $value['last_name'];
            $mid_name = $value['first_name'];
            $last_name = $value['mid_name'];
            $id = $value['worker_id'];
            $company = $value['company'];
            $division = $value['division'];
            $post = $value['post'];
            $date_end = $value['date_end'];

            $arr["$key"] = array(first_name => $first_name, mid_name => $mid_name, last_name => $last_name,
                id => $id, company => $company, division => $division, post => $post, date_end => $date_end);


        }

        return $arr;


    }

    function querySqlTimeing($query){
        $mysqli = new mysqli($this->url, $this->user, $this->pass, $this->db);

        foreach ($mysqli->query($query) as $key => $value) {

            $user_id = $value['user_id'];
            $datetime = $value['datetime'];
            $event = $value['event'];



            $arr["$key"] = array(user_id => $user_id, datetime => $datetime, event => $event);

        }

        return $arr;


    }

    function worker_out_data($date,$id)
    {
        $pieces_date = explode("/", $date);
        $month = $pieces_date[0];
        $year = $pieces_date[1];
        $sqlQuery = "SELECT * FROM $this->table_workers WHERE worker_id=$id";
        $sqlrResult = $this->querySql($sqlQuery);

        foreach ($sqlrResult as $key => $value)
        {
            $first_name= $value['first_name'];
            $mid_name = $value['mid_name'];
            $last_name = $value['last_name'];
            $company = $value['company'];
            $post = $value['post'];
        }

        $FIO =$first_name. " ".$mid_name." ".$last_name;
        if ($company == 'АлтайСпецИзделия') {
            $linkPic = '/img/asilogo.png';
        } elseif($company == 'Интермаркет'){
            $linkPic = '/img/intermarket.png';
        }
        $out_data = '<div class="about_worker">
                    <table>
                        <tr>
                            <th> Год - '.$year.'</th>
                            
                        </tr>
                        <tr>
                            <th> Месяц - '.$month.'</th>
                        </tr>
                        <tr>
                            <th> ФИО - '.$FIO.'</th>
                        </tr>
                        <tr>
                            <th> Организация - '.$company.' </th>
                            <th><img style="width:120px;" src="'.$linkPic.'" alt=""></th>
                        </tr>
                        <tr>
                            <th> Должность - '.$post.'</th>
                        </tr>
                    </table>
                    </div>
                    <table class="weekdays">
                        <tr>
                            <th class="calendarTH">ПН</th>
                            <th class="calendarTH">ВТ</th>
                            <th class="calendarTH">СР</th>
                            <th class="calendarTH">ЧТ</th>
                            <th class="calendarTH">ПТ</th>
                            <th class="calendarTH">СБ</th>
                            <th class="calendarTH">ВС</th>
                        </tr>
                    

                        <tr class="days">
                    '.$this->table_calendar($month, $year, $id);
        return $out_data;

    }
//  Вывод таблицы сотрудника
    function table_calendar($month, $year, $id){
        //Создание полученной даты
        $date = new DateTime($year."-".$month."-1");
        //Прерыдущий месяц
        $year_prev = $year-1;
        $month_prev = $month-1;
        $date_prev = new DateTime($year_prev."-".$month_prev."-1");
        //Первый день в месяце в виде дня недели
        $first_week_day_month = $date->format('N');
        //Количество дней в месяце
        $days_in_month = $date->format('t');
        $days_in_prev_month = $date_prev->format('t');
        //Максимальное количество цикла для вывода календаря
        $max_cycle = $first_week_day_month+$days_in_month-1;
        //Переменная дня дня вывода в календарь
        $calendar_day = 01;
        //Переменная дня адекватной выборки из SQL по дате
        $month_1 =$month+1;
        $year_1 = $year;
        if($month_1 == 13) {
            $year_1 = $year+1;
            $month_1 = 01;
        }
        //Запрос на выборку SQL
        $sqlQuery = "SELECT * FROM timeing WHERE user_id = $id AND datetime >= '$year-$month-01' AND datetime <='$year_1-$month_1-1' ORDER BY datetime";
        //Результат выборки в виде массива
        $sqlrResult = $this->querySqlTimeing($sqlQuery);



        //Цикл вывода календаря
        for($i=0; $i<$max_cycle; $i++)
        {
            if($i+2 == $first_week_day_month)
            {
                $out .= '<th class="calendarTH">'.$days_in_prev_month.'</th>';
                continue;

            }
            if($i==0 && $first_week_day_month==1)
            {
                $out .= '<tr class="days"><th class="calendarTH"></th><th class="calendarTH"></th><th class="calendarTH"></th><th class="calendarTH"></th><th class="calendarTH"></th><th class="calendarTH"></th><th class="calendarTH">'.$days_in_prev_month.'</th></tr>';
            }

          /*  if($i == $first_week_day_month)
            {
                $out .= '<tr class="days"><th class="calendarTH"></th><th class="calendarTH"></th><th class="calendarTH"></th><th class="calendarTH"></th><th class="calendarTH"></th><th class="calendarTH"></th><th class="calendarTH">28</th></tr>';
                continue;

            }*/

            //Если переменная меньше начального дня в месяце
            if($i+1 < $first_week_day_month)
            {
                $out .= '<th class="calendarTH"></th>';
                continue;
            }




            //Вывод дня в календарь
            $out .='<th class="calendarTH"><div class="calendarDay">'.$calendar_day.'</div>';

            //Проход по всей выборке
            foreach ($sqlrResult as $key => $value)
            {
                //Разделение на дату и время
                $pieces[$key] =  explode(" ", $sqlrResult[$key]['datetime']);
                //Вход выход
                $event[$key] = $sqlrResult[$key]['event'];
                //Разделение на год, месяц, день
                $pieces_date[$key] =  explode("-", $pieces[$key][0]);
                //Разделение на час, минуту
                $pieces_time[$key] =  explode(":", $pieces[$key][1]);

                $year_worker[$key] =  $pieces_date[$key][0];
                $month_worker[$key] =  $pieces_date[$key][1];
                $day_worker[$key] =  $pieces_date[$key][2];

                $hour_worker[$key] = $pieces_time[$key][0];
                $minute_worker[$key] = $pieces_time[$key][1];

                
                //Если день вывода в календарь совпарает с днем в базе
                if($calendar_day == $day_worker[$key])
                {   //Если событие входа
                    if($event[$key]  == 1)
                    {
                        $out .="Вход: $hour_worker[$key]:$minute_worker[$key]<br>";
                        $date_in = new DateTime("$year_worker[$key]-$month_worker[$key]-$day_worker[$key] $hour_worker[$key]:$minute_worker[$key]:00");

                    }
                    elseif($event[$key]  == 2 && $event[$key-1]  == 1)
                    {
                        $out .="Выход: $hour_worker[$key]:$minute_worker[$key]<br>";
                        $date_out = new DateTime("$year_worker[$key]-$month_worker[$key]-$day_worker[$key] $hour_worker[$key]:$minute_worker[$key]:00");
                        $interval = $date_in->diff($date_out);
                        $day = $interval->format('%d');
                        $hour = $interval->format('%h');
                        $minute = $interval->format('%i');
                        $hour_day = $day*24;
                        $hour += $hour_day;

                        $global_hour_worker += $hour;
                        $global_minute_worker += $minute;
                        if($global_minute_worker>=60) {
                            $global_hour_worker +=1;
                            $global_minute_worker -=60;
                        }

                        $out .="($hour:$minute)";

                    }


                }
            }
            $out.="</th>";

            $b=$i+1;
            //Следующая строка при достижении воскресенья
            if($b%7==0)
            {
                $out.='</tr><tr  class="days">';
            }

            $calendar_day++;
        }
        //Возвращение таблицы в метод
        return $out.'</tr></table><div class="under_table">Общее количество часов ('.$global_hour_worker.':'.$global_minute_worker.')</div></div>';

    }

    function table_time_worker($month, $year, $id){
        $date = new DateTime($year."-".$month."-1");
        $first_day = $date->format('N');
        $day_month = $date->format('t');
        $month_1 =$month+1;

        $month_1 =$month+1;
        $month_2 =$month-1;
        $year_1 = $year;
        $year_2 = $year;
        if($month_1 == 13) {
            $year_1 = $year+1;
            $month_1 = 01;
        }
        if($month_2 == 0) {
            $year_2 = $year-1;
            $month_2 = 12;
        }

        $sqlQuery = "SELECT * FROM timeing WHERE user_id = $id AND datetime >= '$year_2-$month_2-27' AND datetime <='$year_1-$month_1-1' ORDER BY datetime";
        $sqlrResult = $this->querySqlTimeing($sqlQuery);
        return $sqlrResult;
    }

    function table_worker($date, $id_worker){

        $input_data=$this->worker_out_data($date,$id_worker);

        $out= $input_data;


        return $out;

    }


}
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
        //Первый день в месяце в виде дня недели
        $first_week_day_month = $date->format('N');
        //Количество дней в месяце
        $days_in_month = $date->format('t');
        //Максимальное количество цикла для вывода календаря
        $max_cycle = $first_week_day_month+$days_in_month;
        //Переменная дня дня вывода в календарь
        $calendar_day = 01;
        //Переменная дня адекватной выборки из SQL по дате
        $month_1 =$month+1;
        //Запрос на выборку SQL
        $sqlQuery = "SELECT * FROM timeing WHERE user_id = $id AND datetime > '$year-$month-01' AND datetime <='$year-$month_1-1' ORDER BY datetime";
        //Результат выборки в виде массива
        $sqlrResult = $this->querySqlTimeing($sqlQuery);

        //Цикл вывода календаря
        for($i=1; $i<$max_cycle; $i++)
        {
            //Если переменная меньше начального дня в месяце
            if($i < $first_week_day_month)
            {
                $out .= '<th class="calendarTH"></th>';
                continue;
            }
            //Вывод дня в календарь
            $out .='<th class="calendarTH">'.$calendar_day;




            
            //Следующая строка при достижении ВС
            if($i%7==0)
            {
                $out.='</tr><tr  class="days">';
            }

            $calendar_day++;
        }
        //Возвращение таблицы в метод
        return $out.'</tr></table></div>';



        /*
        $date = new DateTime($year."-".$month."-1");
        $first_day = $date->format('N');
        $day_month = $date->format('t');
        $max_day = $first_day + $day_month;
        $day_calendar = 01;

        $sqlQuery = "SELECT * FROM timeing WHERE user_id = $id AND datetime >= '$year-$month-01' AND datetime <='$year-$month-$day_month' ORDER BY datetime";
        $sqlrResult = $this->querySqlTimeing($sqlQuery);



        for($i = 1; $i<=$max_day-1;$i++){


            if($i < $first_day)
            {
                $out .= '<th class="calendarTH"></th>';
                continue;
            }

            $week_day = $day_calendar + $first_day-2;
            if($week_day%7==0) $out.='</tr><tr  class="days">';

            $out .='<th class="calendarTH">'.$day_calendar;

            foreach ($sqlrResult as $key => $value)
            {

                $pieces[$key] =  explode(" ", $sqlrResult[$key]['datetime']);
                $event = $sqlrResult[$key]['event'];

                $pieces_date[$key] =  explode("-", $pieces[$key][0]);

                $pieces_time[$key] =  explode(":", $pieces[$key][1]);

                $year_worker[$key] =  $pieces_date[$key][0];
                $month_worker[$key] =  $pieces_date[$key][1];

                $day_worker[$key] =  $pieces_date[$key][2];

                $hour_worker[$key] = $pieces_time[$key][0];
                $minute_worker[$key] = $pieces_time[$key][1];

                if($day_calendar == $day_worker[$key])
                {
                    if($event == 1)
                    {
                        $out .="<br>$hour_worker[$key]: $minute_worker[$key]</th> ";
                    }
                    elseif ($event == 2)
                    {

                    }
                   // $out .='<th class="calendarTH">'.$day_calendar .'<br>'.$hour_worker[$key].':'.$minute_worker[$key].'<br>'. $hour_worker[$day_calendar].':'.$minute_worker[$day_calendar].'<br></li> ';

                }
            }



          //  $out .="<li>$day_calendar <br>$hour_worker[0]:$minute_worker[0]
          //          $hour_worker[$day_calendar]:$minute_worker[$day_calendar]<br></li> ";



            $day_calendar ++;
        }

        return $out.'</tr></table></div>';
*/
    }

    function table_time_worker($month, $year, $id){
        $date = new DateTime($year."-".$month."-1");
        $first_day = $date->format('N');
        $day_month = $date->format('t');
        $month_1 =$month+1;
        $sqlQuery = "SELECT * FROM timeing WHERE user_id = $id AND datetime > '$year-$month-01' AND datetime <='$year-$month_1-1' ORDER BY datetime";
        $sqlrResult = $this->querySqlTimeing($sqlQuery);
        return $sqlrResult;
    }

    function table_worker($date, $id_worker){

        $input_data=$this->worker_out_data($date,$id_worker);

        $out= $input_data;


        return $out;

    }


}
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

        $out_data = '<div class="about_worker">
                        <ul>
                            <li> Год - '.$year.'</li>
                        </ul>
                        <ul>
                            <li> Месяц - '.$month.'</li>
                        </ul>
                        <ul>
                            <li> ФИО - '.$FIO.'</li>
                        </ul>
                        <ul>
                            <li> Организация - '.$company.'</li>
                        </ul>
                        <ul>
                            <li> Должность - '.$post.'</li>
                        </ul>
                    </div>
                    <ul class="weekdays">
                        <li>ПН</li>
                        <li>ВТ</li>
                        <li>СР</li>
                        <li>ЧТ</li>
                        <li>ПТ</li>
                        <li>СБ</li>
                        <li>ВС</li>
                    </ul>
                    <ul class="days">
                    '.$this->table_calendar($month, $year, $id);
        return $out_data;

    }

    function table_calendar($month, $year, $id){

        $date = new DateTime($year."-".$month."-1");
        $first_day = $date->format('N');
        $day_month = $date->format('t');
        $max_day = $first_day + $day_month;
        $day_calendar = 01;

        $sqlQuery = "SELECT * FROM timeing WHERE user_id = $id AND datetime >= '$year-$month-01' AND datetime <='$year-$month-$day_month'";
        $sqlrResult = $this->querySqlTimeing($sqlQuery);

        foreach ($sqlrResult as $key => $value)
        {

            $pieces[$key] =  explode(" ", $sqlrResult[$key]['datetime']);

            $pieces_date[$key] =  explode("-", $pieces[$key][0]);

            $pieces_time[$key] =  explode(":", $pieces[$key][1]);

            $year_worker[$key] =  $pieces_date[$key][0];
            $month_worker[$key] =  $pieces_date[$key][1];
            $day_worker[$key] =  $pieces_date[$key][2];

            $hour_worker[$key] = $pieces_time[$key][0];
            $minute_worker[$key] = $pieces_time[$key][1];

        }






        for($i = 1; $i<=$max_day-1;$i++){



            if($i < $first_day)
            {
                $out .= "<li></li>";
                continue;
            }
            if($day_calendar%7==0) $out.='</ul><ul class="days">';

            $out .="<li>$day_calendar <br>$hour_worker[$day_calendar]:$minute_worker[$day_calendar]
                    $hour_worker[$day_calendar]:$minute_worker[$day_calendar]<br>(10:00)</li> ";



            $day_calendar ++;
        }

        return $out.'</ul></div>';
    }



    function table_worker($date, $id_worker){

        $input_data=$this->worker_out_data($date,$id_worker);

        $out= $input_data;


        return $out;

    }


}
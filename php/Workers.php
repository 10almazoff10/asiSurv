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
        $arr_workers = $this->querySql($this->table_workers, NULL);
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

    function querySql($table, $mode){
        $mysqli = new mysqli($this->url, $this->user, $this->pass, $this->db);

        foreach ($mysqli->query("SELECT * FROM $table $mode") as $key => $value) {
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

    function worker_out_data($date,$id)
    {
        $pieces_date = explode("/", $date);
        $month = $pieces_date[0];
        $year = $pieces_date[1];
        $sqlQuery = "WHERE worker_id=$id";
        $sqlrResult = $this->querySql($this->table_workers, $sqlQuery);

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
                    '.$this->table_calendar($month, $year);
        return $out_data;

    }

    function table_calendar($month, $year){

        $date = new DateTime($year."-".$month."-1");
        $first_day = $date->format('N');
        $day_month = $date->format('t');
        $max_day = $first_day + $day_month;
        $day = 1;
        for($i = 1; $i<=42;$i++){

            if($i < $first_day)
            {
                $out .= "<li></li>";
                continue;
            }
            if($i == $max_day) break;



            $out .="<li>$day</li>";
            $day ++;
        }

        return $out;
    }



    function table_worker($date, $id_worker){

        $input_data=$this->worker_out_data($date,$id_worker);

        $out= $input_data;
        /*'     <ul class="weekdays">
                        <li>ПН</li>
                        <li>ВТ</li>
                        <li>СР</li>
                        <li>ЧТ</li>
                        <li>ПТ</li>
                        <li>СБ</li>
                        <li>ВС</li>
                    </ul>


                        <li>28</li>
                        <li>1</li>
                        <li>2</li>
                        <li>3</li>
                        <li>4</li>
                        <li>5</li>
                        <li>6</li>
                        <li>7</li>
                        <li>8</li>
                        <li>9</li>
                        <li>10</li>
                        <li>11</li>
                        <li>12</li>
                        <li>13</li>
                        <li>14</li>
                        <li>15</li>
                        <li>16</li>
                        <li>17</li>
                        <li>18</li>
                        <li>19</li>
                        <li>20</li>
                        <li>21</li>
                        <li>22</li>
                        <li>23</li>
                        <li>24</li>
                        <li>25</li>
                        <li>26</li>
                        <li>27</li>
                        <li>28</li>
                        <li>29</li>
                        <li>30</li>
                        <li>31</li>
                    </ul>
                    </div>';*/

        return $out;

    }


}
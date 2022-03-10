<?php

class Workers
{
    public $url = "localhost";
    public $user = "tex";
    public $pass = "Te99874566";
    public $db = "timesheet";
    public $table_workers = "workers";

    function outputWorkers(){

    }

    function list_workers(){
        $arr_workers = $this->querySql($this->table_workers);
        foreach ($arr_workers as $key => $value)
        {
            $id = $value['id'];
            $first_name= $value['first_name'];
            $mid_name = $value['mid_name'];
            $last_name = $value['last_name'];
            $date_end = $value['date_end'];
            if ($date_end == '')
            {
                echo '<li><a href="?id='.$id.'" class="list-group-item list-group-item-action">'."$first_name $mid_name $last_name" . '</a></li>';
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

    function querySql($table){
        $mysqli = new mysqli($this->url, $this->user, $this->pass, $this->db);
        foreach ($mysqli->query("SELECT * FROM $table")as $key => $value)
        {
            $first_name = $value['last_name'];
            $mid_name = $value['first_name'];
            $last_name = $value['mid_name'];
            $id = $value['worker_id'];
            $company = $value['company'];
            $division = $value['division'];
            $post = $value['post'];
            $date_end = $value['date_end'];

            $arr["$key"] =array(first_name=>$first_name, mid_name=>$mid_name, last_name=>$last_name,
                                id=>$id, company=>$company, division=>$division, post=>$post, date_end=>$date_end);


        }
        return $arr;
    }
}
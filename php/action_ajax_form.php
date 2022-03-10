<?php

if (isset($_POST["date"])) {

    // Формируем массив для JSON ответа
    $result = array(
        'date' => '
                   <div class="about_worker">
                        <ul>
                            <li> Месяц -</li>
                            <li id = "month"></li>
                        </ul>
                        <ul>
                            <li> ФИО -</li>
                            <li id = "worker_fio"></li>
                        </ul>
                        <ul>
                            <li> Организация -</li>
                            <li id = "devision"></li>
                        </ul>
                        <ul>
                            <li> Должность -</li>
                            <li id = "post"></li>
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
                    </div>'

    );

    // Переводим массив в JSON
    echo json_encode($result);
}

?>
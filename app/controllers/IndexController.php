<?php

use Phalcon\Mvc\Controller;

class IndexController extends Controller
{
    public function indexAction()
    {
        $this->view->prefectures = Hotels::find(
            [
                'group' => 'prefectureId'
            ]
        );

        function makeOptions($min, $max, $unit) {
            for($i = $min; $i <= $max; $i++) {
                echo '<option value="' . $i . '">' . $i . $unit . '</option>';
            }   
        }
    }
}
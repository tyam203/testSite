<?php

use Phalcon\Mvc\Controller;

class IndexController extends Controller
{
    public function indexAction()
    {
        if(isset($_SESSION['id'])) {
            $name = $_SESSION['name'];
            $link = $this->tag->linkTo(["logout", "ログアウト", 'class' => 'btn btn-primary']); 
        } else{
            $name = 'ゲスト';
            $link = $this->tag->linkTo(["login", "ログイン", 'class' => 'btn btn-primary']); 
        }
        $this->view->link = $link;
        $this->view->name = $name;
        
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
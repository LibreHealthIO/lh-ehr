<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 6/4/18
 * Time: 2:01 PM
 */


class Wizard
{
    private  $hello = "ASdsa";

    public function drawSetupStep($step = 1, $draw = true){

        $steps = array(
            '1'=>array('url'=>'start.php'),
            '2'=>array('url'=>'server_requirements.php'),
            '3'=>array('url'=>'database_settings.php'),
            '4'=>array('url'=>'administrator_account.php'),
            '5'=>array('url'=>'ready_to_install.php'),
            '6'=>array('url'=>'complete_installation.php'),
        );

        $output  = "<div class='step-div'>\n";
        foreach($steps as $key => $val){
            if($step > $key){
                $css_class = ' class="step completed-step"';
                $output .= "\t\t<div".$css_class."></div>\n";
            }
            else if($step == $key){
                $css_class = ' class="step active-step"';
                $step_num  = "<div class=\"pull-right step-count\"><span class=\"librehealth-color\">$key</span>&nbsp;/&nbsp;6</div>\n";
                $output .= "\t\t<div".$css_class."></div>\n";
            }
            else{
                $css_class = " class='step '";
                $output .= "\t\t<div".$css_class."></div>\n";
            }

                }
        $output .= $step_num;
        $output .= "\t</div><br>\n";
        if($draw) echo $output;
        else return $output;
    }


    public function drawSetupBody($step , $draw = true){

        switch ($step){

            case 1:
                echo "wala";
                break;

            case 2:
                echo "2";
                break;


        }//end of switch statement
    }



}
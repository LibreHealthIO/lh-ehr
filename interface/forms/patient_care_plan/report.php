<?php

include_once("../../globals.php");
include_once($GLOBALS["srcdir"] . "/api.inc");

function patient_care_plan_report($pid, $encounter, $cols, $id) {
    $count = 0;
    $sql = "SELECT * FROM `form_patient_care_plan` WHERE id=? AND pid = ? AND encounter = ?";
    $res = sqlStatement($sql, array($id,$_SESSION["pid"], $_SESSION["encounter"]));
    for ($iter = 0; $row = sqlFetchArray($res); $iter++)
        $data[$iter] = $row;
    if ($data) {
        ?>
        <table style='border-collapse:collapse;border-spacing:0;width: 100%;'>
            <tr>
                <td align='center' style='border:1px solid #ccc;padding:4px;'><span class=bold><?php echo xlt('issue#'); ?></span></td>
                <td align='center' style='border:1px solid #ccc;padding:4px;'><span class=bold><?php echo xlt('key issue'); ?></span></td>
                <td align='center' style='border:1px solid #ccc;padding:4px;'><span class=bold><?php echo xlt('Interventions'); ?></span></td>
                <td align='center' style='border:1px solid #ccc;padding:4px;'><span class=bold><?php echo xlt('Outcome'); ?></span></td> 
                <td align='center' style='border:1px solid #ccc;padding:4px;'><span class=bold><?php echo xlt('Goal'); ?></span></td> 
                <td align='center' style='border:1px solid #ccc;padding:4px;'><span class=bold><?php echo xlt('Progress'); ?></span></td> 
            </tr>
        <?php
        foreach ($data as $key => $value) {
            ?>
            <tr <?php if($value['status'] == 0) {echo text("style='background-color:#c5c5bc'"); }?> > 
                <td style='border:1px solid #ccc;padding:4px;'><span class=text><?php echo text($value['issue'] + 1); ?></span></td>
                <td style='border:1px solid #ccc;padding:4px;'><span class=text><?php echo text($value['Key_issue']); ?></span></td>
                <td style='border:1px solid #ccc;padding:4px;'>
                    <ul>
                        <?php
                            $list =  json_decode($value['Interventions']);
                            for($i = 0; $i < count($list); $i++) {
                                if( $list[$i] != null || $list[$i] != '') {
                                    echo "<li><span class=text>" ;
                                    echo $list[$i];
                                    echo  "</span></li>";
                                }
                            }
                        ?>
                    </ul>
                </td>
                <td style='border:1px solid #ccc;padding:4px;'><span class=text>
                    <ul>
                        <?php
                            $list =  json_decode($value['Outcome']);
                            for($i = 0; $i < count($list); $i++) {
                                if( $list[$i] != null || $list[$i] != '') {
                                    echo "<li><span class=text>" ;
                                    echo $list[$i];
                                    echo  "</span></li>";
                                }
                            }
                        ?>
                    </ul>
                </td>
                <td style='border:1px solid #ccc;padding:4px;'>
                    <ul>
                        <?php
                            $list =  json_decode($value['Goal']);
                            for($i = 0; $i < count($list); $i++) {
                                if( $list[$i] != null || $list[$i] != '') {
                                    echo "<li><span class=text>" ;
                                    echo $list[$i];
                                    echo  "</span></li>";
                                }
                            }
                        ?>
                    </ul>
                </td>
                <td style='border:1px solid #ccc;padding:4px;'>
                    <ul>
                        <?php
                            $list =  json_decode($value['Progress']);
                            for($i = 0; $i < count($list); $i++) {
                                if( $list[$i] != null || $list[$i] != '') {
                                    echo "<li><span class=text>" ;
                                    echo $list[$i];
                                    echo  "</span></li>";
                                }
                            }
                        ?>
                    </ul>
                </td> 
            </tr>
            <?php
        }
        ?>
        </table>
        <?php
    }
}
?> 

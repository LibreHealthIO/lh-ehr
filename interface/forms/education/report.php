<?php

include_once("../../globals.php");
include_once($GLOBALS["srcdir"] . "/api.inc");

function education_report($pid, $encounter, $cols, $id) {
    $count = 0;
    $sql = "SELECT * FROM `form_education` WHERE id=? AND pid = ? AND encounter = ?";
    $res = sqlStatement($sql, array($id,$_SESSION["pid"], $_SESSION["encounter"]));
    for ($iter = 0; $row = sqlFetchArray($res); $iter++)
        $data[$iter] = $row;
    if ($data) {
        ?>
        <table style='border-collapse:collapse;border-spacing:0;width: 100%;'>
            <tr>
                <td align='center' style='border:1px solid #ccc;padding:4px;'><span class=bold><?php echo xlt('Topic'); ?></span></td>
                <td align='center' style='border:1px solid #ccc;padding:4px;'><span class=bold><?php echo xlt('Learners'); ?></span></td>
                <td align='center' style='border:1px solid #ccc;padding:4px;'><span class=bold><?php echo xlt('Learner\'s Readiness for Education'); ?></span></td> 
                <td align='center' style='border:1px solid #ccc;padding:4px;'><span class=bold><?php echo xlt('Method of Education'); ?></span></td> 
                <td align='center' style='border:1px solid #ccc;padding:4px;'><span class=bold><?php echo xlt('Response to Eduction'); ?></span></td> 
                <td align='center' style='border:1px solid #ccc;padding:4px;'><span class=bold><?php echo xlt('Further interventions Needed'); ?></span></td> 
                <td align='center' style='border:1px solid #ccc;padding:4px;'><span class=bold><?php echo xlt('Remarks'); ?></span></td> 
            </tr>
        <?php
        foreach ($data as $key => $value) {
            ?>
            <tr > 
                <td style='border:1px solid #ccc;padding:4px;'><span class=text><?php echo text($value['topic']); ?></span></td>
                <td style='border:1px solid #ccc;padding:4px;'>
                    <ul>
                        <?php
                            $list =  json_decode($value['interventions']);
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
                            $list =  json_decode($value['learners']);
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
                            $list =  json_decode($value['readiness']);
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
                            $list =  json_decode($value['response']);
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
                            $list =  json_decode($value['method']);
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
                <td style='border:1px solid #ccc;padding:4px;'><span class=text><?php echo text($value['remark']); ?></span></td>
            </tr>
            <?php
        }
        ?>
        </table>
        <?php
    }
}
?> 

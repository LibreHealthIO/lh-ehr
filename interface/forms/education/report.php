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
        <style>
            .completed {
                text-transform:uppercase;
                text-decoration: underline;
                padding: 10px;
            }
        </style>
        <table style='border-collapse:collapse;border-spacing:0;width: 100%;'>
            <tr>
                <td align='center' style='border:1px solid #ccc;padding:4px;'><span class="bold"><?php echo xlt('Topic'); ?></span></td>
                <td align='center' style='border:1px solid #ccc;padding:4px;'><span class="bold"><?php echo xlt('Learners'); ?></span></td>
                <td align='center' style='border:1px solid #ccc;padding:4px;'><span class="bold"><?php echo xlt('Learner\'s Readiness for Education'); ?></span></td> 
                <td align='center' style='border:1px solid #ccc;padding:4px;'><span class="bold"><?php echo xlt('Method of Education'); ?></span></td> 
                <td align='center' style='border:1px solid #ccc;padding:4px;'><span class="bold"><?php echo xlt('Response to Eduction'); ?></span></td> 
                <td align='center' style='border:1px solid #ccc;padding:4px;'><span class="bold"><?php echo xlt('Further interventions Needed'); ?></span></td> 
                <td align='center' style='border:1px solid #ccc;padding:4px;'><span class="bold"><?php echo xlt('Remarks'); ?></span></td> 
            </tr>
        <?php
        foreach ($data as $key => $value) {
            ?>
            <tr > 
                <td style='border:1px solid #ccc;padding:4px;'>
                    <?php
                        if($value['status'] == '0') {
                    ?>
                        <b class="completed">Topic Completed</b> <br>
                    <?php
                        }
                    ?>
                <span class=text><?php echo text($value['topic']); ?></span>
                </td>
                <?php
                    $learner =  json_decode($value['learners']);
                    print($learner);
                    $readiness = json_decode($value['readiness']);
                    $response= json_decode($value['response']);
                    $method = json_decode($value['method']);
                    $interventions = json_decode($value['interventions']);
                    $LearnerStatus = json_decode($value['learners_status']);
                    for($i = 0; $i < count($learner); $i++) { 
                        if( $learner !== null && strlen(trim($learner[$i])) > 0) {
                ?>
                            <td style='border:1px solid #ccc;padding:4px;'><span class="text">
                                <?php
                                    if($LearnerStatus[$i] == '0') {
                                ?>
                                    <b class="completed">Completed ED Topic</b>
                                <?php
                                    }
                                ?>
                                <li><span class="text">
                                <?php echo text($learner[$i]); ?>
                                </span></li>
                            </td>
                <?php
                        } else {
                            echo '<td style="border:1px solid #ccc;padding:4px;"><span class="text"></td>';
                        }
                        if( $readiness !== null && strlen(trim($readiness[$i])) > 0) {
                ?>
                            <td style='border:1px solid #ccc;padding:4px;'><span class="text">
                                <li><span class="text">
                                <?php echo text($readiness[$i]); ?>
                                </span></li>
                            </td>
                <?php
                        } else {
                            echo '<td style="border:1px solid #ccc;padding:4px;"><span class="text"></td>';
                        }
                        if( $response !== null && strlen(trim($response[$i])) > 0) {
                ?>
                            <td style='border:1px solid #ccc;padding:4px;'><span class="text">
                                <li><span class="text">
                                <?php echo text($response[$i]); ?>
                                </span></li>
                            </td>
                <?php
                        } else {
                            echo '<td style="border:1px solid #ccc;padding:4px;"><span class="text"></td>';
                        }
                        if( $method !== null && strlen(trim($method[$i])) > 0) {
                ?>
                            <td style='border:1px solid #ccc;padding:4px;'><span class="text">
                                <li><span class="text">
                                <?php echo text($method[$i]); ?>
                                </span></li>
                            </td>
                <?php
                        } else {
                            echo '<td style="border:1px solid #ccc;padding:4px;"><span class="text"></td>';
                        }
                        if( $interventions !== null && strlen(trim($interventions[$i])) > 0) {
                ?>
                            <td style='border:1px solid #ccc;padding:4px;'><span class="text">
                                <li><span class="text">
                                <?php echo text($interventions[$i]); ?>
                                </span></li>
                            </td>
                <?php
                        } else {
                            echo '<td style="border:1px solid #ccc;padding:4px;"><span class="text"></td>';
                        }

                    }
                ?>
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

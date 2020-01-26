<?php

require_once("../globals.php");
require_once("$srcdir/report.inc");
require_once("$srcdir/patient.inc");

$user = $_SESSION['authId'];
if(isset($_POST['pos-bt']))
{
    $value = $_POST['pos-bt'];
    sqlStatement("UPDATE user_settings SET setting_value='$value' WHERE setting_label='pos-bt' AND setting_user='$user'");
}
if(isset($_POST['pos-txt']))
{
    $value = $_POST['pos-txt'];
    sqlStatement("UPDATE user_settings SET setting_value='$value' WHERE setting_label='pos-txt' AND setting_user='$user'");
}
if(isset($_POST['pos-bor']))
{
    $value = $_POST['pos-bor'];
    sqlStatement("UPDATE user_settings SET setting_value='$value' WHERE setting_label='pos-bor' AND setting_user='$user'");
}
if(isset($_POST['pos-bor-col']))
{
    $value = $_POST['pos-bor-col'];
    sqlStatement("UPDATE user_settings SET setting_value='$value' WHERE setting_label='pos-bor-col' AND setting_user='$user'");
}
if(isset($_POST['neg-bt']))
{
    $value = $_POST['neg-bt'];
    sqlStatement("UPDATE user_settings SET setting_value='$value' WHERE setting_label='neg-bt' AND setting_user='$user'");
}
if(isset($_POST['neg-txt']))
{
    $value = $_POST['neg-txt'];
    sqlStatement("UPDATE user_settings SET setting_value='$value' WHERE setting_label='neg-txt' AND setting_user='$user'");
}
if(isset($_POST['neg-bor']))
{
    $value = $_POST['neg-bor'];
    sqlStatement("UPDATE user_settings SET setting_value='$value' WHERE setting_label='neg-bor' AND setting_user='$user'");
}
if(isset($_POST['neg-bor-col']))
{
    $value = $_POST['neg-bor-col'];
    sqlStatement("UPDATE user_settings SET setting_value='$value' WHERE setting_label='neg-bor-col' AND setting_user='$user'");
}
if(isset($_POST['sub-bt']))
{
    $value = $_POST['sub-bt'];
    sqlStatement("UPDATE user_settings SET setting_value='$value' WHERE setting_label='sub-bt' AND setting_user='$user'");
}
if(isset($_POST['sub-txt']))
{
    $value = $_POST['sub-txt'];
    sqlStatement("UPDATE user_settings SET setting_value='$value' WHERE setting_label='sub-txt' AND setting_user='$user'");
}
if(isset($_POST['sub-bor']))
{
    $value = $_POST['sub-bor'];
    sqlStatement("UPDATE user_settings SET setting_value='$value' WHERE setting_label='sub-bor' AND setting_user='$user'");
}
if(isset($_POST['sub-bor-col']))
{
    $value = $_POST['sub-bor-col'];
    sqlStatement("UPDATE user_settings SET setting_value='$value' WHERE setting_label='sub-bor-col' AND setting_user='$user'");
}
if(isset($_POST['misc-bt']))
{
    $value = $_POST['misc-bt'];
    sqlStatement("UPDATE user_settings SET setting_value='$value' WHERE setting_label='misc-bt' AND setting_user='$user'");
}
if(isset($_POST['misc-txt']))
{
    $value = $_POST['misc-txt'];
    sqlStatement("UPDATE user_settings SET setting_value='$value' WHERE setting_label='misc-txt' AND setting_user='$user'");
}
if(isset($_POST['misc-bor']))
{
    $value = $_POST['misc-bor'];
    sqlStatement("UPDATE user_settings SET setting_value='$value' WHERE setting_label='misc-bor' AND setting_user='$user'");
}
if(isset($_POST['misc-bor-col']))
{
    $value = $_POST['misc-bor-col'];
    sqlStatement("UPDATE user_settings SET setting_value='$value' WHERE setting_label='misc-bor-col' AND setting_user='$user'");
}
if(isset($_POST['out-bt']))
{
    $value = $_POST['out-bt'];
    sqlStatement("UPDATE user_settings SET setting_value='$value' WHERE setting_label='out-bt' AND setting_user='$user'");
}
if(isset($_POST['out-txt']))
{
    $value = $_POST['out-txt'];
    sqlStatement("UPDATE user_settings SET setting_value='$value' WHERE setting_label='out-txt' AND setting_user='$user'");
}
if(isset($_POST['out-bor']))
{
    $value = $_POST['out-bor'];
    sqlStatement("UPDATE user_settings SET setting_value='$value' WHERE setting_label='out-bor' AND setting_user='$user'");
}
if(isset($_POST['out-bor-col']))
{
    $value = $_POST['out-bor-col'];
    sqlStatement("UPDATE user_settings SET setting_value='$value' WHERE setting_label='out-bor-col' AND setting_user='$user'");
}

$css_values = array();
$res = sqlStatement("SELECT * FROM user_settings WHERE setting_user = ?", $_SESSION['authId']);
while($row = sqlFetchArray($res)){
    switch($row['setting_label']){
        case "pos-bt" :
            $css_values['pos-bt'] =  $row['setting_value'];
            break;

        case "pos-txt" :
            $css_values['pos-txt'] = $row['setting_value'];
            break;

        case "pos-bor" :
            $css_values['pos-bor'] = $row['setting_value'];
            break;

        case "pos-bor-col" :
            $css_values['pos-bor-col'] = $row['setting_value'];
            break;

        case "neg-bt" :
            $css_values['neg-bt'] = $row['setting_value'];
            break;

        case "neg-txt" :
            $css_values['neg-txt'] = $row['setting_value'];
            break;

        case "neg-bor" :
            $css_values['neg-bor'] = $row['setting_value'];
            break;

        case "neg-bor-col" :
            $css_values['neg-bor-col'] = $row['setting_value'];
            break;

        case "sub-bt" :
            $css_values['sub-bt'] = $row['setting_value'];
            break;

        case "sub-txt" :
            $css_values['sub-txt'] = $row['setting_value'];
            break;

        case "sub-bor" :
            $css_values['sub-bor'] = $row['setting_value'];
            break;

        case "sub-bor-col" :
            $css_values['sub-bor-col'] = $row['setting_value'];
            break;

        case "misc-bt" :
            $css_values['misc-bt'] = $row['setting_value'];
            break;

        case "misc-txt" :
            $css_values['misc-txt'] = $row['setting_value'];
            break;

        case "misc-bor" :
            $css_values['misc-bor'] = $row['setting_value'];
            break;

        case "misc-bor-col" :
            $css_values['misc-bor-col'] = $row['setting_value'];
            break;

        case "out-bt" :
            $css_values['out-bt'] = $row['setting_value'];
            break;

        case "out-txt" :
            $css_values['out-txt'] = $row['setting_value'];
            break;

        case "out-bor" :
            $css_values['out-bor'] = $row['setting_value'];
            break;

        case "out-bor-col" :
            $css_values['out-bor-col'] = $row['setting_value'];
            break;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Element Editor</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <style>
            :root{
                --pos-bg-color: <?php echo $css_values['pos-bt'];?>;
                --pos-txt-color: <?php echo $css_values['pos-txt'];?>;
                --pos-bt-bor: <?php echo $css_values['pos-bor'];?>;
                --pos-bor-color: <?php echo $css_values['pos-bor-col'];?>;
                --neg-bg-color: <?php echo $css_values['neg-bt'];?>;
                --neg-txt-color: <?php echo $css_values['neg-txt'];?>;
                --neg-bt-bor: <?php echo $css_values['neg-bor'];?>;
                --neg-bor-color: <?php echo $css_values['neg-bor-col'];?>;
                --sub-bg-color: <?php echo $css_values['sub-bt'];?>;
                --sub-txt-color: <?php echo $css_values['sub-txt'];?>;
                --sub-bt-bor: <?php echo $css_values['sub-bor'];?>;
                --sub-bor-color: <?php echo $css_values['sub-bor-col'];?>;
                --misc-bg-color: <?php echo $css_values['misc-bt'];?>;
                --misc-txt-color: <?php echo $css_values['misc-txt'];?>;
                --misc-bt-bor: <?php echo $css_values['misc-bor'];?>;
                --misc-bor-color: <?php echo $css_values['misc-bor-col'];?>;
                --out-bg-color: <?php echo $css_values['out-bt'];?>;
                --out-txt-color: <?php echo $css_values['out-txt'];?>;
                --out-bt-bor: <?php echo $css_values['out-bor'];?>;
                --out-bor-color: <?php echo $css_values['out-bor-col'];?>;
            }
            .tab {
                overflow: hidden;
                border: 1px solid #ccc;
            }
            .tab button {
                float: left;
                border: none;
                outline: none;
                cursor: pointer;
                padding: 14px 16px;
                transition: 0.3s;
                font-size: 17px;
            }

            .tab button:hover {
                background-color: rgb(255, 201, 85);
            }

            .tabcontent {
                display: none;
                padding: 6px 12px;
                border: 1px solid #ccc;
                border-top: none;
            }       
            .positive{
                background-color: rgba(154, 231, 154, 0.788);
            }
            .negative{
                background-color: rgba(238, 156, 151, 0.788);
            }
            .submit{
                background-color: rgba(157, 151, 240, 0.788);
            }
            .misc{
                background-color: rgba(204, 201, 201, 0.788);
            }
            .output{
                background-color: rgba(236, 181, 236, 0.788);
            }
            .cp-positive{
                background-color: var(--pos-bg-color);
                color: var(--pos-txt-color);
                border: var(--pos-bt-bor);
                border-color: var(--pos-bor-color);
            }
            .cp-negative{
                background-color: var(--neg-bg-color);
                color: var(--neg-txt-color);
                border: var(--neg-bt-bor);
                border-color: var(--neg-bor-color);
            }
            .cp-submit{
                background-color: var(--sub-bg-color);
                color: var(--sub-txt-color);
                border: var(--sub-bt-bor);
                border-color: var(--sub-bor-color);
            }
            .cp-misc{
                background-color: var(--misc-bg-color);
                color: var(--misc-txt-color);
                border: var(--misc-bt-bor);
                border-color: var(--misc-bor-color);
            }
            .cp-output{
                background-color: var(--out-bg-color);
                color: var(--out-txt-color);
                border: var(--out-bt-bor);
                border-color: var(--out-bor-color);
            }
            #submitter{
                background-color: rgb(255, 201, 85);
                margin-left: auto;
                margin-right: 0px;
            }
        </style>
        </head>
        <body>
            <form method="post" action="ui-editor.php">
                    <div class="container"><h2>Buttons Style Editor</h2></div>
                    <div class="container">
                        <div class="tab" id='top'>
                            <button class="tablinks positive" onclick="openTab(event, 'Type1')">Type1</button>
                            <button class="tablinks negative" onclick="openTab(event, 'Type2')">Type2</button>
                            <button class="tabidlinks submit" onclick="openTab(event, 'Type3')">Type3</button>
                            <button class="tablinks misc" onclick="openTab(event, 'Type4')">Type4</button>
                            <button class="tablinks output" onclick="openTab(event, 'Type5')">Type5</button>
                        </div>
                        <div id="description" class="container">
                            <h3>This is the section where you can customize the buttons the way you want.</h3>
                            <h3>Just click on the tabs above and there you go!</h3>
                        </div>
                        <div id="Type1" class="tabcontent positive">
                            <table class="table">
                                <tr><td><h3>Description</h3></td></tr>
                                <tr>
                                    <td>
                                        <input type="submit" value="Add" class="btn cp-positive">
                                        <input type="submit" value="Edit" class="btn cp-positive">
                                        <input type="submit" value="Create" class="btn cp-positive">
                                        <input type="submit" value="Upload" class="btn cp-positive">
                                    </td>
                                </tr>
                                <tr><td><label>Choose button color : <input value="<?php echo $css_values['pos-bt']; ?>" type="color" name="pos-bt" id="type1-bg"></label> 
                                <label>Choose text color : <input value="<?php echo $css_values['pos-txt']; ?>" type="color" name="pos-txt" id="type1-text"></label></td></tr>
                                <tr>
                                    <td>
                                        <label>Choose border type:
                                            <select name="pos-bor" id="type1-bor">
                                                <option value="<?php echo $css_values['pos-bor']; ?>">Default</option>
                                                <option value="dotted">Dotted</option>
                                                <option value="dashed">Dashed</option>
                                                <option value="solid">Solid</option>
                                                <option value="double">Double</option>
                                                <option value="groove">Groove</option>
                                                <option value="none">Ridge</option>
                                                <option value="inset">Inset</option>
                                                <option value="outset">Outset</option>
                                            </select>
                                        </label>
                                        <label>Choose border color : <input value="<?php echo $css_values['pos-bor-col']; ?>" type="color" name="pos-bor-col" id="type1-bor-col"></label>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div id="Type2" class="tabcontent negative">
                            <table class="table">
                                <tr><td><h3>Description</h3></td></tr>
                                <tr>
                                    <td>
                                        <input type="submit" value="Delete" class="btn cp-negative">
                                        <input type="submit" value="Cancel" class="btn cp-negative">
                                    </td>
                                </tr>
                                <tr><td><label>Choose button color : <input value="<?php echo $css_values['neg-bt']; ?>" type="color" name="neg-bt" id="type2-bg"></label> 
                                <label>Choose text color : <input value="<?php echo $css_values['neg-txt']; ?>" type="color" name="neg-txt" id="type2-text"></label></td></tr>
                                <tr>
                                    <td>
                                        <label>Choose border type:
                                            <select name="neg-bor" id="type2-bor">
                                                <option value="<?php echo $css_values['neg-bor']; ?>">Default</option>
                                                <option value="dotted">Dotted</option>
                                                <option value="dashed">Dashed</option>
                                                <option value="solid">Solid</option>
                                                <option value="double">Double</option>
                                                <option value="groove">Groove</option>
                                                <option value="none">Ridge</option>
                                                <option value="inset">Inset</option>
                                                <option value="outset">Outset</option>
                                            </select>
                                        </label>
                                        <label>Choose border color : <input value="<?php echo $css_values['neg-bor-col']; ?>" type="color" name="neg-bor-col" id="type2-bor-col"></label>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div id="Type3" class="tabcontent submit">
                            <table class="table">
                                <tr><td><h3>Description</h3></td></tr>
                                <tr>
                                    <td>
                                        <input type="submit" value="Search" class="btn cp-submit">
                                        <input type="submit" value="Save" class="btn cp-submit">
                                    </td>
                                </tr>
                                <tr><td><label>Choose button color : <input value="<?php echo $css_values['sub-bt']; ?>" type="color" name="sub-bt" id="type3-bg"></label> 
                                <label>Choose text color : <input value="<?php echo $css_values['sub-txt']; ?>" type="color" name="sub-txt" id="type3-text"></label></td></tr>
                                <tr>
                                    <td>
                                        <label>Choose border type:
                                            <select name="sub-bor" id="type3-bor">
                                                <option value="<?php echo $css_values['sub-bor']; ?>">Default</option>
                                                <option value="dotted">Dotted</option>
                                                <option value="dashed">Dashed</option>
                                                <option value=" id="submitter"solid">Solid</option>
                                                <option value="double">Double</option>
                                                <option value="groove">Groove</option>
                                                <option value="none">Ridge</option>
                                                <option value="inset">Inset</option>
                                                <option value="outset">Outset</option>
                                            </select>
                                        </label>
                                        <label>Choose border color : <input value="<?php echo $css_values['sub-bor-col']; ?>" type="color" name="sub-bor-col" id="type3-bor-col"></label>
                                    </td>
                                </tr>
                            </table>                   
                        </div>
                        <div id="Type4" class="tabcontent misc">
                            <table class="table">
                                <tr><td><h3>Description</h3></td></tr>
                                <tr>
                                    <td>
                                        <input type="submit" value="Manage Translations" class="btn cp-misc">
                                        <input type="submit" value="Info" class="btn cp-misc">
                                        <input type="submit" value="Next" class="btn cp-misc">
                                        <input type="submit" value="Previous" class="btn cp-misc">
                                    </td>
                                </tr>
                                <tr><td><label>Choose button color : <input value="<?php echo $css_values['misc-bt']; ?>" type="color" name="misc-bt" id="type4-bg"></label> 
                                <label>Choose text color : <input value="<?php echo $css_values['misc-txt']; ?>" type="color" name="misc-txt" id="type4-text"></label></td></tr>
                                <tr>
                                    <td>
                                        <label>Choose border type:
                                            <select name="misc-bor" id="type4-bor">
                                                <option value="<?php echo $css_values['misc-bor']; ?>">Default</option>
                                                <option value="dotted">Dotted</option>
                                                <option value="dashed">Dashed</option>
                                                <option value="solid">Solid</option>
                                                <option value="double">Double</option>
                                                <option value="groove">Groove</option>
                                                <option value="none">Ridge</option>
                                                <option value="inset">Inset</option>
                                                <option value="outset">Outset</option>
                                            </select>
                                        </label>
                                        <label>Choose border color : <input value="<?php echo $css_values['misc-bor-col']; ?>" type="color" name="misc-bor-col" id="type4-bor-col"></label>
                                    </td>
                                </tr>
                            </table>                  
                        </div>
                        <div id="Type5" class="tabcontent output">
                            <table class="table">
                                <tr><td><h3>Description</h3></td></tr>
                                <tr>
                                    <td>
                                        <input type="submit" value="Print" class="btn cp-output">
                                        <input type="submit" value="Download" class="btn cp-output">
                                        <input type="submit" value="Export" class="btn cp-output">
                                        <input type="submit" value="Generate" class="btn cp-output">
                                    </td>
                                </tr>
                                <tr><td><label>Choose button color : <input value="<?php echo $css_values['out-bt']; ?>" type="color" name="out-bt" id="type5-bg"></label> 
                                <label>Choose text color : <input value="<?php echo $css_values['out-txt']; ?>" type="color" name="out-txt" id="type5-text"></label></td></tr>
                                <tr>
                                    <td>
                                        <label>Choose border type:
                                            <select name="out-bor" id="type5-bor">
                                                <option value="<?php echo $css_values['out-bor']; ?>">Default</option>
                                                <option value="dotted">Dotted</option>
                                                <option value="dashed">Dashed</option>
                                                <option value="solid">Solid</option>
                                                <option value="double">Double</option>
                                                <option value="groove">Groove</option>
                                                <option value="none">Ridge</option>
                                                <option value="inset">Inset</option>
                                                <option value="outset">Outset</option>
                                            </select>
                                        </label>
                                        <label>Choose border color : <input value="<?php echo $css_values['out-bor-col']; ?>" type="color" name="out-bor-col" id="type5-bor-col"></label>
                                    </td>
                                </tr>
                            </table>                
                        </div>
                    </div>
                    <div class="container"><br><br><input type="submit" class="btn" id="submitter" value="Submit Changes"></div>
            </form>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
            <script>
                $('#type1-bg').on('change', function() {
                    $('.cp-positive').each(function() {
                        this.style.setProperty('--pos-bg-color', $('#type1-bg').val());
                    })
                });
                $('#type1-text').on('change', function() {
                    $('.cp-positive').each(function() {
                        this.style.setProperty('--pos-txt-color', $('#type1-text').val());
                    })
                });
                $('#type1-bor').on('change', function() {
                    $('.cp-positive').each(function() {
                        this.style.setProperty('--pos-bt-bor', $('#type1-bor').val());
                    })
                });
                $('#type1-bor-col').on('change', function() {
                    $('.cp-positive').each(function() {
                        this.style.setProperty('--pos-bor-color', $('#type1-bor-col').val());
                    })
                });

                $('#type2-bg').on('change', function() {
                    $('.cp-negative').each(function() {
                        this.style.setProperty('--neg-bg-color', $('#type2-bg').val());
                    })
                });
                $('#type2-text').on('change', function() {
                    $('.cp-negative').each(function() {
                        this.style.setProperty('--neg-txt-color', $('#type2-text').val());
                    })
                });
                $('#type2-bor').on('change', function() {
                    $('.cp-negative').each(function() {
                        this.style.setProperty('--neg-bt-bor', $('#type2-bor').val());
                    })
                });
                $('#type2-bor-col').on('change', function() {
                    $('.cp-negative').each(function() {
                        this.style.setProperty('--neg-bor-color', $('#type2-bor-col').val());
                    })
                });

                $('#type3-bg').on('change', function() {
                    $('.cp-submit').each(function() {
                        this.style.setProperty('--sub-bg-color', $('#type3-bg').val());
                    })
                });
                $('#type3-text').on('change', function() {
                    $('.cp-submit').each(function() {
                        this.style.setProperty('--sub-txt-color', $('#type3-text').val());
                    })
                });
                $('#type3-bor').on('change', function() {
                    $('.cp-submit').each(function() {
                        this.style.setProperty('--sub-bt-bor', $('#type3-bor').val());
                    })
                });
                $('#type3-bor-col').on('change', function() {
                    $('.cp-submit').each(function() {
                        this.style.setProperty('--sub-bor-color', $('#type3-bor-col').val());
                    })
                });

                $('#type4-bg').on('change', function() {
                    $('.cp-misc').each(function() {
                        this.style.setProperty('--misc-bg-color', $('#type4-bg').val());
                    })
                });
                $('#type4-text').on('change', function() {
                    $('.cp-misc').each(function() {
                        this.style.setProperty('--misc-txt-color', $('#type4-text').val());
                    })
                }); 
                $('#type4-bor').on('change', function() {
                    $('.cp-misc').each(function() {
                        this.style.setProperty('--misc-bt-bor', $('#type4-bor').val());
                    })
                });
                $('#type4-bor-col ').on('change', function() {
                    $('.cp-misc').each(function() {
                        this.style.setProperty('--misc-bor-color', $('#type4-bor-col').val());
                    })
                });

                $('#type5-bg').on('change', function() {
                    $('.cp-output').each(function() {
                        this.style.setProperty('--out-bg-color', $('#type5-bg').val());
                    })
                });
                $('#type5-text').on('change', function() {
                    $('.cp-output').each(function() {
                        this.style.setProperty('--out-txt-color', $('#type5-text').val());
                    })
                });
                $('#type5-bor').on('change', function() {
                    $('.cp-output').each(function() {
                        this.style.setProperty('--out-bt-bor', $('#type5-bor').val());
                    })
                });
                $('#type5-bor-col').on('change', function() {
                    $('.cp-output').each(function() {
                        this.style.setProperty('--out-bor-color', $('#type5-bor-col').val());
                    })
                });
                $('.tablinks').on('click', function() {
                    $('#description').remove();
                })

            function openTab(evt, buttonType) {
                var i, tabcontent, tablinks;
                evt.preventDefault();
                tabcontent = document.getElementsByClassName("tabcontent");
                for (i = 0; i < tabcontent.length; i++) {
                    tabcontent[i].style.display = "none";
                }
                tablinks = document.getElementsByClassName("tablinks");
                for (i = 0; i < tablinks.length; i++) {
                    tablinks[i].className = tablinks[i].className.replace(" active", "");
                }
                document.getElementById(buttonType).style.display = "block";
            }
                $('.cp-positive').on('click', function(evt) {
                    evt.preventDefault();
                });
                $('.cp-negative').on('click', function(evt) {
                    evt.preventDefault();
                });
                $('.cp-submit').on('click', function(evt) {
                    evt.preventDefault();
                });
                $('.cp-misc').on('click', function(evt) {
                    evt.preventDefault();
                });
                $('.cp-output').on('click', function(evt) {
                    evt.preventDefault();
                });
        </script>
    </body>
</html>

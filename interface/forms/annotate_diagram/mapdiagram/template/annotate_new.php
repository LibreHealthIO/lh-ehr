<html>
<head>
    <script type="text/javascript" src="<?php echo $GLOBALS['webroot'];?>/library/js/jquery-1.7.2.min.js"></script>
    <script type="text/javascript" src="<?php echo $GLOBALS['webroot'];?>/library/js/jquery-ui-1.8.6.custom.min.js"></script>
    <script type="text/javascript" src="<?php echo $GLOBALS['webroot'];?>/library/js/jquery.cookie.js"></script>
    <script type="text/javascript" src="<?php echo $GLOBALS['webroot'];?>/interface/forms/annotate_diagram/mapdiagram/js/mapdiagram.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo $this->form->template_dir;?>/css/ui-lightness/jquery-ui-1.8.6.custom.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $this->form->template_dir;?>/css/mapdiagram.css" />
</head>
<body>
<!-- In the beginning -->
<div class="outer-container">
<div class="navtop">
    <button style="color:red;font-weight:bold;" id="btn_savetop"><?php echo xl("Save");?></button>
    <button id="btn_cleartop"><?php echo xl("Clear");?></button>
    <button id="canceltop"><?php echo xl("Cancel");?></button>
    <button id="help" onClick="setCheck(value)" value='help'><?php echo xl("Help");?></button>
    <span class="mode-inline"></span>
    <div id="mode" class="mode">
      <button id="btn_mode"><?php echo xl("Label Mode");?></button><br>
      <span id="legend_grp">
      <label class="rblock"><input class="rblock" type="radio" name="modegrp" value='░' />░</label>
      <label class="rblock"><input class="rblock" type="radio" name="modegrp" value='☵'/>☵</label>
      <label class="rblock"><input class="rblock" type="radio" name="modegrp" value='◯'/>◯</label>
      <label class="rblock"><input class="rblock" type="radio" name="modegrp" value='✓'/>✓</label>
      <label class="rblock"><input class="rblock" type="radio" name="modegrp" value='✗'/>✗</label>
      <label class="rblock"><input class="rblock" type="radio" name="modegrp" value='╱'/>╱</label>
      <label class="rblock"><input class="rblock" type="radio" name="modegrp" value='╲'/>╲</label>
      <label class="rblock"><input class="rblock" type="radio" name="modegrp" value='╳'/>╳</label>
      <label class="rblock"><input class="rblock" type="radio" name="modegrp" id="rtxt" value="txtmode" checked /><?php echo xl("Text");?></label>
      </span>
    </div>
</div>
    <div id="container" class="container">
        <div><input type="text" class="text ui-widget-content ui-corner-all dytxt" id="dytxt" value="" /></div>
        <img src="<?php echo $this->form->image;?>" alt="" id="main-img"/>
    </div>  
    <div id="legend" class="legend">
        <div class="body">
            <ul></ul>
        </div>
    </div>
</div>
<p style="clear:both"/>
<div class="nav">
    <button style="color:red;font-weight:bold;" id="btn_save"><?php echo xl("Save");?></button>
    <button id="btn_clear"><?php echo xl("Clear");?></button>
    <button id="cancel"><?php echo xl("Cancel");?></button>
    <p>
        <?php echo xl("Click a spot on the graphic to add or remove an annotation");?> <br/>
        <?php echo xl("The 'Clear' button will remove all annotations.");?>
    </p>
</div>

<div class="dialog-form" style="display:none">
    <fieldset><!-- -->
    <button id="help-dialog" onClick="setCheck(value)" value='help-dialog'><?php echo xl("Help");?></button>
    <button id="mshade" onClick="setCheck(value)" value='░'>░</button>
    <button id="vshade" onClick="setCheck(value)" value='☵'/>☵</button>
    <button id="plus" onClick="setCheck(value)" value='◯'/>◯</button>
    <button id="check" onClick="setCheck(value)" value='✔'>&#x2714</button>
    <button id="exx" onClick="setCheck(value)" value='✘'>&#x2718</button>
    <button id="lgx" onClick="setCheck(value)" value='╳'/>╳</button>
<div class = "legendgrp" style="display:none">
    <label for="legendtext"><?php echo xl("Enter markers legend item description.");?></label>
    <input type="text" name="legendtext" id="legendtext" class="text ui-widget-content ui-corner-all legendtext" value="" />
</div>
<div class = "labelgrp" id = "labelgrp" style="display:block">
    <label for="label"><?php echo xl("Label");?></label>
    <input type="text" name="label" id="label" class="text ui-widget-content ui-corner-all label" value="" />
    <label for="options"></label>
    <select name="options"></select>
    <label for="optxtra"><?php echo xl("Optional Observation.");?></label>
    <input type="text" name="optxtra" id="optxtra" class="text ui-widget-content ui-corner-all optxtra" value="" />
    <label for="detail"><?php echo xl("Detail");?></label>
    <textarea name="detail" id="detail" class="textarea ui-widget-content ui-corner-all detail"></textarea>
</div>  
    </fieldset>
</div>
<div class="marker-template" style="display:none">
    <span class='count' id='count'></span>
</div>
<div class="xmark-template" style="display:none">
    <span class='xcnt' id='xcnt'></span>
</div>
<div class="dialog-help" title="Basic help" style="display:none">
    <p>Reserved for future help.</p>
</div>
<!-- THE END -->
<script type="text/javascript">
var cancellink = '<?php echo $this->dont_save_link;?>';
var cursorpath = "<?php echo $this->form->template_dir;?>/css/";
var popreturn = false;

    $(document).ready( function() {
        var diagrampath = '<?php echo $this->form->image;?>';
        if(diagrampath==""){ GetNewDiagram("main-img"); }
       
        $("#canceltop").click(function() { location.href=cancellink; });
        $("#cancel").click(function() { location.href=cancellink; });

        var hideNav = <?php echo $this->form->hideNav;?>;
        var optionsLabel = <?php echo $this->form->optionsLabel;?>;
        var options = <?php echo $this->form->optionList;?>;
        var data = <?php echo $this->form->data;?>;
        
        mapdiagram( {
            hideNav: hideNav,
            data: data,
            dropdownOptions: { label: optionsLabel, options: options },
            container: $("#container")
        } );
        
        if(!hideNav)
            $('#container').css({border: "3px solid red"});
        
        (function(){
             var min = 40, max = 300, pad_right = 2, input = document.getElementById('dytxt');

            input.style.width = min+'px';
            input.onkeypress = input.onkeydown = input.onkeyup = function(){
                var input = this;
                setTimeout(function(){
                    var tmp = document.createElement('div');
                    tmp.style.padding = '0';
                    if(getComputedStyle)
                        tmp.style.cssText = getComputedStyle(input, null).cssText;
                    if(input.currentStyle)
                        tmp.style = input.currentStyle;
                    tmp.style.width = '';
                    tmp.style.position = 'absolute';
                    tmp.innerHTML = input.value.replace(/&/g, "&amp;")
                                               .replace(/</g, "&lt;")
                                               .replace(/>/g, "&gt;")
                                               .replace(/"/g, "&quot;")
                                               .replace(/'/g, "&#039;")
                                               .replace(/ /g, '&nbsp;');
                    input.parentNode.appendChild(tmp);
                    var width = tmp.clientWidth+pad_right+1;
                    tmp.parentNode.removeChild(tmp);
                    if(min <= width && width <= max)
                        input.style.width = width+'px';
                }, 1);
            }

        })();
    });
var setCheck = function(bnobj){
    if(bnobj == "help"){// plan writing a help class - alert will do for now
      var strhelp = "To select symbols or options, right click anywhere on diagram for options menu. The Label Mode and Legend mode button will toggle to allow direct diagram editing using special symbols or text. Just select the symbol from the radio buttons presented when Label mode is clicked and that symbol or text will be placed on the diagram where clicked. A text prompt will appear for text entry when text mode is selected. Legend Mode allows the normal imformation popup for adding numbered markers/observations with the added option of selecting the special symbols used in Label mode so they may be named in the label legend. Also I added another text field in case you wish to add an observation not provided in the observations list, one or both fields may be used. You may remove any marker/label in either mode by clicking it, so long as you are in form edit.\n\nYellow markers are placed where symbols have been named for the legend so they can be identified if removal is needed. Hovering over any marker will show tool tip to identify label. Note that the idea behide the text mode and check marks is to allow for text like data in images of forms like range of motion or onset of pain questions. * To add additional diagrams to this feature simply drop the png image into the '/diagram' directory off forms file root. The diagram image is not restricted within its container so ensure an image width appropriate, 600px max seems right.\n\nOne last item concerning text input when in Label text mode(the default): Entered text is not saved until you click outside the text box, so before leaving text entry mode via selecting a symbol or toggling into Legend mode, click anywhere on the diagram first. Double clicking on a new location will give you another text box at new location and save the previous one, while any empty text box, is abandoned.";
        alert(strhelp);
   return;
    }
    if(bnobj == "help-dialog"){
       var strhelp = "To add a description to the legend of a symbol used for shading areas of the diagram, simply click the symbols button, enter the description in the field shown and save. The item is indentified on the diagram with yellow blocks so they may be removed. I suggest adding next to symbol. You may enter any text in the label field as a marker or use the auto numbered values.";
        alert(strhelp);
        return;
    }
    bnobj += " = ";
    $(".label").attr("value", "");
    $(".legendtext").attr("value", bnobj);
    $(".legendgrp").attr("style", "display:block");
    $(".legendtext").focus();
    $(".labelgrp").attr("style", "display:none");
};

function SetDiagram( image, fTitle ) {// from selectnew.php
    $("#dyntitle").attr("value", fTitle+" Annotated");
    $('#main-img').attr('src',image);
    popreturn = true;
    return true;
}
    // show the popup choice of diagrams
function GetNewDiagram(btnObj) {
        window.popup = window.open("../../forms/annotate_diagram/selectnew.php", "Diagram Selection", "width=925,height=710,left=200px,scrollbars=yes,modal=yes,alwaysRaised=yes");
        if( !window.popup ){
           alert("<?php echo xl('Popup Blocked');?> ");
           location.href = cancellink;
        }
        else{
            window.popup.onload = function() {
                window.popup.onunload = function(){
                    if( !popreturn )
                        location.href = cancellink;
                }
            }
        }
}


</script>
<form id="submitForm" name="submitForm" method="post" action="<?php echo $this->form->saveAction;?>" onsubmit="return top.restoreSession()">
    <input type="hidden" name="id" value="<?php echo $this->form->get_id();?>" />
    <input type="hidden" name="pid" value="<?php echo $this->form->get_pid();?>" />
    <input type="hidden" name="process" value="true" />
    <input type="hidden" name="data" id="data" value=""/>
    <input type="hidden" name="imagedata" id="imagedata" value=""/>
    <input type="hidden" name="dyntitle" id="dyntitle" value=""/>
</form>
</body>
</html>
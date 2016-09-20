
<!--<link href="--><?php //echo $GLOBALS['webroot'] ?><!--/interface/tags_filters/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">-->
<script src="<?php echo $GLOBALS['webroot'] ?>/interface/tags_filters/assets/jquery/jquery-3.1.0.min.js"></script>
<script src="<?php echo $GLOBALS['webroot'] ?>/interface/tags_filters/assets/bootstrap/js/bootstrap.min.js"></script>
<link href="<?php echo $GLOBALS['webroot'] ?>/interface/tags_filters/assets/js/bootstrap-tagsinput.css" rel="stylesheet">
<link href="<?php echo $GLOBALS['webroot'] ?>/interface/tags_filters/assets/js/bootstrap-tagsinput-typeahead.css" rel="stylesheet">
<script src="<?php echo $GLOBALS['webroot'] ?>/interface/tags_filters/assets/js/bootstrap-tagsinput.js"></script>
<script src="<?php echo $GLOBALS['webroot'] ?>/interface/tags_filters/assets/js/bootstrap3-typeahead.js"></script>
<style type="text/css">


    .dropdown-menu {
        position: absolute;
        top: 100%;
        left: 0;
        z-index: 1000;
        display: none;
        float: left;
        min-width: 160px;
        padding: 5px 0;
        margin: 2px 0 0;
        font-size: 14px;
        text-align: left;
        list-style: none;
        background-color: #fff;
        -webkit-background-clip: padding-box;
        background-clip: padding-box;
        border: 1px solid #ccc;
        border: 1px solid rgba(0, 0, 0, .15);
        border-radius: 1px;
        -webkit-box-shadow: 0 6px 12px rgba(0, 0, 0, .175);
        box-shadow: 0 6px 12px rgba(0, 0, 0, .175);
    }
    .dropdown-menu.pull-right {
        right: 0;
        left: auto;
    }
    .dropdown-menu .divider {
        height: 1px;
        margin: 9px 0;
        overflow: hidden;
        background-color: #e5e5e5;
    }
    .dropdown-menu > li > a {
        display: block;
        padding: 3px 20px;
        clear: both;
        font-weight: normal;
        line-height: 1.42857143;
        color: #333;
        white-space: nowrap;
    }
    .dropdown-menu > li > a:hover,
    .dropdown-menu > li > a:focus {
        color: black;
        text-decoration: none;
        background-color: #a7a7a7;
    }
    .dropdown-menu > .active > a,
    .dropdown-menu > .active > a:hover,
    .dropdown-menu > .active > a:focus {
        color: #fff;
        text-decoration: none;
        background-color: #a7a7a7;
        outline: 0;
    }
    .dropdown-menu > .disabled > a,
    .dropdown-menu > .disabled > a:hover,
    .dropdown-menu > .disabled > a:focus {
        color: #777;
    }
    .dropdown-menu > .disabled > a:hover,
    .dropdown-menu > .disabled > a:focus {
        text-decoration: none;
        cursor: not-allowed;
        background-color: transparent;
        background-image: none;
        filter: progid:DXImageTransform.Microsoft.gradient(enabled = false);
    }
    .open > .dropdown-menu {
        display: block;
    }
    .open > a {
        outline: 0;
    }
    .dropdown-menu-right {
        right: 0;
        left: auto;
    }
    .dropdown-menu-left {
        right: auto;
        left: 0;
    }

    .bootstrap-tagsinput {
        background-color: #fff;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset;
        color: #555;
        cursor: text;
        display: inline-block;
        line-height: 22px;
        max-width: 100%;
        padding: 4px 6px;
        vertical-align: middle;
        width: 673px;
    }

    .bootstrap-tagsinput input.tt-input {
        vertical-align: middle;
    }

    .bootstrap-tagsinput span.twitter-typeahead {
        display: inline-block;
    }

    p.tt-suggestion {
        width: 400px;
        border-color: grey;
        border-style: solid;
        border-width: 1px 2px 1px 2px;
        background-color:rgba(255, 255, 255, 0.9);
        margin: 0px;
        padding: 4px;
    }

    .tag {
        -moz-border-radius:4px 4px 4px 4px;
        border-radius:4px 4px 4px 4px;
        background-color:#acf;
        padding:4px;
        font-size: 12px;
        line-height: 1;
        text-align: center;
        vertical-align: baseline;
        white-space: nowrap;
    }
    a.tag:hover,
    a.tag:focus {
        color: #fff;
        text-decoration: none;
        cursor: pointer;
    }
    .tag:empty {
        display: none;
    }

    <?php foreach ( $this->tagColors as $color ) { ?>
    .<?php echo $color['value']; ?> {
        background-color: <?php echo $color['color']; ?>
    }
    <?php } ?>

</style>

<script type="text/javascript">
    //$(document).ready( function() {

        var elt = $('#patient-tags');
        elt.tagsinput({
            allowDuplicates: false,
            itemValue: 'tag_id',
            itemText: 'tag_name',
            tagClass: function ( item ) {
                return item.tag_color;
            },
            typeahead: {
                source: <?php echo $this->tagsJson; ?>,
                afterSelect: function(val) {
                    this.$element.val("");
                }
            },
            freeInput: false
        });

        <?php foreach ( $this->tags as $tag ) { ?>
        elt.tagsinput('add', {
            "tag_id": '<?php echo $tag->tag_id; ?>',
            "tag_name": '<?php echo $tag->tag_name; ?>',
            "tag_color": '<?php echo $tag->tag_color; ?>'
        });
        <?php } ?>

        $("input.tt-input").css( "vertical-align", "middle" );
        $("span.twitter-typeahead").css( "display", "inline-block" );


    //});
</script>

<form action="">
    <input id="patient-tags" size="8" type="text" placeholder="Add Tag" style="display: inline-block; width: 100%;" data-provide="typeahead" data-role="tagsinput"/>
</form>


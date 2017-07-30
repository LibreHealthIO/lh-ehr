<link rel="stylesheet" type="text/css" href=".../../css/menu.css"/>
<script type="text/html" id="menu-action">
    <a data-bind="text:label,click: menuActionClick,css: {menuDisabled: ! enabled()}"></a>
</script>

<script type="text/html" id="menu-header">
    <a data-bind="text:label" class="dropdown-toggle " data-toggle="dropdown"> <b class="caret"></b></a>
    <ul class="dropdown-menu" data-bind="foreach: children">        
        <li class="dropdown dropdown-submenu" data-bind="template: {name:header ? 'menu-header' : 'menu-action', data: $data }"></li>
    </ul>
</script>

<script type="text/html" id="menu-template">  
    <!-- Collect the nav links, forms, and other content for toggling -->    
    <ul class="nav navbar-nav" data-bind="foreach: menu">
        <li class="dropdown" data-bind="template: {name:header ? 'menu-header' : 'menu-action', data: $data }"></li>            
    </ul>       
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $('.nav li.dropdown').hover(function() {
            $(this).addClass('open');
        }, function() {
            $(this).removeClass('open');
        });
    });
</script>



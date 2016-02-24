<script type="text/html" id="menu-action">
    <div class='menuLabel' data-bind="text:label,click: menuActionClick,css: {menuDisabled: ! enabled()}"></div>
</script>
<script type="text/html" id="menu-header">
    
    <div class="menuSection">
        <div class='menuLabel' data-bind="text:label"></div>
        <ul class="menuEntries" data-bind="foreach: children">
           <li data-bind="template: {name:header ? 'menu-header' : 'menu-action', data: $data }"></li>
        <ul>
    </div>
</script>
<script type="text/html" id="menu-template">
    <div class='appMenu' data-bind="foreach: menu">
            <span data-bind="template: {name:header ? 'menu-header' : 'menu-action', data: $data }"></span>
    </div>
</script>


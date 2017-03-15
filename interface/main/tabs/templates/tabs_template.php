<script type="text/html" id="tabs-controls">
    <!-- All the unlocked tabs -->
    <div class="tabControls">
    <!-- ko  foreach: tabs.tabsList -->
        <!--ko if:!locked() -->
            <span class="tabSpan tabFloat bgcolor2">
                <span  data-bind="text: title, click: tabClicked, css: {tabHidden: !visible()}"></span>
                <span class="typcn typcn-refresh" data-bind="click: tabRefresh"></span>        
                <span class="typcn typcn-lock-open"  data-bind="click: tabLockToggle"></span>
                <!-- ko if:closable-->
                    <span class="typcn typcn-delete" data-bind="click: tabClose"></span>
                <!-- /ko -->
            </span>
        <!-- /ko -->
    <!-- /ko -->
    </div>
</script>
<script type="text/html" id="tabs-frames">

    <div id="frameBarrier"></div>

    <!-- ko  foreach: tabs.tabsList -->
        <div class="frameDisplay" data-bind="visible:visible">
          
            <div class="body_title tabControls">
                <span class="tabSpan bgcolor2">
                    <span  data-bind="text: title, click: tabClicked, css: {tabHidden: !visible()}"></span>
                    <span class="typcn typcn-refresh" data-bind="click: tabRefresh"></span>
                    <!--ko if:!locked() -->
                        <span class="typcn typcn-lock-open"  data-bind="click: tabLockToggle"></span>
                    <!-- /ko -->
                    <!--ko if:locked() -->
                        <span class="typcn typcn-lock-closed"  data-bind="click: tabLockToggle"></span>
                    <!-- /ko -->

                    <!-- ko if:closable-->
                        <span class="typcn typcn-delete" data-bind="click: tabClose"></span>
                    <!-- /ko -->
                </span>
            </div>
          
            <iframe data-bind="location: $data, iframeName: $data.name, ">

            </iframe>
        </div>

        <div class="handle body_title"></div>

    <!-- /ko -->
</script>

<script type="text/html" id="tabs-controls">
    <!-- All the unlocked tabs -->
    <div class="tabControls">
    <!-- ko  foreach: tabs.tabsList -->
        <!--ko if:!locked() -->
            <span class="tabSpan tabFloat bgcolor2">
                <span  data-bind="text: title, click: tabClicked, css: {tabHidden: !visible()}"></span>
                    <!--ko if:!locked() -->
                        <span class="fa fa-unlock tab-button"  data-bind="click: tabLockToggle" title="Unlock"></span>
                    <!-- /ko -->
                    <!--ko if:locked() -->
                        <span class="fa fa-lock tab-button"  data-bind="click: tabLockToggle" title="Lock"></span>
                    <!-- /ko -->

                    <!-- ko if:closable-->
                        <span class="fa fa-times tab-button" data-bind="click: tabClose" title="Close"></span>
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
                    <span class="fa fa-refresh tab-refresh-icon tab-button" data-bind="click: tabRefresh" title="Refresh"></span>
                    <!--ko if:!locked() -->
                        <span class="fa fa-unlock tab-button"  data-bind="click: tabLockToggle" title="Unlock"></span>
                    <!-- /ko -->
                    <!--ko if:locked() -->
                        <span class="fa fa-lock tab-button"  data-bind="click: tabLockToggle" title="Lock"></span>
                    <!-- /ko -->

                    <!-- ko if:closable-->
                        <span class="fa fa-times tab-button" data-bind="click: tabClose" title="Close"></span>
                    <!-- /ko -->
                </span>
            </div>

            <iframe data-bind="location: $data, iframeName: $data.name, ">

            </iframe>
        </div>

        <div class="handle body_title"></div>

    <!-- /ko -->
</script>

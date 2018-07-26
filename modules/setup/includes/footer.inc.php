<?php
/**
 * This file is responsible for including the neccesssary scripts for running the setup procedure at the footer of every page
 *
 * NB: All js files should be included at this level.
 *
 * LICENSE: This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0
 * See the Mozilla Public License for more details.
 * If a copy of the MPL was not distributed with this file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @package Librehealth EHR
 * @author Mua Laurent <muarachmann@gmail.com>
 * @link http://librehealth.io
 *
 * Please help the overall project by sending changes you make to the author and to the LibreEHR community.
 *
 */
?>


<p class="clearfix"></p>
<p class="clearfix"></p>
<p class="clearfix"></p>
</div> <!-- close for the container div found in header -->


</body>
<script type="text/javascript" src="libs/js/jquery.js"></script>
<script type="text/javascript" src="libs/js/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="libs/js/bootstrap/js/bootstrap-select.min.js"></script>
<script type="text/javascript" src="libs/js/jscolor.js"></script>
<script type="text/javascript" src="libs/js/jquery.magnific-popup.js"></script>
<script type="text/javascript" src="libs/js/iziModalToast/iziModal.min.js"></script>
<script type="text/javascript" src="libs/js/iziModalToast/iziToast.min.js"></script>
<script type="text/javascript" src="libs/js/owl-carousel/owl.carousel.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
            $(".owl-carousel").owlCarousel({
                items : 1,
                nav : true,
                autoplayHoverPause : false,
                dots : true,
                loop : true

            });

            $('.test-popup-link').magnificPopup({
                items: [
                    {
                        src: 'libs/images/theme/theme1.png',
                        type:'image'
                    },
                    {
                        src: 'libs/images/theme/theme2.png',
                        type: 'image' // this overrides default type
                    },
                    {
                        src: 'libs/images/theme/theme3.png',
                        type:'image'
                    }
                ],
                gallery: {
                    enabled: true
                },
                type: 'image' // this is default type
            });

        });
        </script>
<script type="text/javascript" src="libs/js/setup.js"></script>
</html>


/**
 * Copyright (C) 2016 Raymond Magaura <magauran@medfetch.com>
 *
 * LICENSE: This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 3
 * of the License, or (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://opensource.org/licenses/gpl-license.php>;.
 *
 * @package LibreEHR
 * @author   Raymond Magaura <magauran@medfetch.com>
 * @link    http://librehealth.io
 */

function tabs_on() {
    $('#theme_tabs_layout').addClass('nodisplay');
    $('#menu_styling_tabs').addClass('nodisplay');

}

function tabs_off() {
    $('#theme_tabs_layout').removeClass('nodisplay');
    $('#menu_styling_tabs').removeClass('nodisplay');

}

function logout()
{
    top.window.location=webroot_url+"/interface/logout.php"
}

function tabs_status() {
    var newValue =$('#form_0').val();
    if (newValue =='1') {
        tabs_off();
    } else {
        tabs_on();
    }
}




/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.scss in this case)
import '../css/app.scss';
import 'bootstrap';

require("expose-loader?jQuery!jquery");
require("expose-loader?$!jquery");
require("jquery.easing");
require("startbootstrap-sb-admin-2/js/sb-admin-2");

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
// const $ = require('jquery');

//change button position when toggle menu
$("#sidebarToggle, #sidebarToggleTop").on('click', function (e) {

    if ($(".sidebar").hasClass("toggled")) {

        $('.festival-menu-title').toggle();
        $('.change-fest').toggle();
    } else {
        $('.festival-menu-title').toggle();
        $('.change-fest').toggle();
    }
});
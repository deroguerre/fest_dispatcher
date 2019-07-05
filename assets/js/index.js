import "../css/index.scss";

import {Calendar} from "@fullcalendar/core";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import listPlugin from "@fullcalendar/list";
import interaction from "@fullcalendar/interaction";

import frLocale from "@fullcalendar/core/locales/fr";


jQuery(document).ready(function () {
    jQuery(".festival-card").on("click", function () {

    });
});

document.addEventListener("DOMContentLoaded", function () {

    var calendarEl = document.getElementById("calendar");

    var calendar = new Calendar(calendarEl, {
        height: 'parent',
        allDaySlot: false,
        plugins: [interaction, dayGridPlugin, timeGridPlugin, listPlugin],
        defaultView: "timeGridWeek",
        editable: true,
        header: {
            left: "prev, next today",
            // center: "title",
            right: "timeGridDay, timeGridWeek, dayGridMonth"
        },
        locales: [frLocale],
        locale: "fr"
    });
    calendar.render();

    jQuery.ajax({
        type: "GET",
        url: window.location.href + "api/jobs",
        dataType: "json",
        success: function (jobs) {
            jobs.forEach(function (event) {
                console.log(event)
                let currEvent = {
                    title: event.title,
                    start: event.startDate,
                    end: event.endDate
                };
                // console.log(currEvent)
                calendar.addEvent(currEvent)
            });
        }
    });

});
import "../css/index.scss";

import {Calendar} from "@fullcalendar/core";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import listPlugin from "@fullcalendar/list";
import interaction from "@fullcalendar/interaction";

import frLocale from "@fullcalendar/core/locales/fr";

$(document).ready(function () {

    var calendarEl = document.getElementById("calendar");

    var calendar = new Calendar(calendarEl, {
        height: 'parent',
        allDaySlot: false,
        plugins: [interaction, dayGridPlugin, timeGridPlugin, listPlugin],
        defaultView: "timeGridWeek",
        editable: true,
        selectable: true,
        header: {
            left: "prev, next today",
            // center: "title",
            right: "timeGridDay, timeGridWeek, dayGridMonth"
        },
        locales: [frLocale],
        locale: "fr",
        select: function(info) {
            console.log('selected ' + info.startStr + ' to ' + info.endStr);
        }
    });
    calendar.render();

    $.ajax({
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

    $('#newJobModal').on('shown.bs.modal', function () {
        $('#myInput').trigger('focus')
    }).modal();

});
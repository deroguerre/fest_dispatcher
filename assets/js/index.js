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
        select: function (info) {
            openNewJobModal(info);
        }
    });
    calendar.render();

    $.ajax({
        type: "GET",
        url: window.location.href + "api/jobs",
        dataType: "json",
        success: function (jobs) {
            jobs.forEach(function (event) {
                // console.log(event)
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

    function openNewJobModal(info) {

        $('#new-job-start').val(info.startStr.split('+')[0]);
        $('#new-job-end').val(info.endStr.split('+')[0]);

        $('#newJobModal').on('shown.bs.modal', function () {
            $('#new-job-users').trigger('focus')
        }).modal();

        // $('#new-job-start').val(info.startStr);
        // $('#new-job-end').val(info.endStr);
    }

    // $('#new-job-save').on('click', function () {
    //     let title = $('#new-job-title').val();
    //     let team = $('#new-job-team').val();
    //     let users = $('#new-job-users option:selected');
    //     let start = $('#new-job-start').val();
    //     let end = $('#new-job-end').val();
    //
    //
    //     users.each(function (key, user) {
    //         let job = {
    //             title: title,
    //             team: "/teams/" + team,
    //             user: "/users/" + user.value,
    //             startDate: start,
    //             endDate: end
    //         };
    //
    //         console.log(job);
    //
    //         $.ajax({
    //             url: "api/jobs",
    //             type: "POST",
    //             accept: "application/json",
    //             contentType: "application/json",
    //             data: {
    //                 "title": "string",
    //                 "startDate": "2019-07-12T07:30:00",
    //                 "endDate": "2019-07-12T09:00:00",
    //                 "backgroundColor": "string",
    //                 "team": "/api/teams/17",
    //                 "user": "/api/users/53"
    //             },
    //             // async: true,
    //             success: function (data) {
    //                 console.log("####################### SUCCESS #####################");
    //                 console.log(data);
    //             },
    //             error: function (xhr, status, error) {
    //                 console.log(status);
    //                 console.log(xhr.responseText);
    //                 console.log(error);
    //             }
    //         });
    //         return false;
    //     })
    // });

});
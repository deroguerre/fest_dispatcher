import "../css/index.scss";

import {Calendar} from "@fullcalendar/core";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import listPlugin from "@fullcalendar/list";
import interaction from "@fullcalendar/interaction";

import frLocale from "@fullcalendar/core/locales/fr";

$(document).ready(function () {

    var calendarEl = document.getElementById("calendar");

    // init fullcalendar
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
        timeZone: 'none',
        locales: [frLocale],
        locale: "fr",
        select: function (info) {
            openNewJobModal(info);
        },
        eventDrop: function(info) {

            // alert(info.event.title + " was dropped on " + info.event.start.toISOString());
            console.log(info.event.id);
            if (!confirm("Are you sure about this change?")) {
                info.revert();
            } else {

                console.log(info.event);

                let job = {
                    id: info.event.id,
                    start: info.event.start,
                    end: info.event.end
                };

                console.log("job", job);

                var editJobControllerUri = $('#data-from-twig').data('edit-job-controller');

                calendar.editable = false;

                $.ajax({
                    url: editJobControllerUri,
                    type: "POST",
                    dataType: 'json',
                    data: {
                        "job": job
                    },
                    async: true,
                    success: function (response) {
                        calendar.editable = true;
                    },
                    error: function (xhr, status, error) {
                        console.log(status);
                        console.log(xhr.responseText);
                        console.log(error);
                    }
                });
            }
        }
    });
    calendar.render();

    // populate fullcalendar with jobs
    $.ajax({
        type: "GET",
        url: window.location.href + "api/jobs",
        dataType: "json",
        success: function (jobs) {
            jobs.forEach(function (event) {
                let currEvent = {
                    id: event.id,
                    title: event.title,
                    start: event.startDate,
                    end: event.endDate
                };
                calendar.addEvent(currEvent)
            });
        }
    });

    // open modal for new jobs
    function openNewJobModal(info) {

        $('#new-job-start').val(info.startStr.split('+')[0]);
        $('#new-job-end').val(info.endStr.split('+')[0]);

        $('#newJobModal').on('shown.bs.modal', function () {
            $('#new-job-users').trigger('focus')
        }).modal();
    }

    // click on save btn
    $('#new-job-save').on('click', function () {

        var that = this;
        var spinner = $('.spinner-border');

        $(this).attr('disabled', true);
        $(this).hide();
        spinner.show();

        let title = $('#new-job-title').val();
        let team = $('#new-job-team').val();
        let users = $('#new-job-users option:selected');
        let start = $('#new-job-start').val();
        let end = $('#new-job-end').val();

        this.nbUsers = users.length;

        users.each(function (key, user) {
            let job = {
                title: title,
                team: team,
                user: user.value,
                startDate: start,
                endDate: end
            };

            var newJobControllerUri = $('#data-from-twig').data('new-job-controller');

            $.ajax({
                url: newJobControllerUri,
                type: "POST",
                dataType: 'json',
                data: {
                    "job": job
                },
                async: false,
                success: function (response) {
                    let currEvent = {
                        title: title,
                        start: start,
                        end: end
                    };
                    calendar.addEvent(currEvent);
                    that.nbUsers--;
                },
                error: function (xhr, status, error) {
                    console.log(status);
                    console.log(xhr.responseText);
                    console.log(error);
                }
            });

        });

        if (this.nbUsers <= 0) {
            $('#newJobModal').modal('hide');
            spinner.hide();
            $(this).attr('disabled', false);
            $(this).show();
        } else {
            console.log("une erreur c'est produit");
        }

    });

});
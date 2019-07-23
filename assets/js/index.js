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
        eventDrop: function (info) {
            editDatetimeJob(info);
        },
        eventResize: function (info) {
            editDatetimeJob(info);
        },
        eventClick: function (info) {
            info.jsEvent.preventDefault(); // don't let the browser navigate

            if (confirm('Delete "' + info.event.title + '"?')) {
                removeJob(info);
            }
        }
    });

    calendar.render();

    // populate fullcalendar with jobs
    let jobs = $('#data-from-twig').data('jobs-by-festival');
    jobs.forEach(function (event) {
        let currEvent = {
            id: event.id,
            title: event.title,
            start: event.startDate,
            end: event.endDate,
            color: event.backgroundColor
        };
        calendar.addEvent(currEvent)
    });

    // populate fullcalendar with jobs
    // $.ajax({
    //     type: "GET",
    //     url: window.location.href + "api/jobs",
    //     dataType: "json",
    //     async: false,
    //     success: function (jobs) {
    //         jobs.forEach(function (event) {
    //             let currEvent = {
    //                 id: event.id,
    //                 title: event.title,
    //                 start: event.startDate,
    //                 end: event.endDate,
    //                 color: event.backgroundColor
    //             };
    //             calendar.addEvent(currEvent)
    //         });
    //
    //         //event color switcher btn
    //     }
    // });

    $('.fc-header-toolbar').after('<button class="btn btn-primary btn-sm mb-2 event-color-switch">disable color</button>');

    // click on save btn
    $('#new-job-save').on('click', function () {

        //user input control
        if (!$('#new-job-users option:selected').get().length > 0) {

            $('#new-job-users').addClass("is-invalid");
            return;
        }

        var that = this;
        var spinner = $('.spinner-border');

        $(this).attr('disabled', true);
        $(this).hide();
        spinner.show();

        // let title = $('#new-job-title').val();
        // let title = $('#new-job-title').val();
        let team = $('#new-job-team option:selected');
        let users = $('#new-job-users option:selected');
        let start = $('#new-job-start').val();
        let end = $('#new-job-end').val();

        this.nbUsers = users.length;

        users.each(function (key, user) {

            console.log("team", team);

            let job = {
                title: team.text().toUpperCase() + ': ' + user.text,
                team: team.val(),
                user: user.value,
                startDate: start,
                endDate: end,
                backgroundColor: team.data("team-color")
            };

            console.log(job);

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
                        id: response.id,
                        title: job.title,
                        start: job.startDate,
                        end: job.endDate,
                        color: job.backgroundColor
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

    // open modal for new jobs
    function openNewJobModal(info) {

        $('#new-job-start').val(info.startStr.split('+')[0]);
        $('#new-job-end').val(info.endStr.split('+')[0]);

        $('#newJobModal').on('shown.bs.modal', function () {
            $('#new-job-users').trigger('focus')
        }).modal();
    }

    function editDatetimeJob(info) {
        console.log(info);

        let start;
        let end;

        if (typeof info.event.start !== 'undefined') {
            //parse to string and format
            start = info.event.start.toISOString().split('.')[0];
        } else {
            console.log('erreur avec la date de début');
            return false;
        }

        if (typeof info.event.end !== 'undefined') {
            end = info.event.end.toISOString().split('.')[0];
        } else {
            end = start;
        }

        let job = {
            id: info.event.id,
            start: start,
            end: end
        };
        console.log(job);

        let editJobControllerUri = $('#data-from-twig').data('edit-job-controller');

        $.ajax({
            url: editJobControllerUri,
            type: "POST",
            dataType: 'json',
            data: {
                "job": job
            },
            async: true,
            success: function (response) {
                console.log("event edited");
            },
            error: function (xhr, status, error) {
                console.log(status);
                console.log(xhr.responseText);
                console.log(error);
            }
        });
    }

    function removeJob(info) {
        info.event.remove();

        let removeJobControllerUri = $('#data-from-twig').data('remove-job-controller');

        let job = {
            id: info.event.id
        };

        $.ajax({
            url: removeJobControllerUri,
            type: "POST",
            dataType: 'json',
            data: {
                "job": job
            },
            async: true,
            success: function (response) {
                console.log("event removed");
            },
            error: function (xhr, status, error) {
                console.log(status);
                console.log(xhr.responseText);
                console.log(error);
            }
        });
    }

    //event color switcher
    $('.event-color-switch').on('click', function () {
        if ($(this).hasClass("toggled")) {
            $(this).removeClass("toggled");
            $('.fc-event').each(function () {
                $(this).css("background-color", $(this).data("color"));
                $(this).css("border-color", $(this).data("color"));
            });
        } else {
            $(this).addClass("toggled");
            $('.fc-event').each(function (index) {
                $(this).data("color", this.style.backgroundColor);
                $(this).css("background-color", "#4e73df");
                $(this).css("border-color", "#4e73df");
            });
        }
    });

    //event control user is selected
    $('#new-job-users').change(function () {
        $(this).removeClass("is-invalid");
    });

});
import '../css/index.scss';

import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';

import frLocale from '@fullcalendar/core/locales/fr';

document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new Calendar(calendarEl, {
        plugins: [ dayGridPlugin, timeGridPlugin, listPlugin ],
        defaultView: 'timeGridWeek',
        header: {
            left: 'prev, next today',
            center: 'title',
            right: 'timeGridDay, timeGridWeek, dayGridMonth'
        },
        locales: [frLocale],
        locale: 'fr'
    });

    calendar.render();
});
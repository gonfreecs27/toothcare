$(document).ready(function () {

    let selectedEventId = null;

    const $modal = $('#addAppointmentModal');
    const $form = $('#appointmentForm');

    const calendarEl = document.getElementById('calendar');

    function resetForm() {

        selectedEventId = null;

        $form[0].reset();

        $('#btnSaveAppointment')
            .text('Save Appointment')
            .removeClass('btn-warning')
            .addClass('btn-primary');
    }

    function showLoader(show = true) {

        if (show) {
            $('#calendarLoader').removeClass('d-none');
        } else {
            $('#calendarLoader').addClass('d-none');
        }
    }

    function getStatusClass(status) {

        const classes = {
            pending: 'event-pending',
            confirmed: 'event-confirmed',
            completed: 'event-completed',
            cancelled: 'event-cancelled'
        };

        return classes[status] || '';
    }

    function populateSelect(url, selector, placeholder) {

        $.get(url)
            .done(function (data) {

                let options = `<option value="">${placeholder}</option>`;

                data.forEach(item => {
                    options += `
                        <option value="${item.id}">
                            ${item.name}
                        </option>
                    `;
                });

                $(selector).html(options);
            })
            .fail(function () {
                alertify.error(`Failed to load ${placeholder}`);
            });
    }

    function formatTime(date) {

        return date.toTimeString().slice(0, 5);
    }

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',

        height: 'auto',

        selectable: true,
        editable: true,
        nowIndicator: true,

        slotMinTime: '07:00:00',
        slotMaxTime: '21:00:00',

        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
        },

        eventTimeFormat: {
            hour: '2-digit',
            minute: '2-digit',
            meridiem: true
        },

        events: {
            url: '/admin/appointments/list',
            method: 'GET',
            failure() {
                alertify.error('Failed to load appointments');
            }
        },

        loading(isLoading) {
            showLoader(isLoading);
        },

        dateClick(info) {
            resetForm();

            $('input[name="appointment_date"]').val(info.dateStr);

            $modal.modal('show');
        },

        select(info) {

            resetForm();

            const start = new Date(info.start);
            const end = new Date(info.end);

            $('input[name="appointment_date"]')
                .val(info.startStr.split('T')[0]);

            $('input[name="start_time"]')
                .val(formatTime(start));

            $('input[name="end_time"]')
                .val(formatTime(end));

            $modal.modal('show');
        },

        eventClick(info) {
            openAppointmentModal(info.event);
        },

        eventDrop(info) {
            updateAppointmentSchedule(info.event, info.revert);
        },

        eventResize(info) {
            updateAppointmentSchedule(info.event, info.revert);
        },

        eventClassNames(arg) {
            const status = arg.event.extendedProps.status;
            return [getStatusClass(status)];
        }
    });

    calendar.render();

    $('#btnToday').on('click', function () {
        calendar.today();
    });

    $('#btnNewAppointment').on('click', function () {
        resetForm();
        $modal.modal('show');
    });

    $('#btnSaveAppointment').on('click', function () {
        const btn = $(this);

        const startTime = $('input[name="start_time"]').val();
        const endTime = $('input[name="end_time"]').val();

        if (startTime >= endTime) {
            alertify.error('End time must be greater than start time');
            return;
        }

        btn.prop('disabled', true);

        $.ajax({

            url: selectedEventId
                ? `/admin/appointments/update/${selectedEventId}`
                : '/admin/appointments/create',

            type: 'POST',

            data: $form.serialize(),

            success(res) {

                $modal.modal('hide');

                resetForm();

                calendar.refetchEvents();

                alertify.success(
                    selectedEventId
                        ? 'Appointment updated'
                        : 'Appointment created'
                );
            },

            error(xhr) {

                alertify.error(
                    xhr.responseJSON?.error ||
                    'Failed to save appointment'
                );
            },

            complete() {
                btn.prop('disabled', false);
            }
        });
    });

    window.openAppointmentModal = function (event) {

        resetForm();

        selectedEventId = event.id;

        const start = event.start;
        const end = event.end;

        $('select[name="patient_id"]')
            .val(event.extendedProps.patient_id);

        $('select[name="dentist_id"]')
            .val(event.extendedProps.dentist_id);

        $('input[name="appointment_date"]')
            .val(start.toISOString().split('T')[0]);

        $('input[name="start_time"]')
            .val(formatTime(start));

        $('input[name="end_time"]')
            .val(formatTime(end));

        $('select[name="status"]')
            .val(event.extendedProps.status);

        $('textarea[name="reason"]')
            .val(event.extendedProps.reason);

        $('#btnSaveAppointment')
            .text('Update Appointment')
            .removeClass('btn-primary')
            .addClass('btn-warning');

        $modal.modal('show');
    };

    function updateAppointmentSchedule(event, revertCallback) {

        $.ajax({

            url: `/admin/appointments/update-schedule/${event.id}`,

            type: 'POST',

            data: {
                appointment_date: event.startStr.split('T')[0],
                start_time: formatTime(event.start),
                end_time: formatTime(event.end)
            },

            success() {
                alertify.success('Appointment updated');
            },

            error(xhr) {

                revertCallback();

                alertify.error(
                    xhr.responseJSON?.error ||
                    'Failed to update appointment'
                );
            }
        });
    }

    populateSelect(
        '/admin/patients/list',
        'select[name="patient_id"]',
        'Select Patient'
    );

    populateSelect(
        '/admin/dentists/list',
        'select[name="dentist_id"]',
        'Select Dentist'
    );
});
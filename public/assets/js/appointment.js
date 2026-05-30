$(document).ready(function () {

    let selectedEventId = null;
    let serviceSelect = null;

    const $modal = $('#addAppointmentModal');
    const $form = $('#appointmentForm');

    const calendarEl = document.getElementById('calendar');

    function resetForm() {

        selectedEventId = null;

        $form[0].reset();

        if (serviceSelect) {
            serviceSelect.clear();
        }
        $('#servicesTotal').val('0.00');

        $('#btnSaveAppointment')
            .text('Save Appointment')
            .removeClass('btn-warning')
            .addClass('btn-primary');
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

        selectable: false,
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

        events: function(fetchInfo, successCallback, failureCallback) {
            $.ajax({
                url: App.endpoint('admin/appointments/list'),
                method: 'POST',
                data: {
                    start: fetchInfo.startStr,
                    end: fetchInfo.endStr
                },
                success: function(response) {
                    successCallback(response.events);
                    $.each(response.tally, (a, b) => {
                        $(`h3#${a}`).html(b);
                    });
                },
                error: function() {
                    failureCallback();
                    alertify.error('Failed to load appointments');
                }
            });
        },

        dateClick(info) {
            resetForm();

            $('input[name="appointment_date"]').val(info.dateStr);

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

        const start = new Date(`1970-01-01T${startTime}:00`);
        const end = new Date(`1970-01-01T${endTime}:00`);

        if (start >= end) {
            alertify.error('End time must be greater than start time');
            return;
        }

        btn.prop('disabled', true);
        let formData = $form.serializeArray();
        if (selectedEventId) {
            formData.push({
                name: 'id',
                value: selectedEventId
            });
        }

        $.ajax({
            url: selectedEventId
                ? App.endpoint(`admin/appointments/update`)
                : App.endpoint('admin/appointments/create'),

            type: 'POST',
            data: $.param(formData),

            success(res) {
                $modal.modal('hide');
                resetForm();
                calendar.refetchEvents();
                alertify.success(res.message);
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

    function openAppointmentModal(event) {
        resetForm();

        selectedEventId = event.id;

        const start = event.start;
        const end = event.end;

        $('select[name="patient_id"]')
            .val(event.extendedProps.patient_id);

        $('select[name="dentist_id"]')
            .val(event.extendedProps.dentist_id);

        if (serviceSelect) {
            const serviceIds = (event.extendedProps.services || []).map(s => s.id);
            serviceSelect.setValue(serviceIds);
        }

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
            url: App.endpoint('admin/appointments/update_schedule'),
            type: 'POST',
            data: {
                id: event.id,
                start: event.start.toISOString(),
                end: event.end.toISOString(),
            },
            success(response) {
                alertify.success(response.message);
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

    function loadServices() {
        $.get(App.endpoint('admin/services/list'), function (response) {
            const $select = $('#services');

            if ($select[0].selectize) {
                $select[0].selectize.destroy();
            }

            $select.empty();
            serviceSelect = $select.selectize({
                plugins: ['remove_button'],
                valueField: 'id',
                labelField: 'name',
                searchField: ['name'],
                placeholder: 'Select services',
                maxItems: null,
                create: false,
                options: response.data,
                render: {
                    option: function (item, escape) {
                        return `
                            <div class="px-2 py-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="flex-grow-1 pe-2">
                                        <div class="fw-semibold text-dark">
                                            ${escape(item.name)}
                                        </div>

                                        ${item.description
                                ? `
                                                <small class="text-muted d-block text-truncate">
                                                    ${escape(item.description)}
                                                </small>
                                            `
                                : ''
                            }
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-primary-subtle text-primary fw-semibold">
                                            ₱${parseFloat(item.price || 0).toLocaleString()}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        `;
                    },

                    item: function (item, escape) {

                        return `
                            <div class="d-inline-flex align-items-center">

                                <span class="fw-medium">
                                    ${escape(item.name)}
                                </span>

                                <span class="ms-2 badge rounded-pill bg-light text-dark border">
                                    ₱${parseFloat(item.price || 0).toLocaleString()}
                                </span>

                            </div>
                        `;
                    }
                },
                onChange: function updateServicesTotal() {
                    let total = 0;
                    const values = serviceSelect.getValue();
                    values.forEach(id => {
                        const service = serviceSelect.options[id];
                        if (service) {
                            total += parseFloat(service.price || 0);
                        }
                    });

                    $('#servicesTotal').val(
                        total.toLocaleString(undefined, {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        })
                    );
                }
            })[0].selectize;
        });
    }

    populateSelect(
        App.endpoint('admin/patients/list'),
        'select[name="patient_id"]',
        'Select Patient'
    );

    populateSelect(
        App.endpoint('admin/dentists/list'),
        'select[name="dentist_id"]',
        'Select Dentist'
    );

    loadServices();
});
$(document).ready(function () {

    let selectedEventId = null;
    let serviceSelect = null;

    const $modal = $('#addAppointmentModal');
    const $form = $('#appointmentForm');

    const calendarEl = document.getElementById('calendar');

    // =========================
    // WIZARD STATE
    // =========================
    let currentStep = 1;
    const maxStep = 4;

    function showStep(step) {

        currentStep = step;

        $('.wizard-page').addClass('d-none');
        $(`.wizard-page[data-page="${step}"]`).removeClass('d-none');

        $('.wizard-step').removeClass('active completed');

        for (let i = 1; i <= maxStep; i++) {
            const $step = $(`.wizard-step[data-step="${i}"]`);

            if (i < step) {
                $step.addClass('completed');
            }

            if (i === step) {
                $step.addClass('active');
            }
        }

        $('#wizardProgress').css('width', ((step / maxStep) * 100) + '%');

        $('#btnPrevStep').toggle(step > 1);

        if (step === maxStep) {
            $('#btnNextStep').addClass('d-none');
            $('#btnSaveAppointment').removeClass('d-none');

            generateReview();
        } else {
            $('#btnNextStep').removeClass('d-none');
            $('#btnSaveAppointment').addClass('d-none');
        }
    }

    function validateStep(step) {

        if (step === 1) {

            if (!$('select[name="patient_id"]').val()) {
                alertify.error('Please select a patient');
                return false;
            }

            if (!$('select[name="dentist_id"]').val()) {
                alertify.error('Please select a dentist');
                return false;
            }
        }

        if (step === 2) {

            const date = $('input[name="appointment_date"]').val();
            const start = $('input[name="start_time"]').val();
            const end = $('input[name="end_time"]').val();

            if (!date || !start || !end) {
                alertify.error('Please complete schedule details');
                return false;
            }

            const startTime = new Date(`1970-01-01T${start}:00`);
            const endTime = new Date(`1970-01-01T${end}:00`);

            if (startTime >= endTime) {
                alertify.error('End time must be greater than start time');
                return false;
            }
        }

        if (step === 2) {
            if (!serviceSelect || serviceSelect.getValue().length === 0) {
                alertify.error('Please select at least one service');
                return false;
            }
        }

        return true;
    }

    function generateReview() {
        const patient = $('select[name="patient_id"] option:selected').text();
        const dentist = $('select[name="dentist_id"] option:selected').text();
        const date = $('input[name="appointment_date"]').val();
        const start = $('input[name="start_time"]').val();
        const end = $('input[name="end_time"]').val();
        const reason = $('textarea[name="reason"]').val();
        const total = $('#servicesTotal').val();

        const services = serviceSelect
            ? serviceSelect.getValue().map(id => serviceSelect.options[id]?.name).join(', ')
            : '';

        $('#appointmentReview .card-body').html(`

            <div class="mb-3">
                <div class="text-muted small mb-2">Patient Information</div>
                <div class="fw-semibold">
                    <i class="bi bi-person-circle me-1"></i> ${patient}
                </div>
            </div>

            <div class="mb-3">
                <div class="text-muted small mb-2">Assigned Dentist</div>
                <div class="fw-semibold">
                    <i class="bi bi-person-badge me-1"></i> ${dentist}
                </div>
            </div>

            <hr>

            <div class="row g-3">

                <div class="col-md-6">
                    <div class="text-muted small">Schedule</div>
                    <div class="fw-semibold">
                        <i class="bi bi-calendar-event me-1"></i> ${date}
                    </div>
                    <div class="fw-semibold">
                        <i class="bi bi-clock me-1"></i> ${start} - ${end}
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="text-muted small">Services</div>
                    <div class="fw-semibold">
                        <i class="bi bi-clipboard2-pulse me-1"></i>
                        ${services || 'No services selected'}
                    </div>
                </div>

            </div>

            <hr>

            <div class="mb-3">
                <div class="text-muted small">Reason</div>
                <div class="fw-semibold">
                    ${reason || '<span class="text-muted">No reason provided</span>'}
                </div>
            </div>

            <div class="p-3 rounded bg-light border d-flex justify-content-between align-items-center">
                <span class="text-muted">Total Amount</span>
                <span class="fs-5 fw-bold text-primary">₱ ${total}</span>
            </div>

        `);
    }

    function resetForm() {

        selectedEventId = null;
        currentStep = 1;

        $form[0].reset();

        if (serviceSelect) {
            serviceSelect.clear();
        }

        $('#servicesTotal').val('0.00');
        $('#appointmentActions').html('');
        $('#btnSaveAppointment')
            .text('Save Appointment')
            .removeClass('btn-warning')
            .addClass('btn-primary');

        showStep(1);
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

    function renderStatus(status) {
        const map = {
            pending: 'chip-pending',
            confirmed: 'chip-confirmed',
            completed: 'chip-completed',
            cancelled: 'chip-cancelled'
        };

        const icons = {
            pending: 'bi-clock-history',
            confirmed: 'bi-check2-circle',
            completed: 'bi-flag',
            cancelled: 'bi-x-circle'
        };

        $('#appointmentStatusBadge')
            .removeClass()
            .addClass(`status-chip ${map[status] || 'chip-pending'}`)
            .html(`
                <span class="dot"></span>
                <i class="bi ${icons[status] || 'bi-circle'}"></i>
                ${status.charAt(0).toUpperCase() + status.slice(1)}
            `);
    }

    function renderPaymentStatus(status) {
        const map = {
            unpaid: 'chip-unpaid',
            partial: 'chip-partial',
            paid: 'chip-paid'
        };

        const icons = {
            unpaid: 'bi-exclamation-circle',
            partial: 'bi-hourglass-split',
            paid: 'bi-cash-coin'
        };

        $('#paymentStatusBadge')
            .removeClass()
            .addClass(`payment-chip ${map[status] || 'chip-unpaid'}`)
            .html(`
                <span class="dot"></span>
                <i class="bi ${icons[status] || 'bi-exclamation-circle'}"></i>
                ${status.charAt(0).toUpperCase() + status.slice(1)}
            `);
    }

    function populateSelect(url, selector, placeholder) {

        $.get(url)
            .done(function (data) {

                let options = `<option value="">${placeholder}</option>`;

                data.forEach(item => {
                    options += `<option value="${item.id}">${item.name}</option>`;
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

    // =========================
    // CALENDAR
    // =========================
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

        events: function (fetchInfo, successCallback, failureCallback) {
            $.ajax({
                url: App.endpoint('admin/appointments/list'),
                method: 'POST',
                data: {
                    start: fetchInfo.startStr,
                    end: fetchInfo.endStr
                },
                success: function (response) {
                    successCallback(response.events);
                    $.each(response.tally, (a, b) => {
                        $(`h3#${a}`).html(b);
                    });
                },
                error: function () {
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
            return [getStatusClass(arg.event.extendedProps.status)];
        }
    });

    calendar.render();

    // =========================
    // NAVIGATION
    // =========================
    $('#btnNextStep').on('click', function () {
        if (validateStep(currentStep)) {
            showStep(currentStep + 1);
        }
    });

    $('#btnPrevStep').on('click', function () {
        showStep(currentStep - 1);
    });

    // =========================
    // ACTIONS
    // =========================
    $('#btnToday').on('click', function () {
        calendar.today();
    });

    $('#btnNewAppointment').on('click', function () {
        resetForm();
        $modal.modal('show');
    });

    // =========================
    // SAVE
    // =========================
    $('#btnSaveAppointment').on('click', function () {
        const btn = $(this);

        if (!validateStep(2)) return;

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
                ? App.endpoint('admin/appointments/update')
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
                alertify.error(xhr.responseJSON?.error || 'Failed to save appointment');
            },

            complete() {
                btn.prop('disabled', false);
            }
        });
    });

    // =========================
    // OPEN EVENT
    // =========================
    function openAppointmentModal(event) {
        resetForm();

        selectedEventId = event.id;

        $('select[name="patient_id"]').val(event.extendedProps.patient_id);
        $('select[name="dentist_id"]').val(event.extendedProps.dentist_id);

        if (serviceSelect) {
            const serviceIds = (event.extendedProps.services || []).map(s => s.id);
            serviceSelect.setValue(serviceIds);
        }

        const start = event.start;
        const end = event.end;

        $('input[name="appointment_date"]').val(start.toISOString().split('T')[0]);
        $('input[name="start_time"]').val(formatTime(start));
        $('input[name="end_time"]').val(formatTime(end));

        $('textarea[name="reason"]').val(event.extendedProps.reason);
        let paymentAmount = event.extendedProps.payment_amount ?? $("#servicesTotal").val() ?? 0;
        $('#paymentAmount').val(paymentAmount);

        renderStatus(event.extendedProps.status);
        renderPaymentStatus(event.extendedProps.payment_status);
        renderAppointmentActions(
            event.extendedProps.status,
            event.extendedProps.payment_status
        );

        $('#btnSaveAppointment')
            .text('Update Appointment')
            .removeClass('btn-primary')
            .addClass('btn-warning');

        showStep(4);
        $modal.modal('show');
    }

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
                alertify.error(xhr.responseJSON?.error || 'Failed to update appointment');
            }
        });
    }

    // =========================
    // SERVICES
    // =========================
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
                                            Php ${parseFloat(item.price || 0).toLocaleString()}
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
                                    Php ${parseFloat(item.price || 0).toLocaleString()}
                                </span>

                            </div>
                        `;
                    }
                },
                onChange: function () {
                    let total = 0;
                    const values = serviceSelect.getValue();
                    values.forEach(id => {
                        const service = serviceSelect.options[id];
                        if (service) {
                            total += parseFloat(service.price || 0);
                        }
                    });

                    const formatted = total.toLocaleString(undefined, {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });

                    $('#servicesTotal').val(formatted);
                    $('#paymentAmount').val(formatted);
                }
            })[0].selectize;
        });
    }

    function renderAppointmentActions(status, paymentStatus) {
        let html = '';

        switch (status) {
            // =====================
            // PENDING
            // =====================
            case 'pending':
                html = `
                    <div class="d-flex flex-wrap gap-2">
                        <button type="button" class="btn btn-outline-success" id="btnConfirmAppointment">
                            <i class="bi bi-cash-coin me-1"></i>
                            Mark as Confirmed
                        </button>

                        <button type="button" class="btn btn-outline-success" id="btnPayAppointment">
                            <i class="bi bi-cash-coin me-1"></i>
                            Mark as Paid
                        </button>

                        <button type="button" class="btn btn-outline-danger" id="btnCancelAppointment">
                            <i class="bi bi-x-circle me-1"></i>
                            Cancel Appointment
                        </button>

                    </div>
                `;
                break;

            // =====================
            // CONFIRMED
            // =====================
            case 'confirmed':
                html = `
                    <div class="d-flex flex-wrap gap-2">

                        <button type="button" class="btn btn-success" id="btnCompleteAppointment">
                            <i class="bi bi-check-circle me-1"></i>
                            Mark as Completed
                        </button>

                        <button type="button" class="btn btn-outline-danger" id="btnCancelAppointment">
                            <i class="bi bi-x-circle me-1"></i>
                            Cancel Appointment
                        </button>

                    </div>
                `;
                break;

            // =====================
            // CANCELLED
            // =====================
            case 'cancelled':
                html = `
                    <div class="d-flex flex-wrap gap-2">

                        <button type="button" class="btn btn-warning" id="btnRevertAppointment">
                            <i class="bi bi-arrow-counterclockwise me-1"></i>
                            Revert to Pending
                        </button>

                    </div>
                `;
                break;
        }

        $('#appointmentActions').html(html);
    }
    
    function confirmAndUpdate(title, message, status) {
        alertify.confirm(title, message,
            function () {
                $.post(App.endpoint('admin/appointments/update_status'), {
                    id: selectedEventId,
                    status: status
                }).done(response => {
                    alertify.success(response.message);
                    calendar.refetchEvents();
                    $modal.modal('hide');
                }).fail(xhr => {
                    alertify.error(xhr.responseJSON?.error || 'Failed to update status');
                });
            },
            function () {}
        );
    }

    function updatePaymentStatus(status) {
        $.ajax({
            url: App.endpoint('admin/appointments/update_payment'),
            type: 'POST',
            data: {
                id: selectedEventId,
                status: 'completed',
                payment_status: status
            },
            success: function (response) {
                alertify.success(response.message);
                location.reload();
            },
            error: function () {
                alertify.error('Failed to update payment status');
            }
        });
    }

    $(document).on('click', '#btnConfirmAppointment', function () {
        confirmAndUpdate('Confirm Appointment', 'Confirm this appointment?', 'confirmed');
    });

    $(document).on('click', '#btnPayAppointment', function () {
        alertify.confirm(
            'Payment Confirmation',
            `Marking this transaction as paid will update this transaction to completed and paid.<br/>
            Continue?`,
            function () {
                updatePaymentStatus('paid');
            },
            function () {}
        );
    });

    $(document).on('click', '#btnCancelAppointment', function () {
        confirmAndUpdate('Cancel Appointment', 'Are you sure you want to cancel?', 'cancelled');
    });

    $(document).on('click', '#btnCompleteAppointment', function () {
        confirmAndUpdate('Complete Appointment', 'Mark as completed?', 'completed');
    });

    $(document).on('click', '#btnRevertAppointment', function () {
        confirmAndUpdate('Revert Appointment', 'Revert to pending status?', 'pending');
    });

    // =========================
    // INIT
    // =========================
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
    showStep(1);
});
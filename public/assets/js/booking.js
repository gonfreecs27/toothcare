$(document).ready(function () {
    let currentStep = 1;
    const totalSteps = 5;

    const $steps = $('.wizard-step');
    const $progress = $('#wizardProgress');
    const $form = $('#appointmentWizard');

    function showStep(step) {
        currentStep = step;

        $steps.addClass('d-none');
        $(`[data-step="${step}"]`).removeClass('d-none');

        $('#btnPrev').toggleClass('d-none', step === 1);
        $('#btnNext').toggleClass('d-none', step === totalSteps);
        $('#btnSubmit').toggleClass('d-none', step !== totalSteps);

        updateProgress();
        updateSummary();
    }

    function updateProgress() {
        const percent = ((currentStep - 1) / (totalSteps - 1)) * 100;
        $progress.css('width', percent + '%');
    }

    function getServices() {
        let services = [];

        $('input[name="services[]"]:checked').each(function () {
            const label = $(this).closest('.service-card');

            services.push({
                name: label.find('strong').text().trim(),
                price: parseFloat(label.data('price')) || 0
            });
        });

        return services;
    }

    function getDentist() {
        const el = $('input[name="dentist_id"]:checked');
        return el.length ? el.closest('.dentist-card').find('h6').text().trim() : '';
    }

    function updateSummary() {
        const services = getServices();
        const dentist = getDentist();

        const date = $('#appointmentDate').val();
        const start = $('#startTime').val();
        const end = $('#endTime').val();

        let total = 0;
        services.forEach(s => total += s.price);

        let serviceHtml = services.length
            ? services.map(s => `${s.name} - ₱${s.price.toFixed(2)}`).join('<br>')
            : '-';

        const firstname = $('input[name="firstname"]').val() || '';
        const lastname = $('input[name="lastname"]').val() || '';

        $('#summaryBox').html(`
            <strong>Services:</strong><br>${serviceHtml}<br><br>

            <strong>Dentist:</strong><br>${dentist || '-'}<br><br>

            <strong>Schedule:</strong><br>
            ${date || '-'} <br>
            ${start || '-'} → ${end || '-'}<br><br>

            <strong>Patient:</strong><br>
            ${firstname + ' ' + lastname || '-'}
            <br><br>

            <strong>Total:</strong><br>
            ₱${total.toFixed(2)}
        `);
    }

    function timeToMinutes(t) {
        if (!t) return 0;
        const [h, m] = t.split(':').map(Number);
        return (h * 60) + m;
    }

    function validateStep() {

        if (currentStep === 1) {
            return $('input[name="services[]"]:checked').length > 0;
        }

        if (currentStep === 2) {
            return $('input[name="dentist_id"]:checked').length > 0;
        }

        if (currentStep === 3) {
            const date = $('#appointmentDate').val();
            const start = $('#startTime').val();
            const end = $('#endTime').val();

            if (!date || !start || !end) return false;

            const startMin = timeToMinutes(start);
            const endMin = timeToMinutes(end);

            if (endMin <= startMin) {
                alertify.error('End time must be later than start time.');
                return false;
            }

            return true;
        }

        if (currentStep === 4) {
            return (
                $('input[name="firstname"]').val().trim() &&
                $('input[name="lastname"]').val().trim() &&
                $('input[name="contact"]').val().trim() &&
                $('input[name="email"]').val().trim()
            );
        }

        return true;
    }

    $('#btnNext').on('click', function () {

        if (!validateStep()) {
            alertify.error('Please complete this step before continuing.');
            return;
        }

        if (currentStep < totalSteps) {
            showStep(currentStep + 1);
        }
    });

    $('#btnPrev').on('click', function () {
        if (currentStep > 1) {
            showStep(currentStep - 1);
        }
    });

    /**
     * TIME RANGE RULE: 9AM - 5PM ONLY
     */
    function generateTimeOptions() {
        const startMinutes = 9 * 60;   // 09:00
        const endMinutes = 17 * 60;    // 17:00
        const interval = 30;

        let options = '<option value="">Select time</option>';

        for (let mins = startMinutes; mins <= endMinutes - interval; mins += interval) {

            const h = String(Math.floor(mins / 60)).padStart(2, '0');
            const m = String(mins % 60).padStart(2, '0');

            const time = `${h}:${m}`;

            options += `
                <option value="${time}">
                    ${formatTime(time)}
                </option>
            `;
        }

        $('#startTime').html(options);
    }

    function formatTime(time) {
        let [h, m] = time.split(':');
        h = parseInt(h);

        const ampm = h >= 12 ? 'PM' : 'AM';
        h = h % 12 || 12;

        return `${h}:${m} ${ampm}`;
    }

    $('#appointmentDate').on('change', function () {

        const d = new Date($(this).val());

        if (d.getDay() === 0) {
            alertify.error('Clinic is closed on Sundays');
            $(this).val('');
            return;
        }

        generateTimeOptions();
        updateSummary();
    });

    $('#startTime').on('change', function () {
        const start = $(this).val();
        if (!start) return;

        const startMin = timeToMinutes(start);
        const endMinutes = 17 * 60;
        const interval = 30;

        let options = '<option value="">Select end time</option>';

        for (let mins = startMin + interval; mins <= endMinutes; mins += interval) {

            const h = String(Math.floor(mins / 60)).padStart(2, '0');
            const m = String(mins % 60).padStart(2, '0');

            const time = `${h}:${m}`;

            options += `
                <option value="${time}">
                    ${formatTime(time)}
                </option>
            `;
        }

        $('#endTime').html(options);
    });

    $(document).on('input change', 'input, select, textarea', updateSummary);

    $('#appointmentWizard').on('submit', function (e) {
        e.preventDefault();

        if (!validateStep()) {
            alertify.error('Please complete all required fields.');
            return;
        }

        $.ajax({
            url: App.endpoint('admin/appointments/book'),
            type: 'POST',
            data: $(this).serialize(),
            success: function () {
                // hide form
                $form.hide();

                // show success UI
                $('.booking-form').html(`
                    <div class="text-center py-5" id="successBox">
                        <i class="bi bi-check-circle-fill text-success display-1"></i>
                        <h2 class="mt-3">Booking Successful!</h2>
                        <p class="text-muted">Your appointment has been recorded.</p>

                        <a href="${App.config.baseUrl}" class="btn btn-primary mt-3">
                            Go back to Home
                        </a>
                    </div>
                `);
            },
            error: function () {
                alertify.error('Failed to book appointment.');
            }
        });
    });

    showStep(1);

    const calendarEl = document.getElementById('calendar');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 'auto',
        selectable: false,
        editable: false,
        nowIndicator: false,
        headerToolbar: {
            left: 'title',
            center: '',
            right: ''
        },
        eventDidMount: function(info) {
            const event = info.event;

            const content = `
                <b>${event.title}</b><br>
                Time: ${event.start.toLocaleString()}<br>
                Status: ${event.extendedProps.status ?? 'N/A'}
            `;

            new bootstrap.Tooltip(info.el, {
                title: content,
                html: true,
                placement: 'top',
                trigger: 'hover',
                container: 'body'
            });
        },

        events: function (fetchInfo, successCallback, failureCallback) {
            $.ajax({
                url: App.endpoint('admin/appointments/list-public'),
                method: 'POST',
                data: {
                    start: fetchInfo.startStr,
                    end: fetchInfo.endStr
                },
                success: function (response) {
                    successCallback(response.data.events);
                },
                error: function () {
                    failureCallback();
                    alertify.error('Failed to load appointments');
                }
            });
        },
    });

    calendar.render();
});
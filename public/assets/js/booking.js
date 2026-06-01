$(document).ready(function () {
    let currentStep = 1;

    function showStep(step) {
        $('.wizard-step').addClass('d-none');
        $('[data-step="' + step + '"]').removeClass('d-none');

        $('#btnPrev').toggleClass('d-none', step === 1);
        $('#btnNext').toggleClass('d-none', step === 5);
        $('#btnSubmit').toggleClass('d-none', step !== 5);

        updateProgress();
        updateSummary();
    }

    function updateProgress() {
        let percent = (currentStep / 5) * 100;
        $('#wizardProgress').css('width', percent + '%');
    }

    function updateSummary() {
        let services = [];
        $('input[name="services[]"]:checked').each(function () {
            services.push($(this).closest('.service-card').text().trim());
        });

        let dentist = $('input[name="dentist_id"]:checked').closest('.dentist-card').text().trim();
        let date = $('#appointmentDate').val();
        let time = $('#appointmentTime').val();

        $('#summaryBox').html(`
        <strong>Services:</strong><br>${services.join('<br>')}<br><br>
        <strong>Dentist:</strong><br>${dentist || '-'}<br><br>
        <strong>Date:</strong> ${date || '-'}<br>
        <strong>Time:</strong> ${time || '-'}
    `);
    }

    function validateStep() {
        if (currentStep === 1)
            return $('input[name="services[]"]:checked').length;

        if (currentStep === 2)
            return $('input[name="dentist_id"]:checked').length;

        if (currentStep === 3)
            return $('#appointmentDate').val() && $('#appointmentTime').val();

        if (currentStep === 4)
            return $('input[name="firstname"]').val() && $('input[name="lastname"]').val();

        return true;
    }

    $('#btnNext').click(function () {
        if (!validateStep()) {
            alert('Please complete this step.');
            return;
        }

        currentStep++;
        showStep(currentStep);
    });

    $('#btnPrev').click(function () {
        currentStep--;
        showStep(currentStep);
    });

    $('#appointmentDate').on('change', function () {

        let d = new Date($(this).val());

        if (d.getDay() === 0) {
            alert('Clinic is closed on Sundays');
            $(this).val('');
            return;
        }

        // demo slots
        $('#appointmentTime').html(`
        <option>09:00 AM</option>
        <option>10:00 AM</option>
        <option>11:00 AM</option>
        <option>01:00 PM</option>
        <option>02:00 PM</option>
    `);

    });

    $(document).on('change input', 'input,select,textarea', updateSummary);

    $('#appointmentWizard').on('submit', function (e) {
        e.preventDefault();

        alert('Appointment submitted (connect to backend endpoint)');
    });

    showStep(1);
});
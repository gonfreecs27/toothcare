$(document).ready(function () {
    let table = $('#feedbackTable').DataTable({
        responsive: true,
        pageLength: 10
    });

    const feedbackModal = new bootstrap.Modal(
        document.getElementById('feedbackModal')
    );

    // View Feedback
    $(document).on('click', '.btn-view', function () {
        let rating = '';

        for (let i = 1; i <= 5; i++) {
            rating += i <= $(this).data('rating')
                ? '<i class="bi bi-star-fill text-warning"></i>'
                : '<i class="bi bi-star text-muted"></i>';
        }

        $('#feedbackName').text($(this).data('name'));
        $('#feedbackEmail').text($(this).data('email') || '-');
        $('#feedbackRating').html(rating);
        $('#feedbackDate').text($(this).data('date'));
        $('#feedbackMessage').text($(this).data('message'));

        feedbackModal.show();
    });

    // Approve
    $(document).on('click', '.btn-approve', function () {
        let id = $(this).data('id');
        alertify.confirm(
            'Approve Feedback',
            'Approve this feedback?',
            function () {
                $.post(
                    App.api('feedbacks/approve'),
                    { id: id },
                    function (res) {
                        if (res.success) {
                            alertify.success(res.message);
                            setTimeout(function () {
                                location.reload();
                            }, 500);
                        } else {
                            alertify.error(res.message);
                        }
                    },
                    'json'
                );
            },
            function () { }
        );
    });

    // Reject
    $(document).on('click', '.btn-reject', function () {
        let id = $(this).data('id');
        alertify.confirm(
            'Reject Feedback',
            'Reject this feedback?',
            function () {
                $.post(
                    App.api('feedbacks/reject'),
                    { id: id },
                    function (res) {
                        if (res.success) {
                            alertify.success(res.message);
                            setTimeout(function () {
                                location.reload();
                            }, 500);
                        } else {
                            alertify.error(res.message);
                        }
                    },
                    'json'
                );
            },
            function () { }
        );
    });

    // Feature
    $(document).on('click', '.btn-feature', function () {
        let id = $(this).data('id');
        $.post(
            App.api('feedbacks/feature'),
            { id: id },
            function (res) {
                if (res.success) {
                    alertify.success(res.message);
                    setTimeout(function () {
                        location.reload();
                    }, 500);
                } else {
                    alertify.error(res.message);
                }
            },
            'json'
        );
    });
});
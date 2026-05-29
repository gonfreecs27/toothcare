<div class="footer text-center">
    © <?= date('Y') ?> ToothCare Dental Clinic System
</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
<script>
    $(document).ready(function() {
        const $sidebar = $('.sidebar');
        const $overlay = $('#sidebarOverlay');
        const $toggleBtn = $('#menuToggle');

        $toggleBtn.on('click', function() {
            $sidebar.toggleClass('show');
            $overlay.toggleClass('show');
        });

        $overlay.on('click', function() {
            $sidebar.removeClass('show');
            $overlay.removeClass('show');
        });
    });
</script>
</body>

</html>
<script>
    $(document).ready(function() {
        const title_id = '{{ $titleChecklists->id }}';
        const percentage = '{{ $titleChecklists->percentage }}';
        progressBar(title_id, percentage);

        function progressBar(id, percentage) {
            var progressBar = $('.progress-bar-' + id);
            progressBar.css('width', percentage + '%');
            progressBar.attr('aria-valuenow', percentage);
            progressBar.text(Math.round(percentage) + '%');
            progressBar.removeClass('bg-danger bg-warning bg-info bg-success');
            if (percentage <= 25) {
                progressBar.addClass('bg-danger');
            } else if (percentage > 25 && percentage < 50) {
                progressBar.addClass('bg-warning');
            } else if (percentage >= 50 && percentage <= 75) {
                progressBar.addClass('bg-info');
            } else if (percentage > 75) {
                progressBar.addClass('bg-success');
            }
        }
    });
</script>

<script>
    $(document).ready(function() {
        const title_id = '{{ $titleChecklists->id }}';

        // Tandai untuk mencegah pengiriman berulang kali
        let isSubmitting = false;

        $('#checklistform-all-' + title_id).on('change', function() {
            event.preventDefault();

            // Mencegah pengiriman ganda
            if (isSubmitting) return;

            isSubmitting = true;
            var checklistId = $(this).closest('form').find('input[name="title_checklists_id"]').val();
            var isChecked = $(this).is(':checked');
            var toastBerhasil = isChecked ? 'Berhasil centang semua checklist!' :
                'Berhasil tidak centang semua checklist!';

            $.ajax({
                url: '/perbaharui/semua/checklist',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    title_checklists_id: checklistId,
                    is_active: isChecked ? 1 : 0
                },
                success: function(response) {
                    toastr.success(toastBerhasil);
                    progressBar(response.titlechecklist.id, response.titlechecklist
                        .percentage);
                    updateCheckboxes(response.checklist);

                    // Untuk Mengatur Icon Checklist //
                    $('#iconChecklist-' + response.titlechecklist.cards_id).removeClass(
                        'hidden');
                    $('#perhitunganChecklist-' + response.titlechecklist.cards_id).html(
                        response.perChecklist + '/' + response.jumlahChecklist);

                    if (response.perChecklist < response.jumlahChecklist) {
                        var tema_aplikasi = response.result_tema.tema_aplikasi;
                        var cardId = response.titlechecklist.cards_id;
                        var iconChecklist = $('#iconChecklist-' + cardId);
                        var iconChecklistCheck = $('#icon-checklist-' + cardId);

                        if (tema_aplikasi == 'Terang') {
                            iconChecklist.removeClass('progress-checklist-100-light')
                                .removeClass('progress-checklist-100-dark');
                            iconChecklist.addClass('progress-checklist-light').removeClass(
                                'progress-checklist-dark');
                            iconChecklistCheck.addClass('icon-check-not-full-light')
                                .removeClass('icon-check-not-full-dark');
                            iconChecklistCheck.removeClass('icon-check-full-light')
                                .removeClass('icon-check-full-dark');

                        } else if (tema_aplikasi == 'Gelap') {
                            iconChecklist.removeClass('progress-checklist-100-dark')
                                .removeClass('progress-checklist-100-light');
                            iconChecklist.addClass('progress-checklist-dark').removeClass(
                                'progress-checklist-light');
                            iconChecklistCheck.addClass('icon-check-not-full-dark')
                                .removeClass('icon-check-not-full-light');
                            iconChecklistCheck.removeClass('icon-check-full-dark')
                                .removeClass('icon-check-full-light');
                        }
                    } else if (response.perChecklist == response.jumlahChecklist) {
                        var tema_aplikasi = response.result_tema.tema_aplikasi;
                        var cardId = response.titlechecklist.cards_id;
                        var iconChecklist = $('#iconChecklist-' + cardId);
                        var iconChecklistCheck = $('#icon-checklist-' + cardId);

                        if (tema_aplikasi == 'Terang') {
                            iconChecklist.addClass('progress-checklist-100-light')
                                .removeClass('progress-checklist-100-dark');
                            iconChecklist.addClass('progress-checklist-light').removeClass(
                                'progress-checklist-dark');
                            iconChecklistCheck.removeClass('icon-check-not-full-light')
                                .removeClass('icon-check-not-full-dark');
                            iconChecklistCheck.addClass('icon-check-full-light')
                                .removeClass('icon-check-full-dark');

                        } else if (tema_aplikasi == 'Gelap') {
                            iconChecklist.addClass('progress-checklist-100-dark')
                                .removeClass('progress-checklist-100-light');
                            iconChecklist.addClass('progress-checklist-dark').removeClass(
                                'progress-checklist-light');
                            iconChecklistCheck.removeClass('icon-check-not-full-dark')
                                .removeClass('icon-check-not-full-light');
                            iconChecklistCheck.addClass('icon-check-full-dark').removeClass(
                                'icon-check-full-light');
                        }
                    }
                    // /Untuk Mengatur Icon Checklist //

                    // Setel ulang tanda
                    isSubmitting = false;
                },
                error: function(xhr) {
                    toastr.error('Gagal memperbarui checklist!');

                    // Setel ulang tanda
                    isSubmitting = false;
                }
            });
        });

        function updateCheckboxes(checklists) {
            checklists.forEach(function(checklist) {
                var checkbox = $('#' + checklist.id);
                checkbox.prop('checked', checklist.is_active == 1);
                var label = $('#labelCheckbox-' + checklist.id);
                if (checklist.is_active == 1) {
                    label.addClass('strike-through');
                } else {
                    label.removeClass('strike-through');
                }
            });
        }
    });
</script>

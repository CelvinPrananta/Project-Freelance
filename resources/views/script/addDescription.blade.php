<script>
    $(document).ready(function() {
        const id = '{{ $isianKartu->id }}';
        // Section Keterangan

        // Set height Keterangan
        const keterangan = $('#keterangan' + id).val();
        const lineCount = keterangan.split('\n').length;
        const desiredRows = lineCount + 2;
        $('#keterangan' + id).attr('rows', Math.max(desiredRows, 4));

        // Tandai untuk mencegah pengiriman berulang kali
        let isSubmitting = false;

        // Input keterangan
        $('#keterangan' + id).on('click', function() {
            $('#saveButton' + id).removeClass('hidden');
            $('#cancelButton' + id).removeClass('hidden');
        });

        // Button cancel form keterangan
        $('#cancelButton' + id).on('click', function() {
            $('#saveButton' + id).addClass('hidden');
            $('#cancelButton' + id).addClass('hidden');
            $('#myForm' + id)[0].reset();
        });

        // Cegah pengiriman formulir dengan tombol Enter untuk elemen non-textarea
        $('#myForm' + id).on('keydown', function(event) {
            if (event.key === 'Enter' && !$(event.target).is('textarea')) {

                // Mencegah pengiriman formulir default
                event.preventDefault();
            }
        });

        // Menangani tombol Enter untuk textarea (masukkan baris baru)
        $('#myForm' + id + ' textarea').on('keydown', function(event) {
            if (event.key === 'Enter') {

                // Mencegah pengiriman formulir default
                event.preventDefault();
                var textarea = $(this).get(0);
                var cursorPos = textarea.selectionStart;
                var textBefore = textarea.value.substring(0, cursorPos);
                var textAfter = textarea.value.substring(cursorPos);
                textarea.value = textBefore + '\n' + textAfter;

                // Pindahkan kursor ke baris baru
                textarea.selectionStart = textarea.selectionEnd = cursorPos + 1;
            }
        });

        // Form keterangan
        $('#myForm' + id).on('submit', function(event) {
            event.preventDefault();

            // Mencegah pengiriman ganda
            if (isSubmitting) return;

            isSubmitting = true;
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: "{{ route('addDescription') }}",
                data: formData,
                success: function(response) {
                    $('#saveButton' + id).addClass('hidden');
                    $('#cancelButton' + id).addClass('hidden');
                    toastr.success('Berhasil memperbaharui keterangan!');
                    // Perbaharui Status Keterangan
                    if (response.status_keterangan == 'hide') {
                        $('#descriptionStatus' + id).addClass('hidden');
                    } else {
                        $('#descriptionStatus' + id).removeClass('hidden');
                    }

                    // Setel ulang tanda
                    isSubmitting = false;
                },
                error: function(error) {
                    toastr.error('Gagal memperbaharui keterangan!');

                    // Setel ulang tanda
                    isSubmitting = false;
                }
            });
        });
        // End Section Keterangan

    });
</script>

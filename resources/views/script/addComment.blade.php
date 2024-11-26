<script>
    $(document).ready(function() {
        const formId = '#commentForm{{ $isianKartu->id }}';
        const contentAreaId = '#contentarea{{ $isianKartu->id }}';
        const saveButtonId = '#simpanButton{{ $isianKartu->id }}';
        const isianTagId = '#isianTag{{ $isianKartu->id }}';

        $(contentAreaId).on('input', function() {
            if ($(this).val().trim() !== '') {
                $(saveButtonId).removeClass('hidden');
            } else {
                $(saveButtonId).addClass('hidden');
            }
        });
        $(contentAreaId).on('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                $(saveButtonId).click();
            }
        });
        $(formId).on('submit', function(e) {
            e.preventDefault();
            let form = $(this);
            let url = form.attr('action');
            let data = form.serialize();

            $.ajax({
                type: "POST",
                url: url,
                data: data,
                success: function(response) {
                    let newComment = `
                    <div class="isian-tag" id="showActivity{{ $isianKartu->id }}">
                        <div class="isian-history">
                                        <img class="avatar-activity" src="{{ URL::to('/assets/images/' . Auth::user()->avatar) }}" loading="lazy">
                                        <div class="title-activity">
                                            <p class="isian-activity">${response.name}<br><span>${response.content}</span></p>
                                        </div>
                                      </div>
                                      <div class="waktu-history">
                                        <p class="isian-activity"><i class="fa-solid fa-clock" style="color: #808080;" aria-hidden="true"></i> ${response.created_at}</p>
                                      </div>
                                      </div>`;
                    toastr.success('Berhasil memberikan komentar!');
                    $('.activity-tag').prepend(newComment);
                    $(contentAreaId).val('');
                    $(saveButtonId).addClass('hidden');
                },
                error: function(error) {
                    toastr.error('Gagal memberikan komentar!');
                }
            });
        });
    });
</script>

function updateColumnModal(columnId, columnName, formAction) {
    $('#update-column-id').val(columnId);
    $('#update-column-name').val(columnName);
    $('#updateColumnForm').attr('action', formAction);
    $('#column-name-error').text('');
    $('#update-column-name').removeClass('is-invalid');
    $('#updateColumn').modal('show');
}

function updateCardModal(cardId, cardName, formAction) {
    // alert('test');
    $('#update-card-id').val(cardId);
    $('#update-card-name').val(cardName);
    $('#updateCardForm').attr('action', formAction);
    $('#card-name-error').text('');
    $('#update-card-name').removeClass('is-invalid');
    $('#updateCard').modal('show');
}

function deleteColumnModal(columnId, columnName, formAction) {
    $('#columnName').text(columnName);
    $('#column-id').val(columnId);
    $('#deleteColumnForm').attr('action', formAction);
    $('#deleteColumn').modal('show');
}

function deleteCardModal(cardId, cardName, columnName, formAction) {
    $('#columnName2').text(columnName);
    $('#cardName2').text(cardName);
    $('#card-id').val(cardId);
    $('#deleteCardForm').attr('action', formAction);
    $('#isianKartu').modal('hide').one('hidden.bs.modal', function () {
        $('#deleteCard').modal('show');
    });
}

function deleteCardModal2(cardId, cardName, columnName, formAction) {
    // alert('test');
    $('#columnName3').text(columnName);
    $('#cardName3').text(cardName);
    $('#card-id').val(cardId);
    $('#deleteCardForm2').attr('action', formAction);
    $('#deleteCard2').modal('show');
}
// #span-nama
    // $(document).ready(function () {
    //     $(document).on('click', '.copy-card', function(e) {
    //         e.preventDefault();
    //         var id = $(this).data('id');
    //         var kartuName = $(this).data('kartuname');
    //         var test = $('#span-nama-' +id).html();
    //         alert(test);
    //     });
    // });
function copyCardModal(cardId, formAction) {
    var cardName = $('#span-nama-' +cardId).html();
    $('#copy-card-id').val(cardId);
    $('#copy-card-name').val(cardName).attr('placeholder', cardName);
    $('#copyCardForm').attr('action', formAction);
    $('#copy-card-name-error').text('');
    $('#copy-card-name').removeClass('is-invalid');
    $('#keep-checklists').prop('checked', true);

    // Mengambil total is_active dari server
    $.ajax({
        url: '/cards/' + cardId + '/total-active-checklists',
        type: 'GET',
        success: function(response) {
            $('#keep-checklists').next('label').text('Checklists (' + response.totalActiveChecklists + ')');
            $('#copyCard').modal('show');
        },
        error: function(error) {
            toastr.error('Gagal mengambil total checklist!');
        }
    });
}
function addSlashes(str) {
    return str.replace(/\\/g, '\\\\')
              .replace(/'/g, "\\'")
              .replace(/"/g, '\\"')
              .replace(/\0/g, '\\0');
}

$(document).ready(function() {
    $('#updateColumnForm').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var url = form.attr('action');
        var columnId = $('#update-column-id').val();
        var formData = form.serialize();

        $.ajax({
            type: 'POST',
            url: url,
            data: formData,
            success: function(response) {
                $('#updateColumn').modal('hide');
                $('#kolomNama' + columnId).text(response.name);
                $('[data-kolom-id="' + columnId + '"]').text(response.name);
                $('#edit-column-' + columnId).attr('onclick', `updateColumnModal(${columnId}, '${response.name}', '${url}')`);
                toastr.success('Berhasil memperbaharui kolom!');
            },
            error: function(response) {
                if (response.responseJSON && response.responseJSON.errors) {
                    var errors = response.responseJSON.errors;
                    if (errors.column_name) {
                        $('#update-column-name').addClass('is-invalid');
                        $('#column-name-error').text(errors.column_name[0]);
                    }
                } else {
                    toastr.error('Gagal memperbaharui kolom!');
                }
            }
        });
    });
});

$(document).ready(function() {
    $('#updateCardForm').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var url = form.attr('action');
        var cardId = $('#update-card-id').val();
        var formData = form.serialize();

        $.ajax({
            type: 'POST',
            url: url,
            data: formData,
            success: function(response) {
                $('#updateCard').modal('hide');
                $('#span-nama-' + cardId).text(response.name);
                $('[data-kartu-id="' + cardId + '"]').text(response.name);
                $('#edit-card-' + cardId).attr('onclick', `updateCardModal(${cardId}, '${addSlashes(response.name)}', '${url}')`);
                toastr.success('Berhasil memperbaharui kartu!');
            },
            error: function(response) {
                if (response.responseJSON && response.responseJSON.errors) {
                    var errors = response.responseJSON.errors;
                    if (errors.column_name) {
                        $('#update-card-name').addClass('is-invalid');
                        $('#card-name-error').text(errors.column_name[0]);
                    }
                } else {
                    toastr.error('Gagal memperbaharui kartu!');
                }
            }
        });
        return false;
    });
});

$(document).ready(function() {
    $('#deleteColumnForm').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var columnId = $('#column-id').val();
        var formData = form.serialize();
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: formData,
            success: function(response) {
                $('#deleteColumn').modal('hide');
                $('#kolom-card-' + columnId).remove();

                toastr.success('Berhasil menghapus kolom!');
            },
            error: function(error) {
                toastr.error('Gagal menghapus kolom!');
            }
        });
    });
});

$(document).ready(function() {
    $('#deleteCardForm').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var cardId = $('#card-id').val();
        var formData = form.serialize();
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: formData,
            success: function(response) {
                $('#deleteCard').modal('hide');
                $('li[data-id="' + cardId + '"]').remove();
                toastr.success(response.message);

                // Perbarui visibilitas tautan Recover Card
                var columnId = response.columnId;
                var softDeletedCards = response.softDeletedCards;
                var recoverCards = $('#recover-kartu-link-' + columnId);

                if (softDeletedCards > 0) {
                    recoverCards.show();
                } else {
                    recoverCards.hide();
                    $('#pulihkanKartuModal').modal('hide');
                }
            },
            error: function(error) {
                toastr.error('Gagal menghapus kartu!');
            }
        });
    });

    // Tangani transisi kembali ke modal isianKartu
    $(document).on('click', '.cancel-btn', function() {
        $('#deleteCard').modal('hide').one('hidden.bs.modal', function () {
            $('#isianKartu').modal('show');
        });
    });

    // Tangani tombol tutup modal dan klik di luar modal
    $(document).on('click', '.submit-btn', function() {
        $('#deleteCard').modal('hide').one('hidden.bs.modal', function () {
            $('#isianKartu').modal('hide');
        });
    });
});

$(document).ready(function() {
    $('#deleteCardForm2').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var cardId = $('#card-id').val();
        var formData = form.serialize();
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: formData,
            success: function(response) {
                $('#deleteCard2').modal('hide');
                $('li[data-id="' + cardId + '"]').remove();
                toastr.success(response.message);

                // Perbarui visibilitas tautan Recover Card
                var columnId = response.columnId;
                var softDeletedCards = response.softDeletedCards;
                var recoverCards = $('#recover-kartu-link-' + columnId);

                if (softDeletedCards > 0) {
                    recoverCards.show();
                } else {
                    recoverCards.hide();
                    $('#pulihkanKartuModal').modal('hide');
                }
            },
            error: function(error) {
                toastr.error('Gagal menghapus kartu!');
            }
        });
    });
});

$(document).ready(function() {

    // Tandai untuk mencegah pengiriman berulang kali
    let isSubmitting = false;

    $('#copyCardForm').on('submit', function(e) {
        e.preventDefault();

        // Mencegah pengiriman ganda
        if (isSubmitting) return;

        isSubmitting = true;

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                let cardContainer = document.getElementById('containerCard' + response.column.id);
                let newCard = document.createElement('li');
                newCard.classList.add('kartu-loghub');
                newCard.setAttribute('data-id', response.new_card_id);
                newCard.setAttribute('onmouseenter', 'aksiKartuShow(' + response.new_card_id + ')');
                newCard.setAttribute('onmouseleave', 'aksiKartuHide(' + response.new_card_id + ')');
                newCard.style.position = 'relative';

                // Container Kartu ketika disalin
                newCard.innerHTML = `
                    <!-- Tampilan Aksi Edit -->
                    <div class="cover-card card-cover2-${response.card.pattern || ''} ${response.card.pattern == null ? 'hiddens' : ''}" id="cover-card-${response.card.id}"></div>
                    <div class="dropdown dropdown-action aksi-card" id="aksi-card${response.card.id}" style="position: absolute !important;">
                        <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-pencil fa-sm aksi-card-icon"></i>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="#" class="dropdown-item" onclick="updateCardModal(${response.card.id}, '${addSlashes(response.card.name)}', '${response.card.updateUrl}');" id="edit-card-${response.card.id}">
                                <i class="fa-regular fa-pen-to-square m-r-5"></i> Edit
                            </a>
                            <a href="#" class="dropdown-item" onclick="deleteCardModal2('${response.card.id}', '${addSlashes(response.card.name)}', '${response.column.name}', '${response.card.deleteUrl}');">
                                <i class='fa fa-trash-o m-r-5'></i> Delete
                            </a>
                            <a href="#" class="dropdown-item" onclick="copyCardModal('${response.card.id}', '${addSlashes(response.card.name)}', '${response.card.copyCardUrl}');" id="copy-card-${response.card.id}">
                                <i class="fa-regular fa-copy m-r-5"></i> Copy Card
                            </a>
                        </div>
                    </div>
                    <!-- /Tampilan Aksi Edit -->

                    <!-- Tampilan Kartu Pengguna -->
                    <a href="#" data-toggle="modal" data-target="#isianKartu" onclick="$('#card_id').val(${response.card.id}); $('#form_kartu').submit();">
                        <div class="card-nama" ${response.card.pattern ? 'style="border-top-right-radius: 0 !important; border-bottom-right-radius: 8px !important; border-top-left-radius: 0 !important; border-bottom-left-radius: 8px !important;"' : ''}>
                            <span class="flex ms-3" id="span-nama-${response.card.id}" style="width: 150px; ${response.card.description ? 'margin-bottom: 10px;' : ''}">${response.card.name}</span>
                            <div class="tampilan-info gap-2">

                                <!-- Muncul apabila terdapat deskripsi pada kartu -->
                                ${response.card.description ? `
                                    <div class="info-status8" id="descriptionStatus${response.card.id}">
                                        <i class="fa-solid fa-align-left icon-deskripsi-light
                                            @foreach($result_tema as $sql_mode => $mode_tema)
                                                @if($mode_tema->tema_aplikasi == 'Gelap')
                                                    icon-deskripsi-dark
                                                @endif
                                            @endforeach">
                                        </i>
                                        <span class="text-status8"><b>This card has a description.</b></span>
                                    </div>` : `
                                    <div class="info-status8 hidden" id="descriptionStatus${response.card.id}">
                                        <i class="fa-solid fa-align-left icon-deskripsi-light
                                            @foreach($result_tema as $sql_mode => $mode_tema)
                                                @if($mode_tema->tema_aplikasi == 'Gelap')
                                                    icon-deskripsi-dark
                                                @endif
                                            @endforeach">
                                        </i>
                                        <span class="text-status8"><b>This card has a description.</b></span>
                                    </div>`}
                                <!-- /Muncul apabila terdapat deskripsi pada kartu -->

                                <!-- Muncul apabila terdapat checklist pada kartu -->
                                <div id="iconChecklist-${response.card.id}" class="progress-checklist-light hidden @foreach($result_tema as $mode_tema) @if ($mode_tema->tema_aplikasi == 'Gelap') progress-checklist-dark hidden @endif @endforeach">
                                    <div class="info-status9">
                                        <i id="icon-checklist-${response.card.id}" class="fa-regular fa-square-check icon-check-not-full-light @foreach($result_tema as $mode_tema) @if ($mode_tema->tema_aplikasi == 'Gelap') icon-check-not-full-dark @endif @endforeach"></i>
                                        ${response.card.description ? `<span class="text-status9"><b>Checklist items</b></span>` : `<span class="text-status9a"><b>Checklist items</b></span>`}
                                        <span id="perhitunganChecklist-${response.card.id}" class="total"></span>
                                    </div>
                                </div>
                                <!-- /Muncul apabila terdapat checklist pada kartu -->

                            </div>
                        </div>
                    </a>
                    <!-- /Tampilan Kartu Pengguna -->
                `;

                // Tambahkan kartu yang disalin ke dalam container kartu
                cardContainer.appendChild(newCard);

                // Periksa penyebutan dan kirim pemberitahuan penyebutan
                const checklists = response.checklists;
                checklists.forEach(checklist => {
                    if (checklist.name.includes('@')) {
                        const users = extractMentions(checklist.name);
                        sendMentions(users, checklist.name);
                    }
                });

                // Untuk Mengatur Icon Checklist //
                if(response.checklists != '')
                {
                    $('#iconChecklist-' + response.card.id).removeClass('hidden');
                    $('#perhitunganChecklist-' + response.card.id).html(response.perChecklist + '/' + response.jumlahChecklist);
                }
                if (response.perChecklist < response.jumlahChecklist) {
                    var tema_aplikasi = response.result_tema.tema_aplikasi;
                    var cardId = response.card.id;
                    var iconChecklist = $('#iconChecklist-' + cardId);
                    var iconChecklistCheck = $('#icon-checklist-' + cardId);

                    if (tema_aplikasi == 'Terang') {
                        iconChecklist.removeClass('progress-checklist-100-light').removeClass('progress-checklist-100-dark');
                        iconChecklist.addClass('progress-checklist-light').removeClass('progress-checklist-dark');
                        iconChecklistCheck.addClass('icon-check-not-full-light').removeClass('icon-check-not-full-dark');
                        iconChecklistCheck.removeClass('icon-check-full-light').removeClass('icon-check-full-dark');

                    } else if (tema_aplikasi == 'Gelap') {
                        iconChecklist.removeClass('progress-checklist-100-dark').removeClass('progress-checklist-100-light');
                        iconChecklist.addClass('progress-checklist-dark').removeClass('progress-checklist-light');
                        iconChecklistCheck.addClass('icon-check-not-full-dark').removeClass('icon-check-not-full-light');
                        iconChecklistCheck.removeClass('icon-check-full-dark').removeClass('icon-check-full-light');
                    }
                } else if (response.perChecklist == response.jumlahChecklist) {
                    var tema_aplikasi = response.result_tema.tema_aplikasi;
                    var cardId = response.card.id;
                    var iconChecklist = $('#iconChecklist-' + cardId);
                    var iconChecklistCheck = $('#icon-checklist-' + cardId);

                    if (tema_aplikasi == 'Terang') {
                        iconChecklist.addClass('progress-checklist-100-light').removeClass('progress-checklist-100-dark');
                        iconChecklist.addClass('progress-checklist-light').removeClass('progress-checklist-dark');
                        iconChecklistCheck.removeClass('icon-check-not-full-light').removeClass('icon-check-not-full-dark');
                        iconChecklistCheck.addClass('icon-check-full-light').removeClass('icon-check-full-dark');

                    } else if (tema_aplikasi == 'Gelap') {
                        iconChecklist.addClass('progress-checklist-100-dark').removeClass('progress-checklist-100-light');
                        iconChecklist.addClass('progress-checklist-dark').removeClass('progress-checklist-light');
                        iconChecklistCheck.removeClass('icon-check-not-full-dark').removeClass('icon-check-not-full-light');
                        iconChecklistCheck.addClass('icon-check-full-dark').removeClass('icon-check-full-light');
                    }
                }

                if (response.perChecklist == 0 && response.jumlahChecklist == 0) {
                    var tema_aplikasi = response.result_tema.tema_aplikasi;
                    var iconChecklist = $('#iconChecklist-' + cardId);

                    if (tema_aplikasi == 'Terang') {
                        iconChecklist.addClass('hidden');
                    } else if (tema_aplikasi == 'Gelap') {
                        iconChecklist.addClass('hidden');
                    }
                }
                // /Untuk Mengatur Icon Checklist //

                toastr.success('Berhasil menyalin kartu!');
                $('#copyCard').modal('hide');

                // Setel ulang tanda
                isSubmitting = false;
            },
            error: function(xhr, status, error) {
                toastr.error('Gagal menyalin kartu!');
                $('#copy-card-name-error').text('Terjadi kesalahan saat menyalin kartu!');
                $('#copy-card-name').addClass('is-invalid');

                // Setel ulang tanda
                isSubmitting = false;
            }
        });
    });

    function extractMentions(text) {
        const mentionPattern = /@(\w+)/g;
        let matches;
        const users = [];
        while ((matches = mentionPattern.exec(text)) !== null) {
            users.push(matches[1]);
        }
        return users;
    }

    function sendMentions(users, checklistName) {
        const promises = users.map(username => {
            let form = document.getElementById('copyCardForm');
            let formData = new FormData(form);
            return fetch('/mention-tag-checklist', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': formData.get('_token')
                },
                body: JSON.stringify({
                    username: username,
                    name: checklistName
                })
            });
        });

        Promise.all(promises)
            .then(responses => {
                const allSuccessful = responses.every(response => response.ok);
                if (allSuccessful) {
                    console.log('Berhasil mengirimkan mention tag!');
                } else {
                    toastr.error('Gagal mengirimkan mention tag!');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }
});

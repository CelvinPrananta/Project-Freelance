<script>
    // Fungsi untuk memanggil modal kartu
    $(document).ready(function() {
        $('#pulihkanKartuModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var columnId = button.data('column-id');
            var modal = $(this);

            // Set nilai column_id pada input tersembunyi di modal
            modal.find('#column_id').val(columnId);

            // Lakukan AJAX request untuk mendapatkan data kartu yang akan dipulihkan
            $.ajax({
                url: '{{ route('pulihkan-kartu') }}',
                method: 'GET',
                data: {
                    column_id: columnId
                },
                success: function(response) {
                    var dataRecover = response.dataRecover;
                    var list = '';

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

                    // Iterasi setiap kartu dan bangun elemen list
                    dataRecover.forEach(function(card) {

                        // Ubah deleted_at menjadi format indonesia
                        var deletedAt = moment(card.deleted_at).format(
                            'YYYY-MM-DD HH:mm:ss');
                        var waktuPulihkanKartu = moment(deletedAt,
                            'YYYY-MM-DD HH:mm:ss').format(
                            'D MMMM YYYY [at] h:mm');

                        list += '<li class="pulihkan-kartu" id="pulihkan-kartu-' +
                            card.id + '">' +
                            '<div class="isian-pulihkan-kartu">' + card.name +
                            '<div class="info-status19">' +
                            '<button class="opsi-pulihkan2" onclick="deleteKartu(' +
                            card.id + ');">' +
                            '<i class="fa fa-trash-o"></i>' +
                            '<span class="text-status19">' +
                            '<b>' + 'Delete Permanently' + '</b>' +
                            '</span>' +
                            '</button>' +
                            '</div>' +
                            '<div class="info-status18">' +
                            '<button class="opsi-pulihkan" onclick="pulihkanKartu(' +
                            card.id + ');">' +
                            '<i class="fa-solid fa-rotate-left"></i>' +
                            '<span class="text-status18">' +
                            '<b>' + 'Restore' + '</b>' +
                            '</span>' +
                            '</button>' +
                            '</div>' +
                            '<div class="waktu-pulihkan-kartu">Deleted ' +
                            waktuPulihkanKartu + '</div>' + '</div>' +
                            '</li>';
                    });

                    // Masukkan list kartu ke dalam elemen yang sesuai di modal
                    modal.find('#listPulihkanKartu').html(list);
                }
            });
        });
    });

    // Fungsi untuk memulihkan kartu
    function pulihkanKartu(cardId) {
        $.post('{{ route('pulihkanKartu') }}', {
            id: cardId,
            _token: '{{ csrf_token() }}'
        }, function(response) {
            if (response.message === 'Berhasil memulihkan kartu!') {
                toastr.success(response.message);

                // Hapus kartu dari daftar modal
                $('#pulihkan-kartu-' + cardId).remove();

                // Perbarui visibilitas tautan Recover Card
                const columnId = response.columnId;
                const softDeletedCards = response.softDeletedCards;
                const recoverCards = $('#recover-kartu-link-' + columnId);

                if (softDeletedCards > 0) {
                    recoverCards.show();
                } else {
                    recoverCards.hide();
                    $('#pulihkanKartuModal').modal('hide');
                }

                // Container Kartu ketika dipulihkan
                pulihkanKartuContainer(response);

                // Menggunakan timeout untuk memastikan elemen sudah ada di DOM
                setTimeout(() => {
                    // Untuk Mengatur Icon Checklist
                    const cardId = response.card.id;
                    $('#iconChecklist-' + cardId).removeClass('hidden');
                    $('#perhitunganChecklist-' + cardId).html(response.perChecklist + '/' + response
                        .jumlahChecklist);

                    const tema_aplikasi = response.result_tema.tema_aplikasi;

                    if (response.perChecklist < response.jumlahChecklist) {
                        const iconChecklist = $('#iconChecklist-' + cardId);
                        const iconChecklistCheck = $('#icon-checklist-' + cardId);

                        if (tema_aplikasi == 'Terang') {
                            iconChecklist.removeClass('progress-checklist-100-light').removeClass(
                                'progress-checklist-100-dark');
                            iconChecklist.addClass('progress-checklist-light').removeClass(
                                'progress-checklist-dark');
                            iconChecklistCheck.addClass('icon-check-not-full-light').removeClass(
                                'icon-check-not-full-dark');
                            iconChecklistCheck.removeClass('icon-check-full-light').removeClass(
                                'icon-check-full-dark');
                        } else if (tema_aplikasi == 'Gelap') {
                            iconChecklist.removeClass('progress-checklist-100-dark').removeClass(
                                'progress-checklist-100-light');
                            iconChecklist.addClass('progress-checklist-dark').removeClass(
                                'progress-checklist-light');
                            iconChecklistCheck.addClass('icon-check-not-full-dark').removeClass(
                                'icon-check-not-full-light');
                            iconChecklistCheck.removeClass('icon-check-full-dark').removeClass(
                                'icon-check-full-light');
                        }
                    } else if (response.perChecklist == response.jumlahChecklist) {
                        const iconChecklist = $('#iconChecklist-' + cardId);
                        const iconChecklistCheck = $('#icon-checklist-' + cardId);

                        if (tema_aplikasi == 'Terang') {
                            iconChecklist.addClass('progress-checklist-100-light').removeClass(
                                'progress-checklist-100-dark');
                            iconChecklist.addClass('progress-checklist-light').removeClass(
                                'progress-checklist-dark');
                            iconChecklistCheck.removeClass('icon-check-not-full-light').removeClass(
                                'icon-check-not-full-dark');
                            iconChecklistCheck.addClass('icon-check-full-light').removeClass(
                                'icon-check-full-dark');
                        } else if (tema_aplikasi == 'Gelap') {
                            iconChecklist.addClass('progress-checklist-100-dark').removeClass(
                                'progress-checklist-100-light');
                            iconChecklist.addClass('progress-checklist-dark').removeClass(
                                'progress-checklist-light');
                            iconChecklistCheck.removeClass('icon-check-not-full-dark').removeClass(
                                'icon-check-not-full-light');
                            iconChecklistCheck.addClass('icon-check-full-dark').removeClass(
                                'icon-check-full-light');
                        }
                    }

                    if (response.perChecklist == 0 && response.jumlahChecklist == 0) {
                        const iconChecklist = $('#iconChecklist-' + cardId);

                        if (tema_aplikasi == 'Terang') {
                            iconChecklist.addClass('hidden');
                        } else if (tema_aplikasi == 'Gelap') {
                            iconChecklist.addClass('hidden');
                        }
                    }

                    // Timeout 100ms untuk memastikan elemen ada di DOM
                }, 100);

            } else {
                toastr.error('Gagal memulihkan kartu!');
            }
        }).fail(function() {
            toastr.error('Gagal memulihkan kartu!');
        });
    }

    function addSlashes(str) {
        return str.replace(/\\/g, '\\\\')
            .replace(/'/g, "\\'")
            .replace(/"/g, '\\"')
            .replace(/\0/g, '\\0');
    }

    function pulihkanKartuContainer(response) {
        const {
            columnId,
            card,
            column
        } = response;

        let cardContainer = document.getElementById('containerCard' + columnId);
        let new_card = document.createElement('li');
        new_card.classList.add('kartu-loghub');
        new_card.setAttribute('data-id', card.id);
        new_card.setAttribute('onmouseenter', 'aksiKartuShow(' + card.id + ')');
        new_card.setAttribute('onmouseleave', 'aksiKartuHide(' + card.id + ')');
        new_card.style.position = 'relative';

        // Container Kartu ketika dipulihkan
        new_card.innerHTML = `
            <!-- Tampilan Aksi Edit -->
            <div class="cover-card card-cover2-${card.pattern || ''} ${card.pattern == null ? 'hiddens' : ''}" id="cover-card-${card.id}"></div>
            <div class="dropdown dropdown-action aksi-card" id="aksi-card${card.id}" style="position: absolute !important;">
                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <i class="fa-solid fa-pencil fa-sm aksi-card-icon"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a href="#" class="dropdown-item" onclick="updateCardModal(${card.id}, '${addSlashes(card.name)}', '${card.updateUrl}');" id="edit-card-${card.id}">
                        <i class="fa-regular fa-pen-to-square m-r-5"></i> Edit
                    </a>
                    <a href="#" class="dropdown-item" onclick="deleteCardModal2(${card.id}, '${addSlashes(card.name)}', '${column.name}', '${card.deleteUrl}');">
                        <i class='fa fa-trash-o m-r-5'></i> Delete
                    </a>
                    <a href="#" class="dropdown-item" onclick="copyCardModal(${card.id}, '${addSlashes(card.name)}', '${card.copyCardUrl}');" id="copy-card-${card.id}">
                        <i class="fa-regular fa-copy m-r-5"></i> Copy Card
                    </a>
                </div>
            </div>
            <!-- /Tampilan Aksi Edit -->

            <!-- Tampilan Kartu Pengguna -->
            <a href="#" data-toggle="modal" data-target="#isianKartu" onclick="$('#card_id').val(${card.id}); $('#form_kartu').submit();">
                <div class="card-nama" ${card.pattern ? 'style="border-top-right-radius: 0 !important; border-bottom-right-radius: 8px !important; border-top-left-radius: 0 !important; border-bottom-left-radius: 8px !important;"' : ''}>
                    <span class="flex ms-3" id="span-nama-${card.id}" style="width: 150px; ${card.description ? 'margin-bottom: 10px;' : ''}">${card.name}</span>
                    <div class="tampilan-info gap-2">
                        <!-- Muncul apabila terdapat deskripsi pada kartu -->
                            <div class="info-status8 ${card.description ? `''` : `'hidden'`}" id="descriptionStatus${card.id}">
      <i
                                                        class="fa-solid fa-align-left {{ $result_tema->tema_aplikasi == 'Gelap' ? 'icon-deskripsi-dark' : 'icon-deskripsi-light' }}">
                                                    </i>
      <span class="text-status8"><b>${card.description ? `'This card has a description.'` : ``}</b></span>
    </div>
                        <!-- /Muncul apabila terdapat deskripsi pada kartu -->

                        <!-- Muncul apabila terdapat checklist pada kartu -->
                        <div id="iconChecklist-${card.id}" class="progress-checklist-light hidden  @if ($result_tema->tema_aplikasi == 'Gelap') progress-checklist-dark hidden @endif ">
                            <div class="info-status9">
                                <i id="icon-checklist-${card.id}" class="fa-regular fa-square-check icon-check-not-full-light  @if ($result_tema->tema_aplikasi == 'Gelap') icon-check-not-full-dark @endif "></i>
                                ${card.description ? `<span class="text-status9"><b>Checklist items</b></span>` : `<span class="text-status9a"><b>Checklist items</b></span>`}
                                <span id="perhitunganChecklist-${card.id}" class="total"></span>
                            </div>
                        </div>
                        <!-- /Muncul apabila terdapat checklist pada kartu -->

                    </div>
                </div>
            </a>
            <!-- /Tampilan Kartu Pengguna -->
        `;

        // Menyisipkan kartu pada posisi yang benar berdasarkan ID atau posisi asli
        if (card.position > 0) {

            // Sisipkan kartu di posisi semula jika position lebih besar dari 0
            var position = card.position - 1;
            var referenceNode = cardContainer.children[position];
            cardContainer.insertBefore(new_card, referenceNode);

        } else {
            // Tambahkan kartu di akhir jika position adalah 0
            let cardsArray = Array.from(cardContainer.children);
            cardsArray.push(new_card);
            cardsArray.sort((a, b) => a.getAttribute('data-id') - b.getAttribute('data-id'));
            cardContainer.innerHTML = '';
            cardsArray.forEach(card => cardContainer.appendChild(card));
        }
    }

    // Fungsi untuk menghapus kartu secara permanen
    function deleteKartu(cardId) {
        $.ajax({
            url: '{{ route('hapusKartuPermanen') }}',
            method: 'POST',
            data: {
                id: cardId,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.message === 'Berhasil menghapus kartu permanen!') {
                    toastr.success(response.message);

                    // Hapus kartu dari daftar modal
                    $('#pulihkan-kartu-' + cardId).remove();

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
                } else {
                    toastr.error('Gagal menghapus kartu!');
                }
            }
        }).fail(function() {
            toastr.error('Gagal menghapus kartu!');
        });
    }
</script>

<script>
    function addColumnScript(event) {

        // Tandai untuk mencegah pengiriman berulang kali
        let isSubmitting = false;

        event.preventDefault();
        $('.submit-btn').prop('disabled', true);
        // Mencegah pengiriman ganda
        if (isSubmitting) return;
        isSubmitting = true;

        let form = document.getElementById('addColForm');
        let formData = new FormData(form);
        let url = form.getAttribute('action');

        fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': formData.get('_token'),
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.id) {
                    let newColumn = document.createElement('div');
                    newColumn.classList.add('kolom-card', 'hover:scale-105', 'hover:relative');
                    newColumn.id = `kolom-card-${data.id}`;
                    newColumn.setAttribute('onmouseenter', 'aksiKolomShow(' + data.id + ')');
                    newColumn.setAttribute('onmouseleave', 'aksiKolomHide(' + data.id + ')');
                    newColumn.dataset.id = data.id;

                    // Container kolom ketika ditambahkan
                    newColumn.innerHTML = `
                    <div class="dropdown dropdown-action aksi-kolom" id="aksi-kolom${data.id}">
                        <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-ellipsis"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="#" class="dropdown-item" onclick="updateColumnModal(${data.id}, '${data.name}', '${data.updateUrl}');" id="edit-column-${data.id}">
                                <i class="fa fa-pencil m-r-5"></i> Edit
                            </a>
                            <a href="#" class="dropdown-item" onclick="deleteColumnModal(${data.id}, '${data.name}', '${data.deleteUrl}');">
                                <i class='fa fa-trash-o m-r-5'></i> Delete
                            </a>
                            <a href="#" class="dropdown-item recover-kartu-link" id="recover-kartu-link-${data.id}" data-toggle="modal" data-target="#pulihkanKartuModal" data-column-id="${data.id}" style="${data.softDeletedCards > 0 ? '' : 'display: none;'}">
                                <i class="fa-solid fa-recycle m-r-5"></i> Recover Card
                            </a>
                        </div>
                    </div>
                    <h5 id="kolomNama${data.id}" class="kolom-nama mb-3 font-semibold text-lgs dark:text-white">${data.name}</h5>
                    <ul class="card-container" id="containerCard${data.id}">
                    </ul>
                    <div class="card-loghub hidden" id="cardLoghub${data.id}">
                        <div class="flex items-center p-3 text-base font-bold rounded-lg bg-gray-50 hover:bg-gray-100 group hover:shadow dark:bg-gray-600 dark:hover:bg-gray-500 dark:text-white">
                            <form id="addCardForm${data.id}" action="${data.addCardUrl}" method="POST" onsubmit="addCardScript(event, '${data.id}')">
                                @csrf
                                <input type="hidden" class="form-control" name="board_id" value="${data.board_id}">
                                <input type="hidden" class="form-control" name="team_id" value="${data.team_id}">
                                <input type="hidden" class="form-control" name="column_id" value="${data.id}">
                                <input type="text" class="form-control" name="name" id="cardName" style="width: 130%; border-radius: 10px; background-color: #f5fffa;" placeholder="Enter card's name..." required>
                                <button type="submit" class="btn btn-outline-info btn-add">Add card</button>
                            </form>
                        </div>
                    </div>
                    <button onclick="openAdd('${data.id}')" class="btn btn-outline-info" id="btn-add${data.id}">
                        <i class="fa-solid fa-plus"></i> Add a card...
                    </button>
                `;

                    // Tambahkan kolom baru
                    let cardContainer = document.getElementById('cardContainer');

                    if (cardContainer) {
                        cardContainer.appendChild(newColumn);
                        toastr.success('Berhasil membuat kolom!');
                        $('.submit-btn').prop('disabled', false);
                        // Menutup modal
                        $('#addCol').modal('hide');

                        // Memuat Ulang Form Kosongan
                        form.reset();

                    } else {
                        toastr.error('Gagal menemukan kontainer kolom!');
                    }

                } else {
                    toastr.error('Gagal membuat kolom!');
                }

                // Setel ulang tanda
                isSubmitting = false;
            })

            .catch(error => {
                console.error('Kesalahan:', error);
                toastr.error('Gagal membuat kolom!');

                // Setel ulang tanda
                isSubmitting = false;
            });
    }
    let isSubmitting = false;

    function addSlashes(str) {
        return str.replace(/\\/g, '\\\\')
            .replace(/'/g, "\\'")
            .replace(/"/g, '\\"')
            .replace(/\0/g, '\\0');
    }

    function addCardScript(event, columnId) {
        event.preventDefault();
        $('.spinner-border').removeClass('d-none');
        $('.btn-add').prop('disabled', true)
        if (isSubmitting) return; // Prevent duplicate submissions
        isSubmitting = true;
        let form = document.getElementById('addCardForm' + columnId);
        let formData = new FormData(form);
        let url = form.getAttribute('action');

        fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': formData.get('_token'),
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    let cardContainer = document.getElementById('containerCard' + data.column.id);
                    let newCard = document.createElement('li');
                    newCard.classList.add('kartu-loghub');
                    newCard.setAttribute('data-id', data.card.id);
                    newCard.setAttribute('onmouseenter', 'aksiKartuShow(' + data.card.id + ')');
                    newCard.setAttribute('onmouseleave', 'aksiKartuHide(' + data.card.id + ')');
                    newCard.style.position = 'relative';

                    // Container Kartu ketika ditambahkan
                    newCard.innerHTML = `
                    <!-- Tampilan Aksi Edit -->
                    <div class="cover-card card-cover2-${data.card.pattern || ''} ${data.card.pattern == null ? 'hiddens' : ''}" id="cover-card-${data.card.id}"></div>
                    <div class="dropdown dropdown-action aksi-card" id="aksi-card${data.card.id}" style="position: absolute !important;">
                        <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-pencil fa-sm aksi-card-icon"></i>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="#" class="dropdown-item" onclick="updateCardModal(${data.card.id}, '${addSlashes(data.card.name)}', '${data.card.updateUrl}');" id="edit-card-${data.card.id}">
                                <i class="fa-regular fa-pen-to-square m-r-5"></i> Edit
                            </a>
                            <a href="#" class="dropdown-item" onclick="deleteCardModal2('${data.card.id}', '${addSlashes(data.card.name)}', '${data.column.name}', '${data.card.deleteUrl}');">
                                <i class='fa fa-trash-o m-r-5'></i> Delete
                            </a>
                            <a href="#" class="dropdown-item" onclick="copyCardModal('${data.card.id}', '${data.card.name}', '${data.card.copyCardUrl}');" id="copy-card-${data.card.id}">
                                <i class="fa-regular fa-copy m-r-5"></i> Copy Card
                            </a>
                        </div>
                    </div>
                    <!-- /Tampilan Aksi Edit -->

                    <!-- Tampilan Kartu Pengguna -->
                    <a href="#" data-toggle="modal" data-target="#isianKartu" onclick="$('#card_id').val(${data.card.id}); $('#form_kartu').submit();">
                        <div class="card-nama" ${data.card.pattern ? 'style="border-top-right-radius: 0 !important; border-bottom-right-radius: 8px !important; border-top-left-radius: 0 !important; border-bottom-left-radius: 8px !important;"' : ''}>
                            <span class="flex ms-3" id="span-nama-${data.card.id}" style="width: 150px; ${data.card.description ? 'margin-bottom: 10px;' : ''}">${data.card.name}</span>
                            <div class="tampilan-info gap-2">

                                <!-- Muncul apabila terdapat deskripsi pada kartu -->
                                ${data.card.description ? `
                                    <div class="info-status8" id="descriptionStatus${data.card.id}">
                                        <i class="fa-solid fa-align-left icon-deskripsi-light
                                                @if ($result_tema->tema_aplikasi == 'Gelap') icon-deskripsi-dark @endif">
                                        </i>
                                        <span class="text-status8"><b>This card has a description.</b></span>
                                    </div>` : `
                                    <div class="info-status8 hidden" id="descriptionStatus${data.card.id}">
                                        <i class="fa-solid fa-align-left icon-deskripsi-light
                                                @if ($result_tema->tema_aplikasi == 'Gelap') icon-deskripsi-dark @endif ">
                                        </i>
                                        <span class="text-status8"><b>This card has a description.</b></span>
                                    </div>`}
                                <!-- /Muncul apabila terdapat deskripsi pada kartu -->

                                <!-- Muncul apabila terdapat checklist pada kartu -->
                                <div id="iconChecklist-${data.card.id}" class="progress-checklist-light hidden
                                 @if ($result_tema->tema_aplikasi == 'Gelap') progress-checklist-dark hidden @endif">
                                    <div class="info-status9">
                                        <i id="icon-checklist-${data.card.id}" class="fa-regular fa-square-check icon-check-not-full-light  @if ($result_tema->tema_aplikasi == 'Gelap') icon-check-not-full-dark @endif"></i>
                                        ${data.card.description ? `<span class="text-status9"><b>Checklist items</b></span>` : `<span class="text-status9a"><b>Checklist items</b></span>`}
                                        <span id="perhitunganChecklist-${data.card.id}" class="total"></span>
                                    </div>
                                </div>
                                <!-- /Muncul apabila terdapat checklist pada kartu -->

                            </div>
                        </div>
                    </a>
                    <!-- /Tampilan Kartu Pengguna -->
                `;
                    cardContainer.appendChild(newCard);

                    $(document).ready(function() {
                        const columnContainer = document.getElementById('cardContainer');
                        new Sortable(columnContainer, {
                            animation: 150,
                            onEnd: function(evt) {
                                updateColumnPositions();
                            },
                        });

                        const cardContainers = document.getElementsByClassName('card-container');
                        Array.from(cardContainers).forEach(e => {
                            new Sortable(e, {
                                group: 'shared',
                                animation: 150,
                                onAdd: function(evt) {
                                    updateCardColumn(evt);
                                },
                                onEnd: function(evt) {
                                    updateCardPositions(evt.to);
                                },
                            });
                        });

                        function updateColumnPositions() {
                            const positions = {};
                            const columnIds = columnContainer.children;
                            for (let i = 0; i < columnIds.length; i++) {
                                const column = columnIds[i];
                                const id = column.dataset.id;
                                if (id !== undefined) {
                                    positions[id] = i + 1;
                                }
                            }

                            fetch('{{ route('perbaharuiPosisiKolom') }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({
                                        positions
                                    })
                                })

                                .then(response => response.json())

                                .then(data => {
                                    if (data.success) {
                                        toastr.success('Berhasil perbaharui posisi kolom!');
                                    } else {
                                        toastr.error('Gagal perbaharui posisi kolom!');
                                    }
                                })

                                .catch(error => {
                                    console.error('Terjadi kesalahan saat perbaharui posisi kolom:',
                                        error);
                                });

                        }

                        function updateCardPositions(container) {
                            const positions = {};
                            const cards = container.children;
                            for (let i = 0; i < cards.length; i++) {
                                const card = cards[i];
                                const id = card.dataset.id;
                                if (id !== undefined) {
                                    positions[id] = i + 1;
                                }
                            }

                            fetch('{{ route('perbaharuiPosisiKartu') }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({
                                        positions
                                    })
                                })

                                .then(response => response.json())

                                .then(data => {
                                    if (data.success) {
                                        toastr.success('Berhasil perbaharui posisi kartu!');
                                    } else {
                                        toastr.error('Gagal perbaharui posisi kartu!');
                                    }
                                })

                                .catch(error => {
                                    console.error('Terjadi kesalahan saat perbaharui posisi kartu:',
                                        error);
                                });

                        }

                        function updateCardColumn(evt) {
                            const cardId = evt.item.dataset.id;
                            const newColumnId = evt.to.dataset.id;

                            fetch('{{ route('perbaharuiPosisiKartuKeKolom') }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({
                                        card_id: cardId,
                                        new_column_id: newColumnId
                                    })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {} else {}
                                })
                                .catch(error => {
                                    console.error('Terjadi kesalahan saat memindah kartu ke kolom:',
                                        error);
                                });
                        }
                    });
                    $('.spinner-border').addClass('d-none');
                    $('.submit-btn').prop('disabled', false);
                    toastr.success('Berhasil membuat kartu!');

                    // Memuat Ulang Form Kosongan
                    form.reset();

                    // Fitur Buka dan Tutup Tambah Kartu
                    const cardLoghub = document.getElementById('cardLoghub' + columnId);
                    const btnadd = document.getElementById('btn-add' + columnId);
                    let style = cardLoghub.getAttribute("class");
                    if (style.includes('flex')) {
                        cardLoghub.classList.remove("flex");
                        btnadd.innerHTML = "<i class='fa-solid fa-plus'></i> Add a card...";
                    } else {
                        cardLoghub.classList.add("flex");
                        btnadd.innerHTML = "Cancel";
                    }
                    // End Fitur Buka dan Tutup Tambah Kartu

                } else {
                    toastr.error('Gagal membuat kartu!');
                }

                // Setel ulang tanda
                isSubmitting = false;
            })
            .catch(error => {
                console.error('Kesalahan:', error);

                // Setel ulang tanda
                isSubmitting = false;
            });
    }
</script>

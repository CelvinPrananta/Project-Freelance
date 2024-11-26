<script>
    // ---------------------------------------------------------- Untuk Memanggil pulihkanTitleChecklistModal & Data Recover  ---------------------------------------------------------- //
    // Fungsi untuk memanggil modal judul & checklist
    function recoverTitleChecklistModal(cardId) {
        $('#card-id').val(cardId);
        $('#isianKartu').modal('hide').one('hidden.bs.modal', function() {
            $('#pulihkanTitleChecklistModal').modal('show');
        });
    }

    // Fungsi untuk memanggil data recover judul & checklist
    $(document).ready(function() {
        $('.recover-title-checklist').on('click', function(event) {
            event.preventDefault();
            var button = $(this);
            var cardId = button.data('card-id');

            // Set nilai card_id pada input tersembunyi di modal
            $('#pulihkanTitleChecklistModal').find('#card_id').val(cardId);

            // Lakukan permintaan AJAX untuk mendapatkan data pemulihan
            $.ajax({
                url: '{{ route('dataPulihkanTitleChecklist') }}',
                method: 'GET',
                data: {
                    card_id: cardId
                },
                success: function(response) {
                    var dataTitleRecover = response.dataTitleRecover;
                    var dataChecklistRecover = response.dataChecklistRecover;
                    var listTitle = '';
                    var listChecklist = '';

                    // Perbarui visibilitas tautan Pulihkan Judul & Checklist
                    var softDeletedTitle = response.softDeletedTitle;
                    var softDeletedChecklist = response.softDeletedChecklist;
                    var recoverTitleChecklist = $('#recover-title-checklist-' + cardId);

                    if (softDeletedTitle > 0 || softDeletedChecklist > 0) {
                        recoverTitleChecklist.show();
                    } else {
                        recoverTitleChecklist.hide();

                        // Hanya sembunyikan modal jika kedua nilai adalah 0
                        $('#pulihkanTitleChecklistModal').modal('hide').one(
                            'hidden.bs.modal',
                            function() {
                                $('#isianKartu').modal('show');
                            });
                    }

                    // Buat data pemulihan judul checklist
                    dataTitleRecover.forEach(function(titleChecklist) {

                        // Ubah deleted_at menjadi format indonesia
                        var deletedAt = moment(titleChecklist.deleted_at).format(
                            'YYYY-MM-DD HH:mm:ss');
                        var waktuPulihkanKartu = moment(deletedAt,
                            'YYYY-MM-DD HH:mm:ss').format(
                            'D MMMM YYYY [at] h:mm');

                        listTitle +=
                            '<li class="pulihkan-title" id="pulihkan-title-' +
                            titleChecklist.id + '">' +
                            '<div class="isian-pulihkan-title">' + titleChecklist
                            .name +
                            '<div class="info-status15">' +
                            '<button class="opsi-pulihkan-title2" onclick="deleteTitle(' +
                            titleChecklist.id + ');">' +
                            '<i class="fa fa-trash-o"></i>' +
                            '<span class="text-status15">' +
                            '<b>' + 'Delete Permanently' + '</b>' +
                            '</span>' +
                            '</button>' +
                            '</div>' +
                            '<div class="info-status14">' +
                            '<button class="opsi-pulihkan-title" onclick="pulihkanTitle(' +
                            titleChecklist.id + ');">' +
                            '<i class="fa-solid fa-rotate-left"></i>' +
                            '<span class="text-status14">' +
                            '<b>' + 'Restore' + '</b>' +
                            '</span>' +
                            '</button>' +
                            '</div>' +
                            '<div class="waktu-pulihkan-title">Deleted ' +
                            waktuPulihkanKartu + '</div>' +
                            '</div>' +
                            '</li>';
                    });

                    // Buat data pemulihan checklist
                    dataChecklistRecover.forEach(function(checklist) {

                        // Ubah deleted_at menjadi format indonesia
                        var deletedAt = moment(checklist.deleted_at).format(
                            'YYYY-MM-DD HH:mm:ss');
                        var waktuPulihkanKartu = moment(deletedAt,
                            'YYYY-MM-DD HH:mm:ss').format(
                            'D MMMM YYYY [at] h:mm');

                        listChecklist +=
                            '<li class="pulihkan-checklist" id="pulihkan-checklist-' +
                            checklist.id + '">' +
                            '<div class="isian-pulihkan-checklist">' +
                            '<p class="text-from-judul">' + 'from ' + checklist
                            .title_checklist_name + '</p>' + checklist.name +
                            '<div class="info-status17">' +
                            '<button class="opsi-pulihkan-checklist2" onclick="deleteChecklist(' +
                            checklist.id + ');">' +
                            '<i class="fa fa-trash-o"></i>' +
                            '<span class="text-status17">' +
                            '<b>' + 'Delete Permanently' + '</b>' +
                            '</span>' +
                            '</button>' +
                            '</div>' +
                            '<div class="info-status16">' +
                            '<button class="opsi-pulihkan-checklist" onclick="pulihkanChecklist(' +
                            checklist.id + ');">' +
                            '<i class="fa-solid fa-rotate-left"></i>' +
                            '<span class="text-status16">' +
                            '<b>' + 'Restore' + '</b>' +
                            '</span>' +
                            '</button>' +
                            '</div>' +
                            '<div class="waktu-pulihkan-checklist">Deleted ' +
                            waktuPulihkanKartu + '</div>' +
                            '</div>' +
                            '</li>';
                    });

                    // Masukkan data ke dalam modal
                    $('#pulihkanTitleChecklistModal').find('#listPulihkanTitleChecklist')
                        .html(listTitle);
                    $('#pulihkanTitleChecklistModal').find('#listPulihkanChecklist').html(
                        listChecklist);
                }
            });
        });

        // Tangani transisi kembali ke modal isianKartu
        $(document).on('click', '.titlechecklist-btn', function() {
            $('#pulihkanTitleChecklistModal').modal('hide').one('hidden.bs.modal', function() {
                $('#isianKartu').modal('show');
            });
        });

        // Tangani tombol tutup modal dan klik di luar modal
        $('#pulihkanTitleChecklistModal').on('hidden.bs.modal', function() {
            $('#isianKartu').modal('show');
        });
    });
    // ---------------------------------------------------------- /Untuk Memanggil pulihkanTitleChecklistModal & Data Recover  ---------------------------------------------------------- //


    // ---------------------------------------------------------- Pemulihan Data Judul Checklist ---------------------------------------------------------- //
    // Fungsi untuk memulihkan judul checklist
    function pulihkanTitle(cardId) {
        $.post('{{ route('pulihkanJudulChecklist') }}', {
            id: cardId,
            _token: '{{ csrf_token() }}'
        }, function(response) {
            if (response.message === 'Berhasil memulihkan judul checklist!') {
                toastr.success(response.message);

                // Hapus kartu dari daftar modal
                $('#pulihkan-title-' + cardId).remove();

                // Perbarui visibilitas tautan Pulihkan Judul & Checklist
                var softDeletedTitle = response.softDeletedTitle;
                var softDeletedChecklist = response.softDeletedChecklist;
                var recoverTitleChecklist = $('#recover-title-checklist-' + cardId);

                if (softDeletedTitle > 0 || softDeletedChecklist > 0) {
                    recoverTitleChecklist.show();
                } else {
                    recoverTitleChecklist.hide();

                    // Hanya sembunyikan modal jika kedua nilai adalah 0
                    $('#pulihkanTitleChecklistModal').modal('hide').one('hidden.bs.modal', function() {
                        $('#isianKartu').modal('show');
                    });
                }

                // Mendapatkan data title checklist dari Controller
                // const titleData = response.titlechecklist;
                // const id = titleData.cards_id;
                // const titleId = titleData.id;
                // const percentage = titleData.percentage;
                const {
                    checklist,
                    titlechecklist
                } = response;
                const titleId = titlechecklist.id;
                const percentage = titlechecklist.percentage;

                // Container Judul Checklist ketika dipulihkan
                let pulihkanTitleFormHTML = pulihkanTitleChecklistContainer(response);

                // Konversi string HTML menjadi elemen DOM
                let pulihkanTitleForm = $(pulihkanTitleFormHTML);

                // Fungsi perbaharui title dan checklist ketika HTML dimasukkan
                perbaharuiJudulDanCeklist(titleId, percentage);

                // Menyisipkan title dan Checklist pada posisi yang benar
                posisiPulihkanTitle(pulihkanTitleForm[0], response.titlechecklist.position);

                // Untuk Pindah Title dan Checklist
                pindahTitleDanChecklist();

                // Perbaharui Progress Bar pada UI
                if (response.titlechecklist) {
                    progressBar(titleId, percentage);
                }

                // Perbarui kotak centang checklist semua berdasarkan nilai persentase
                let checklistAllForm = $(`#checklist-all-${titleId}`);
                let checklistCheckbox = checklistAllForm.find(`#checklistform-all-${titleId}`);
                checklistCheckbox.prop('checked', percentage === 100).removeClass('hidden');

                // Untuk Mengatur Icon Checklist //
                $('#iconChecklist-' + response.titlechecklist.cards_id).removeClass('hidden');
                $('#perhitunganChecklist-' + response.titlechecklist.cards_id).html(response.perChecklist +
                    '/' + response.jumlahChecklist);

                // if (response.perChecklist < response.jumlahChecklist) {
                //     var tema_aplikasi = response.result_tema.tema_aplikasi;
                //     var iconChecklist = $('#iconChecklist-' + response.titlechecklist.cards_id);
                //     var iconChecklistCheck = $('#icon-checklist-' + response.titlechecklist.cards_id);

                //     if (tema_aplikasi == 'Terang') {
                //         iconChecklist.removeClass('progress-checklist-100-light').removeClass(
                //             'progress-checklist-100-dark');
                //         iconChecklist.addClass('progress-checklist-light').removeClass(
                //             'progress-checklist-dark');
                //         iconChecklistCheck.addClass('icon-check-not-full-light').removeClass(
                //             'icon-check-not-full-dark');
                //         iconChecklistCheck.removeClass('icon-check-full-light').removeClass(
                //             'icon-check-full-dark');

                //     } else if (tema_aplikasi == 'Gelap') {
                //         iconChecklist.removeClass('progress-checklist-100-dark').removeClass(
                //             'progress-checklist-100-light');
                //         iconChecklist.addClass('progress-checklist-dark').removeClass(
                //             'progress-checklist-light');
                //         iconChecklistCheck.addClass('icon-check-not-full-dark').removeClass(
                //             'icon-check-not-full-light');
                //         iconChecklistCheck.removeClass('icon-check-full-dark').removeClass(
                //             'icon-check-full-light');
                //     }
                // } else if (response.perChecklist == response.jumlahChecklist) {
                //     var tema_aplikasi = response.result_tema.tema_aplikasi;
                //     var iconChecklist = $('#iconChecklist-' + response.titlechecklist.cards_id);
                //     var iconChecklistCheck = $('#icon-checklist-' + response.titlechecklist.cards_id);

                //     if (tema_aplikasi == 'Terang') {
                //         iconChecklist.addClass('progress-checklist-100-light').removeClass(
                //             'progress-checklist-100-dark');
                //         iconChecklist.addClass('progress-checklist-light').removeClass(
                //             'progress-checklist-dark');
                //         iconChecklistCheck.removeClass('icon-check-not-full-light').removeClass(
                //             'icon-check-not-full-dark');
                //         iconChecklistCheck.addClass('icon-check-full-light').removeClass(
                //             'icon-check-full-dark');

                //     } else if (tema_aplikasi == 'Gelap') {
                //         iconChecklist.addClass('progress-checklist-100-dark').removeClass(
                //             'progress-checklist-100-light');
                //         iconChecklist.addClass('progress-checklist-dark').removeClass(
                //             'progress-checklist-light');
                //         iconChecklistCheck.removeClass('icon-check-not-full-dark').removeClass(
                //             'icon-check-not-full-light');
                //         iconChecklistCheck.addClass('icon-check-full-dark').removeClass(
                //             'icon-check-full-light');
                //     }
                // }

                // if (response.perChecklist == 0 && response.jumlahChecklist == 0) {
                //     var tema_aplikasi = response.result_tema.tema_aplikasi;
                //     var iconChecklist = $('#iconChecklist-' + response.titlechecklist.cards_id);

                //     if (tema_aplikasi == 'Terang') {
                //         iconChecklist.addClass('hidden');
                //     } else if (tema_aplikasi == 'Gelap') {
                //         iconChecklist.addClass('hidden');
                //     }
                // }
                // /Untuk Mengatur Icon Checklist //

            } else {
                toastr.error('Gagal memulihkan judul checklist!');
            }
        }).fail(function() {
            toastr.error('Gagal memulihkan judul checklist!');
        });
    }

    // Fungsi untuk memulihkan data judul checklist
    function pulihkanTitleChecklistContainer(response) {
        let html = `
                <div class="menu-checklist border border-1 border-darkss p-2 rounded-xl" data-id="${response.titlechecklist.id}">

                    <!-- Perbaharui & Hapus Judul Checklist -->
                    <div class="header-checklist flex justify-content">
                        <i class="fa-regular fa-square-check fa-xl" style="position: absolute; color: #489bdb; margin-top: 20px;"></i>
                        <form id="myFormTitleUpdate${response.titlechecklist.id}" method="POST" class="update-title">
                            @csrf
                            <input type="hidden" id="title_id" name="title_id" value="${response.titlechecklist.id}">
                            <input type="hidden" id="card_id" name="card_id" value="${response.titlechecklist.cards_id}">
                            <input type="text" class="isian-title border border-1 border-darks w-402 p-2 rounded-xl" style="font-size: 17px" id="titleChecklistUpdate${response.titlechecklist.id}" name="titleChecklistUpdate" placeholder="Enter a title" data-id="${response.titlechecklist.id}" value="${response.titlechecklist.name}">
                            <div class="aksi-update-title gap-2">
                                <button type="submit" class="btn btn-outline-info icon-keterangan hidden" id="saveButtonTitleUpdate${response.titlechecklist.id}">Save</button>
                                <button type="button" class="btn btn-outline-danger icon-keterangan hidden" id="cancelButtonTitleUpdate${response.titlechecklist.id}">Cancel</button>
                            </div>
                        </form>

                        {{-- @if ($isianKartu->history->where('content', 'Membuat Kartu')->where('user_id', auth()->user()->id)->isNotEmpty()) --}}
                            <form id="myFormTitleDelete${response.titlechecklist.id}" method="POST">
                                @csrf
                                <input type="hidden" id="id" name="id" value="${response.titlechecklist.id}">
                                <input type="hidden" id="card_id" name="card_id" value="${response.titlechecklist.cards_id}">
                                <div class="icon-hapus-title" id="hapus-title${response.titlechecklist.id}">
                                    <button type="submit" style="border: none; background: none; padding: 0;">
                                        <div class="info-status5">
                                            <i class="fa fa-trash-o icon-trash"  @if ($result_tema->tema_aplikasi == 'Gelap') style="color: white;" @endif></i>
                                            <span class="text-status5"><b>Delete Title's</b></span>
                                        </div>
                                    </button>
                                </div>
                            </form>
                        {{-- @endif --}}
                    </div>
                    <!-- /Perbaharui & Hapus Judul Checklist -->

                    <!-- Progress Bar Checklist -->
                    <div class="checklist-all gap-2" id="checklist-all-${response.titlechecklist.id}">
                        <form action="{{ route('perbaharuiSemuaChecklist') }}" id="checklistAllForm${response.titlechecklist.id}" method="POST">
                            @csrf
                            <input type="hidden" name="title_checklists_id" value="${response.titlechecklist.id}">
                            <div class="info-status21">
                                <input type="checkbox" class="checklistform-all hidden" name="checklistform-all" id="checklistform-all-${response.titlechecklist.id}" ${response.titleChecklistsPercentage}>
                                <span class="text-status21">
                                    <b>Check All</b>
                                </span>
                            </div>
                        </form>
                    </div>
                    <div class="progress" data-checklist-id="${response.titlechecklist.id}">
                        <div class="progress-bar progress-bar-${response.titlechecklist.id}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
                            0%
                        </div>
                    </div>
                    <!-- Progress Bar Checklist -->

                    <!-- Perbaharui & Hapus Checklist -->
                    <div class="checklist-container" id="checklist-container-${response.titlechecklist.id}">`;

        if (response.checklists && response.checklists.length > 0) {
            response.checklists.forEach(function(checklist) {
                html += `
                        <div id="section-checklist-${checklist.id}" class="input-checklist" data-id="${checklist.id}">
                            <form id="myFormChecklistUpdate${checklist.id}" method="POST" class="form-checklist">
                                @csrf
                                <input class="dynamicCheckbox" type="checkbox" id="${checklist.id}" name="${checklist.id}" ${checklist.is_active ? 'checked' : ''}>
                                <label  onclick="mentionTags4('checkbox-${checklist.id}')" class="dynamicCheckboxLabel border border-1 border-darks w-402 p-2 rounded-xl ${checklist.is_active ? 'strike-through' : ''}" id="labelCheckbox-${checklist.id}" for="labelCheckbox-${checklist.id}">${checklist.name}</label>
                                <input type="hidden" id="checklist_id" name="checklist_id" value="${checklist.id}">
                                <input type="hidden" id="card_id" name="card_id" value="${response.cardId}">
                                <input type="text" class="dynamicCheckboxValue border border-1 border-darks w-402 p-2 rounded-xl hidden" id="checkbox-${checklist.id}" name="checkbox-${checklist.id}" value="${checklist.name}" placeholder="Enter a checklist">
                                <div class="mention-tag" id="mention-tag-checkbox${checklist.id}"></div>

                                <div onclick="checklistUpdate(${checklist.id})" class="aksi-update-checklist2 gap-2 margin-bottom-0" id="checklist-${checklist.id}">
                                    <button type="button" class="saves btn btn-outline-info hidden" id="saveButtonChecklistUpdate-${checklist.id}">Save</button>
                                    <button type="button" class="cancels btn btn-outline-danger hidden" id="cancelButtonChecklistUpdate-${checklist.id}">Cancel</button>
                                </div>
                            </form>
                            <form id="myFormChecklistDelete${checklist.id}" method="POST">
                                @csrf
                                <input type="hidden" id="card_id" name="card_id" value="${response.cardId}">
                                <input type="hidden" id="title_checklists_id" name="title_checklists_id" value="${response.titlechecklist.id}">
                                <input type="hidden" id="id" name="id" value="${checklist.id}">
                                <div class="icon-hapus-checklist" id="hapus-checklist${checklist.id}">
                                    <button type="button" class="deletes" id="deleteButtonChecklist-${checklist.id}" style="border: none; background: none; padding: 0;">
                                        <div class="info-status6">
                                            <i class="fa fa-trash-o icon-trash" @if ($result_tema->tema_aplikasi == 'Gelap') style="color: white;" @endif></i>
                                            <span class="text-status6"><b>Delete Checklist</b></span>
                                        </div>
                                    </button>
                                </div>
                            </form>
                        </div>`;
            });
        }

        html += `
                    </div>
                    <!-- /Perbaharui & Hapus Checklist -->

                    <!-- Tambah baru checklist -->
                    <form id="myFormChecklist${response.titlechecklist.id}" method="POST">
                        @csrf
                        <input type="hidden" id="title_id" name="title_id" value="${response.titlechecklist.id}">
                        <input type="hidden" id="card_id" name="card_id" value="${response.cardId}">
                        <div class="header-tambah-checklist flex gap-4">
                            <i class="fa-xl"></i>
                            <input  type="text" class="tambah-baru-checklist border border-1 border-dark w-407s p-2 rounded-xl hidden" id="checklist${response.titlechecklist.id}" name="checklist" placeholder="Enter a checklist" required>
                            <div class="mention-tag" id="mention-tag-checklist${response.titlechecklist.id}"></div>
                        </div>
                        <div class="aksi-update-checklist gap-2">
                            <button type="submit" class="btn btn-outline-info icon-keterangan hidden" id="saveButtonChecklist${response.titlechecklist.id}">Save</button>
                            <button type="button" class="btn btn-outline-danger icon-keterangan hidden" id="cancelButtonChecklist${response.titlechecklist.id}">Cancel</button>
                        </div>
                    </form>



             <button type="button" onclick="mentionTags('checklist${response.titlechecklist.id}')" class="btn btn-outline-info add-checklist" id="AddChecklist" data-id="${response.titlechecklist.id}" data-persen="${response.percentage}"><i class="fa-solid fa-plus" aria-hidden="true"></i> Add an Item...</button>
                                        <!-- Tambah baru checklist -->

                </div>`;
        return html;
    }

    // Fungsi untuk memperbaharui judul checklist dan tambah checklist
    function perbaharuiJudulDanCeklist(titleId, percentage) {

    }

    // Fungsi untuk memulihkan Judul Checklist ke dalam posisinya
    function posisiPulihkanTitle(titleForm, position) {
        let titleContainer = document.getElementById('titleContainer');
        if (position > 0) {
            let referenceNode = titleContainer.children[position - 1];
            titleContainer.insertBefore(titleForm, referenceNode);
        } else {
            let titleArray = Array.from(titleContainer.children);
            titleArray.push(titleForm);
            titleArray.sort((a, b) => a.getAttribute('data-id') - b.getAttribute('data-id'));
            titleContainer.innerHTML = '';
            titleArray.forEach(titlechecklist => titleContainer.appendChild(titlechecklist));
        }
    }

    // Fungsi untuk menghapus judul checklist secara permanen
    function deleteTitle(cardId) {
        $.ajax({
            url: '{{ route('hapusJudulChecklistPermanen') }}',
            method: 'POST',
            data: {
                id: cardId,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.message === 'Berhasil menghapus judul checklist permanen!') {
                    toastr.success(response.message);

                    // Hapus title dari daftar modal
                    $('#pulihkan-title-' + cardId).remove();

                    // Perbarui visibilitas tautan Pulihkan Judul & Checklist
                    var softDeletedTitle = response.softDeletedTitle;
                    var softDeletedChecklist = response.softDeletedChecklist;
                    var recoverTitleChecklist = $('#recover-title-checklist-' + cardId);

                    if (softDeletedTitle > 0 || softDeletedChecklist > 0) {
                        recoverTitleChecklist.show();
                    } else {
                        recoverTitleChecklist.hide();

                        // Hanya sembunyikan modal jika kedua nilai adalah 0
                        $('#pulihkanTitleChecklistModal').modal('hide').one('hidden.bs.modal', function() {
                            $('#isianKartu').modal('show');
                        });
                    }

                } else {
                    toastr.error('Gagal menghapus judul checklist!');
                }
            }
        }).fail(function() {
            toastr.error('Gagal menghapus judul checklist!');
        });
    }
    // ---------------------------------------------------------- /Pemulihan Data Judul Checklist ---------------------------------------------------------- //


    // ---------------------------------------------------------- Pemulihan Data Checklist ---------------------------------------------------------- //
    // Fungsi untuk memulihkan checklist
    function pulihkanChecklist(cardId) {
        $.post('{{ route('pulihkanChecklist') }}', {
            id: cardId,
            _token: '{{ csrf_token() }}'
        }, function(response) {
            if (response.message === 'Berhasil memulihkan checklist!') {
                toastr.success(response.message);

                // Hapus title dari daftar modal
                $('#pulihkan-checklist-' + cardId).remove();

                // Perbarui visibilitas tautan Pulihkan Judul & Checklist
                var softDeletedTitle = response.softDeletedTitle;
                var softDeletedChecklist = response.softDeletedChecklist;
                var recoverTitleChecklist = $('#recover-title-checklist-' + cardId);

                if (softDeletedTitle > 0 || softDeletedChecklist > 0) {
                    recoverTitleChecklist.show();
                } else {
                    recoverTitleChecklist.hide();

                    // Hanya sembunyikan modal jika kedua nilai adalah 0
                    $('#pulihkanTitleChecklistModal').modal('hide').one('hidden.bs.modal', function() {
                        $('#isianKartu').modal('show');
                    });
                }

                // Mendapatkan data title checklist dan checklist dari Controller
                const {
                    checklist,
                    titlechecklist
                } = response;
                const titleId = titlechecklist.id;
                const percentage = titlechecklist.percentage;

                // Container Checklist ketika dipulihkan
                let pulihkanChecklistFormHTML = pulihkanChecklistContainer(checklist, titlechecklist);

                // Konversi string HTML menjadi elemen DOM
                let pulihkanChecklistForm = $(pulihkanChecklistFormHTML);

                // Menyisipkan title dan Checklist pada posisi yang benar
                posisiPulihkanChecklist(pulihkanChecklistForm[0], response.checklist.position, response
                    .titlechecklist.id);

                // Untuk Pindah Title dan Checklist
                pindahTitleDanChecklist();

                // Perbaharui Progress Bar pada UI
                if (response.titlechecklist) {
                    progressBar(titleId, percentage);
                }

                // Perbarui kotak centang checklist semua berdasarkan nilai persentase
                let checklistAllForm = $(`#checklist-all-${titleId}`);
                let checklistCheckbox = checklistAllForm.find(`#checklistform-all-${titleId}`);
                checklistCheckbox.prop('checked', percentage === 100).removeClass('hidden');

                // Untuk Mengatur Icon Checklist //
                $('#iconChecklist-' + response.titlechecklist.cards_id).removeClass('hidden');
                $('#perhitunganChecklist-' + response.titlechecklist.cards_id).html(response.perChecklist +
                    '/' + response.jumlahChecklist);

                if (response.perChecklist < response.jumlahChecklist) {
                    var tema_aplikasi = response.result_tema.tema_aplikasi;
                    var iconChecklist = $('#iconChecklist-' + response.titlechecklist.cards_id);
                    var iconChecklistCheck = $('#icon-checklist-' + response.titlechecklist.cards_id);

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
                    var tema_aplikasi = response.result_tema.tema_aplikasi;
                    var iconChecklist = $('#iconChecklist-' + response.titlechecklist.cards_id);
                    var iconChecklistCheck = $('#icon-checklist-' + response.titlechecklist.cards_id);

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
                    var tema_aplikasi = response.result_tema.tema_aplikasi;
                    var iconChecklist = $('#iconChecklist-' + response.titlechecklist.cards_id);

                    if (tema_aplikasi == 'Terang') {
                        iconChecklist.addClass('hidden');
                    } else if (tema_aplikasi == 'Gelap') {
                        iconChecklist.addClass('hidden');
                    }
                }
                // /Untuk Mengatur Icon Checklist //

            } else {
                toastr.error('Gagal memulihkan checklist!');
            }
        }).fail(function() {
            toastr.error('Gagal memulihkan checklist!');
        });
    }

    // Fungsi untuk memulihkan data checklist
    function pulihkanChecklistContainer(checklist, titlechecklist) {
        return `
                <div id="section-checklist-${checklist.id}" class="input-checklist" data-id="${checklist.id}">
                    <form id="myFormChecklistUpdate${checklist.id}" method="POST" class="form-checklist">
                        @csrf
                        <input class="dynamicCheckbox" type="checkbox" id="${checklist.id}" name="${checklist.id}" ${checklist.is_active ? 'checked' : ''}>
                        <label onclick="mentionTags4('checkbox-${checklist.id}')" class="dynamicCheckboxLabel border border-1 border-darks w-402 p-2 rounded-xl ${checklist.is_active ? 'strike-through' : ''}" id="labelCheckbox-${checklist.id}" for="labelCheckbox-${checklist.id}">${checklist.name}</label>
                        <input type="hidden" id="checklist_id" name="checklist_id" value="${checklist.id}">
                        <input type="hidden" id="card_id" name="card_id" value="${titlechecklist.cards_id}">
                        <input  type="text" class="dynamicCheckboxValue border border-1 border-darks w-402 p-2 rounded-xl hidden" id="checkbox-${checklist.id}" name="checkbox-${checklist.id}" value="${checklist.name}" placeholder="Enter a checklist">
                        <div class="mention-tag" id="mention-tag-checkbox${checklist.id}"></div>

                        <div onclick="checklistUpdate(${checklist.id})" class="aksi-update-checklist2 gap-2 margin-bottom-0" id="checklist-${checklist.id}">
                            <button type="button" class="saves btn btn-outline-info hidden" id="saveButtonChecklistUpdate-${checklist.id}">Save</button>
                            <button type="button" class="cancels btn btn-outline-danger hidden" id="cancelButtonChecklistUpdate-${checklist.id}">Cancel</button>
                        </div>
                    </form>
                    <form id="myFormChecklistDelete${checklist.id}" method="POST">
                        @csrf
                        <input type="hidden" id="card_id" name="card_id" value="${titlechecklist.cards_id}">
                        <input type="hidden" id="title_checklists_id" name="title_checklists_id" value="${titlechecklist.id}">
                        <input type="hidden" id="id" name="id" value="${checklist.id}">
                        <div class="icon-hapus-checklist" id="hapus-checklist${checklist.id}">
                            <button type="button" class="deletes" id="deleteButtonChecklist-${checklist.id}" style="border: none; background: none; padding: 0;">
                                <div class="info-status6">
                                    <i class="fa fa-trash-o icon-trash"  @if ($result_tema->tema_aplikasi == 'Gelap') style="color: white;" @endif ></i>
                                    <span class="text-status6"><b>Delete Checklist</b></span>
                                </div>
                            </button>
                        </div>
                    </form>
                </div>`;
    }

    // Fungsi untuk memulihkan Checklist ke dalam posisinya
    function posisiPulihkanChecklist(checklistForm, position, titleID) {
        let checklistContainer = document.getElementById(`checklist-container-${titleID}`);
        if (position > 0) {
            let referenceNode = checklistContainer.children[position - 1];
            checklistContainer.insertBefore(checklistForm, referenceNode);
        } else {
            let checklistArray = Array.from(checklistContainer.children);
            checklistArray.push(checklistForm);
            checklistArray.sort((a, b) => a.getAttribute('data-id') - b.getAttribute('data-id'));
            checklistContainer.innerHTML = '';
            checklistArray.forEach(checklist => checklistContainer.appendChild(checklist));
        }
    }

    // Fungsi untuk menghapus checklist secara permanen
    function deleteChecklist(cardId) {
        $.ajax({
            url: '{{ route('hapusChecklistPermanen') }}',
            method: 'POST',
            data: {
                id: cardId,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.message === 'Berhasil menghapus checklist permanen!') {
                    toastr.success(response.message);

                    // Hapus title dari daftar modal
                    $('#pulihkan-checklist-' + cardId).remove();

                    // Perbarui visibilitas tautan Pulihkan Judul & Checklist
                    var softDeletedTitle = response.softDeletedTitle;
                    var softDeletedChecklist = response.softDeletedChecklist;
                    var recoverTitleChecklist = $('#recover-title-checklist-' + cardId);

                    if (softDeletedTitle > 0 || softDeletedChecklist > 0) {
                        recoverTitleChecklist.show();
                    } else {
                        recoverTitleChecklist.hide();

                        // Hanya sembunyikan modal jika kedua nilai adalah 0
                        $('#pulihkanTitleChecklistModal').modal('hide').one('hidden.bs.modal', function() {
                            $('#isianKartu').modal('show');
                        });
                    }

                } else {
                    toastr.error('Gagal menghapus judul checklist!');
                }
            }
        }).fail(function() {
            toastr.error('Gagal menghapus judul checklist!');
        });
    }
    // ---------------------------------------------------------- /Pemulihan Data Checklist ---------------------------------------------------------- //


    // ---------------------------------------------------------- Untuk Perbaharui Posisi Judul & Checklist, Progress Bar ---------------------------------------------------------- //
    // Fungsi untuk pindah posisi Judul Checklist dan Checklist
    function pindahTitleDanChecklist() {
        $(document).ready(function() {
            const titleContainer = document.getElementById('titleContainer');
            const sortable = new Sortable(titleContainer, {
                animation: 150,
                onEnd: function(evt) {
                    updateTitlePositions();
                },
            });

            const checklistsContainers = [...document.getElementsByClassName('checklist-container')];
            checklistsContainers.forEach(e => {
                new Sortable(e, {
                    animation: 150,
                    group: 'checklists',
                    onEnd: function(evt) {
                        updateChecklistPositions();
                    },
                });
            });

            function updateTitlePositions() {
                const positions = {};
                const titleIds = titleContainer.children;
                for (let i = 0; i < titleIds.length; i++) {
                    const title = titleIds[i];
                    const id = title.dataset.id;
                    if (id !== undefined) {
                        positions[id] = i + 1;
                    }
                }

                fetch('{{ route('perbaharuiPosisiJudul') }}', {
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
                            toastr.success('Berhasil perbaharui posisi judul!');
                        } else {
                            toastr.error('Gagal perbaharui posisi judul!');
                        }
                    })

                    .catch(error => {
                        console.error('Terjadi kesalahan saat perbaharui posisi judul:', error);
                    });

            }

            function updateChecklistPositions() {
                const positions = {};
                checklistsContainers.forEach(container => {
                    const titleId = container.closest('.menu-checklist').dataset.id;
                    const checklists = container.children;
                    for (let i = 0; i < checklists.length; i++) {
                        const checklist = checklists[i];
                        const id = checklist.dataset.id;
                        if (id !== undefined) {
                            positions[id] = {
                                position: i + 1,
                                title_id: titleId
                            };
                        }
                    }
                });

                fetch('{{ route('perbaharuiPosisiCeklist') }}', {
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
                            toastr.success('Berhasil perbaharui posisi checklist!');
                        } else {
                            toastr.error('Gagal perbaharui posisi checklist!');
                        }

                        // Perbaharui Progress Bar pada UI
                        if (data.titlechecklist) {
                            data.titlechecklist.forEach(tc => {
                                progressBar(tc.id, tc.percentage);
                            });
                        }
                    })

                    .catch(error => {
                        console.error('Terjadi kesalahan saat perbaharui posisi checklist:', error);
                    });

            }
        });
    }

    // Fungsi untuk perbaharui progress bar
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

    function checklistUpdate(id) {
        $(document).ready(function() {
            const dynamicCheckboxValue = document.getElementById(`checkbox-${id}`);
            const aksiUpdateChecklist = document.getElementById(`checklist-${id}`);
            const saveButton = document.getElementById(`saveButtonChecklistUpdate-${id}`);
            const cancelButton = document.getElementById(`cancelButtonChecklistUpdate-${id}`);


            dynamicCheckboxValue.addEventListener('click', function() {
                aksiUpdateChecklist.style.marginBottom = '10px';
                aksiUpdateChecklist.style.marginTop = '5px';
            });

            saveButton.addEventListener('click', function() {
                aksiUpdateChecklist.style.marginBottom = '0';
                aksiUpdateChecklist.style.marginTop = '0';
            });

            cancelButton.addEventListener('click', function() {
                aksiUpdateChecklist.style.marginBottom = '0';
                aksiUpdateChecklist.style.marginTop = '0';
            });
        });
    }
    // ---------------------------------------------------------- /Untuk Perbaharui Posisi Judul & Checklist, Progress Bar ---------------------------------------------------------- //
</script>

<script>
    $(document).ready(function() {
        $(document).on('click', '.add-title', function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            const id = $(this).data('id');
            $('#titleChecklist' + id).removeClass('hidden');
            $('#titleChecklist' + id).focus();
            $('#saveButtonTitle' + id).removeClass('hidden');
            $('#makeTemplate' + id).removeClass('hidden');
            $('#cancelButtonTitle' + id).removeClass('hidden');
            $('#iconCheck-' + id).removeClass('hidden');
            $('#addTitle-' + id).addClass('hidden');
            $('#saveButtonTitle' + id).prop('disabled', false);
            $('#cancelButtonTitle' + id).prop('disabled', false);
            $('#myFormTitle' + id)[0].reset();

            // Button cancel form title
            $('#cancelButtonTitle' + id).on('click', function() {
                $('#titleChecklist' + id).addClass('hidden');
                $('#saveButtonTitle' + id).addClass('hidden');
                $('#makeTemplate' + id).addClass('hidden');
                $('#cancelButtonTitle' + id).addClass('hidden');
                $('#iconCheck-' + id).addClass('hidden');
                $('#addTitle-' + id).removeClass('hidden');
                $('#myFormTitle' + id)[0].reset();
            });
            // Form title
            isSubmitting = false;
            $('#myFormTitle' + id).on('submit', function(event) {
                event.preventDefault();
                $('.spinner-border').removeClass('d-none');
                $('#saveButtonTitle' + id).prop('disabled', true);
                $('#cancelButtonTitle' + id).prop('disabled', true);
                // Mencegah pengiriman ganda
                if (isSubmitting) return;
                isSubmitting = true;
                var formData = $(this).serialize();

                // Periksa masukan yang kosong
                var isEmpty = false;
                var titleField = $('#titleChecklist' + id);
                if (titleField.val().trim() === '') {
                    isEmpty = true;
                }

                if (isEmpty) {
                    toastr.error('Bidang judul tidak boleh kosong!');

                    // Setel ulang tanda
                    isSubmitting = false;
                    return;
                }

                $.ajax({
                    type: 'POST',
                    url: "{{ route('addTitle') }}",
                    data: formData,
                    success: function(response) {
                        $('.spinner-border').addClass('d-none');
                        // Mendapatkan ID dan Percentage dari Controller //
                        const title_id = `${response.titlechecklist.id}`;
                        const percentage = `${response.titlechecklist.percentage}`;
                        $('#titleChecklist' + id).addClass('hidden');
                        $('#saveButtonTitle' + id).addClass('hidden');
                        $('#makeTemplate' + id).addClass('hidden');
                        $('#cancelButtonTitle' + id).addClass('hidden');
                        $('#iconCheck-' + id).addClass('hidden');
                        $('#addTitle-' + id).removeClass('hidden');
                        toastr.success('Berhasil menambahkan judul!');
                        var newForm = `<div class="menu-checklist border border-1 border-darkss p-2 rounded-xl" data-id="${response.titlechecklist.id}">
                                        <!-- Perbaharui & Hapus Judul Checklist -->
                                        <div class="header-checklist flex justify-content">
                                            <i class="fa-regular fa-square-check fa-xl" style="position: absolute; color: #489bdb; margin-top: 20px;"></i>
                                            <form id="myFormTitleUpdate${response.titlechecklist.id}" method="POST" class="update-title">
                                                @csrf
                                                    <input type="hidden" id="title_id" name="title_id" value="${response.titlechecklist.id}">
                                                    <input type="hidden" id="card_id" name="card_id" value="${response.titlechecklist.cards_id}">
                                                    <input type="text" class="isian-title border border-1 border-darks w-402 p-2 rounded-xl" style="font-size: 17px" id="titleChecklistUpdate${response.titlechecklist.id}" name="titleChecklistUpdate" placeholder="Enter a title"  data-id="${response.titlechecklist.id}"value="${response.titlechecklist.name}">
                                                    <div class="aksi-update-title gap-2">
                                                        <button type="submit" class="btn btn-outline-info icon-keterangan hidden" id="saveButtonTitleUpdate${response.titlechecklist.id}">
                                                             <span class="spinner-border spinner-border-sm mr-2 mb-1 d-none" role="status"
                                        aria-hidden="true">
                                    </span>
                                                            Save</button>
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
                                                                <i class="fa fa-trash-o icon-trash"  @if ($result_tema->tema_aplikasi == 'Gelap') style="color: white;" @endif ></i>
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
                                        <div class="checklist-container" id="checklist-container-${response.titlechecklist.id}"></div>
                                        <!-- /Perbaharui & Hapus Checklist -->

                                        <!-- Tambah baru checklist -->
                                        <form id="myFormChecklist${response.titlechecklist.id}" method="POST">
                                            @csrf
                                                <input type="hidden" id="title_id" name="title_id" value="${response.titlechecklist.id}">
                                                <input type="hidden" id="card_id" name="card_id" value="${response.titlechecklist.cards_id}">
                                                <div class="header-tambah-checklist flex gap-4">
                                                    <i class="fa-xl"></i>
                                                    <input  type="text" class="tambah-baru-checklist border border-1 border-dark w-407s p-2 rounded-xl hidden" id="checklist${response.titlechecklist.id}" name="checklist" placeholder="Enter a checklist" required>
                                                    <div class="mention-tag" id="mention-tag-checklist${response.titlechecklist.id}"></div>
                                                </div>
                                                <div class="aksi-update-checklist gap-2">
                                                    <button  type="submit" class="btn btn-outline-info icon-keterangan hidden" id="saveButtonChecklist${response.titlechecklist.id}">
                                                         <span class="spinner-border spinner-border-sm mr-2 mb-1 d-none" role="status"
                                        aria-hidden="true">
                                    </span>
                                                        Save</button>
                                                    <button type="button" class="btn btn-outline-danger icon-keterangan hidden" id="cancelButtonChecklist${response.titlechecklist.id}">Cancel</button>
                                                </div>
                                        </form>

                                        <button type="button" onclick="mentionTags('checklist${response.titlechecklist.id}')" class="btn btn-outline-info add-checklist" id="AddChecklist" data-id="${response.titlechecklist.id}" data-persen="${response.percentage}"><i class="fa-solid fa-plus" aria-hidden="true"></i> Add an Item...</button>
                                        <!-- Tambah baru checklist -->

                                    </div>`;
                        $('#titleContainer').append(newForm);
                        let isInputFocused = false;
                        const checklistsContainers = document.getElementById(
                            `checklist-container-${title_id}`);
                        let initialChecklistPositions = getChecklistPositions();


                        $(document).on('focus',
                            '.dynamicCheckboxValue',
                            function(e) {
                                e.preventDefault();
                                isInputFocused = true;
                                disableDragAndDrop();
                            });
                        $(document).on('blur',
                            '.dynamicCheckboxValue ',
                            function(e) {
                                e.preventDefault();
                                isInputFocused = false;
                                enableDragAndDrop();
                            });

                        function disableDragAndDrop() {
                            check.option("disabled", true);
                        }

                        function enableDragAndDrop() {
                            check.option("disabled", false);
                        }


                        const check = new Sortable(checklistsContainers, {
                            animation: 150,
                            group: 'checklists',
                            onEnd: function(evt) {
                                const newChecklistPositions =
                                    getChecklistPositions();
                                if (JSON.stringify(
                                        initialChecklistPositions) !==
                                    JSON.stringify(
                                        newChecklistPositions)) {
                                    updateChecklistPositions(
                                        newChecklistPositions);
                                    initialChecklistPositions =
                                        newChecklistPositions;
                                }
                            },
                        });

                        function getChecklistPositions() {
                            const positions = {};
                            const titleIds = checklistsContainers.closest(
                                '.menu-checklist').dataset.id;
                            const checklists = checklistsContainers.children;
                            for (let i = 0; i < checklists.length; i++) {
                                const checklist = checklists[i];
                                const id = checklist.dataset.id;
                                if (id !== undefined) {
                                    positions[id] = {
                                        position: i + 1,
                                        title_id: titleIds
                                    };
                                }
                            }
                            return positions;
                        }

                        function updateChecklistPositions(positions) {
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
                                        toastr.success(
                                            'Berhasil perbaharui posisi checklist!'
                                        );
                                    } else {
                                        toastr.error(
                                            'Gagal perbaharui posisi checklist!'
                                        );
                                    }

                                    if (data.titlechecklist) {
                                        data.titlechecklist.forEach(tc => {
                                            progressBar(tc.id, tc
                                                .percentage);
                                        });
                                    }
                                })
                                .catch(error => {
                                    console.error(
                                        'Terjadi kesalahan saat perbaharui posisi checklist:',
                                        error);
                                });
                        }

                    },
                    error: function(error) {
                        toastr.error('Gagal menambahkan judul!');
                    }
                });
            });
        });
    });
</script>

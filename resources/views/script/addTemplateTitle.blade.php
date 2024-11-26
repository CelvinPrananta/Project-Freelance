<script>
    $(document).ready(function() {
        const id = '{{ $isianKartu->id }}';

        // Tandai untuk mencegah pengiriman berulang kali
        let isSubmitting = false;

        // Section Title
        // Button add form title
        $('#addTitle-' + id).on('click', function() {
            $('#titleChecklist' + id).removeClass('hidden');
            $('#saveButtonTitle' + id).removeClass('hidden');
            $('#makeTemplate' + id).removeClass('hidden');
            $('#cancelButtonTitle' + id).removeClass('hidden');
            $('#iconCheck-' + id).removeClass('hidden');
            $('#addTitle-' + id).addClass('hidden');
            $('#makeTemplate' + id).prop('disabled', false);
            $('#makeTemplateForm' + id)[0].reset();
        });
        // Button cancel form title
        $('#cancelButtonTitle' + id).on('click', function() {
            $('#titleChecklist' + id).addClass('hidden');
            $('#saveButtonTitle' + id).addClass('hidden');
            $('#makeTemplate' + id).addClass('hidden');
            $('#cancelButtonTitle' + id).addClass('hidden');
            $('#iconCheck-' + id).addClass('hidden');
            $('#addTitle-' + id).removeClass('hidden');
            $('#makeTemplateForm' + id)[0].reset();
        });
        // Form title
        $('#makeTemplateForm' + id).on('submit', function(event) {
            event.preventDefault();
            $('#makeTemplate' + id).prop('disabled', true);
            // Mencegah pengiriman ganda
            if (isSubmitting) return;

            isSubmitting = true;
            var formData = $(this).serialize();

            $.ajax({
                type: 'POST',
                url: "{{ route('templateTitle') }}",
                data: formData,
                success: function(response) {
                    $('#titleChecklist' + id).addClass('hidden');
                    $('#saveButtonTitle' + id).addClass('hidden');
                    $('#makeTemplate' + id).addClass('hidden');
                    $('#cancelButtonTitle' + id).addClass('hidden');
                    $('#iconCheck-' + id).addClass('hidden');
                    $('#addTitle-' + id).removeClass('hidden');
                    toastr.success('Berhasil membuat template judul!');
                    response.titlechecklists.forEach(function(titlechecklist) {
                        // Mendapatkan ID dan Percentage dari Controller //
                        const title_id = `${titlechecklist.id}`;
                        const percentage = `${titlechecklist.percentage}`;
                        var newTemplate = `
                            <div class="menu-checklist border border-1 border-darkss p-2 rounded-xl" data-id="${titlechecklist.id}">
                                <!-- Perbaharui & Hapus Judul Checklist -->
                                <div class="header-checklist flex justify-content">
                                    <i class="fa-regular fa-square-check fa-xl" style="position: absolute; color: #489bdb; margin-top: 20px;"></i>
                                    <form id="myFormTitleUpdate${titlechecklist.id}" method="POST" class="update-title">
                                        @csrf
                                        <input type="hidden" id="title_id" name="title_id" value="${titlechecklist.id}">
                                        <input type="hidden" id="card_id" name="card_id" value="${titlechecklist.cards_id}">
                                        <input type="text" class="isian-title border border-1 border-darks w-402 p-2 rounded-xl" style="font-size: 17px" id="titleChecklistUpdate${titlechecklist.id}" name="titleChecklistUpdate" placeholder="Enter a title" data-id="${titlechecklist.id}" value="${titlechecklist.name}">
                                        <div class="aksi-update-title gap-2">
                                            <button type="submit" class="btn btn-outline-info icon-keterangan hidden" id="saveButtonTitleUpdate${titlechecklist.id}">
                                                 <span class="spinner-border spinner-border-sm mr-2 mb-1 d-none" role="status"
                                        aria-hidden="true">
                                    </span>
                                                Save</button>
                                            <button type="button" class="btn btn-outline-danger icon-keterangan hidden" id="cancelButtonTitleUpdate${titlechecklist.id}">Cancel</button>
                                        </div>
                                    </form>

                                    {{-- @if ($isianKartu->history->where('content', 'Membuat Kartu')->where('user_id', auth()->user()->id)->isNotEmpty()) --}}
                                        <form id="myFormTitleDelete${titlechecklist.id}" method="POST">
                                            @csrf
                                            <input type="hidden" id="id" name="id" value="${titlechecklist.id}">
                                            <input type="hidden" id="card_id" name="card_id" value="${titlechecklist.cards_id}">
                                            <div class="icon-hapus-title" id="hapus-title${titlechecklist.id}">
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
                                <div class="checklist-all gap-2" id="checklist-all-${titlechecklist.id}">
                                    <form action="{{ route('perbaharuiSemuaChecklist') }}" id="checklistAllForm${titlechecklist.id}" method="POST">
                                        @csrf
                                        <input type="hidden" name="title_checklists_id" value="${titlechecklist.id}">
                                        <div class="info-status21">
                                            <input type="checkbox" class="checklistform-all hidden" name="checklistform-all" id="checklistform-all-${titlechecklist.id}">
                                            <span class="text-status21">
                                                <b>Check All</b>
                                            </span>
                                        </div>
                                    </form>
                                </div>
                                <div class="progress" data-checklist-id="${titlechecklist.id}">
                                    <div class="progress-bar progress-bar-${titlechecklist.id}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
                                        0%
                                    </div>
                                </div>
                                <!-- Progress Bar Checklist -->

                                <!-- Perbaharui & Hapus Checklist -->
                                <div class="checklist-container" id="checklist-container-${titlechecklist.id}"></div>
                                <!-- /Perbaharui & Hapus Checklist -->

                                <!-- Tambah baru checklist -->
                                <form id="myFormChecklist${titlechecklist.id}" method="POST">
                                    @csrf
                                    <input type="hidden" id="title_id" name="title_id" value="${titlechecklist.id}">
                                    <input type="hidden" id="card_id" name="card_id" value="${titlechecklist.cards_id}">
                                    <div class="header-tambah-checklist flex gap-4">
                                        <i class="fa-xl"></i>
                                        <input  type="text" class="tambah-baru-checklist border border-1 border-dark w-407s p-2 rounded-xl hidden" id="checklist${titlechecklist.id}" name="checklist" placeholder="Enter a checklist" required>
                                        <div class="mention-tag" id="mention-tag-checklist${titlechecklist.id}"></div>
                                    </div>
                                    <div class="aksi-update-checklist gap-2">
                                        <button type="submit" class="btn btn-outline-info icon-keterangan hidden" id="saveButtonChecklist${titlechecklist.id}">
                                             <span class="spinner-border spinner-border-sm mr-2 mb-1 d-none" role="status"
                                        aria-hidden="true">
                                    </span>
                                            Save</button>
                                        <button type="button" class="btn btn-outline-danger icon-keterangan hidden" id="cancelButtonChecklist${titlechecklist.id}">Cancel</button>
                                    </div>
                                </form>
                                <button type="button" onclick="mentionTags('checklist${titlechecklist.id}')" class="btn btn-outline-info add-checklist" id="AddChecklist" data-id="${titlechecklist.id}" data-persen="${titlechecklist.percentage}"><i class="fa-solid fa-plus" aria-hidden="true"></i> Add an Item...</button>
                                        <!-- Tambah baru checklist -->

                            </div>`;
                        $('#titleContainer').append(newTemplate);
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
                    });
                },
                error: function(xhr, status, error) {
                    toastr.error('Gagal membuat template judul!');
                }
            });
        });
        // End Section Title
    });
</script>

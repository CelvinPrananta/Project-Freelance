<script>
    $(document).ready(function() {

        let isInputFocused = false;
        const titleContainer = document.getElementById('titleContainer');
        const checklistsContainers = [...document.getElementsByClassName('checklist-container')];
        const sortableInstances = [];
        let initialTitlePositions = getTitlePositions(); // Simpan posisi awal titles
        // let initialChecklistPositions = getChecklistPositions();
        let initialChecklistPositions = getChecklistPositions();

        // Ketika input atau title difokuskan
        $(document).on('focus', '.isian-title, .dynamicCheckboxValue , .tambah-baru-checklist', function(e) {
            e.preventDefault();
            isInputFocused = true;
            disableDragAndDrop();
        });

        // Ketika input atau title kehilangan fokus
        $(document).on('blur', '.isian-title, .dynamicCheckboxValue ,.tambah-baru-checklist', function() {
            isInputFocused = false;
            enableDragAndDrop();
        });

        // Initialize sortable untuk title
        const titleSortable = new Sortable(titleContainer, {
            animation: 150,
            delay: 200,
            delayOnTouchOnly: true,
            onEnd: function(evt) {
                const newTitlePositions = getTitlePositions();
                if (JSON.stringify(initialTitlePositions) !== JSON.stringify(newTitlePositions)) {
                    updateTitlePositions(newTitlePositions);
                    initialTitlePositions =
                        newTitlePositions; // Update posisi awal dengan posisi baru
                }
            },
        });

        // Initialize sortable untuk checklists
        // const checklistsContainers = [...document.getElementsByClassName('checklist-container')];
        checklistsContainers.forEach(e => {
            const sortableInstance = new Sortable(e, {
                animation: 150,
                delay: 200,
                delayOnTouchOnly: true,
                group: 'checklists',
                onEnd: function(evt) {
                    if (!isInputFocused) {
                        const newChecklistPositions = getChecklistPositions();

                        if (JSON.stringify(initialChecklistPositions) !== JSON.stringify(
                                newChecklistPositions)) {
                            updateChecklistPositions(newChecklistPositions);
                            initialChecklistPositions =
                                newChecklistPositions; // Update posisi awal dengan posisi baru
                        }
                    }
                },
            });
            sortableInstances.push(sortableInstance);
        });

        // Disable drag-and-drop
        function disableDragAndDrop() {
            titleSortable.option("disabled", true);
            sortableInstances.forEach(sortableInstance => {
                sortableInstance.option("disabled", true);
            });
        }

        // Enable drag-and-drop
        function enableDragAndDrop() {
            titleSortable.option("disabled", false);
            sortableInstances.forEach(sortableInstance => {
                sortableInstance.option("disabled", false);
            });
        }

        // Get positions for titles
        function getTitlePositions() {
            const positions = {};
            const titleIds = titleContainer.children;
            for (let i = 0; i < titleIds.length; i++) {
                const title = titleIds[i];
                const id = title.dataset.id;
                if (id !== undefined) {
                    positions[id] = i + 1;
                }
            }
            return positions;
        }

        // Update positions for titles
        function updateTitlePositions(positions) {
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

        // Get positions for checklists
        function getChecklistPositions() {
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
            return positions;
        }

        // Update positions for checklists
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
                        toastr.success('Berhasil perbaharui posisi checklist!');
                    } else {
                        toastr.error('Gagal perbaharui posisi checklist!');
                    }

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
</script>

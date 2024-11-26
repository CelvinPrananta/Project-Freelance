// Khusus ketika klik button +Tambah Kartu //
function openAdd(id) {
    // $('.input-card-name').focus();
    const cardLoghub = document.getElementById(`cardLoghub${id}`);
    const btnadd = document.getElementById(`btn-add${id}`);
    $('.btn-add').prop('disabled', false)
    let style = cardLoghub.getAttribute("class");

    if (style.includes('flex')) {
        cardLoghub.classList.remove("flex");
        btnadd.innerHTML = "<i class='fa-solid fa-plus'></i> Add a card...";
    } else {
        cardLoghub.classList.add("flex");
        btnadd.innerHTML = "Cancel";
    }
    // cardName{{ $dataKolom->id }}
    setTimeout(function() {
            // $('#cardName' + id).focus();
        document.querySelector(`#cardLoghub${id} #cardName${id}`).focus();
    }, 100); // Delay agar input sudah terlihat sebelum fokus
}
// /Khusus ketika klik button +Tambah Kartu //

// Khusus untuk menghilangkan dan memunculkan edit & delete beserta menghilangkan text yang ada di +Tambah Kartu //
function hideAdd() {
    const targetCard = document.querySelectorAll(".card-loghub");
    targetCard.forEach(element => {
        element.classList.add("hidden");
    });
}
function aksiKolomShow(id) {
    const aksiKolom = document.getElementById(`aksi-kolom${id}`);
    const aksiKolom2 = document.getElementById(`aksi-kolom2${id}`);
    if (aksiKolom) {
        aksiKolom.classList.remove("hidden");
        aksiKolom.classList.add("flex");
    }
    if (aksiKolom2) {
        aksiKolom2.classList.remove("hidden");
        aksiKolom2.classList.add("flex");
    }
}
function aksiKolomHide(id) {
    const aksiKolom = document.getElementById(`aksi-kolom${id}`);
    const aksiKolom2 = document.getElementById(`aksi-kolom2${id}`);
    if (aksiKolom) {
        aksiKolom.classList.remove("flex");
        aksiKolom.classList.add("hidden");
    }
    if (aksiKolom2) {
        aksiKolom2.classList.remove("flex");
        aksiKolom2.classList.add("hidden");
    }
    hideAdd();
}
// /Khusus untuk menghilangkan dan memunculkan edit & delete beserta menghilangkan text yang ada di +Tambah Kartu //

// Khusus untuk menghilangkan dan memunculkan edit kartu //
function hideKartu() {
    const targetKartu = document.querySelectorAll(".aksi-card");
    targetKartu.forEach(element => {
        element.classList.add("hidden");
    });
}
function aksiKartuShow(id){
    const aksiKartu = document.getElementById(`aksi-card${id}`);
    aksiKartu.classList.add("flex");
}
function aksiKartuHide(id){
    const aksiKartu = document.getElementById(`aksi-card${id}`);
    aksiKartu.classList.remove("flex");
    aksiKartu.classList.add("hidden");
    hideKartu()
}
// /Khusus untuk menghilangkan dan memunculkan edit kartu //

// Untuk menyembunyikan dan melihat activity //
function showActivity(id){
    var activityDiv = document.getElementById(`showActivity${id}`);
    var icon = document.getElementById(`showActivityIcon${id}`);

    if (icon.classList.contains('fa-eye')) {
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }

    if (activityDiv.classList.contains('hiddens')) {
        activityDiv.classList.remove('hiddens');
    } else {
        activityDiv.classList.add('hiddens');
    }
}
// /Untuk menyembunyikan dan melihat activity //

// Untuk menyembunyikan dan melihat simpan komentar //
function saveComment(id) {
    var komentarTextarea = document.getElementById(`contentarea${id}`);
    var simpanButton = document.getElementById(`simpanButton${id}`);

    komentarTextarea.addEventListener('input', function () {
        if (komentarTextarea.value.trim() !== "") {
            simpanButton.classList.remove('hidden');
        } else {
            simpanButton.classList.add('hidden');
        }
    });
}

function changeCard(id) {
    var changeCardElement = document.getElementById(`select-card${id}`);
    if (changeCardElement) {
        changeCardElement.addEventListener('change', function() {
            var modalTarget = this.value;
            if (modalTarget !== "-- Select Card --") {
                $('.modal').modal('hide');
                setTimeout(function() {
                    $('#isianKartu').modal('show');
                    $('#card_id').val(modalTarget);
                    $('#form_kartu').submit();
                }, 500);
            }
        });
    }
}
// /Untuk menyembunyikan dan melihat simpan komentar //

// Textarea mengikuti jumlah data yang ada //
// $(document).ready(function(event) {
//     const textareas = document.querySelectorAll('textarea');
//     textareas.forEach(textarea => {
//         if (textarea.id.startsWith('keterangan')) {
//             const lineCount = textarea.value.split('\n').length;
//             textarea.rows = Math.max(lineCount, 4);
//         }
//     });
// });
// /Textarea mengikuti jumlah data yang ada //

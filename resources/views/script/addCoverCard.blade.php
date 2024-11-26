<script>
    function selectPattern2(cover, cardId) {
        var selectedPattern = document.querySelector('#cover-field-' + cardId);
        selectedPattern.value = cover;

        var allPatterns = document.querySelectorAll('.h-full');
        allPatterns.forEach(function(item) {
            item.classList.remove('border-black');
        });

        var selectedPatternElement = document.getElementById('cover-' + cover + '-' + cardId);
        selectedPatternElement.classList.add('border-black');

        var allChecks = document.querySelectorAll('.fa-circle-check');
        allChecks.forEach(function(item) {
            item.parentElement.style.opacity = '0';
        });

        var selectedCheck = document.getElementById('check-' + cover + '-' + cardId);
        selectedCheck.style.opacity = '100';

        $.ajax({
            url: "{{ route('perbaharuiCover') }}",
            method: "POST",
            data: {
                _token: '{{ csrf_token() }}',
                card_id: cardId,
                pattern: cover
            },
            success: function(response) {
                toastr.success('Berhasil perbaharui cover!');
                updateCoverCard(cover, cardId);
                updateTextCover(cardId, cover);
                // var aksiCard = document.querySelector('#aksi-card' + cardId);
            },
            error: function(error) {
                toastr.error('Gagal perbaharui cover!');
            }
        });
    }

    function hapusCoverCard(cardId, event) {
        event.preventDefault();
        $.ajax({
            url: '{{ route("hapusCover") }}',
            method: "POST",
            data: {
                _token: '{{ csrf_token() }}',
                card_id: cardId,
                pattern: 'NULL'
            },
            success: function(response) {
                toastr.success('Berhasil menghapus cover!');
                updateCoverCard('NULL', cardId);
                updateTextCover(cardId, 'NULL');
            },
            error: function(error) {
                toastr.error('Gagal menghapus cover!');
            }
        });
    }

    function updateCoverCard(cover, cardId) {
        var coverCard = document.querySelector('#cover-card-' + cardId);
        if (coverCard) {
            coverCard.className = 'cover-card card-cover2-' + cover;
            if (cover === 'NULL' || cover === '') {
                coverCard.classList.add('hiddens');
            } else {
                coverCard.classList.remove('hiddens');
            }
        }
        var coverCard2 = document.querySelector('#cover-card2-' + cardId);
        if (coverCard2) {
            coverCard2.className = 'cover-card card-cover2-' + cover;
            if (cover === 'NULL' || cover === '') {
                coverCard2.classList.add('hiddens');
            } else {
                coverCard2.classList.remove('hiddens');
            }
        }
        var coverCard3 = document.querySelector('#cover-card3-' + cardId);
        if (coverCard3) {
            coverCard3.className = 'dropdown-item card-cover3-' + cover;
            if (cover === 'NULL' || cover === '') {
                coverCard3.classList.add('hiddens');
            } else {
                coverCard3.classList.remove('hiddens');
            }
        }
    }

    function updateTextCover(cardId, cover) {
        var linkElement = document.querySelector('#formCover-' + cardId);
        if (linkElement) {
            if (cover === 'NULL' || cover === '') {
                linkElement.innerHTML = '<i class="fa-solid fa-clapperboard m-r-5"></i> Add Cover';
            } else {
                linkElement.innerHTML = '<i class="fa-solid fa-clapperboard m-r-5"></i> Change Cover';
            }
        }
    }

    $(document).ready(function() {
        var coverCards = document.querySelectorAll('.cover-card');
        coverCards.forEach(function(card) {
            var patternMatch = card.className.match(/card-cover2-(\S+)/);
            var pattern = card.classList.contains('card-cover2-NULL') ? 'NULL' : (patternMatch ? patternMatch[1] : '');
            if (pattern === 'NULL' || pattern === '') {
                card.classList.add('hiddens');
            } else {
                card.classList.remove('hiddens');
            }
        });

        var links = document.querySelectorAll('.dropdown-item');
        links.forEach(function(link) {
            var cardId = link.id.split('-')[1];
            var patternMatch = link.className.match(/card-cover4-(\S+)/);
            var pattern = link.classList.contains('hiddens') ? 'NULL' : (patternMatch ? patternMatch[1] : '');
            updateTextCover(cardId, pattern);
        });
    });

    $('#formCover').on('click', function(event) {
        event.preventDefault();
    });

    $(document).ready(function() {
        var coverTextElement = document.getElementById('coverText');
        if (!coverTextElement) return;
        if ('{{ $isianKartu->pattern }}') {
            coverTextElement.textContent = 'Change Cover';
        }
    });
</script>
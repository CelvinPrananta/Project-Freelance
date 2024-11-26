document.addEventListener("DOMContentLoaded", function () {
    var titleElement = document.getElementById('pageTitle');
    var text = titleElement.textContent;
    var index = 0;

    function marqueeTitle() {
        index = (index + 1) % text.length;
        var newText = text.substring(index) + text.substring(0, index);
        titleElement.textContent = newText;
    }

    setInterval(marqueeTitle, 1000); // Ubah angka ini untuk mengatur kecepatan
});
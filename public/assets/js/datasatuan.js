$(document).on('click', '.edit_databarang', function() {
    var id = $(this).data('id');
    var kode_barang = $(this).data('kode_barang');
    var nama_barang = $(this).data('nama_barang');
    $("#e_id").val(id);
    $("#e_kode_barang").val(kode_barang);
    $("#e_nama_barang").val(nama_barang);
});

$(document).on('click', '.delete_databarang', function() {
    var id = $(this).data('id');
    $(".e_id").val(id);
});
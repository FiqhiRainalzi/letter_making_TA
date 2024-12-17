//konfirm delete
function confirmDelete(id) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data yang dihapus tidak bisa dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `/admin/akunPenggunaDelete/${id}`;
        }
    });
}
//show message
function showSuccessMessage(message) {
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: message,
        position: 'top-end', // Mengatur posisi di pojok atas kanan
        showConfirmButton: false, // Tidak menampilkan tombol OK
        timer: 3000, // Notifikasi akan hilang otomatis dalam 3 detik
        timerProgressBar: true, // Menampilkan progress bar saat hitung mundur
        toast: true // Menyesuaikan tampilan menjadi kecil seperti toast
    });
}

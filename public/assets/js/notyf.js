document.addEventListener('DOMContentLoaded', function () {
const queryString = window.location.search;
const urlParams = new URLSearchParams(queryString);
const errorMessage = urlParams.get('errorMessage');
const successMessage = urlParams.get('successMessage');
const notyf = new Notyf();
if (errorMessage) {
    // Tampilkan notifikasi dengan Notyf
    notyf.error({
        message: errorMessage,
        duration: 3500, // Durasi notifikasi
        dismissible: true // Bisa ditutup oleh pengguna
    });

    // Hapus parameter 'errorMessage' dari URL tanpa menambahkan riwayat ke browser
    urlParams.delete('errorMessage');
    const newUrl = `${window.location.pathname}${urlParams.toString() ? '?' + urlParams.toString() : ''}`;
    window.history.replaceState({}, document.title, newUrl);
}
else if (successMessage) {
    // Tampilkan notifikasi dengan Notyf
    notyf.success({
        message: successMessage,
        duration: 3500, // Durasi notifikasi
        dismissible: true // Bisa ditutup oleh pengguna
    });

    // Hapus parameter 'errorMessage' dari URL tanpa menambahkan riwayat ke browser
    urlParams.delete('successMessage');
    const newUrl = `${window.location.pathname}${urlParams.toString() ? '?' + urlParams.toString() : ''}`;
    window.history.replaceState({}, document.title, newUrl);
}
});
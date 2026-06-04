<script>
window.showAlert = function(type, title, message) {
    if (typeof Swal === 'undefined') {
        console.error('SweetAlert2 is not loaded!');
        return;
    }
    
    Swal.fire({
        icon: type,
        title: title,
        text: message,
        backdrop: 'rgba(0,0,0,0.4)',
        allowOutsideClick: false,
        showConfirmButton: true,
        confirmButtonText: 'OK',
        timer: type === 'success' ? 3000 : null,
        timerProgressBar: type === 'success',
        showClass: {
            popup: 'animate__animated animate__fadeInDown animate__faster'
        },
        hideClass: {
            popup: 'animate__animated animate__fadeOutUp animate__faster'
        },
        customClass: {
            popup: 'rounded-2xl shadow-2xl',
            title: 'text-lg font-bold text-gray-900 font-display',
            confirmButton: 'bg-[#674c1d] hover:bg-[#4a3514] text-white font-semibold py-2.5 px-6 rounded-xl transition-all shadow-sm'
        },
        buttonsStyling: false
    });
};

window.showErrorAlert = function(message) {
    showAlert('error', 'Gagal', message);
};

window.showSuccessAlert = function(message) {
    showAlert('success', 'Berhasil', message);
};

window.showWarningAlert = function(message) {
    showAlert('warning', 'Peringatan', message);
};
</script>

@if(session('success'))
<script>
document.addEventListener('DOMContentLoaded', function () {
    showSuccessAlert(@json(session('success')));
});
</script>
@endif

@if(session('error'))
<script>
document.addEventListener('DOMContentLoaded', function () {
    showErrorAlert(@json(session('error')));
});
</script>
@endif

@if($errors->any())
<script>
document.addEventListener('DOMContentLoaded', function () {
    showErrorAlert(@json($errors->first()));
});
</script>
@endif

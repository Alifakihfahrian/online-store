@extends('layouts.customer')

@section('title', 'Memproses Pembayaran')

@section('extra_css')
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<style>
    .redirect-container {
        min-height: 80vh;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
    }

    .loading-spinner {
        width: 80px;
        height: 80px;
        margin-bottom: 20px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .progress {
        height: 10px;
        width: 200px;
        margin: 20px 0;
        border-radius: 5px;
        overflow: hidden;
    }

    .progress-bar {
        width: 0%;
        height: 100%;
        background: var(--bs-primary);
        animation: progress 2s linear forwards;
    }

    @keyframes progress {
        0% { width: 0%; }
        100% { width: 100%; }
    }
</style>
@endsection

@section('content')
<div class="redirect-container">
    <div class="spinner-border text-primary loading-spinner" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
    
    <h3>Memproses Pembayaran Anda</h3>
    <p class="text-secondary">Mohon tunggu sebentar...</p>
    
    <div class="progress">
        <div class="progress-bar" role="progressbar"></div>
    </div>
</div>
@endsection

@section('extra_js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tunggu 2 detik
    setTimeout(function() {
        // Tampilkan SweetAlert
        Swal.fire({
            icon: 'success',
            title: 'Pembayaran Berhasil!',
            text: 'Pulsa akan segera masuk ke nomor tujuan',
            showConfirmButton: false,
            timer: 1500
        }).then(() => {
            // Redirect ke dashboard
            window.location.href = '{{ route("customer.dashboard") }}';
        });
    }, 2000);
});
</script>
@endsection 
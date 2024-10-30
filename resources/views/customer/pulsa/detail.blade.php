@extends('layouts.customer')

@section('title', 'Detail Pembelian Pulsa')

@section('extra_css')
<style>
    body {
        transition: opacity 0.5s;
    }
    
    /* ... CSS lainnya ... */
</style>
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Detail Pembelian</h5>
            <p>Nomor Telepon: {{ $phoneNumber }}</p>
            <p>Provider: {{ $provider }}</p>
            <p>Nominal: Rp {{ number_format($nominal, 0, ',', '.') }}</p>
            <p>Total Bayar: Rp {{ number_format($price, 0, ',', '.') }}</p>
            
            <form id="paymentForm">
                @csrf
                <input type="hidden" name="phone_number" value="{{ $phoneNumber }}">
                <input type="hidden" name="provider" value="{{ $provider }}">
                <input type="hidden" name="nominal" value="{{ $nominal }}">
                <button type="submit" class="btn btn-primary">Bayar Sekarang</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('extra_js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.getElementById('paymentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Tampilkan loading
    Swal.fire({
        title: 'Memproses Pembayaran',
        text: 'Mohon tunggu sebentar...',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });

    // Kirim request AJAX
    fetch('{{ route("pulsa.pay") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            phone_number: '{{ $phoneNumber }}',
            provider: '{{ $provider }}',
            nominal: {{ $nominal }}
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: data.message,
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                // Tambahkan efek fade out
                document.body.style.opacity = '0';
                document.body.style.transition = 'opacity 0.5s';
                
                // Redirect ke dashboard setelah fade out
                setTimeout(() => {
                    window.location.href = '{{ route("customer.dashboard") }}';
                }, 500);
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: data.message || 'Terjadi kesalahan saat memproses pembayaran.'
            });
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Terjadi kesalahan saat memproses pembayaran.'
        });
    });
});
</script>
@endsection 
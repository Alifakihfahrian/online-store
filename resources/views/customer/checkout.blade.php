@extends('layouts.customer')

@section('title', 'Proses Pembayaran')

@section('extra_css')
<style>
    .payment-method-card {
        cursor: pointer;
        transition: all 0.3s;
    }
    .payment-method-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .payment-method-card.selected {
        border-color: var(--bs-primary);
        background-color: var(--bs-primary-bg-subtle);
    }
</style>
@endsection

@section('content')
<div class="container mt-4">
    <h1 class="mb-4"><i class="bi bi-credit-card"></i> Proses Pembayaran</h1>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title">Alamat Pengiriman</h5>
                    <form>
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Alamat</label>
                            <textarea class="form-control" id="address" rows="3" required></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="city" class="form-label">Kota</label>
                                <input type="text" class="form-control" id="city" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="postal_code" class="form-label">Kode Pos</label>
                                <input type="text" class="form-control" id="postal_code" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Nomor Telepon</label>
                            <input type="tel" class="form-control" id="phone" required>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Metode Pembayaran</h5>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="card payment-method-card" onclick="selectPaymentMethod(this, 'credit_card')">
                                <div class="card-body text-center">
                                    <i class="bi bi-credit-card fs-1"></i>
                                    <p class="mt-2">Kartu Kredit</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card payment-method-card" onclick="selectPaymentMethod(this, 'bank_transfer')">
                                <div class="card-body text-center">
                                    <i class="bi bi-bank fs-1"></i>
                                    <p class="mt-2">Transfer Bank</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card payment-method-card" onclick="selectPaymentMethod(this, 'e_wallet')">
                                <div class="card-body text-center">
                                    <i class="bi bi-wallet2 fs-1"></i>
                                    <p class="mt-2">E-Wallet</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Ringkasan Pesanan</h5>
                    @foreach($cartItems as $item)
                        <div class="d-flex justify-content-between mb-2">
                            <span>{{ $item->product->name }} (x{{ $item->quantity }})</span>
                            <span>Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                    <hr>
                    <div class="d-flex justify-content-between mb-2">
                        <strong>Total:</strong>
                        <strong>Rp {{ number_format($total, 0, ',', '.') }}</strong>
                    </div>
                    <button class="btn btn-primary w-100 mt-3" onclick="processPayment()">
                        <i class="bi bi-lock"></i> Bayar Sekarang
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('extra_js')
<script>
    let selectedPaymentMethod = null;

    function selectPaymentMethod(element, method) {
        document.querySelectorAll('.payment-method-card').forEach(card => {
            card.classList.remove('selected');
        });
        element.classList.add('selected');
        selectedPaymentMethod = method;
    }

    function processPayment() {
        if (!selectedPaymentMethod) {
            alert('Silakan pilih metode pembayaran');
            return;
        }

        // Validasi form
        const form = document.querySelector('form');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        // Simulasi proses pembayaran
        Swal.fire({
            title: 'Memproses Pembayaran',
            text: 'Mohon tunggu...',
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => {
                Swal.showLoading();
            }
        });

        // Simulasi delay proses pembayaran
        setTimeout(() => {
            Swal.fire({
                icon: 'success',
                title: 'Pembayaran Berhasil',
                text: 'Terima kasih atas pesanan Anda!',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '{{ route("customer.dashboard") }}';
                }
            });
        }, 2000);
    }
</script>
@endsection

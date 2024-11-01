@extends('layouts.customer')

@section('title', 'Proses Pembayaran')

@section('extra_css')
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
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
    .fade-out {
        opacity: 0;
        transition: opacity 0.3s ease-out;
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
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cartItems as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $item->product->image ? asset('storage/' . $item->product->image) : asset('images/no-image.jpg') }}" 
                                                 alt="{{ $item->product->name }}"
                                                 class="img-thumbnail me-2"
                                                 style="width: 50px; height: 50px; object-fit: cover;">
                                            <div>
                                                <h6 class="mb-0">{{ $item->product->name }}</h6>
                                                <small class="text-muted">{{ $item->product->category->name }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Rp {{ number_format($item->product->price, 0, ',', '.') }}</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td>Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                    <td><strong>Rp {{ number_format($total, 0, ',', '.') }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            // Kosongkan keranjang setelah pembayaran berhasil
            $.ajax({
                url: '/cart/clear',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Pembayaran Berhasil',
                        text: 'Terima kasih atas pesanan Anda!',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.body.classList.add('fade-out');
                            setTimeout(function() {
                                window.location.href = '{{ route("customer.dashboard") }}';
                            }, 300);
                        }
                    });
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Terjadi kesalahan saat mengosongkan keranjang.'
                    });
                }
            });
        }, 2000);
    }
</script>
@endsection

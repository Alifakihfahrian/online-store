@extends('layouts.customer')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="container mt-4">
    <h1>Keranjang Belanja</h1>
    @if($cartItems->isEmpty())
        <div class="alert alert-info">
            Keranjang belanja Anda kosong.
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('customer.dashboard') }}" class="btn btn-primary">Belanja yukk...</a>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cartItems as $item)
                        <tr>
                            <td>{{ $item->product->name }}</td>
                            <td>Rp {{ number_format($item->product->price, 0, ',', '.') }}</td>
                            <td>
                                <input type="number" class="form-control form-control-sm cart-quantity" 
                                       value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock }}"
                                       data-product-id="{{ $item->product_id }}">
                            </td>
                            <td>Rp {{ number_format($item->quantity * $item->product->price, 0, ',', '.') }}</td>
                            <td>
                                <button class="btn btn-danger btn-sm remove-from-cart" data-product-id="{{ $item->product_id }}">Hapus</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end"><strong>Total:</strong></td>
                        <td><strong>Rp {{ number_format($total, 0, ',', '.') }}</strong></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="text-end mt-3">
            <a href="{{ route('customer.dashboard') }}" class="btn btn-secondary">Lanjutkan Belanja</a>
            <a href="{{ route('checkout') }}" class="btn btn-primary w-100">
                <i class="bi bi-credit-card"></i> Proses Pembayaran
            </a>
        </div>
    @endif
</div>
@endsection

@section('extra_js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    $('.cart-quantity').on('change', function() {
        var productId = $(this).data('product-id');
        var quantity = $(this).val();
        updateCartItem(productId, quantity);
    });

    $('.remove-from-cart').on('click', function() {
        var productId = $(this).data('product-id');
        removeFromCart(productId);
    });

    function updateCartItem(productId, quantity) {
        $.ajax({
            url: '/cart/update/' + productId,
            method: 'POST',
            data: { quantity: quantity },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                location.reload();
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Terjadi kesalahan. Silakan coba lagi.'
                });
            }
        });
    }

    function removeFromCart(productId) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Produk ini akan dihapus dari keranjang.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/cart/update/' + productId,
                    method: 'POST',
                    data: { quantity: 0 },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        location.reload();
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Terjadi kesalahan. Silakan coba lagi.'
                        });
                    }
                });
            }
        });
    }
});
</script>
@endsection

@extends('layouts.customer')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="container mt-4">
    <h2>Keranjang Belanja</h2>
    
    @if($cartItems->isEmpty())
        <div class="alert alert-info">
            Keranjang belanja Anda masih kosong.
            <a href="{{ route('customer.dashboard') }}" class="alert-link">Mulai belanja sekarang!</a>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover">
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
                        <td>
                            <div class="input-group" style="width: 120px;">
                                <button type="button" class="btn btn-outline-secondary btn-sm btn-minus" data-id="{{ $item->id }}">-</button>
                                <input type="number" 
                                       class="form-control form-control-sm text-center quantity-input"
                                       value="{{ $item->quantity }}" 
                                       min="1" 
                                       max="{{ $item->product->stock }}"
                                       data-id="{{ $item->id }}"
                                       readonly>
                                <button type="button" class="btn btn-outline-secondary btn-sm btn-plus" data-id="{{ $item->id }}">+</button>
                            </div>
                        </td>
                        <td>Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</td>
                        <td>
                            <button class="btn btn-danger btn-sm remove-item" data-id="{{ $item->id }}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end"><strong>Total:</strong></td>
                        <td colspan="2">
                            <strong>
                                Rp {{ number_format($cartItems->sum(function($item) {
                                    return $item->product->price * $item->quantity;
                                }), 0, ',', '.') }}
                            </strong>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="d-flex justify-content-between mt-3">
            <a href="{{ route('customer.dashboard') }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left"></i> Lanjut Belanja
            </a>
            <a href="{{ route('checkout.index') }}" class="btn btn-primary">
                Checkout <i class="bi bi-arrow-right"></i>
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

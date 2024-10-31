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
                        <td class="price-per-item" data-price="{{ $item->product->price }}">
                            Rp {{ number_format($item->product->price, 0, ',', '.') }}
                        </td>
                        <td>
                            <div class="input-group" style="width: 120px;">
                                <button type="button" class="btn btn-outline-secondary btn-sm btn-minus" data-id="{{ $item->id }}">-</button>
                                <input type="number" 
                                       class="form-control form-control-sm text-center quantity-input"
                                       value="{{ $item->quantity }}" 
                                       min="1" 
                                       max="{{ $item->product->stock }}"
                                       data-id="{{ $item->id }}">
                                <button type="button" class="btn btn-outline-secondary btn-sm btn-plus" data-id="{{ $item->id }}">+</button>
                            </div>
                        </td>
                        <td>
                            <span class="subtotal" data-value="{{ $item->product->price * $item->quantity }}">
                                Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}
                            </span>
                        </td>
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
                            <strong id="cart-total">
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
    // Handler untuk tombol plus
    $('.btn-plus').on('click', function() {
        var id = $(this).data('id');
        var input = $(this).siblings('.quantity-input');
        var currentValue = parseInt(input.val());
        var maxStock = parseInt(input.attr('max'));
        
        if (currentValue < maxStock) {
            input.val(currentValue + 1);
            updateCartItem(id, currentValue + 1, this);
        }
    });

    // Handler untuk tombol minus
    $('.btn-minus').on('click', function() {
        var id = $(this).data('id');
        var input = $(this).siblings('.quantity-input');
        var currentValue = parseInt(input.val());
        
        if (currentValue > 1) {
            input.val(currentValue - 1);
            updateCartItem(id, currentValue - 1, this);
        }
    });

    // Handler untuk input langsung
    $('.quantity-input').on('change', function() {
        var id = $(this).data('id');
        var newValue = parseInt($(this).val());
        var maxStock = parseInt($(this).attr('max'));
        
        if (newValue < 1) {
            $(this).val(1);
            newValue = 1;
        } else if (newValue > maxStock) {
            $(this).val(maxStock);
            newValue = maxStock;
        }
        
        updateCartItem(id, newValue, this);
    });

    function updateCartItem(cartItemId, quantity, element) {
        const row = $(element).closest('tr');
        const pricePerItem = parseInt(row.find('.price-per-item').data('price'));
        const subtotal = quantity * pricePerItem;
        
        // Update subtotal untuk item ini
        row.find('.subtotal')
           .text('Rp ' + formatNumber(subtotal))
           .data('value', subtotal); // Update data-value juga
        
        // Update total keseluruhan
        updateTotal();
        
        $.ajax({
            url: '/cart/update/' + cartItemId,
            method: 'POST',
            data: { 
                quantity: quantity,
                _token: $('meta[name="csrf-token"]').attr('content')
            }
        });
    }

    function updateTotal() {
        let total = 0;
        // Hitung ulang total dari semua subtotal
        $('.subtotal').each(function() {
            const subtotalValue = parseInt($(this).data('value'));
            total += subtotalValue;
        });
        $('#cart-total').text('Rp ' + formatNumber(total));
    }

    function formatNumber(number) {
        return new Intl.NumberFormat('id-ID').format(number);
    }

    // Handler untuk tombol hapus
    $('.remove-item').on('click', function() {
        var id = $(this).data('id');
        removeFromCart(id);
    });

    function removeFromCart(cartItemId) {
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
                    url: '/cart/remove/' + cartItemId,
                    method: 'POST',
                    data: { 
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            // Hapus baris item dari tabel
                            $('button[data-id="' + cartItemId + '"]').closest('tr').remove();
                            
                            // Update total
                            updateTotal();
                            
                            // Jika keranjang kosong, reload halaman
                            if ($('tbody tr').length === 0) {
                                location.reload();
                            }

                            // Tampilkan pesan sukses
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: 'Item berhasil dihapus dari keranjang',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Terjadi kesalahan saat menghapus item'
                        });
                    }
                });
            }
        });
    }
});
</script>
@endsection

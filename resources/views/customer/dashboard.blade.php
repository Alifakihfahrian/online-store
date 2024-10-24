@extends('layouts.customer')

@section('title', 'Dashboard Customer')

@section('extra_css')
<style>
    .card-img-top {
        width: 100%;
        height: 200px; /* Atur tinggi sesuai kebutuhan */
        object-fit: cover; /* Ini akan memastikan gambar menutupi area tanpa distorsi */
    }
    .card-img-placeholder {
        width: 100%;
        height: 200px; /* Sama dengan .card-img-top */
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f8f9fa;
        color: #6c757d;
    }
</style>
@endsection

@section('content')
<div class="container mt-4">
    <h1>Produk Tersedia</h1>
    @if($search)
        <p>Hasil pencarian untuk: "{{ $search }}"</p>
    @endif
    
    @if($products->isEmpty())
        <div class="alert alert-info">
            Tidak ada produk yang ditemukan.
        </div>
    @else
        <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
            @foreach($products as $product)
                <div class="col">
                    <div class="card h-100">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                        @else
                            <div class="card-img-placeholder">
                                <i class="bi bi-image" style="font-size: 3rem;"></i>
                            </div>
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text">{{ Str::limit($product->description, 100) }}</p>
                            <p class="card-text"><strong>Harga:</strong> Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                            <p class="card-text"><strong>Stok:</strong> {{ $product->stock }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-outline-secondary decrease-quantity" data-product-id="{{ $product->id }}">-</button>
                                    <input type="number" class="form-control form-control-sm mx-1 product-quantity" style="width: 50px;" value="1" min="1" max="{{ $product->stock }}" data-product-id="{{ $product->id }}">
                                    <button type="button" class="btn btn-sm btn-outline-secondary increase-quantity" data-product-id="{{ $product->id }}">+</button>
                                </div>
                                <button class="btn btn-primary btn-sm add-to-cart" data-product-id="{{ $product->id }}">Tambah ke Keranjang</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-4">
            {{ $products->links() }}
        </div>
    @endif
</div>
@endsection

@section('extra_js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('.add-to-cart').click(function() {
        var productId = $(this).data('product-id');
        var quantity = $('input.product-quantity[data-product-id="' + productId + '"]').val();
        addToCart(productId, quantity);
    });

    $('.decrease-quantity, .increase-quantity').click(function() {
        var productId = $(this).data('product-id');
        var input = $('input.product-quantity[data-product-id="' + productId + '"]');
        var currentValue = parseInt(input.val());
        var maxValue = parseInt(input.attr('max'));

        if ($(this).hasClass('decrease-quantity') && currentValue > 1) {
            input.val(currentValue - 1);
        } else if ($(this).hasClass('increase-quantity') && currentValue < maxValue) {
            input.val(currentValue + 1);
        }
    });

    $('.product-quantity').on('input', function() {
        var maxValue = parseInt($(this).attr('max'));
        var value = parseInt($(this).val());

        if (isNaN(value) || value < 1) {
            $(this).val(1);
        } else if (value > maxValue) {
            $(this).val(maxValue);
        }
    });

    function addToCart(productId, quantity) {
        Swal.fire({
            title: 'Menambahkan ke keranjang...',
            text: 'Mohon tunggu',
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: '/cart/add/' + productId,
            method: 'POST',
            data: { quantity: quantity },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: response.message,
                    timer: 1500,
                    showConfirmButton: false
                });
                updateCartCount(response.cartCount);
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Terjadi kesalahan. Silakan coba lagi.'
                });
            }
        });
    }

    function updateCartCount(count) {
        $('#cart-count').text(count);
    }

    // Update cart count on page load
    $.get('/cart/count', function(data) {
        updateCartCount(data);
    });
});
</script>
@endsection

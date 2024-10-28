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

    .category-pills {
        overflow-x: auto;
        white-space: nowrap;
        -webkit-overflow-scrolling: touch;
        padding: 10px 0;
    }
    
    .category-pill {
        display: inline-block;
        padding: 8px 16px;
        margin: 0 4px;
        border-radius: 20px;
        cursor: pointer;
        transition: all 0.3s;
    }

    .category-pill:hover {
        background-color: var(--bs-primary);
        color: white;
    }

    .category-pill.active {
        background-color: var(--bs-primary);
        color: white;
    }

    .dropdown-item.active {
        background-color: var(--bs-primary);
        color: white;
    }
    
    .card {
        transition: transform 0.2s;
    }
    
    .card:hover {
        transform: translateY(-5px);
    }

    .badge {
        font-size: 0.8rem;
    }

    .dropdown-menu {
        min-width: 200px;
    }
    
    .dropdown-item {
        padding: 8px 16px;
    }
    
    .dropdown-item:hover {
        background-color: var(--bs-primary-bg-subtle);
    }
    
    .dropdown-item.active {
        background-color: var(--bs-primary);
        color: white;
    }
    
    .dropdown-item.active i {
        color: white;
    }
    
    .dropdown-item i {
        color: var(--bs-primary);
    }

    .btn-link {
        text-decoration: none;
        color: var(--bs-primary);
    }
    .btn-link.disabled {
        color: var(--bs-gray-500);
        pointer-events: none;
    }

    .input-group .btn {
        padding: 0.375rem 0.75rem;
        z-index: 0;
    }
    
    .quantity-input {
        text-align: center;
        background-color: var(--bs-body-bg) !important; /* Menggunakan warna background sesuai theme */
        color: var(--bs-body-color) !important; /* Menggunakan warna text sesuai theme */
        border-color: var(--bs-border-color) !important; /* Menggunakan warna border sesuai theme */
    }
    
    .quantity-input::-webkit-inner-spin-button,
    .quantity-input::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    
    /* Warna hover untuk tombol plus/minus */
    [data-bs-theme="dark"] .btn-outline-secondary:hover {
        background-color: var(--bs-secondary);
        border-color: var(--bs-secondary);
    }

    /* Style untuk card dan gambar produk */
    .card {
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .product-image-container {
        position: relative;
        width: 100%;
        padding-top: 75%; /* Rasio aspek 4:3 */
        overflow: hidden;
    }

    .product-image {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: contain; /* Ubah dari 'cover' ke 'contain' */
        background-color: #f8f9fa; /* Background color untuk area kosong */
        padding: 10px; /* Padding agar gambar tidak terlalu dekat dengan tepi */
    }

    .card-body {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .card-title {
        font-size: 1.1rem;
        margin-bottom: 0.5rem;
        font-weight: 500;
    }

    .card-text {
        margin-bottom: 0.5rem;
    }

    /* Pastikan tombol selalu di bawah */
    .mt-auto {
        margin-top: auto !important;
    }
</style>
@endsection

@section('content')
<div class="container mt-4">
    <!-- Search dan Filter -->
    <div class="row mb-4">
        <div class="col-md-6">
            <!-- Form Search -->
            <form action="{{ route('customer.dashboard') }}" method="GET" class="d-flex">
                <input type="hidden" name="category" value="{{ request('category') }}">
                <div class="input-group">
                    <input type="text" 
                           name="search" 
                           class="form-control" 
                           placeholder="Cari produk..." 
                           value="{{ request('search') }}"
                           aria-label="Search">
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-search"></i> Cari
                    </button>
                    @if(request('search'))
                        <a href="{{ route('customer.dashboard', ['category' => request('category')]) }}" 
                           class="btn btn-outline-secondary">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
        <div class="col-md-6">
            <div class="d-flex justify-content-end">
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="categoryDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-filter"></i> 
                        {{ $selectedCategory ? $categories->find($selectedCategory)->name : 'Semua Kategori' }}
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="categoryDropdown">
                        <li>
                            <a class="dropdown-item {{ !$selectedCategory ? 'active' : '' }}" 
                               href="{{ route('customer.dashboard', ['search' => request('search')]) }}">
                               <i class="bi bi-grid-fill me-2"></i> Semua Kategori
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        @foreach($categories as $category)
                            <li>
                                <a class="dropdown-item {{ $selectedCategory == $category->id ? 'active' : '' }}" 
                                   href="{{ route('customer.dashboard', ['category' => $category->id, 'search' => request('search')]) }}">
                                   <i class="bi bi-tag-fill me-2"></i> {{ $category->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Hasil pencarian jika ada -->
    @if(request('search'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            Hasil pencarian untuk: "{{ request('search') }}"
            <a href="{{ route('customer.dashboard', ['category' => request('category')]) }}" 
               class="btn btn-sm btn-info ms-2">
                Hapus pencarian
            </a>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Produk -->
    <div class="row row-cols-1 row-cols-md-3 g-4">
        @foreach($products as $product)
        <div class="col">
            <div class="card h-100">
                <div class="product-image-container">
                    <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/no-image.jpg') }}" 
                         class="product-image" 
                         alt="{{ $product->name }}">
                </div>
                <div class="card-body">
                    <h5 class="card-title">{{ $product->name }}</h5>
                    <p class="card-text">{{ $product->description }}</p>
                    <p class="card-text">
                        <small class="text-muted">Kategori: {{ $product->category->name }}</small>
                    </p>
                    <p class="card-text">Stok: {{ $product->stock }}</p>
                    <p class="card-text">Harga: Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                    <div class="mt-auto">
                        <form action="{{ route('cart.add', $product->id) }}" method="POST" class="add-to-cart-form">
                            @csrf
                            <div class="d-flex gap-2">
                                <div class="input-group" style="width: 120px;">
                                    <button type="button" class="btn btn-outline-secondary btn-minus">-</button>
                                    <input type="number" 
                                           name="quantity" 
                                           class="form-control text-center quantity-input"
                                           value="1" 
                                           min="1" 
                                           max="{{ $product->stock }}"
                                           data-stock="{{ $product->stock }}"
                                           readonly>
                                    <button type="button" class="btn btn-outline-secondary btn-plus">+</button>
                                </div>
                                <button type="submit" class="btn btn-primary flex-grow-1">
                                    Tambahkan ke Keranjang
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Simple Pagination -->
    <div class="d-flex justify-content-between align-items-center mt-4 mb-4">
        @if ($products->previousPageUrl())
            <a href="{{ $products->previousPageUrl() }}" class="btn btn-link">« Previous</a>
        @else
            <span class="btn btn-link disabled">« Previous</span>
        @endif

        <span class="text-muted">
            Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} 
            of {{ $products->total() }} products
        </span>

        @if ($products->nextPageUrl())
            <a href="{{ $products->nextPageUrl() }}" class="btn btn-link">Next »</a>
        @else
            <span class="btn btn-link disabled">Next »</span>
        @endif
    </div>
</div>

<style>
/* Tambahkan style untuk search bar */
.input-group {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    border-radius: 0.375rem;
}

.input-group .form-control {
    border-right: none;
}

.input-group .form-control:focus {
    box-shadow: none;
}

.input-group .btn {
    border-left: none;
}

.input-group .btn-outline-secondary {
    border-left: 1px solid #ced4da;
}

/* Style untuk alert hasil pencarian */
.alert-info {
    background-color: #f8f9fa;
    border-color: #e9ecef;
    color: #495057;
}

.alert-info .btn-info {
    background-color: transparent;
    border-color: #6c757d;
    color: #6c757d;
}

.alert-info .btn-info:hover {
    background-color: #6c757d;
    color: white;
}
</style>
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

document.addEventListener('DOMContentLoaded', function() {
    // Fungsi untuk tombol minus
    document.querySelectorAll('.btn-minus').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.parentElement.querySelector('.quantity-input');
            const currentValue = parseInt(input.value);
            if (currentValue > 1) {
                input.value = currentValue - 1;
            }
        });
    });

    // Fungsi untuk tombol plus
    document.querySelectorAll('.btn-plus').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.parentElement.querySelector('.quantity-input');
            const currentValue = parseInt(input.value);
            const maxStock = parseInt(input.dataset.stock);
            if (currentValue < maxStock) {
                input.value = currentValue + 1;
            } else {
                Swal.fire({
                    title: 'Stok Terbatas',
                    text: 'Jumlah melebihi stok yang tersedia!',
                    icon: 'warning',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
            }
        });
    });

    // Fungsi untuk update angka keranjang
    function updateCartCount(count) {
        const cartCountElement = document.querySelector('.cart-count');
        if (cartCountElement) {
            // Update angka
            cartCountElement.textContent = count;
            
            // Tambahkan animasi pop
            cartCountElement.style.transform = 'scale(1.3)';
            setTimeout(() => {
                cartCountElement.style.transform = 'scale(1)';
            }, 200);
        }
    }

    // Tangani submit form
    document.querySelectorAll('.add-to-cart-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            fetch(this.action, {
                method: 'POST',
                body: new FormData(this),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                credentials: 'same-origin'  // Tambahkan ini
            })
            .then(response => response.json())
            .then(data => {
                console.log('Response:', data);
                if(data.message === 'Produk berhasil ditambahkan ke keranjang') {
                    // Update cart count dengan animasi
                    if(data.cartCount !== undefined) {
                        updateCartCount(data.cartCount);
                    }

                    // Tampilkan Sweet Alert sukses
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message,
                        showConfirmButton: false,
                        timer: 1500,
                        position: 'center',
                        showClass: {
                            popup: 'animate__animated animate__fadeInDown'
                        },
                        hideClass: {
                            popup: 'animate__animated animate__fadeOutUp'
                        }
                    });
                } else {
                    // Tampilkan Sweet Alert error
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: data.message || 'Gagal menambahkan produk ke keranjang',
                        confirmButtonColor: '#d33'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error); // Untuk debugging
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan saat memproses permintaan',
                    confirmButtonColor: '#d33'
                });
            });
        });
    });
});
</script>

<style>
/* Animasi untuk Sweet Alert */
.animated {
    animation-duration: 0.5s;
    animation-fill-mode: both;
}

@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translate3d(0, -20%, 0);
    }
    to {
        opacity: 1;
        transform: translate3d(0, 0, 0);
    }
}

.fadeInDown {
    animation-name: fadeInDown;
}
</style>
@endsection

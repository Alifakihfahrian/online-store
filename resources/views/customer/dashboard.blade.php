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
                    <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                </div>
                <div class="card-body">
                    <h5 class="card-title">{{ $product->name }}</h5>
                    <p class="card-text">Rp {{ number_format($product->price) }}</p>
                    
                    <form action="{{ route('cart.add') }}" method="POST" class="add-to-cart-form">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-cart-plus"></i> Tambahkan ke Keranjang
                        </button>
                    </form>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.querySelectorAll('.add-to-cart-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch(this.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: data.message
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: data.message || 'Terjadi kesalahan'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: 'Terjadi kesalahan saat menambahkan ke keranjang'
            });
        });
    });
});
</script>
@endsection

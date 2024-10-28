@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Daftar Produk</h1>
        <a href="{{ route('admin.create-product') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Tambah Produk
        </a>
    </div>

    <!-- Filter dan Pencarian -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.dashboard') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label">Cari Produk</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" placeholder="Nama atau deskripsi produk">
                </div>
                <div class="col-md-4">
                    <label for="category" class="form-label">Filter Kategori</label>
                    <select class="form-select" id="category" name="category">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="bi bi-search"></i> Cari
                    </button>
                    @if(request('search') || request('category'))
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Hasil Pencarian -->
    @if(request('search') || request('category'))
        <div class="alert alert-info">
            Menampilkan hasil untuk 
            @if(request('search'))
                pencarian "{{ request('search') }}"
            @endif
            @if(request('category'))
                dalam kategori "{{ $categories->find(request('category'))->name }}"
            @endif
        </div>
    @endif

    <!-- Tabel Produk -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Gambar</th>
                            <th>Nama</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr id="product-{{ $product->id }}">
                                <td>{{ ($products->currentPage() - 1) * $products->perPage() + $loop->iteration }}</td>
                                <td>
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" 
                                             alt="{{ $product->name }}" 
                                             style="max-height: 50px;">
                                    @else
                                        <span class="text-muted">No image</span>
                                    @endif
                                </td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->category ? $product->category->name : 'Tanpa Kategori' }}</td>
                                <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                <td>{{ $product->stock }}</td>
                                <td>
                                    <a href="{{ route('admin.edit-product', $product->id) }}" 
                                       class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button class="btn btn-sm btn-danger delete-product" 
                                            data-id="{{ $product->id }}" 
                                            data-name="{{ $product->name }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada produk</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Simple Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                @if ($products->previousPageUrl())
                    <a href="{{ $products->previousPageUrl() }}" class="btn btn-link">« Previous</a>
                @else
                    <span class="btn btn-link disabled">« Previous</span>
                @endif

                <span class="text-muted">
                    Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} 
                    of {{ $products->total() }} results
                </span>

                @if ($products->nextPageUrl())
                    <a href="{{ $products->nextPageUrl() }}" class="btn btn-link">Next »</a>
                @else
                    <span class="btn btn-link disabled">Next »</span>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .btn-link {
        text-decoration: none;
        color: var(--bs-primary);
    }
    .btn-link.disabled {
        color: var(--bs-gray-500);
        pointer-events: none;
    }
</style>
@endsection

@section('extra_js')
<script>
    // Hapus produk dengan Sweet Alert
    document.querySelectorAll('.delete-product').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.id;
            const productName = this.dataset.name;

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: `Akan menghapus produk "${productName}"`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Buat form untuk delete request
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `{{ url('admin/products') }}/${productId}`;
                    form.style.display = 'none';

                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';

                    const method = document.createElement('input');
                    method.type = 'hidden';
                    method.name = '_method';
                    method.value = 'DELETE';

                    form.appendChild(csrfToken);
                    form.appendChild(method);
                    document.body.appendChild(form);
                    
                    // Submit form
                    form.submit();
                }
            });
        });
    });

    // Tampilkan pesan sukses jika ada
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            timer: 1500,
            showConfirmButton: false
        });
    @endif
</script>
@endsection

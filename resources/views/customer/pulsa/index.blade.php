@extends('layouts.customer')

@section('title', 'Beli Pulsa')

@section('content')
<div class="container mt-5">
    <!-- Tombol Kembali -->
    <div class="mb-4">
        <a href="{{ route('customer.dashboard') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h2 class="text-center mb-4">Beli Pulsa</h2>
                    
                    <form action="{{ route('pulsa.redirect') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="1">
                        <input type="hidden" name="quantity" value="1">
                        
                        <!-- Provider Selection -->
                        <div class="mb-4">
                            <label class="form-label">Pilih Provider</label>
                            <div class="row row-cols-2 row-cols-md-5 g-3">
                                <!-- Telkomsel -->
                                <div class="col">
                                    <div class="card provider-card text-center h-100" onclick="selectProvider(this, 'Telkomsel')">
                                        <div class="card-body">
                                            <svg class="provider-logo" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2Z" fill="#EC1C24"/>
                                                <path d="M15.5 7H8.5C7.67 7 7 7.67 7 8.5V15.5C7 16.33 7.67 17 8.5 17H15.5C16.33 17 17 16.33 17 15.5V8.5C17 7.67 16.33 7 15.5 7Z" fill="white"/>
                                            </svg>
                                            <div class="provider-name">Telkomsel</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Indosat -->
                                <div class="col">
                                    <div class="card provider-card text-center h-100" onclick="selectProvider(this, 'Indosat')">
                                        <div class="card-body">
                                            <svg class="provider-logo" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2Z" fill="#FF0000"/>
                                                <path d="M12 6C8.69 6 6 8.69 6 12C6 15.31 8.69 18 12 18C15.31 18 18 15.31 18 12C18 8.69 15.31 6 12 6Z" fill="white"/>
                                            </svg>
                                            <div class="provider-name">Indosat</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- XL -->
                                <div class="col">
                                    <div class="card provider-card text-center h-100" onclick="selectProvider(this, 'XL')">
                                        <div class="card-body">
                                            <svg class="provider-logo" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2Z" fill="#0066CC"/>
                                                <path d="M15.5 8.5L12 12L15.5 15.5M8.5 8.5L12 12L8.5 15.5" stroke="white" stroke-width="2"/>
                                            </svg>
                                            <div class="provider-name">XL</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tri -->
                                <div class="col">
                                    <div class="card provider-card text-center h-100" onclick="selectProvider(this, 'Tri')">
                                        <div class="card-body">
                                            <svg class="provider-logo" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2Z" fill="#9400D3"/>
                                                <path d="M12 7L8 17H16L12 7Z" fill="white"/>
                                            </svg>
                                            <div class="provider-name">Tri</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- By.U -->
                                <div class="col">
                                    <div class="card provider-card text-center h-100" onclick="selectProvider(this, 'By.U')">
                                        <div class="card-body">
                                            <svg class="provider-logo" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2Z" fill="#00A4EF"/>
                                                <path d="M8 8H12C13.1 8 14 8.9 14 10C14 11.1 13.1 12 12 12H8V8Z" fill="white"/>
                                                <path d="M8 12H12C13.1 12 14 12.9 14 14C14 15.1 13.1 16 12 16H8V12Z" fill="white"/>
                                                <path d="M16 8V16" stroke="white" stroke-width="2"/>
                                            </svg>
                                            <div class="provider-name">By.U</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="provider" id="selected_provider" required>
                        </div>

                        <!-- Nomor Telepon -->
                        <div class="mb-4">
                            <label for="phone_number" class="form-label">Nomor Telepon</label>
                            <input type="tel" class="form-control" id="phone_number" name="phone_number" required>
                        </div>

                        <!-- Nominal Pulsa -->
                        <div class="mb-4">
                            <label for="nominal" class="form-label">Nominal Pulsa</label>
                            <div class="row g-3">
                                <!-- Quick Amount Buttons -->
                                <div class="col-12">
                                    <div class="btn-group flex-wrap" role="group" aria-label="Nominal pulsa">
                                        <button type="button" class="btn btn-outline-primary nominal-btn" data-nominal="5000">5.000</button>
                                        <button type="button" class="btn btn-outline-primary nominal-btn" data-nominal="10000">10.000</button>
                                        <button type="button" class="btn btn-outline-primary nominal-btn" data-nominal="20000">20.000</button>
                                        <button type="button" class="btn btn-outline-primary nominal-btn" data-nominal="50000">50.000</button>
                                        <button type="button" class="btn btn-outline-primary nominal-btn" data-nominal="100000">100.000</button>
                                    </div>
                                </div>
                                
                                <!-- Custom Amount Input -->
                                <div class="col-12">
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" 
                                               class="form-control nominal-input" 
                                               id="nominal" 
                                               name="nominal" 
                                               placeholder="Masukkan nominal lainnya"
                                               min="1000"
                                               step="1000"
                                               required>
                                    </div>
                                    <div class="form-text">Minimal Rp 1.000</div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-purchase">
                                <i class="bi bi-cart-check"></i> Lanjutkan ke Pembayaran
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('extra_css')
<style>
.provider-logo {
    width: 80px;
    height: 80px;
    margin-bottom: 10px;
}

.provider-card {
    cursor: pointer;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.provider-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.provider-card.selected {
    border-color: var(--bs-primary);
    background-color: var(--bs-primary-bg-subtle);
}

.provider-name {
    font-weight: 600;
    margin-top: 10px;
}

/* Animasi hover untuk logo */
.provider-card:hover .provider-logo {
    transform: scale(1.1);
    transition: transform 0.3s ease;
}

.nominal-btn {
    min-width: 100px;
    margin: 2px;
}

.nominal-btn.active {
    background-color: var(--bs-primary);
    color: white;
}

.nominal-input {
    font-size: 1.1rem;
    padding: 10px;
}

.input-group-text {
    font-size: 1.1rem;
    padding: 10px 15px;
}

/* Untuk tampilan mobile */
@media (max-width: 768px) {
    .btn-group {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
    }
    
    .nominal-btn {
        border-radius: 5px !important;
        margin: 2px;
    }
}
</style>
@endsection

@section('extra_js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function selectProvider(element, provider) {
    // Remove selected class from all provider cards
    document.querySelectorAll('.provider-card').forEach(card => {
        card.classList.remove('selected');
    });
    
    // Add selected class to clicked card
    element.classList.add('selected');
    
    // Update hidden input
    document.getElementById('selected_provider').value = provider;
}

document.querySelectorAll('.nominal-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const nominal = this.dataset.nominal;
        document.getElementById('nominal').value = nominal;
        
        // Update active state
        document.querySelectorAll('.nominal-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
    });
});

document.getElementById('pulsaForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('{{ route("cart.add") }}', {
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

document.addEventListener('DOMContentLoaded', function() {
    const nominalBtns = document.querySelectorAll('.nominal-btn');
    const nominalInput = document.getElementById('nominal');

    // Fungsi untuk memformat angka ke format rupiah
    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID').format(angka);
    }

    // Event listener untuk tombol nominal cepat
    nominalBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Hapus kelas active dari semua tombol
            nominalBtns.forEach(b => b.classList.remove('active'));
            
            // Tambah kelas active ke tombol yang diklik
            this.classList.add('active');
            
            // Update nilai input
            const nominal = this.dataset.nominal;
            nominalInput.value = nominal;
        });
    });

    // Event listener untuk input manual
    nominalInput.addEventListener('input', function() {
        // Hapus kelas active dari semua tombol saat input manual
        nominalBtns.forEach(btn => btn.classList.remove('active'));
        
        // Pastikan nilai minimal 1000
        if (this.value < 1000) {
            this.value = 1000;
        }
        
        // Bulatkan ke kelipatan 1000
        this.value = Math.round(this.value / 1000) * 1000;
    });

    // Validasi form sebelum submit
    document.querySelector('form').addEventListener('submit', function(e) {
        const nominal = parseInt(nominalInput.value);
        if (nominal < 1000) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Nominal Tidak Valid',
                text: 'Minimal nominal pulsa adalah Rp 1.000'
            });
        }
    });
});
</script>
@endsection 
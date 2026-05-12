<x-app-layout>
    <style>
        :root {
            --dashboard-primary: #2563EB;
        }

        .dashboard-content {
            padding-top: 1.5rem;
            padding-bottom: 4rem;
        }

        /* Hero Section */
        .hero-banner {
            background: linear-gradient(135deg, #1E3A8A 0%, #2563EB 100%);
            border-radius: 20px;
            padding: 3rem 2rem;
            color: white;
            box-shadow: 0 10px 25px rgba(37, 99, 235, 0.25);
            margin-bottom: 2.5rem;
        }

        .hero-banner h1 {
            font-weight: 700;
            font-size: clamp(1.8rem, 5.5vw, 2.4rem);
            margin-bottom: 0.5rem;
        }

        /* Stat Boxes */
        .stat-box {
            background: #ffffff;
            border: 1px solid #eef0f4;
            border-radius: 16px;
            padding: 1.8rem 1.4rem;
            transition: all 0.3s ease;
            height: 100%;
        }

        .stat-box:hover {
            transform: translateY(-6px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.07);
        }

        .stat-icon-circle {
            width: 58px;
            height: 58px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin-bottom: 1rem;
        }

        /* Action Cards */
        .action-card {
            background: #ffffff;
            border: 1px solid #eef0f4;
            border-radius: 16px;
            padding: 1.8rem;
            text-decoration: none !important;
            color: inherit !important;
            display: block;
            transition: all 0.3s ease;
            height: 100%;
        }

        .action-card:hover {
            border-color: var(--dashboard-primary);
            background: #f0f7ff;
            transform: translateY(-4px);
        }

        @media (max-width: 768px) {
            .hero-banner {
                padding: 2.2rem 1.5rem;
            }
            .stat-box, .action-card {
                padding: 1.5rem;
            }
        }
    </style>

    <div class="dashboard-content">
        <!-- Hero -->
        <div class="hero-banner">
            <h1>Halo, {{ auth()->user()->name }} 👋</h1>
            <p>Siap untuk meningkatkan skill kamu hari ini?</p>

            <div class="d-flex gap-3 flex-wrap">
                <a href="{{ route('murid.cari-tutor') }}" class="btn btn-light">
                    <i class="fa fa-search me-2"></i>Cari Tutor
                </a>
                <a href="{{ route('murid.riwayat') }}" class="btn btn-outline-light">
                    <i class="fa fa-calendar me-2"></i>Jadwal Saya
                </a>
            </div>
        </div>

        <!-- Stats Row -->
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="stat-box">
                    <div class="stat-icon-circle" style="background: #eff6ff; color: #2563EB;">
                        <i class="fa fa-calendar-check"></i>
                    </div>
                    <small class="text-uppercase fw-bold text-muted">Booking Aktif</small>
                    <h2 class="mb-0 fw-bold text-primary">{{ $bookingAktif ?? 0 }}</h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-box">
                    <div class="stat-icon-circle" style="background: #ecfdf5; color: #10B981;">
                        <i class="fa fa-graduation-cap"></i>
                    </div>
                    <small class="text-uppercase fw-bold text-muted">Total Sesi</small>
                    <h2 class="mb-0 fw-bold" style="color: #10B981;">{{ $totalSesi ?? 0 }}</h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-box">
                    <div class="stat-icon-circle" style="background: #fffbeb; color: #f59e0b;">
                        <i class="fa fa-star"></i>
                    </div>
                    <small class="text-uppercase fw-bold text-muted">Tutor Favorit</small>
                    <h2 class="mb-0 fw-bold" style="color: #f59e0b;">{{ $tutorFavorit ?? 0 }}</h2>
                </div>
            </div>
        </div>

        <!-- Aksi Cepat -->
        <h5 class="fw-bold mb-4">Aksi Cepat</h5>
        <div class="row g-4">
            <div class="col-md-6">
                <a href="{{ route('murid.cari-tutor') }}" class="action-card">
                    <div class="d-flex align-items-center gap-3">
                        <div style="font-size: 2.6rem;">🔍</div>
                        <div>
                            <h6 class="mb-1 fw-bold">Eksplorasi Tutor</h6>
                            <p class="mb-0 text-muted small">Cari tutor berdasarkan mata pelajaran atau lokasi.</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-6">
                <a href="{{ route('murid.riwayat') }}" class="action-card">
                    <div class="d-flex align-items-center gap-3">
                        <div style="font-size: 2.6rem;">📋</div>
                        <div>
                            <h6 class="mb-1 fw-bold">Manajemen Kelas</h6>
                            <p class="mb-0 text-muted small">Lihat status pembayaran dan riwayat belajar kamu.</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="mt-5 p-5 text-center" style="background: white; border-radius: 16px; border: 1px solid #eef0f4;">
            <h6 class="fw-bold mb-4">Aktivitas Terakhir</h6>
            <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="80" style="opacity: 0.15;" alt="">
            <p class="text-muted mt-3">Belum ada aktivitas terbaru saat ini.</p>
        </div>
    </div>
</x-app-layout>

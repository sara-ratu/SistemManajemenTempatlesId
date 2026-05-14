<x-app-layout>
    <style>
        :root {
            --primary: #2563EB;
        }

        .sidebar {
            background: #ffffff;
            border-right: 1px solid #e5e7eb;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            width: 260px;
            padding-top: 80px;
            overflow-y: auto;
            z-index: 10;
        }

        .sidebar .nav-link {
            color: #4b5563;
            padding: 0.75rem 1.25rem;
            border-radius: 8px;
            margin: 0.25rem 0.75rem;
            transition: all 0.3s ease;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: #f1f5f9;
            color: var(--primary);
            font-weight: 500;
        }

        .main-content {
            margin-left: 260px;
            padding: 1.5rem 2rem;
        }

        .hero-banner {
            background: linear-gradient(135deg, #1E3A8A 0%, #2563EB 100%);
            border-radius: 20px;
            padding: 2.5rem 2rem;
            color: white;
            margin-bottom: 2rem;
        }

        .stat-box {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 1.5rem;
            transition: all 0.3s ease;
        }

        .stat-box:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.1);
        }

        .section-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 1.25rem;
        }

        @media (max-width: 992px) {
            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
                padding-top: 1rem;
            }
            .main-content {
                margin-left: 0;
                padding: 1rem;
            }
        }
    </style>

    <div class="d-flex">

        <!-- ================= SIDEBAR ================= -->
        <div class="sidebar">
            <div class="px-4 py-3 border-bottom">
                <h5 class="fw-bold text-primary mb-0">🎓 EduPlatform</h5>
            </div>

            <div class="mt-4">
                <a href="{{ route('Member.dashboard') }}" class="nav-link active d-flex align-items-center gap-3">
                    <i class="fa fa-home fa-fw"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('Member.cari-tutor') }}" class="nav-link d-flex align-items-center gap-3">
                    <i class="fa fa-search fa-fw"></i>
                    <span>Cari Tutor</span>
                </a>
                <a href="{{ route('Member.riwayat') }}" class="nav-link d-flex align-items-center gap-3">
                    <i class="fa fa-calendar-alt fa-fw"></i>
                    <span>Jadwal & Riwayat</span>
                </a>
                <a href="#" class="nav-link d-flex align-items-center gap-3">
                    <i class="fa fa-book-open fa-fw"></i>
                    <span>Kelas Saya</span>
                </a>
                <a href="#" class="nav-link d-flex align-items-center gap-3">
                    <i class="fa fa-star fa-fw"></i>
                    <span>Tutor Favorit</span>
                </a>
                <a href="#" class="nav-link d-flex align-items-center gap-3">
                    <i class="fa fa-credit-card fa-fw"></i>
                    <span>Pembayaran</span>
                </a>
            </div>

            <div class="mt-auto p-4 border-top position-absolute bottom-0 w-100">
                <a href="{{ route('profile.edit') }}" class="nav-link d-flex align-items-center gap-3">
                    <i class="fa fa-user fa-fw"></i>
                    <span>Profil Saya</span>
                </a>
                <a href="{{ route('logout') }}" class="nav-link d-flex align-items-center gap-3 text-danger"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fa fa-sign-out-alt fa-fw"></i>
                    <span>Keluar</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div>

        <!-- ================= MAIN CONTENT ================= -->
        <div class="main-content flex-grow-1">

            <!-- Hero -->
            <div class="hero-banner">
                <h1>Halo, {{ auth()->user()->name }} 👋</h1>
                <p class="lead mb-0">Selamat datang kembali. Siap melanjutkan perjalanan belajarmu hari ini?</p>
            </div>

            <!-- Stats -->
            <div class="row g-4 mb-5">
                <div class="col-md-4">
                    <div class="stat-box">
                        <div class="d-flex align-items-center gap-3">
                            <div style="width: 50px; height: 50px; background: #EFF6FF; color: #2563EB; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.6rem;">
                                <i class="fa fa-calendar-check"></i>
                            </div>
                            <div>
                                <small class="text-muted fw-medium">BOOKING AKTIF</small>
                                <h2 class="fw-bold mb-0">{{ $bookingAktif ?? 0 }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-box">
                        <div class="d-flex align-items-center gap-3">
                            <div style="width: 50px; height: 50px; background: #ECFDF5; color: #10B981; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.6rem;">
                                <i class="fa fa-graduation-cap"></i>
                            </div>
                            <div>
                                <small class="text-muted fw-medium">TOTAL SESI</small>
                                <h2 class="fw-bold mb-0">{{ $totalSesi ?? 0 }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-box">
                        <div class="d-flex align-items-center gap-3">
                            <div style="width: 50px; height: 50px; background: #FEF3C7; color: #D97706; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.6rem;">
                                <i class="fa fa-star"></i>
                            </div>
                            <div>
                                <small class="text-muted fw-medium">TUTOR FAVORIT</small>
                                <h2 class="fw-bold mb-0">{{ $tutorFavorit ?? 0 }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Aktivitas Terakhir -->
            <h5 class="section-title">Aktivitas Terakhir</h5>
            <div class="p-5 text-center bg-white border rounded-3" style="border-color: #e5e7eb;">
                <i class="fa fa-clock fa-3x text-muted mb-3" style="opacity: 0.15;"></i>
                <p class="text-muted">Belum ada aktivitas belajar saat ini</p>
                <a href="{{ route('Member.cari-tutor') }}" class="btn btn-primary mt-3">
                    <i class="fa fa-search me-2"></i>Mulai Cari Tutor
                </a>
            </div>

        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Riwayat Booking</h2>
    </x-slot>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,400&display=swap" rel="stylesheet">

    <style>
        :root {
            --ink:      #0A0F1E;
            --ink-soft: #3D4460;
            --muted:    #8B92A9;
            --line:     #EEF0F6;
            --surface:  #F7F8FC;
            --white:    #FFFFFF;

            --brand:       #3563F0;
            --brand-light: #EBF0FE;
            --brand-dark:  #1A3EC4;

            --emerald:       #0BAB64;
            --emerald-light: #E0F8EE;

            --amber:       #F59E0B;
            --amber-light: #FEF3C7;

            --rose:       #F43F5E;
            --rose-light: #FFE4E9;

            --radius-sm: 10px;
            --radius:    16px;
            --radius-lg: 22px;
            --shadow-sm: 0 1px 3px rgba(10,15,30,.06), 0 1px 2px rgba(10,15,30,.04);
            --shadow:    0 4px 16px rgba(10,15,30,.08), 0 1px 4px rgba(10,15,30,.04);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        .rv-wrap {
            font-family: 'DM Sans', sans-serif;
            color: var(--ink);
            background: var(--surface);
            min-height: 100vh;
            padding: 2rem 1rem 3rem;
        }

        .rv-inner {
            max-width: 680px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        /* ── Breadcrumb ── */
        .breadcrumb {
            display: flex; align-items: center; gap: 6px;
            font-size: 12px; color: var(--muted);
            flex-wrap: wrap;
        }
        .breadcrumb a { color: var(--muted); text-decoration: none; transition: color .12s; }
        .breadcrumb a:hover { color: var(--ink); }
        .breadcrumb .sep { color: var(--line); }
        .breadcrumb .cur { color: var(--ink); font-weight: 600; }

        /* ── Page Title ── */
        .page-title {
            font-family: 'Sora', sans-serif;
            font-size: clamp(1.35rem, 4vw, 1.75rem);
            font-weight: 800;
            letter-spacing: -.02em;
            color: var(--ink);
        }
        .page-sub {
            font-size: 13px; color: var(--muted);
            margin-top: 4px;
        }

        /* ── Filter Bar ── */
        .filter-bar {
            display: flex; align-items: center; gap: 8px;
            flex-wrap: wrap;
        }
        .filter-chip {
            font-family: 'DM Sans', sans-serif;
            font-size: 12px; font-weight: 600;
            padding: 6px 14px; border-radius: 100px;
            border: 1.5px solid var(--line);
            background: var(--white);
            color: var(--muted);
            text-decoration: none;
            transition: all .15s;
            cursor: pointer;
        }
        .filter-chip:hover,
        .filter-chip.active {
            background: var(--ink);
            border-color: var(--ink);
            color: white;
        }
        .filter-chip.fc-confirmed.active { background: var(--emerald); border-color: var(--emerald); }
        .filter-chip.fc-pending.active   { background: var(--amber);   border-color: var(--amber); color: var(--ink); }
        .filter-chip.fc-cancelled.active { background: var(--rose);    border-color: var(--rose); }

        /* ── Bookings Card ── */
        .bookings-card {
            background: var(--white);
            border: 1px solid var(--line);
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow-sm);
        }
        .bookings-card-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 1.125rem 1.25rem;
            border-bottom: 1px solid var(--line);
        }
        .bookings-card-title {
            font-family: 'Sora', sans-serif;
            font-size: 14px; font-weight: 700; color: var(--ink);
            display: flex; align-items: center; gap: 8px;
        }
        .count-pill {
            background: var(--brand-light);
            color: var(--brand);
            font-size: 10px; font-weight: 700;
            padding: 2px 8px; border-radius: 100px;
        }

        /* ── Booking Row ── */
        .booking-row {
            display: flex; align-items: flex-start; gap: 12px;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--line);
            transition: background .12s;
        }
        .booking-row:last-child { border-bottom: none; }
        .booking-row:hover { background: var(--surface); }

        .b-avatar {
            width: 42px; height: 42px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-family: 'Sora', sans-serif;
            font-weight: 700; font-size: 13px; flex-shrink: 0;
            margin-top: 1px;
        }
        .ba-0 { background: var(--brand-light);   color: var(--brand); }
        .ba-1 { background: var(--emerald-light);  color: var(--emerald); }
        .ba-2 { background: #F0EDFE;               color: #6D28D9; }
        .ba-3 { background: var(--amber-light);    color: #92400E; }
        .ba-4 { background: var(--rose-light);     color: var(--rose); }

        .b-main { flex: 1; min-width: 0; }
        .b-name {
            font-size: 13.5px; font-weight: 600; color: var(--ink);
            margin-bottom: 2px;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .b-subject {
            font-size: 11.5px; color: var(--brand); font-weight: 600;
            margin-bottom: 4px;
        }
        .b-meta {
            font-size: 11.5px; color: var(--muted);
            display: flex; align-items: center; gap: 6px; flex-wrap: wrap;
        }
        .b-meta-sep { color: var(--line); }

        .b-right {
            display: flex; flex-direction: column; align-items: flex-end; gap: 6px;
            flex-shrink: 0;
        }
        .b-badge {
            font-size: 10px; font-weight: 700;
            padding: 4px 10px; border-radius: 100px;
            letter-spacing: .02em;
        }
        .bb-confirmed { background: var(--emerald-light); color: #065F46; }
        .bb-pending   { background: var(--amber-light);   color: #78350F; }
        .bb-cancelled { background: var(--rose-light);    color: #9F1239; }
        .bb-default   { background: var(--line);          color: var(--ink-soft); }

        .b-harga {
            font-family: 'Sora', sans-serif;
            font-size: 12px; font-weight: 700; color: var(--ink);
            letter-spacing: -.01em;
        }

        /* ── Notes ── */
        .b-catatan {
            font-size: 11px; color: var(--muted);
            background: var(--surface);
            border-radius: var(--radius-sm);
            padding: 6px 10px; margin-top: 6px;
            line-height: 1.5;
            font-style: italic;
        }

        /* ── Empty ── */
        .empty-box {
            padding: 3rem 1rem; text-align: center;
        }
        .empty-emoji { font-size: 40px; margin-bottom: .875rem; }
        .empty-text {
            font-size: 13px; color: var(--muted);
            margin-bottom: 1.25rem; line-height: 1.6;
        }
        .empty-btn {
            display: inline-flex; align-items: center; gap: 6px;
            background: var(--brand); color: white;
            font-family: 'Sora', sans-serif; font-weight: 700; font-size: 12px;
            padding: 10px 20px; border-radius: 100px; text-decoration: none;
            transition: transform .15s, box-shadow .15s;
        }
        .empty-btn:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(53,99,240,.3); }

        /* ── Pagination ── */
        .pagination-wrap {
            display: flex; justify-content: center; gap: 6px;
            padding: 1rem 1.25rem;
            border-top: 1px solid var(--line);
            flex-wrap: wrap;
        }
        .pagination-wrap a,
        .pagination-wrap span {
            font-family: 'DM Sans', sans-serif;
            font-size: 12px; font-weight: 600;
            padding: 6px 12px; border-radius: var(--radius-sm);
            text-decoration: none;
            border: 1.5px solid var(--line);
            color: var(--muted);
            background: var(--white);
            transition: all .12s;
        }
        .pagination-wrap a:hover { border-color: var(--brand); color: var(--brand); }
        .pagination-wrap span[aria-current] {
            background: var(--ink); border-color: var(--ink); color: white;
        }
        .pagination-wrap span.disabled { opacity: .4; cursor: default; }

        /* ── Back Link ── */
        .back-link {
            display: inline-flex; align-items: center; gap: 6px;
            font-size: 12px; font-weight: 600; color: var(--muted);
            text-decoration: none; transition: color .12s;
        }
        .back-link:hover { color: var(--ink); }

        /* ── Animations ── */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .anim-1 { animation: fadeUp .45s .00s ease both; }
        .anim-2 { animation: fadeUp .45s .09s ease both; }
        .anim-3 { animation: fadeUp .45s .17s ease both; }

        /* ── Responsive ── */
        @media (max-width: 480px) {
            .rv-wrap { padding: 1.25rem .875rem 2.5rem; }
            .booking-row { padding: .875rem 1rem; gap: 10px; }
            .bookings-card-header { padding: .875rem 1rem; }
            .b-harga { display: none; }
        }
    </style>

    <div class="rv-wrap">
        <div class="rv-inner">

            {{-- ── Header ── --}}
            <div class="anim-1">
                <a href="{{ route('murid.dashboard') }}" class="back-link">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                    Dashboard
                </a>
                <div class="page-title" style="margin-top:.75rem;">Booking Saya</div>
                <div class="page-sub">Semua riwayat sesi belajarmu</div>
            </div>

            {{-- ── Bookings ── --}}
            <div class="anim-2">
                <div class="bookings-card">
                    <div class="bookings-card-header">
                        <span class="bookings-card-title">
                            Semua Booking
                            @if($bookings->total() > 0)
                                <span class="count-pill">{{ $bookings->total() }}</span>
                            @endif
                        </span>
                    </div>

                    @php $avClass = ['ba-0','ba-1','ba-2','ba-3','ba-4']; @endphp

                    @if($bookings->count() > 0)
                        @foreach($bookings as $i => $booking)
                        <div class="booking-row">
                            <div class="b-avatar {{ $avClass[$i % 5] }}">
                                {{ strtoupper(substr($booking->tutor->name ?? 'TU', 0, 2)) }}
                            </div>
                            <div class="b-main">
                                <div class="b-name">{{ $booking->tutor->name ?? 'Tutor' }}</div>
                                @if($booking->subject)
                                    <div class="b-subject">{{ $booking->subject->nama_mapel }}</div>
                                @endif
                                <div class="b-meta">
                                    <span>{{ \Carbon\Carbon::parse($booking->tanggal)->translatedFormat('l, j M Y') }}</span>
                                    <span class="b-meta-sep">·</span>
                                    <span>{{ substr($booking->jam_mulai,0,5) }}–{{ substr($booking->jam_selesai,0,5) }}</span>
                                </div>
                                @if($booking->catatan)
                                    <div class="b-catatan">{{ $booking->catatan }}</div>
                                @endif
                            </div>
                            <div class="b-right">
                                @php
                                    $bCls = match($booking->status) {
                                        'confirmed' => 'bb-confirmed',
                                        'pending'   => 'bb-pending',
                                        'cancelled' => 'bb-cancelled',
                                        default     => 'bb-default',
                                    };
                                @endphp
                                <span class="b-badge {{ $bCls }}">{{ ucfirst($booking->status) }}</span>
                                @if($booking->harga)
                                    <div class="b-harga">Rp {{ number_format($booking->harga) }}</div>
                                @endif
                            </div>
                        </div>
                        @endforeach

                        {{-- Pagination --}}
                        @if($bookings->hasPages())
                            <div class="pagination-wrap">
                                {{ $bookings->links() }}
                            </div>
                        @endif
                    @else
                        <div class="empty-box">
                            <div class="empty-emoji">📭</div>
                            <div class="empty-text">Belum ada booking sama sekali.<br>Yuk mulai belajar bersama tutor!</div>
                            <a href="{{ route('murid.cari-tutor') }}" class="empty-btn">
                                Cari Tutor
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

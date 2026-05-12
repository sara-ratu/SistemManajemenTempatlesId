<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Dashboard Murid</h2>
    </x-slot>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,600;1,9..144,400&display=swap" rel="stylesheet">

    <style>
        /* ─── Design Tokens ─── */
        :root {
            --ink:        #0D1117;
            --ink-2:      #3B4255;
            --ink-3:      #7A8299;
            --line:       #E8ECF4;
            --surface:    #F3F5FA;
            --white:      #FFFFFF;

            --blue:       #2563EB;
            --blue-dim:   #EEF3FD;
            --blue-dark:  #1749C4;

            --green:      #059669;
            --green-dim:  #ECFDF5;

            --gold:       #D97706;
            --gold-dim:   #FFFBEB;

            --rose:       #E11D48;
            --rose-dim:   #FFF1F4;

            --r-sm: 12px;
            --r:    18px;
            --r-lg: 24px;
            --r-xl: 30px;

            --sh-xs: 0 1px 2px rgba(13,17,23,.04);
            --sh-sm: 0 2px 8px rgba(13,17,23,.06), 0 1px 2px rgba(13,17,23,.04);
            --sh:    0 6px 24px rgba(13,17,23,.08), 0 1px 4px rgba(13,17,23,.04);
            --sh-lg: 0 16px 48px rgba(13,17,23,.12), 0 2px 8px rgba(13,17,23,.05);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        /* ─── Layout Wrapper ─── */
        .db {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--ink);
            background: var(--surface);
            min-height: 100vh;
            padding: 1.75rem 1.5rem 4rem;
        }

        .db-inner {
            max-width: 960px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        /* ─── Hero Banner ─── */
        .hero {
            border-radius: var(--r-xl);
            background: var(--ink);
            padding: 2.75rem 2.5rem 2.5rem;
            position: relative;
            overflow: hidden;
            color: white;
            animation: rise .55s cubic-bezier(.22,1,.36,1) both;
        }

        /* Multi-layer background art */
        .hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 65% 80% at 105% -15%, rgba(37,99,235,.7) 0%, transparent 65%),
                radial-gradient(ellipse 45% 55% at -8% 115%, rgba(5,150,105,.4) 0%, transparent 60%),
                radial-gradient(ellipse 30% 40% at 55% 120%, rgba(217,119,6,.2) 0%, transparent 55%);
            pointer-events: none;
        }

        /* Subtle grid texture */
        .hero::after {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,.035) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,.035) 1px, transparent 1px);
            background-size: 32px 32px;
            pointer-events: none;
        }

        .hero-inner { position: relative; z-index: 1; }

        .hero-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255,255,255,.08);
            border: 1px solid rgba(255,255,255,.14);
            border-radius: 100px;
            font-size: 10.5px;
            font-weight: 700;
            letter-spacing: .06em;
            text-transform: uppercase;
            color: rgba(255,255,255,.65);
            padding: 5px 13px 5px 9px;
            margin-bottom: 1rem;
        }

        .hero-chip-dot {
            width: 6px; height: 6px;
            border-radius: 50%;
            background: #34D399;
            box-shadow: 0 0 0 2px rgba(52,211,153,.3);
            animation: pulse-dot 2s ease-in-out infinite;
        }

        @keyframes pulse-dot {
            0%, 100% { box-shadow: 0 0 0 2px rgba(52,211,153,.3); }
            50%       { box-shadow: 0 0 0 5px rgba(52,211,153,.12); }
        }

        .hero-name {
            font-family: 'Fraunces', serif;
            font-size: clamp(2rem, 4vw, 2.75rem);
            font-weight: 600;
            line-height: 1.1;
            letter-spacing: -.01em;
            margin-bottom: .5rem;
        }

        .hero-name em {
            font-style: italic;
            color: rgba(255,255,255,.55);
        }

        .hero-sub {
            font-size: 13.5px;
            color: rgba(255,255,255,.5);
            margin-bottom: 1.75rem;
            line-height: 1.6;
            max-width: 340px;
        }

        .hero-actions {
            display: flex;
            align-items: center;
            gap: .75rem;
            flex-wrap: wrap;
        }

        .hero-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 700;
            font-size: 13px;
            padding: 11px 22px;
            border-radius: 100px;
            text-decoration: none;
            transition: transform .2s, box-shadow .2s, background .15s;
        }

        .hero-btn.primary {
            background: white;
            color: var(--ink);
            box-shadow: 0 4px 20px rgba(0,0,0,.25);
        }
        .hero-btn.primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 32px rgba(0,0,0,.3);
        }

        .hero-btn.ghost {
            background: rgba(255,255,255,.1);
            color: rgba(255,255,255,.8);
            border: 1px solid rgba(255,255,255,.18);
        }
        .hero-btn.ghost:hover {
            background: rgba(255,255,255,.16);
        }

        .hero-btn svg {
            width: 13px; height: 13px;
            transition: transform .2s;
        }
        .hero-btn.primary:hover svg { transform: translateX(2px); }

        /* Decorative floating circles */
        .hero-deco {
            position: absolute;
            right: -24px; top: -24px;
            width: 200px; height: 200px;
            border-radius: 50%;
            border: 1px solid rgba(255,255,255,.06);
            pointer-events: none;
        }
        .hero-deco::before {
            content: '';
            position: absolute;
            inset: 28px;
            border-radius: 50%;
            border: 1px solid rgba(255,255,255,.05);
        }
        .hero-deco::after {
            content: '';
            position: absolute;
            inset: 56px;
            border-radius: 50%;
            background: rgba(255,255,255,.03);
        }

        /* ─── Stats Row ─── */
        .stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.125rem;
            animation: rise .55s .08s cubic-bezier(.22,1,.36,1) both;
        }

        .stat {
            background: var(--white);
            border: 1px solid var(--line);
            border-radius: var(--r);
            padding: 1.5rem 1.5rem 1.25rem;
            box-shadow: var(--sh-sm);
            position: relative;
            overflow: hidden;
            transition: transform .2s, box-shadow .2s;
        }
        .stat:hover { transform: translateY(-2px); box-shadow: var(--sh); }

        /* Colored top stripe */
        .stat::before {
            content: '';
            position: absolute;
            top: 0; left: 1rem; right: 1rem;
            height: 2.5px;
            border-radius: 0 0 4px 4px;
        }
        .stat.s-blue::before  { background: var(--blue); }
        .stat.s-green::before { background: var(--green); }
        .stat.s-gold::before  { background: var(--gold); }

        .stat-icon {
            width: 38px; height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 17px;
            margin-bottom: .875rem;
        }
        .si-blue  { background: var(--blue-dim); }
        .si-green { background: var(--green-dim); }
        .si-gold  { background: var(--gold-dim); }

        .stat-label {
            font-size: 9px;
            font-weight: 800;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: var(--ink-3);
            margin-bottom: 4px;
        }

        .stat-val {
            font-family: 'Fraunces', serif;
            font-size: 36px;
            font-weight: 600;
            letter-spacing: -.02em;
            line-height: 1;
            margin-bottom: 3px;
        }
        .sv-blue  { color: var(--blue); }
        .sv-green { color: var(--green); }
        .sv-gold  { color: var(--gold); }

        .stat-sub { font-size: 10.5px; color: var(--ink-3); }

        /* ─── Section Label ─── */
        .s-label {
            font-size: 9.5px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .1em;
            color: var(--ink-3);
            margin-bottom: .75rem;
        }

        /* ─── Quick Actions ─── */
        .qa-wrap { animation: rise .55s .16s cubic-bezier(.22,1,.36,1) both; }

        .qa-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.125rem;
        }

        .qa {
            background: var(--white);
            border: 1px solid var(--line);
            border-radius: var(--r);
            padding: 1.375rem 1.25rem;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            box-shadow: var(--sh-sm);
            transition: transform .22s, box-shadow .22s, border-color .22s;
            position: relative;
            overflow: hidden;
        }

        .qa::after {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 80% 20%, var(--blue-dim) 0%, transparent 70%);
            opacity: 0;
            transition: opacity .3s;
        }
        .qa:hover::after { opacity: 1; }
        .qa:hover { transform: translateY(-3px); box-shadow: var(--sh); border-color: #C7D8FB; }

        .qa-icon {
            width: 44px; height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            position: relative; z-index: 1;
        }
        .qi-blue  { background: var(--blue);  }
        .qi-green { background: var(--green); }

        .qa-body { position: relative; z-index: 1; }
        .qa-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 13.5px;
            font-weight: 700;
            color: var(--ink);
            margin-bottom: 3px;
        }
        .qa-desc { font-size: 11.5px; color: var(--ink-3); line-height: 1.5; }

        /* ─── Bookings Card ─── */
        .bk-wrap { animation: rise .55s .24s cubic-bezier(.22,1,.36,1) both; }

        .bk-card {
            background: var(--white);
            border: 1px solid var(--line);
            border-radius: var(--r-lg);
            overflow: hidden;
            box-shadow: var(--sh-sm);
        }

        .bk-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--line);
            background: rgba(243,245,250,.5);
        }

        .bk-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 14.5px;
            font-weight: 700;
            color: var(--ink);
            display: flex;
            align-items: center;
            gap: 9px;
        }

        .bk-count {
            background: var(--blue);
            color: white;
            font-size: 10px;
            font-weight: 700;
            padding: 2px 9px;
            border-radius: 100px;
        }

        .bk-view {
            font-size: 12px;
            font-weight: 600;
            color: var(--blue);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 4px;
            transition: gap .15s;
        }
        .bk-view:hover { gap: 7px; }

        /* ─── Booking Row ─── */
        .bk-row {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 1.125rem 1.5rem;
            border-bottom: 1px solid var(--line);
            transition: background .12s;
            cursor: default;
        }
        .bk-row:last-child { border-bottom: none; }
        .bk-row:hover { background: var(--surface); }

        .bk-av {
            width: 42px; height: 42px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Fraunces', serif;
            font-weight: 600;
            font-size: 14px;
            flex-shrink: 0;
            border: 2px solid transparent;
        }
        /* Avatar color variants */
        .av-0 { background: var(--blue-dim);  color: var(--blue);  border-color: #CDDCFC; }
        .av-1 { background: var(--green-dim); color: var(--green); border-color: #BBF7D0; }
        .av-2 { background: #F0EDFE;          color: #6D28D9;      border-color: #DDD6FE; }
        .av-3 { background: var(--gold-dim);  color: var(--gold);  border-color: #FDE68A; }
        .av-4 { background: var(--rose-dim);  color: var(--rose);  border-color: #FFC9D5; }

        .bk-info { flex: 1; min-width: 0; }

        .bk-name {
            font-size: 13.5px;
            font-weight: 600;
            color: var(--ink);
            margin-bottom: 3px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .bk-time {
            font-size: 11.5px;
            color: var(--ink-3);
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .bk-time-dot {
            width: 3px; height: 3px;
            border-radius: 50%;
            background: var(--ink-3);
            flex-shrink: 0;
        }

        .bk-badge {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .03em;
            padding: 4px 11px;
            border-radius: 100px;
            flex-shrink: 0;
        }
        .bb-confirmed { background: var(--green-dim); color: #065F46; }
        .bb-pending   { background: var(--gold-dim);  color: #78350F; }
        .bb-cancelled { background: var(--rose-dim);  color: #9F1239; }
        .bb-default   { background: var(--line);      color: var(--ink-2); }

        /* ─── Empty State ─── */
        .empty {
            padding: 3rem 1.5rem;
            text-align: center;
        }

        .empty-illo {
            width: 72px; height: 72px;
            border-radius: 50%;
            background: var(--blue-dim);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            margin: 0 auto 1.125rem;
        }

        .empty-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 15px;
            font-weight: 700;
            color: var(--ink);
            margin-bottom: .4rem;
        }

        .empty-desc {
            font-size: 12.5px;
            color: var(--ink-3);
            line-height: 1.6;
            max-width: 240px;
            margin: 0 auto 1.375rem;
        }

        .empty-cta {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: var(--blue);
            color: white;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 700;
            font-size: 12.5px;
            padding: 10px 22px;
            border-radius: 100px;
            text-decoration: none;
            transition: transform .2s, box-shadow .2s;
            box-shadow: 0 4px 16px rgba(37,99,235,.3);
        }
        .empty-cta:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 28px rgba(37,99,235,.4);
        }

        /* ─── Animation ─── */
        @keyframes rise {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ─── Responsive ─── */
        @media (max-width: 768px) {
            .db { padding: 1.25rem 1rem 3rem; }
        }
        @media (max-width: 520px) {
            .db { padding: 1rem .875rem 3rem; }
            .stats { gap: .75rem; }
            .stat { padding: 1.125rem 1rem 1rem; }
            .stat-val { font-size: 28px; }
            .hero { padding: 1.75rem 1.375rem 1.625rem; }
        }
        @media (max-width: 380px) {
            .stats { grid-template-columns: repeat(3, 1fr); gap: .5rem; }
            .stat { padding: .875rem .75rem .875rem; }
            .stat-val { font-size: 22px; }
            .qa-grid { gap: .75rem; }
        }
    </style>

    <div class="db">
        <div class="db-inner">

            {{-- ── Hero ── --}}
            <div class="hero">
                <div class="hero-deco"></div>
                <div class="hero-inner">
                    <div class="hero-chip">
                        <span class="hero-chip-dot"></span>
                        Selamat Datang Kembali
                    </div>
                    <div class="hero-name">
                        Halo, <em>{{ auth()->user()->name }}</em> 👋
                    </div>
                    <p class="hero-sub">Siap belajar hari ini? Temukan tutor terbaik yang pas untukmu.</p>
                    <div class="hero-actions">
                        <a href="{{ route('murid.cari-tutor') }}" class="hero-btn primary">
                            Cari Tutor Sekarang
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </a>
                        <a href="{{ route('murid.riwayat') }}" class="hero-btn ghost">Lihat Jadwal</a>
                    </div>
                </div>
            </div>

            {{-- ── Stats ── --}}
            <div class="stats">
                <div class="stat s-blue">
                    <div class="stat-icon si-blue">📅</div>
                    <div class="stat-label">Aktif</div>
                    <div class="stat-val sv-blue">{{ $bookingAktif ?? 0 }}</div>
                    <div class="stat-sub">Booking</div>
                </div>
                <div class="stat s-green">
                    <div class="stat-icon si-green">✅</div>
                    <div class="stat-label">Total</div>
                    <div class="stat-val sv-green">{{ $totalSesi ?? 0 }}</div>
                    <div class="stat-sub">Sesi</div>
                </div>
                <div class="stat s-gold">
                    <div class="stat-icon si-gold">⭐</div>
                    <div class="stat-label">Favorit</div>
                    <div class="stat-val sv-gold">{{ $tutorFavorit ?? 0 }}</div>
                    <div class="stat-sub">Tutor</div>
                </div>
            </div>

            {{-- ── Quick Actions ── --}}
            <div class="qa-wrap">
                <p class="s-label">Aksi Cepat</p>
                <div class="qa-grid">
                    <a href="{{ route('murid.cari-tutor') }}" class="qa">
                        <div class="qa-icon qi-blue">🔍</div>
                        <div class="qa-body">
                            <div class="qa-title">Cari Tutor</div>
                            <div class="qa-desc">Temukan tutor sesuai kebutuhanmu</div>
                        </div>
                    </a>
                    <a href="{{ route('murid.riwayat') }}" class="qa">
                        <div class="qa-icon qi-green">📋</div>
                        <div class="qa-body">
                            <div class="qa-title">Booking Saya</div>
                            <div class="qa-desc">Lihat semua jadwal belajarmu</div>
                        </div>
                    </a>
                </div>
            </div>

            {{-- ── Booking Terbaru ── --}}
            <div class="bk-wrap">
                <p class="s-label">Booking Terbaru</p>
                <div class="bk-card">
                    <div class="bk-head">
                        <span class="bk-title">
                            Jadwal Saya
                            @if(isset($bookingTerbaru) && count($bookingTerbaru) > 0)
                                <span class="bk-count">{{ count($bookingTerbaru) }}</span>
                            @endif
                        </span>
                        <a href="{{ route('murid.riwayat') }}" class="bk-view">
                            Lihat semua
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </a>
                    </div>

                    @php $avClass = ['av-0','av-1','av-2','av-3','av-4']; @endphp

                    @if(isset($bookingTerbaru) && count($bookingTerbaru) > 0)
                        @foreach($bookingTerbaru as $i => $booking)
                            @php
                                $badgeCls = match($booking->status) {
                                    'confirmed' => 'bb-confirmed',
                                    'pending'   => 'bb-pending',
                                    'cancelled' => 'bb-cancelled',
                                    default     => 'bb-default',
                                };
                                $statusLabel = match($booking->status) {
                                    'confirmed' => 'Confirmed',
                                    'pending'   => 'Pending',
                                    'cancelled' => 'Dibatalkan',
                                    default     => ucfirst($booking->status),
                                };
                            @endphp
                            <div class="bk-row">
                                <div class="bk-av {{ $avClass[$i % 5] }}">
                                    {{ strtoupper(substr($booking->tutor->name ?? 'TU', 0, 2)) }}
                                </div>
                                <div class="bk-info">
                                    <div class="bk-name">{{ $booking->tutor->name ?? 'Tutor' }}</div>
                                    <div class="bk-time">
                                        <span>{{ \Carbon\Carbon::parse($booking->tanggal)->translatedFormat('l, j M Y') }}</span>
                                        <span class="bk-time-dot"></span>
                                        <span>{{ substr($booking->jam_mulai,0,5) }}–{{ substr($booking->jam_selesai,0,5) }}</span>
                                    </div>
                                </div>
                                <span class="bk-badge {{ $badgeCls }}">{{ $statusLabel }}</span>
                            </div>
                        @endforeach
                    @else
                        <div class="empty">
                            <div class="empty-illo">📭</div>
                            <div class="empty-title">Belum ada booking</div>
                            <p class="empty-desc">Yuk mulai belajar bersama tutor pilihanmu sekarang!</p>
                            <a href="{{ route('murid.cari-tutor') }}" class="empty-cta">
                                Cari Tutor
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

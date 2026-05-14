<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Cari Tutor</h2>
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
            --radius-sm: 10px;
            --radius:    16px;
            --radius-lg: 22px;
            --shadow-sm: 0 1px 3px rgba(10,15,30,0.06);
            --shadow:    0 4px 16px rgba(10,15,30,0.08);
            --shadow-lg: 0 12px 40px rgba(10,15,30,0.12);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        .ct-wrap {
            font-family: 'DM Sans', sans-serif;
            color: var(--ink);
            background: var(--surface);
            min-height: 100vh;
            padding: 2rem 1rem 3rem;
        }
        .ct-inner {
            max-width: 900px;
            margin: 0 auto;
            display: flex; flex-direction: column; gap: 1.25rem;
        }

        /* ── Search Hero ── */
        .search-hero {
            border-radius: var(--radius-lg);
            background: var(--ink);
            padding: 2rem 1.75rem 1.875rem;
            position: relative; overflow: hidden; color: white;
            animation: fadeUp .45s ease both;
        }
        .search-hero::before {
            content: '';
            position: absolute; inset: 0;
            background:
                radial-gradient(ellipse 55% 65% at 95% -5%, rgba(53,99,240,.6) 0%, transparent 65%),
                radial-gradient(ellipse 35% 45% at -5% 105%, rgba(11,171,100,.28) 0%, transparent 55%);
            pointer-events: none;
        }
        .hero-noise {
            position: absolute; inset: 0; pointer-events: none; opacity: .025;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='1'/%3E%3C/svg%3E");
            background-size: 200px;
        }
        .hero-content { position: relative; z-index: 1; }
        .hero-eyebrow {
            display: inline-flex; align-items: center; gap: 5px;
            background: rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.18);
            border-radius: 100px; font-size: 11px; font-weight: 600;
            letter-spacing: .04em; text-transform: uppercase;
            color: rgba(255,255,255,.75); padding: 4px 12px; margin-bottom: .875rem;
        }
        .hero-title {
            font-family: 'Sora', sans-serif;
            font-size: clamp(1.6rem, 5vw, 2.1rem);
            font-weight: 800; line-height: 1.1;
            letter-spacing: -.02em; margin-bottom: .375rem;
        }
        .hero-sub { font-size: 13px; color: rgba(255,255,255,.5); margin-bottom: 1.375rem; }

        /* Search bar */
        .search-form { display: flex; gap: 8px; flex-wrap: wrap; }
        .search-field {
            flex: 1; min-width: 180px; position: relative;
        }
        .sf-icon {
            position: absolute; left: 14px; top: 50%;
            transform: translateY(-50%); font-size: 13px; pointer-events: none;
        }
        .sf-input {
            width: 100%; height: 44px;
            background: rgba(255,255,255,.12);
            border: 1px solid rgba(255,255,255,.2);
            border-radius: 100px; color: white;
            font-family: 'DM Sans', sans-serif; font-size: 13px;
            padding: 0 16px 0 38px; outline: none;
            transition: background .15s, border-color .15s;
        }
        .sf-input::placeholder { color: rgba(255,255,255,.45); }
        .sf-input:focus { background: rgba(255,255,255,.18); border-color: rgba(255,255,255,.38); }

        .sf-loc { flex: 0 0 160px; }
        .search-submit {
            height: 44px; padding: 0 22px;
            background: white; color: var(--ink);
            font-family: 'Sora', sans-serif; font-weight: 700; font-size: 12.5px;
            border: none; border-radius: 100px; cursor: pointer;
            white-space: nowrap;
            transition: transform .15s, box-shadow .15s;
        }
        .search-submit:hover { transform: translateY(-1.5px); box-shadow: 0 6px 20px rgba(0,0,0,.22); }

        /* ── Filter Bar ── */
        .filter-bar {
            display: flex; gap: 8px; flex-wrap: wrap; align-items: center;
            animation: fadeUp .45s .1s ease both;
        }
        .filter-label {
            font-size: 10.5px; font-weight: 700;
            text-transform: uppercase; letter-spacing: .07em; color: var(--muted);
        }
        .filter-chip {
            display: inline-flex; align-items: center;
            height: 31px; padding: 0 14px;
            background: white; border: 1px solid var(--line);
            border-radius: 100px;
            font-family: 'DM Sans', sans-serif; font-size: 12px; font-weight: 600; color: var(--ink-soft);
            text-decoration: none; cursor: pointer;
            transition: all .14s; white-space: nowrap;
        }
        .filter-chip:hover, .filter-chip.active {
            background: var(--brand); border-color: var(--brand); color: white;
        }

        /* ── Results header ── */
        .results-head {
            display: flex; align-items: center; justify-content: space-between;
            animation: fadeUp .45s .16s ease both;
        }
        .section-label {
            font-size: 10px; font-weight: 700;
            text-transform: uppercase; letter-spacing: .08em; color: var(--muted);
        }
        .result-count { font-size: 12px; color: var(--muted); font-weight: 500; }

        /* ── Tutor Grid ── */
        .tutor-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(270px, 1fr));
            gap: 1rem;
            animation: fadeUp .45s .2s ease both;
        }

        .tutor-card {
            background: white; border: 1px solid var(--line);
            border-radius: var(--radius-lg); overflow: hidden;
            text-decoration: none; display: flex; flex-direction: column;
            box-shadow: var(--shadow-sm);
            transition: transform .2s, box-shadow .2s, border-color .2s;
        }
        .tutor-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
            border-color: #C8D8FF;
        }

        .tc-body { padding: 1.125rem 1.125rem .875rem; flex: 1; }
        .tc-top { display: flex; align-items: flex-start; gap: 12px; margin-bottom: .875rem; }

        .tc-avatar {
            width: 46px; height: 46px; border-radius: 13px;
            display: flex; align-items: center; justify-content: center;
            font-family: 'Sora', sans-serif; font-weight: 700; font-size: 15px; flex-shrink: 0;
        }
        .ava-0 { background: var(--brand-light);   color: var(--brand); }
        .ava-1 { background: var(--emerald-light);  color: var(--emerald); }
        .ava-2 { background: #F0EDFE;               color: #6D28D9; }
        .ava-3 { background: var(--amber-light);    color: #78400A; }
        .ava-4 { background: #FFE4E9;               color: #C4143E; }

        .tc-name {
            font-family: 'Sora', sans-serif; font-size: 14px; font-weight: 700; color: var(--ink);
            margin-bottom: 3px; line-height: 1.2;
        }
        .tc-mapel { font-size: 11px; color: var(--muted); line-height: 1.4; }
        .tc-rating {
            margin-left: auto; flex-shrink: 0;
            display: flex; align-items: center; gap: 3px;
            font-family: 'Sora', sans-serif; font-size: 12px; font-weight: 700;
            color: #92400E;
        }

        .tc-tags { display: flex; flex-wrap: wrap; gap: 5px; margin-bottom: .875rem; }
        .tc-tag {
            font-size: 10px; font-weight: 600; padding: 3px 9px; border-radius: 100px;
            background: var(--surface); color: var(--ink-soft);
            border: 1px solid var(--line);
        }

        .tc-avail {
            display: flex; align-items: center; gap: 5px;
            font-size: 11px; font-weight: 600; color: var(--emerald);
        }
        .avail-dot {
            width: 7px; height: 7px; border-radius: 50%;
            background: var(--emerald); flex-shrink: 0;
            box-shadow: 0 0 0 3px rgba(11,171,100,.2);
        }

        .tc-foot {
            padding: .75rem 1.125rem;
            border-top: 1px solid var(--line);
            display: flex; align-items: center; justify-content: space-between;
        }
        .tc-price {
            font-family: 'Sora', sans-serif; font-size: 16.5px; font-weight: 700;
            color: var(--ink); letter-spacing: -.02em;
        }
        .tc-price-sub { font-size: 10px; color: var(--muted); margin-top: 1px; }

        .book-btn {
            height: 32px; padding: 0 16px;
            background: var(--brand); color: white;
            font-family: 'Sora', sans-serif; font-weight: 700; font-size: 11px;
            border: none; border-radius: 100px; cursor: pointer; text-decoration: none;
            display: inline-flex; align-items: center; gap: 4px;
            transition: background .15s, transform .12s, box-shadow .15s;
        }
        .book-btn:hover {
            background: var(--brand-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(53,99,240,.35);
        }

        /* ── Empty State ── */
        .empty-state {
            background: white; border: 1px solid var(--line);
            border-radius: var(--radius-lg);
            padding: 3.5rem 1.5rem; text-align: center;
            animation: fadeUp .45s .2s ease both;
        }
        .es-icon { font-size: 44px; margin-bottom: .875rem; }
        .es-title {
            font-family: 'Sora', sans-serif; font-size: 18px; font-weight: 700;
            color: var(--ink); margin-bottom: .375rem;
        }
        .es-text { font-size: 13px; color: var(--muted); line-height: 1.5; margin-bottom: 1.25rem; }
        .reset-btn {
            display: inline-flex; align-items: center; gap: 6px;
            background: var(--brand); color: white;
            font-family: 'Sora', sans-serif; font-weight: 700; font-size: 12.5px;
            padding: 11px 22px; border-radius: 100px; text-decoration: none;
            transition: transform .15s, box-shadow .15s;
        }
        .reset-btn:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(53,99,240,.3); }

        /* ── Pagination ── */
        .pagination-wrap {
            display: flex; justify-content: center; gap: 6px; flex-wrap: wrap;
            animation: fadeUp .45s .28s ease both;
            margin-top: .5rem;
        }
        .pg-btn {
            width: 36px; height: 36px;
            display: flex; align-items: center; justify-content: center;
            border: 1px solid var(--line); border-radius: 10px;
            font-family: 'Sora', sans-serif; font-size: 12.5px; font-weight: 600;
            color: var(--ink-soft); background: white;
            text-decoration: none;
            transition: all .14s;
        }
        .pg-btn:hover, .pg-btn.active { background: var(--brand); border-color: var(--brand); color: white; }
        .pg-btn.disabled { opacity: .35; pointer-events: none; }

        /* ── Animations ── */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── Responsive ── */
        @media (max-width: 640px) {
            .tutor-grid { grid-template-columns: 1fr; }
            .search-form { flex-direction: column; }
            .sf-loc { flex: auto; }
            .search-submit { width: 100%; }
        }
    </style>

    <div class="ct-wrap">
        <div class="ct-inner">

            {{-- ── Search Hero ── --}}
            <div class="search-hero">
                <div class="hero-noise"></div>
                <div class="hero-content">
                    <div class="hero-eyebrow">✦ Cari Tutor Terbaik</div>
                    <div class="hero-title">Belajar Lebih Cerdas</div>
                    <div class="hero-sub">{{ $totalTutor ?? '50' }}+ tutor terverifikasi siap membantumu</div>
                    <form method="GET" action="{{ route('Member.cari-tutor') }}" class="search-form">
                        <div class="search-field">
                            <span class="sf-icon">🔎</span>
                            <input type="text" name="q" class="sf-input"
                                placeholder="Nama tutor atau mata pelajaran..."
                                value="{{ request('q') }}" autocomplete="off">
                        </div>
                        <div class="search-field sf-loc">
                            <span class="sf-icon">📍</span>
                            <input type="text" name="lokasi" class="sf-input"
                                placeholder="Kota / lokasi"
                                value="{{ request('lokasi') }}" autocomplete="off">
                        </div>
                        <button type="submit" class="search-submit">Cari →</button>
                    </form>
                </div>
            </div>

            {{-- ── Filter Bar ── --}}
            <div class="filter-bar">
                <span class="filter-label">Filter:</span>
                @php $mapels = ['Semua','Matematika','Fisika','Kimia','Biologi','Bahasa Inggris','Bahasa Indonesia','Pemrograman']; @endphp
                @foreach($mapels as $mapel)
                <a href="{{ route('Member.cari-tutor', array_merge(request()->query(), ['mapel' => $mapel === 'Semua' ? '' : $mapel])) }}"
                   class="filter-chip {{ request('mapel','') === ($mapel === 'Semua' ? '' : $mapel) ? 'active' : '' }}">
                    {{ $mapel }}
                </a>
                @endforeach
            </div>

            {{-- ── Results Header ── --}}
            <div class="results-head">
                <p class="section-label">Tutor Tersedia</p>
                <span class="result-count">{{ isset($tutors) ? $tutors->total() : 0 }} hasil ditemukan</span>
            </div>

            @if(isset($tutors) && $tutors->count() > 0)

                @php $avColors = ['ava-0','ava-1','ava-2','ava-3','ava-4']; @endphp
                <div class="tutor-grid">
                    @foreach($tutors as $i => $tutor)
                    <div class="tutor-card">
                        <div class="tc-body">
                            <div class="tc-top">
                                <div class="tc-avatar {{ $avColors[$i % 5] }}">
                                    {{ strtoupper(substr($tutor->name, 0, 2)) }}
                                </div>
                                <div style="flex:1; min-width:0;">
                                    <div class="tc-name">{{ $tutor->name }}</div>
                                    <div class="tc-mapel">{{ implode(', ', $tutor->mapel_list ?? ['Tutor']) }}</div>
                                </div>
                                <div class="tc-rating">⭐ {{ number_format($tutor->rating ?? 0, 1) }}</div>
                            </div>

                            @if(isset($tutor->mapel_list) && count($tutor->mapel_list) > 0)
                            <div class="tc-tags">
                                @foreach(array_slice($tutor->mapel_list, 0, 3) as $tag)
                                <span class="tc-tag">{{ $tag }}</span>
                                @endforeach
                                @if(count($tutor->mapel_list) > 3)
                                <span class="tc-tag">+{{ count($tutor->mapel_list) - 3 }}</span>
                                @endif
                            </div>
                            @endif

                            <div class="tc-avail">
                                <span class="avail-dot"></span>
                                Tersedia hari ini
                            </div>
                        </div>

                        <div class="tc-foot">
                            <div>
                                <div class="tc-price">Rp{{ number_format($tutor->harga_per_jam ?? 0, 0, ',', '.') }}</div>
                                <div class="tc-price-sub">per jam</div>
                            </div>
                            <a href="{{ route('Member.detail-tutor', $tutor->id) }}" class="book-btn">
                                Lihat Profil →
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>

                @if($tutors->hasPages())
                <div class="pagination-wrap">
                    @if($tutors->onFirstPage())
                        <span class="pg-btn disabled">←</span>
                    @else
                        <a href="{{ $tutors->previousPageUrl() }}" class="pg-btn">←</a>
                    @endif

                    @foreach($tutors->getUrlRange(1, $tutors->lastPage()) as $page => $url)
                        <a href="{{ $url }}" class="pg-btn {{ $page == $tutors->currentPage() ? 'active' : '' }}">{{ $page }}</a>
                    @endforeach

                    @if($tutors->hasMorePages())
                        <a href="{{ $tutors->nextPageUrl() }}" class="pg-btn">→</a>
                    @else
                        <span class="pg-btn disabled">→</span>
                    @endif
                </div>
                @endif

            @else
                <div class="empty-state">
                    <div class="es-icon">🔍</div>
                    <div class="es-title">Tutor tidak ditemukan</div>
                    <div class="es-text">Coba ubah kata kunci atau hapus filter yang digunakan</div>
                    <a href="{{ route('Member.cari-tutor') }}" class="reset-btn">Reset Pencarian</a>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
